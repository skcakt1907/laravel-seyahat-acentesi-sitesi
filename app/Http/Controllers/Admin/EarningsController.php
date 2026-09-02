<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EarningsController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startToday   = $now->copy()->startOfDay();
        $startWeek    = $now->copy()->startOfWeek();
        $startMonth   = $now->copy()->startOfMonth();
        $startYear    = $now->copy()->startOfYear();
        $startLast30  = $now->copy()->subDays(29)->startOfDay();
        $startLast12m = $now->copy()->subMonths(11)->startOfMonth();

        $base = DB::table('payments');

        // KPI: total successful revenue
        $totalRevenue   = (clone $base)->where('status', 'paid')->sum('amount');
        $todayRevenue   = (clone $base)->where('status', 'paid')->where('created_at', '>=', $startToday)->sum('amount');
        $weekRevenue    = (clone $base)->where('status', 'paid')->where('created_at', '>=', $startWeek)->sum('amount');
        $monthRevenue   = (clone $base)->where('status', 'paid')->where('created_at', '>=', $startMonth)->sum('amount');
        $yearRevenue    = (clone $base)->where('status', 'paid')->where('created_at', '>=', $startYear)->sum('amount');

        $pendingAmount  = (clone $base)->where('status', 'pending')->sum('amount');
        $failedAmount   = (clone $base)->where('status', 'failed')->sum('amount');
        $refundedAmount = (clone $base)->where('status', 'refunded')->sum('amount');

        $totalTxCount   = (clone $base)->count();
        $successCount   = (clone $base)->where('status', 'paid')->count();
        $pendingCount   = (clone $base)->where('status', 'pending')->count();
        $failedCount    = (clone $base)->where('status', 'failed')->count();

        $avgOrderValue  = $successCount > 0 ? round($totalRevenue / $successCount, 2) : 0;
        $successRate    = $totalTxCount > 0 ? round(($successCount / $totalTxCount) * 100, 1) : 0;

        // Compare this month vs last month
        $startLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endLastMonth   = $now->copy()->subMonth()->endOfMonth();
        $lastMonthRevenue = (clone $base)->where('status', 'paid')
            ->whereBetween('created_at', [$startLastMonth, $endLastMonth])->sum('amount');
        $monthGrowth = $lastMonthRevenue > 0
            ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($monthRevenue > 0 ? 100 : 0);

        // Daily revenue (last 30 days) for chart
        $dailyRows = (clone $base)
            ->select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(CASE WHEN status="paid" THEN amount ELSE 0 END) as total'))
            ->where('created_at', '>=', $startLast30)
            ->groupBy('d')
            ->orderBy('d')
            ->get()
            ->keyBy('d');

        $dailyLabels = [];
        $dailyData   = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = $now->copy()->subDays($i)->format('d M');
            $dailyData[]   = (float) ($dailyRows[$day]->total ?? 0);
        }

        // Monthly revenue (last 12 months) for chart
        $monthlyRows = (clone $base)
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as m'), DB::raw('SUM(CASE WHEN status="paid" THEN amount ELSE 0 END) as total'))
            ->where('created_at', '>=', $startLast12m)
            ->groupBy('m')
            ->orderBy('m')
            ->get()
            ->keyBy('m');

        $monthlyLabels = [];
        $monthlyData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $key = $now->copy()->subMonths($i)->format('Y-m');
            $monthlyLabels[] = $now->copy()->subMonths($i)->format('M Y');
            $monthlyData[]   = (float) ($monthlyRows[$key]->total ?? 0);
        }

        // Status pie data
        $statusBreakdown = [
            'paid'  => (float) $totalRevenue,
            'pending'  => (float) $pendingAmount,
            'failed'   => (float) $failedAmount,
            'refunded' => (float) $refundedAmount,
        ];

        // Provider breakdown (PayTR, Iyzico, Garanti, etc.)
        $topServices = DB::table('payments')
            ->join('customers', 'customers.id', '=', 'payments.customer_id')
            ->where('payments.status', 'paid')
            ->select(
                DB::raw('COALESCE(NULLIF(customers.activity_name, ""), customers.package) as service_name'),
                'customers.type as service_type',
                DB::raw('SUM(payments.amount) as total'),
                DB::raw('COUNT(payments.id) as sales')
            )
            ->groupBy('service_name', 'service_type')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        // Goals vs actuals (week / month / year). Targets editable later via settings.
        $weekTarget  = 1500;
        $monthTarget = 6000;
        $yearTarget  = 60000;
        $goals = [
            ['label' => 'Haftalık Hedef', 'actual' => (float) $weekRevenue,  'target' => $weekTarget,  'icon' => 'fa-calendar-day',  'color' => '#0ea5e9'],
            ['label' => 'Aylık Hedef',    'actual' => (float) $monthRevenue, 'target' => $monthTarget, 'icon' => 'fa-calendar-alt',  'color' => '#8b5cf6'],
            ['label' => 'Yıllık Hedef',   'actual' => (float) $yearRevenue,  'target' => $yearTarget,  'icon' => 'fa-calendar-check','color' => '#f59e0b'],
        ];

        // Recent 10 transactions
        $recentTx = DB::table('payments')
            ->leftJoin('customers', 'customers.id', '=', 'payments.customer_id')
            ->select(
                'payments.*',
                'customers.first_name',
                'customers.last_name',
                'customers.email'
            )
            ->orderByDesc('payments.created_at')
            ->limit(10)
            ->get();

        // TAHSIL EDILMEYEN TRANSFER TUTARI
        // Transfer ucreti musteriden ELDEN alinir, sisteme girmez. Bu yuzden burasi
        // "kazanc" degil, "hakedis" gosterir: henuz odeme kaydi olusmamis transferler.
        // Odemesi kaydedilmis olanlar zaten ciroda (payments) sayildigi icin haric tutulur.
        $transferEarnings = 0.0;
        $transferCount    = 0;
        $odemesiOlanlar = DB::table('payments')->where('status', 'paid')
            ->whereNotNull('customer_id')->pluck('customer_id')->unique();
        $transferBookings = DB::table('customers')->where('type', 'transfer')
            ->when($odemesiOlanlar->isNotEmpty(),
                fn ($q) => $q->whereNotIn('id', $odemesiOlanlar))
            ->get(['adult_count', 'child_count', 'package']);
        if ($transferBookings->isNotEmpty()) {
            $transfersLookup = DB::table('transfers')->get()->keyBy('title');
            foreach ($transferBookings as $b) {
                $t = $transfersLookup[$b->package] ?? null;
                if (!$t) { continue; }
                $pax = max(1, (int) ($b->adult_count ?? 0) + (int) ($b->child_count ?? 0));
                if ($pax <= 4)      { $transferEarnings += (float) ($t->price_1_4 ?? $t->price ?? 0); }
                elseif ($pax <= 6)  { $transferEarnings += (float) ($t->price_5_6 ?? 0); }
                elseif ($pax <= 8)  { $transferEarnings += (float) ($t->price_7_8 ?? 0); }
                else                { $transferEarnings += (float) ($t->price_9_14 ?? 0); }
                $transferCount++;
            }
        }

        // Currency (default GBP)
        $currency = '£';

        return view('admin.earnings.index', compact(
            'transferEarnings', 'transferCount',
            'totalRevenue', 'todayRevenue', 'weekRevenue', 'monthRevenue', 'yearRevenue',
            'pendingAmount', 'failedAmount', 'refundedAmount',
            'totalTxCount', 'successCount', 'pendingCount', 'failedCount',
            'avgOrderValue', 'successRate', 'monthGrowth', 'lastMonthRevenue',
            'dailyLabels', 'dailyData', 'monthlyLabels', 'monthlyData',
            'statusBreakdown', 'topServices', 'goals', 'recentTx',
            'currency'
        ));
    }
}

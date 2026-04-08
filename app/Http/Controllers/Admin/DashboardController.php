<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $weekStart = now()->startOfWeek()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();

        $stats = [
            'total_customers'    => DB::table('customers')->count(),
            'today_customers'    => DB::table('customers')->whereDate('created_at', $today)->count(),
            'week_customers'     => DB::table('customers')->whereDate('created_at', '>=', $weekStart)->count(),
            'month_customers'    => DB::table('customers')->whereDate('created_at', '>=', $monthStart)->count(),
            'total_transfers'    => DB::table('customers')->where('type', 'transfer')->count(),
            'total_activities'   => DB::table('customers')->where('type', 'activity')->count(),
            'total_sliders'      => DB::table('slider')->count(),
            'active_sliders'     => DB::table('slider')->where('durum', 1)->count(),
            'pending_reviews'    => DB::table('reviews')->where('approved', 0)->count(),
            'unread_messages'    => DB::table('contact_messages')->where('read', 0)->count(),
            'total_revenue'      => (float) DB::table('payments')->where('status', 'paid')->sum('amount'),
            'today_revenue'      => (float) DB::table('payments')->where('status', 'paid')->whereDate('created_at', $today)->sum('amount'),
            'month_revenue'      => (float) DB::table('payments')->where('status', 'paid')->whereDate('created_at', '>=', $monthStart)->sum('amount'),
        ];

        // Mini revenue chart — last 14 days
        $revenueRows = DB::table('payments')
            ->select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(CASE WHEN status="paid" THEN amount ELSE 0 END) as t'))
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('d')
            ->get()
            ->keyBy('d');
        $miniLabels = [];
        $miniData = [];
        for ($i = 13; $i >= 0; $i--) {
            $day = now()->subDays($i)->format('Y-m-d');
            $miniLabels[] = now()->subDays($i)->format('d M');
            $miniData[]   = (float) ($revenueRows[$day]->t ?? 0);
        }

        // Recent customers
        $recent_customers = DB::table('customers')
            ->select('id', 'first_name', 'last_name', 'email', 'phone', 'type', 'package', 'activity_name', 'created_at')
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();

        // Upcoming arrivals (next 7 days)
        $upcoming_arrivals = DB::table('customers')
            ->where('type', 'transfer')
            ->whereNotNull('arrival_date')
            ->whereBetween('arrival_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->orderBy('arrival_date')
            ->orderBy('arrival_time')
            ->limit(6)
            ->get();

        // Latest reviews
        $latest_reviews = DB::table('reviews')
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        // Top 3 selling activities (by paid payments via customers)
        $top_activities = DB::table('payments')
            ->join('customers', 'customers.id', '=', 'payments.customer_id')
            ->where('payments.status', 'paid')
            ->where('customers.type', 'activity')
            ->select(
                DB::raw('COALESCE(NULLIF(customers.activity_name, ""), customers.package) as name'),
                DB::raw('SUM(payments.amount) as total'),
                DB::raw('COUNT(*) as cnt')
            )
            ->groupBy('name')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'recent_customers', 'miniLabels', 'miniData',
            'upcoming_arrivals', 'latest_reviews', 'top_activities'
        ));
    }
}

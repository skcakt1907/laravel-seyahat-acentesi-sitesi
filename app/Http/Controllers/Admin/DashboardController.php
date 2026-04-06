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
            // Customer totals
            'total_customers'    => DB::table('customers')->count(),
            'today_customers'    => DB::table('customers')->whereDate('created_at', $today)->count(),
            'week_customers'     => DB::table('customers')->whereDate('created_at', '>=', $weekStart)->count(),
            'month_customers'    => DB::table('customers')->whereDate('created_at', '>=', $monthStart)->count(),

            // By type
            'total_transfers'    => DB::table('customers')->where('type', 'transfer')->count(),
            'total_activities'   => DB::table('customers')->where('type', 'activity')->count(),

            // Content
            'total_sliders'      => DB::table('slider')->count(),
            'active_sliders'     => DB::table('slider')->where('durum', 1)->count(),
        ];

        // Recent customers
        $recent_customers = DB::table('customers')
            ->select('id', 'first_name', 'last_name', 'email', 'phone', 'type', 'package', 'activity_name', 'created_at')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_customers'));
    }
}

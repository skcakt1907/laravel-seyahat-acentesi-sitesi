<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        // Mark all unseen customers as seen
        DB::table('customers')->where('seen', 0)->update(['seen' => 1]);

        $customers = Customer::query()
            ->latest()
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function exportCsv()
    {
        $filename = 'customers-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['First Name', 'Last Name', 'Email', 'Phone', 'Type', 'Package/Activity', 'Date'], ';');

            Customer::query()
                ->orderByDesc('id')
                ->chunk(200, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $row->first_name,
                            $row->last_name,
                            $row->email,
                            $row->phone,
                            $row->type,
                            $row->activity_name ?: $row->package,
                            optional($row->created_at)->format('Y-m-d H:i:s'),
                        ], ';');
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }
}


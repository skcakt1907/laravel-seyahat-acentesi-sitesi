<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageOrderController extends Controller
{
    private $sectionLabels = [
        'transfers' => ['icon' => 'fa-shuttle-van', 'label' => 'Transfer Routes'],
        'activities' => ['icon' => 'fa-star', 'label' => 'Popular Activities'],
        'why-us' => ['icon' => 'fa-shield-alt', 'label' => 'Why Choose Us'],
        'testimonials' => ['icon' => 'fa-comment-dots', 'label' => 'Testimonials / Reviews'],
        'contact' => ['icon' => 'fa-envelope', 'label' => 'Contact Form'],
    ];

    public function index()
    {
        $ayar = DB::table('ayarlar')->first();
        $order = $ayar && $ayar->section_order
            ? json_decode($ayar->section_order, true)
            : ['transfers', 'activities', 'why-us', 'testimonials', 'contact'];

        $sections = $this->sectionLabels;

        return view('admin.page-order.index', compact('order', 'sections'));
    }

    public function update(Request $request)
    {
        $order = $request->input('order', []);

        DB::table('ayarlar')->where('id', 1)->update([
            'section_order' => json_encode($order),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}

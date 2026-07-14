<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
@php
    $adults = (int) ($customer->adult_count ?? 0);
    $children = (int) ($customer->child_count ?? 0);
    $guestCount = max(1, $adults + $children);
    $totalPrice = 0;
    if ($customer->type === 'activity') {
        $rec = \DB::table('activities')->where('slug', $customer->package)->orWhere('title', $customer->activity_name)->first();
        $totalPrice = (float) ($rec->price ?? 0) * $guestCount;
    } else {
        $rec = \DB::table('transfers')->where('title', $customer->package)->first();
        if ($rec) {
            if ($guestCount <= 4)       $totalPrice = (float) ($rec->price_1_4 ?? 0);
            elseif ($guestCount <= 6)   $totalPrice = (float) ($rec->price_5_6 ?? 0);
            elseif ($guestCount <= 8)   $totalPrice = (float) ($rec->price_7_8 ?? 0);
            else                        $totalPrice = (float) ($rec->price_9_14 ?? 0);
        }
    }
@endphp
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7fb;padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
                    {{-- Header --}}
                    <tr>
                        <td style="background:#0b1d33;padding:24px 30px;text-align:center;">
                            <span style="font-size:20px;color:#fff;font-weight:400;">Marmaris <strong style="color:#0099ff;font-weight:800;">Travel Center</strong></span>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:36px 30px;">
                            {{-- Alert Icon --}}
                            <div style="text-align:center;margin-bottom:24px;">
                                <div style="width:64px;height:64px;border-radius:50%;background:#0066cc;display:inline-flex;align-items:center;justify-content:center;">
                                    <span style="font-size:28px;color:#fff;">&#128276;</span>
                                </div>
                            </div>

                            <h1 style="font-size:22px;color:#0b1d33;text-align:center;margin:0 0 8px;">New {{ $customer->type === 'transfer' ? 'Transfer' : 'Activity' }} Booking!</h1>
                            <p style="font-size:14px;color:#64748b;text-align:center;margin:0 0 6px;">
                                <strong style="color:#0b1d33;">{{ $customer->first_name }} {{ $customer->last_name }}</strong> just made a booking.
                            </p>
                            <p style="font-size:12px;color:#94a3b8;text-align:center;margin:0 0 28px;">
                                {{ now()->format('d M Y, H:i') }}
                            </p>

                            {{-- Booking Details --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-radius:12px;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Booking ID</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:700;text-align:right;border-bottom:1px solid #e2e8f0;">#TCM{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Type</td>
                                                <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">
                                                    <span style="background:{{ $customer->type === 'transfer' ? '#dbeafe' : '#fef3c7' }};color:{{ $customer->type === 'transfer' ? '#1d4ed8' : '#b45309' }};padding:3px 10px;border-radius:50px;font-size:12px;">{{ ucfirst($customer->type) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Name</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Email</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;"><a href="mailto:{{ $customer->email }}" style="color:#0066cc;text-decoration:none;">{{ $customer->email }}</a></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Phone</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;"><a href="tel:{{ $customer->phone }}" style="color:#0066cc;text-decoration:none;">{{ $customer->phone }}</a></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">{{ $customer->type === 'transfer' ? 'Route' : 'Activity' }}</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ $customer->activity_name ?: $customer->package }}</td>
                                            </tr>
                                            @if($customer->type === 'transfer')
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Hotel</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ $customer->hotel_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Guests</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ $customer->adult_count }} adult(s){{ $customer->child_count ? ', ' . $customer->child_count . ' child(ren)' : '' }}</td>
                                            </tr>
                                            @if($customer->adult_names || $customer->child_names)
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">All Passengers</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ trim($customer->adult_names . ($customer->child_names ? ', ' . $customer->child_names : ''), ', ') }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Arrival</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ \Carbon\Carbon::parse($customer->arrival_date)->format('d M Y') }} at {{ $customer->arrival_time }} &mdash; Flight {{ $customer->arrival_flight }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Departure</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ \Carbon\Carbon::parse($customer->departure_date)->format('d M Y') }} at {{ $customer->departure_time }} &mdash; Flight {{ $customer->departure_flight }}</td>
                                            </tr>
                                            @if($customer->notes)
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;">Notes</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;">{{ $customer->notes }}</td>
                                            </tr>
                                            @endif
                                            @endif
                                            @if($totalPrice > 0)
                                            <tr>
                                                <td style="padding:14px 0 0;font-size:14px;color:#0b1d33;font-weight:700;border-top:2px solid #0b1d33;">Total</td>
                                                <td style="padding:14px 0 0;font-size:18px;color:#0066cc;font-weight:800;text-align:right;border-top:2px solid #0b1d33;">&pound;{{ number_format($totalPrice, 2) }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <div style="text-align:center;">
                                <a href="{{ url('/admin/customers') }}" style="display:inline-block;background:#0066cc;color:#fff;padding:14px 36px;border-radius:50px;text-decoration:none;font-size:14px;font-weight:700;">View in Admin Panel</a>
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8fafc;padding:20px 30px;text-align:center;border-top:1px solid #e2e8f0;">
                            <p style="font-size:12px;color:#94a3b8;margin:0;">This is an automated notification from Marmaris Travel Center.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

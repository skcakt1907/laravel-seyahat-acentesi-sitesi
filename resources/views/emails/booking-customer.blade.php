<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7fb;padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
                    {{-- Header --}}
                    <tr>
                        <td style="background:#0b1d33;padding:24px 30px;text-align:center;">
                            <span style="font-size:20px;color:#fff;font-weight:400;">Marmaris <strong style="color:#ff6b00;font-weight:800;">Travel Center</strong></span>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:36px 30px;">
                            {{-- Success Icon --}}
                            <div style="text-align:center;margin-bottom:24px;">
                                <div style="width:64px;height:64px;border-radius:50%;background:#10b981;display:inline-flex;align-items:center;justify-content:center;">
                                    <span style="font-size:28px;color:#fff;">&#10003;</span>
                                </div>
                            </div>

                            <h1 style="font-size:22px;color:#0b1d33;text-align:center;margin:0 0 8px;">Booking Confirmed!</h1>
                            <p style="font-size:14px;color:#64748b;text-align:center;margin:0 0 28px;line-height:1.6;">
                                Thank you, {{ $customer->first_name }}! Your {{ $customer->type === 'transfer' ? 'transfer' : 'activity' }} booking has been received.
                            </p>

                            {{-- Booking Details --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-radius:12px;padding:20px;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Booking ID</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:700;text-align:right;border-bottom:1px solid #e2e8f0;">#TCM{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Name</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Email</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ $customer->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Phone</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ $customer->phone }}</td>
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
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Arrival</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ \Carbon\Carbon::parse($customer->arrival_date)->format('d M Y') }} at {{ $customer->arrival_time }} &mdash; Flight {{ $customer->arrival_flight }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;">Departure</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;">{{ \Carbon\Carbon::parse($customer->departure_date)->format('d M Y') }} at {{ $customer->departure_time }} &mdash; Flight {{ $customer->departure_flight }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:14px;color:#334155;line-height:1.7;margin:0 0 24px;text-align:center;">
                                Our team will contact you shortly to confirm the details. If you have any questions, feel free to reach out.
                            </p>

                            <div style="text-align:center;">
                                <a href="{{ url('/') }}" style="display:inline-block;background:#0066cc;color:#fff;padding:14px 36px;border-radius:50px;text-decoration:none;font-size:14px;font-weight:700;">Visit Our Website</a>
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f8fafc;padding:20px 30px;text-align:center;border-top:1px solid #e2e8f0;">
                            <p style="font-size:12px;color:#94a3b8;margin:0;">&copy; {{ date('Y') }} Marmaris Travel Center. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

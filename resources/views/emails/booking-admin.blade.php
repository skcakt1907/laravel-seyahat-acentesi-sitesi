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
                            <span style="font-size:20px;color:#fff;font-weight:400;">Travel Center <strong style="color:#ff6b00;font-weight:800;">Marmaris</strong></span>
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
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;">{{ $customer->type === 'transfer' ? 'Route' : 'Activity' }}</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;">{{ $customer->activity_name ?: $customer->package }}</td>
                                            </tr>
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
                            <p style="font-size:12px;color:#94a3b8;margin:0;">This is an automated notification from Travel Center Marmaris.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

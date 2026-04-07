<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7fb;padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
                    <tr>
                        <td style="background:#0b1d33;padding:24px 30px;text-align:center;">
                            <span style="font-size:20px;color:#fff;">Marmaris <strong style="color:#ff6b00;">Travel Center</strong></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 30px;">
                            <div style="text-align:center;margin-bottom:24px;">
                                <div style="width:64px;height:64px;border-radius:50%;background:#6366f1;display:inline-flex;align-items:center;justify-content:center;">
                                    <span style="font-size:28px;color:#fff;">&#9993;</span>
                                </div>
                            </div>

                            <h1 style="font-size:22px;color:#0b1d33;text-align:center;margin:0 0 8px;">New Contact Message</h1>
                            <p style="font-size:12px;color:#94a3b8;text-align:center;margin:0 0 28px;">{{ now()->format('d M Y, H:i') }}</p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-radius:12px;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Name</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ $contactName }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Email</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;"><a href="mailto:{{ $contactEmail }}" style="color:#0066cc;text-decoration:none;">{{ $contactEmail }}</a></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;font-size:13px;color:#64748b;border-bottom:1px solid #e2e8f0;">Subject</td>
                                                <td style="padding:8px 0;font-size:13px;color:#0b1d33;font-weight:600;text-align:right;border-bottom:1px solid #e2e8f0;">{{ $contactSubject }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <div style="background:#f8fafc;border-radius:12px;padding:20px;margin-bottom:24px;border-left:4px solid #6366f1;">
                                <p style="font-size:13px;color:#64748b;margin:0 0 6px;font-weight:600;">Message:</p>
                                <p style="font-size:14px;color:#334155;margin:0;line-height:1.7;">{{ $contactMessage }}</p>
                            </div>

                            <div style="text-align:center;">
                                <a href="{{ url('/admin/contacts') }}" style="display:inline-block;background:#0066cc;color:#fff;padding:14px 36px;border-radius:50px;text-decoration:none;font-size:14px;font-weight:700;">View in Admin Panel</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f8fafc;padding:20px 30px;text-align:center;border-top:1px solid #e2e8f0;">
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Automated notification from Marmaris Travel Center</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

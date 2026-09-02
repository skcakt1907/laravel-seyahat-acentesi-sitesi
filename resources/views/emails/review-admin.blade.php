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
                            <span style="font-size:20px;color:#fff;">Marmaris <strong style="color:#0099ff;">Travel Center</strong></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 30px;">
                            <div style="text-align:center;margin-bottom:24px;">
                                <div style="width:64px;height:64px;border-radius:50%;background:#f59e0b;display:inline-flex;align-items:center;justify-content:center;">
                                    <span style="font-size:28px;color:#fff;">&#9733;</span>
                                </div>
                            </div>

                            <h1 style="font-size:22px;color:#0b1d33;text-align:center;margin:0 0 8px;">New Review Submitted</h1>
                            <p style="font-size:14px;color:#64748b;text-align:center;margin:0 0 6px;">
                                <strong style="color:#0b1d33;">{{ $reviewName }}</strong>
                                @if($reviewLocation) from {{ $reviewLocation }} @endif
                            </p>
                            <p style="font-size:12px;color:#94a3b8;text-align:center;margin:0 0 28px;">{{ now()->format('d M Y, H:i') }}</p>

                            {{-- Star Rating --}}
                            <div style="text-align:center;margin-bottom:24px;">
                                <span style="font-size:24px;color:#f59e0b;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $reviewRating)&#9733;@else<span style="color:#e2e8f0;">&#9733;</span>@endif
                                    @endfor
                                </span>
                                <div style="font-size:14px;color:#0b1d33;font-weight:700;margin-top:4px;">{{ $reviewRating }}/5</div>
                            </div>

                            <div style="background:#f8fafc;border-radius:12px;padding:20px;margin-bottom:24px;border-left:4px solid #f59e0b;">
                                <p style="font-size:14px;color:#334155;margin:0;line-height:1.7;font-style:italic;">"{{ $reviewComment }}"</p>
                            </div>

                            <p style="font-size:13px;color:#64748b;text-align:center;margin:0 0 24px;">This review is pending approval.</p>

                            <div style="text-align:center;">
                                <a href="{{ url('/admin/reviews') }}" style="display:inline-block;background:#f59e0b;color:#fff;padding:14px 36px;border-radius:50px;text-decoration:none;font-size:14px;font-weight:700;">Review & Approve</a>
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

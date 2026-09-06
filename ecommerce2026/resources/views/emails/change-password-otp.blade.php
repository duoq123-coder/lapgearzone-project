<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã xác nhận đổi mật khẩu</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f7f7f8; margin: 0; padding: 30px 15px; color: #333333; line-height: 1.6;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #eaeaea;">
        <!-- Header -->
        <tr>
            <td style="background-color: #111114; padding: 30px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;">
                    LAPGEARZONE
                </h1>
                <p style="color: #cd4c20; margin: 6px 0 0 0; font-size: 13px; font-weight: 600;">HỆ THỐNG BẢO MẬT TÀI KHOẢN</p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 35px 30px;">
                <h2 style="color: #111111; font-size: 20px; font-weight: 700; margin-top: 0; margin-bottom: 15px;">
                    Xác nhận yêu cầu đổi mật khẩu
                </h2>
                <p style="font-size: 15px; margin-bottom: 15px; color: #444444;">
                    Xin chào <strong>{{ $name }}</strong>,
                </p>
                <p style="font-size: 15px; margin-bottom: 20px; color: #444444;">
                    Chúng tôi nhận được yêu cầu cập nhật mật khẩu mới cho tài khoản của bạn trên hệ thống <strong>LapGearZone</strong>.
                </p>

                <p style="font-size: 14px; margin-bottom: 10px; color: #666666; font-weight: 600;">
                    Mã xác thực (OTP) của bạn là:
                </p>

                <!-- OTP Code Box -->
                <div style="background-color: #fdf6f0; border: 2px dashed #cd4c20; border-radius: 8px; padding: 18px; text-align: center; margin: 20px 0;">
                    <span style="font-family: 'Space Mono', 'Courier New', Courier, monospace; font-size: 34px; font-weight: 800; letter-spacing: 8px; color: #cd4c20;">
                        {{ $code }}
                    </span>
                </div>

                <p style="font-size: 13px; color: #dc3545; margin-bottom: 20px; font-weight: 600;">
                    ⚠️ Mã xác nhận này có hiệu lực trong vòng <strong>15 phút</strong>. Tuyệt đối không chia sẻ mã này cho bất kỳ ai.
                </p>

                <p style="font-size: 14px; color: #666666; margin-bottom: 25px; line-height: 1.5;">
                    Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email. Mật khẩu hiện tại của bạn sẽ <strong>hoàn toàn được giữ nguyên không thay đổi</strong>.
                </p>

                <hr style="border: none; border-top: 1px solid #eeeeee; margin: 25px 0;">

                <p style="font-size: 13px; color: #888888; margin-bottom: 0;">
                    Trân trọng,<br>
                    <strong>Đội ngũ hỗ trợ & Bảo mật LapGearZone</strong>
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #fafafa; padding: 20px; text-align: center; border-top: 1px solid #eaeaea;">
                <p style="font-size: 12px; color: #999999; margin: 0;">
                    &copy; {{ date('Y') }} LapGearZone. All rights reserved.<br>
                    Email tự động, vui lòng không phản hồi trực tiếp vào thư này.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>

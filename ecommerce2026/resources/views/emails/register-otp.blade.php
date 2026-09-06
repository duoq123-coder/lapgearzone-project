<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đăng ký tài khoản LapGearZone</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f7f7f8; margin: 0; padding: 30px 15px; color: #333333; line-height: 1.6;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #eaeaea;">

        <!-- Header -->
        <tr>
            <td style="background-color: #000000; padding: 30px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;">
                    LAPGEARZONE
                </h1>
                <p style="color: #bbbbbb; margin: 6px 0 0 0; font-size: 13px;">Cửa hàng thiết bị &amp; Phụ kiện công nghệ cao cấp</p>
            </td>
        </tr>

        <!-- Welcome Banner -->
        <tr>
            <td style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%); padding: 20px 30px; text-align: center;">
                <p style="color: #ffffff; margin: 0; font-size: 15px; font-weight: 600; letter-spacing: 0.5px;">
                    🎉 Chào mừng bạn gia nhập cộng đồng LapGearZone!
                </p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 35px 30px;">
                <h2 style="color: #111111; font-size: 20px; font-weight: 700; margin-top: 0; margin-bottom: 15px;">
                    Xác nhận đăng ký tài khoản
                </h2>
                <p style="font-size: 15px; margin-bottom: 15px; color: #444444;">
                    Xin chào <strong>{{ $name }}</strong>,
                </p>
                <p style="font-size: 15px; margin-bottom: 20px; color: #444444;">
                    Cảm ơn bạn đã đăng ký tài khoản tại <strong>LapGearZone</strong>. Để hoàn tất quá trình đăng ký, vui lòng nhập mã OTP bên dưới vào trang xác nhận.
                </p>

                <p style="font-size: 14px; margin-bottom: 10px; color: #666666; font-weight: 600;">
                    Mã xác nhận (OTP) của bạn là:
                </p>

                <!-- OTP Code Box -->
                <div style="background-color: #f4f4f6; border: 2px dashed #ff6b35; border-radius: 8px; padding: 18px; text-align: center; margin: 20px 0;">
                    <span style="font-family: 'Courier New', Courier, monospace; font-size: 38px; font-weight: 800; letter-spacing: 10px; color: #000000;">
                        {{ $code }}
                    </span>
                </div>

                <p style="font-size: 13px; color: #dc3545; margin-bottom: 20px; font-weight: 600;">
                    ⚠️ Mã xác nhận có hiệu lực trong <strong>10 phút</strong>. Tuyệt đối không chia sẻ mã này cho bất kỳ ai.
                </p>

                <div style="background-color: #f8f9fa; border-left: 4px solid #ff6b35; border-radius: 4px; padding: 15px 20px; margin-bottom: 25px;">
                    <p style="font-size: 13px; color: #555555; margin: 0; line-height: 1.7;">
                        ✅ Sau khi xác nhận, tài khoản của bạn sẽ được kích hoạt và bạn có thể:<br>
                        &nbsp;&nbsp;• Đặt hàng và theo dõi đơn hàng trực tuyến<br>
                        &nbsp;&nbsp;• Đặt lịch sửa chữa laptop dễ dàng<br>
                        &nbsp;&nbsp;• Tra cứu lịch sử bảo hành thiết bị
                    </p>
                </div>

                <p style="font-size: 14px; color: #666666; margin-bottom: 25px; line-height: 1.5;">
                    Nếu bạn không thực hiện yêu cầu đăng ký này, vui lòng bỏ qua email này. Không có tài khoản nào được tạo.
                </p>

                <hr style="border: none; border-top: 1px solid #eeeeee; margin: 25px 0;">

                <p style="font-size: 13px; color: #888888; margin-bottom: 0;">
                    Trân trọng,<br>
                    <strong>Đội ngũ hỗ trợ LapGearZone</strong>
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

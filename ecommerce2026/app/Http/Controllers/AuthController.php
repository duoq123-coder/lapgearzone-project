<?php

namespace App\Http\Controllers;

use App\Mail\RegisterOtp;
use App\Mail\ResetPasswordCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Hiển thị form đăng ký
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // ==========================================
    // ĐĂNG KÝ TÀI KHOẢN QUA XÁC MINH OTP EMAIL
    // ==========================================

    // Bước 1: Nhận thông tin đăng ký → gửi OTP về email
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Tạo mã OTP 6 chữ số
        $otp = (string) mt_rand(100000, 999999);

        // Lưu OTP vào bảng register_otps (upsert theo email)
        DB::table('register_otps')->updateOrInsert(
            ['email' => $request->email],
            [
                'otp'        => $otp,
                'created_at' => now(),
            ]
        );

        // Lưu thông tin đăng ký tạm vào session (chưa tạo user)
        session([
            'register_pending' => [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password,
            ],
        ]);

        // Gửi email OTP
        try {
            Mail::to($request->email)->send(new RegisterOtp($otp, $request->name));
            Log::info('Registration OTP sent to: ' . $request->email);
        } catch (\Exception $e) {
            Log::error('Send register OTP email failed: ' . $e->getMessage());
            return back()
                ->with('error', 'Không thể gửi email xác nhận. Vui lòng kiểm tra lại kết nối hoặc thử lại sau.')
                ->withInput();
        }

        return redirect()->route('register.verify.otp.form')
            ->with('success', 'Mã OTP gồm 6 chữ số đã được gửi đến ' . $request->email . '. Vui lòng kiểm tra hộp thư!');
    }

    // Bước 2: Hiển thị form nhập OTP xác nhận đăng ký
    public function showVerifyRegisterOtpForm()
    {
        if (!session()->has('register_pending')) {
            return redirect()->route('register')->with('error', 'Vui lòng điền thông tin đăng ký trước.');
        }

        $email = session('register_pending.email');
        return view('auth.verify-register-otp', compact('email'));
    }

    // Bước 2: Xử lý kiểm tra OTP và tạo tài khoản
    public function verifyRegisterOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Vui lòng nhập mã OTP.',
            'otp.size'     => 'Mã OTP phải đúng 6 chữ số.',
        ]);

        if (!session()->has('register_pending')) {
            return redirect()->route('register')->with('error', 'Phiên đăng ký đã hết hạn. Vui lòng thực hiện lại.');
        }

        $pending = session('register_pending');
        $email   = $pending['email'];

        $record = DB::table('register_otps')->where('email', $email)->first();

        if (!$record) {
            return back()->with('error', 'Không tìm thấy yêu cầu xác nhận. Vui lòng đăng ký lại.');
        }

        // Kiểm tra hết hạn (10 phút)
        if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
            DB::table('register_otps')->where('email', $email)->delete();
            session()->forget('register_pending');
            return redirect()->route('register')
                ->with('error', 'Mã OTP đã hết hạn (quá 10 phút). Vui lòng đăng ký lại.');
        }

        // Kiểm tra trùng khớp OTP
        if ($record->otp !== trim($request->otp)) {
            return back()->with('error', 'Mã OTP không chính xác. Vui lòng kiểm tra lại email.');
        }

        // OTP hợp lệ → Tạo tài khoản
        try {
            User::create([
                'name'     => $pending['name'],
                'email'    => $pending['email'],
                'password' => $pending['password'],
                'role'     => 'customer_bronze',
            ]);

            Log::info('User registered successfully via OTP: ' . $email);

            // Dọn dẹp
            DB::table('register_otps')->where('email', $email)->delete();
            session()->forget('register_pending');

            return redirect()->route('login')
                ->with('success', '🎉 Đăng ký tài khoản thành công! Vui lòng đăng nhập để tiếp tục.');
        } catch (\Exception $e) {
            Log::error('Create user after OTP verify failed: ' . $e->getMessage());
            return back()->with('error', 'Tạo tài khoản không thành công. Vui lòng thử lại sau.');
        }
    }

    // Gửi lại OTP đăng ký
    public function resendRegisterOtp(Request $request)
    {
        if (!session()->has('register_pending')) {
            return redirect()->route('register')->with('error', 'Phiên đăng ký đã hết hạn. Vui lòng thực hiện lại.');
        }

        $pending = session('register_pending');
        $email   = $pending['email'];

        // Rate-limit: chỉ cho phép gửi lại sau 60 giây
        $existing = DB::table('register_otps')->where('email', $email)->first();
        if ($existing && Carbon::parse($existing->created_at)->addSeconds(60)->isFuture()) {
            $waitSeconds = now()->diffInSeconds(Carbon::parse($existing->created_at)->addSeconds(60));
            return back()->with('error', "Vui lòng chờ {$waitSeconds} giây trước khi gửi lại mã.");
        }

        $otp = (string) mt_rand(100000, 999999);

        DB::table('register_otps')->updateOrInsert(
            ['email' => $email],
            [
                'otp'        => $otp,
                'created_at' => now(),
            ]
        );

        try {
            Mail::to($email)->send(new RegisterOtp($otp, $pending['name']));
            Log::info('Register OTP resent to: ' . $email);
            return back()->with('success', 'Mã OTP mới đã được gửi lại về email ' . $email . '.');
        } catch (\Exception $e) {
            Log::error('Resend register OTP failed: ' . $e->getMessage());
            return back()->with('error', 'Không thể gửi email. Vui lòng thử lại sau.');
        }
    }

    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập người dùng (Hỗ trợ CCCD, Email hoặc Số điện thoại)
    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginId = trim($request->login_id);
        $password = $request->password;

        // Tìm kiếm các tài khoản khớp với CCCD, Email hoặc Số điện thoại
        $users = User::where('cccd', $loginId)
            ->orWhere('email', $loginId)
            ->orWhere('phone', $loginId)
            ->get();

        foreach ($users as $user) {
            if (Hash::check($password, $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();

                if (Auth::user()->must_change_password) {
                    return redirect()->route('password.change.force');
                }

                if (Auth::user()->role === 'admin') {
                    return redirect()->intended(route('admin.dashboard'));
                }
                if (Auth::user()->role === 'delivery') {
                    return redirect()->intended(route('delivery.index'));
                }
                return redirect()->intended(route('welcome'));
            }
        }

        return back()->withErrors([
            'login_id' => 'Thông tin đăng nhập hoặc mật khẩu không chính xác.',
        ])->onlyInput('login_id');
    }

    // Xử lý đăng xuất người dùng
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ==========================================
    // PASSWORD RESET QUA EMAIL (MÃ OTP)
    // ==========================================

    // Bước 1: Hiển thị form nhập email để nhận mã OTP
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Bước 1: Gửi mã OTP xác nhận về email
    public function sendResetCodeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email của bạn.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Không tìm thấy tài khoản nào khớp với email này.')->withInput();
        }

        // Tạo mã xác nhận OTP 6 chữ số
        $code = (string) mt_rand(100000, 999999);

        // Lưu vào bảng password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $code,
                'created_at' => now(),
            ]
        );

        // Gửi email chứa mã OTP
        try {
            Mail::to($user->email)->send(new ResetPasswordCode($code, $user->name));
        } catch (\Exception $e) {
            Log::error('Send reset password email failed: ' . $e->getMessage());
            return back()->with('error', 'Không thể gửi email lúc này. Vui lòng kiểm tra lại kết nối mạng hoặc thử lại sau.')->withInput();
        }

        // Lưu email vào session để phục vụ bước xác thực
        session(['password_reset_email' => $user->email]);

        return redirect()->route('password.verify.form')
            ->with('success', 'Mã xác nhận gồm 6 chữ số đã được gửi về email ' . $user->email . '. Vui lòng kiểm tra hộp thư của bạn!');
    }

    // Bước 2: Hiển thị form nhập mã OTP
    public function showVerifyResetCodeForm()
    {
        if (!session()->has('password_reset_email')) {
            return redirect()->route('password.request')->with('error', 'Vui lòng nhập email để nhận mã trước.');
        }

        $email = session('password_reset_email');
        return view('auth.verify-reset-code', compact('email'));
    }

    // Bước 2: Xử lý kiểm tra mã OTP
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reset_code' => 'required|string',
        ], [
            'reset_code.required' => 'Vui lòng nhập mã xác nhận nhận được từ email.',
        ]);

        $inputCode = trim($request->reset_code);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->with('error', 'Yêu cầu khôi phục không tồn tại hoặc đã bị hủy. Vui lòng gửi lại mã.')->withInput();
        }

        // Kiểm tra thời hạn mã (15 phút)
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return back()->with('error', 'Mã xác nhận này đã hết hạn (quá 15 phút). Vui lòng yêu cầu gửi lại mã mới.')->withInput();
        }

        // Kiểm tra trùng khớp mã
        if ($record->token !== $inputCode) {
            return back()->with('error', 'Mã xác nhận không chính xác. Vui lòng kiểm tra kỹ email và nhập lại.')->withInput();
        }

        // Lưu trạng thái đã xác minh email thành công
        session([
            'password_reset_verified_email' => $request->email,
        ]);

        return redirect()->route('password.reset')
            ->with('success', 'Xác minh mã thành công! Vui lòng nhập mật khẩu mới.');
    }

    // Bước 3: Hiển thị form nhập mật khẩu mới
    public function showResetForm()
    {
        if (!session()->has('password_reset_verified_email')) {
            return redirect()->route('password.request')->with('error', 'Vui lòng xác minh mã trước khi đổi mật khẩu.');
        }

        $email = session('password_reset_verified_email');
        return view('auth.reset-password', compact('email'));
    }

    // Bước 3: Xử lý cập nhật mật khẩu mới
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu phải có tối thiểu 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        if (!session()->has('password_reset_verified_email')) {
            return redirect()->route('password.request')->with('error', 'Phiên làm việc đã hết hạn. Vui lòng thực hiện lại từ đầu.');
        }

        $email = session('password_reset_verified_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')->with('error', 'Tài khoản không tồn tại.');
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => $request->password,
        ]);

        // Xóa token khỏi database và xóa session
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        session()->forget(['password_reset_email', 'password_reset_verified_email']);

        Log::info('Password reset successfully via email OTP for: ' . $user->email);

        return redirect()->route('login')
            ->with('success', 'Đặt lại mật khẩu thành công! Bạn có thể đăng nhập ngay bằng mật khẩu mới.');
    }
}
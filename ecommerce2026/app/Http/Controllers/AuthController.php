<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
// Hiển thị form đăng ký
public function showRegistrationForm()
{
return view('auth.register');
}
// Xử lý đăng ký người dùng
public function register(Request $request)
{
$request->validate([
'name' => 'required|string|max:255',
'email' => 'required|string|email|max:255|unique:users',
'password' => 'required|string|min:8|confirmed',
]);
try {
Log::info('Registering user with email: ' . $request->email);
User::create([
'name' => $request->name,
'email' => $request->email,
'password' => Hash::make($request->password),
'role' => 'customer_bronze',
]);
Log::info('User registered successfully: ' . $request->email);
return redirect()->route('login')->with('success', 'Registration successful! Please login.');
} catch (\Exception $e) {
Log::error('Registration failed: ' . $e->getMessage());
return back()->with('error', 'Registration failed. Please try again.');
}
}
// Hiển thị form đăng nhập
public function showLoginForm()
{
return view('auth.login');
}
// Xử lý đăng nhập người dùng
public function login(Request $request)
{
    $request->validate([
        'login_id' => 'required|string',
        'password' => 'required|string',
    ]);

    $loginField = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'cccd';

    if (Auth::attempt([$loginField => $request->login_id, 'password' => $request->password])) {
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

    return back()->withErrors([
        'login_id' => 'Thông tin đăng nhập không chính xác.',
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
// PASSWORD RESET (Quên mật khẩu)
// ========================================== 

// Hiển thị form yêu cầu reset mật khẩu
public function showForgotPasswordForm()
{
return view('auth.forgot-password');
}

// Xử lý yêu cầu reset mật khẩu
public function sendResetLink(Request $request)
{
$request->validate([
'email' => 'required|string|email|exists:users,email',
]);

try {
// Tạo token reset
$token = \Illuminate\Support\Str::random(60);
$hashedToken = hash('sha256', $token);

// Lưu vào database
\DB::table('password_reset_tokens')->updateOrInsert(
['email' => $request->email],
[
'token' => $hashedToken,
'created_at' => now(),
]
);

// Ghi log (trong thực tế nên gửi email)
Log::info('Password reset link created for: ' . $request->email);

// Tạo link reset
$resetLink = route('password.reset', ['token' => $token, 'email' => $request->email]);

// TODO: Gửi email với link reset
// Mail::send('emails.reset-password', ['link' => $resetLink], function($message) use ($request) {
//     $message->to($request->email);
// });

return redirect()->route('login')->with('success', 'Link reset mật khẩu đã được gửi (check email hoặc xem log).');
} catch (\Exception $e) {
Log::error('Send reset link failed: ' . $e->getMessage());
return back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
}
}

// Hiển thị form nhập mật khẩu mới
public function showResetForm($token)
{
return view('auth.reset-password', ['token' => $token]);
}

// Xử lý đặt lại mật khẩu
public function resetPassword(Request $request)
{
$request->validate([
'email' => 'required|string|email|exists:users,email',
'token' => 'required|string',
'password' => 'required|string|min:8|confirmed',
]);

try {
// Kiểm tra token
$resetRecord = \DB::table('password_reset_tokens')
->where('email', $request->email)
->first();

if (!$resetRecord) {
return back()->with('error', 'Token không hợp lệ hoặc đã hết hạn.');
}

// Kiểm tra token có trùng khớp không
if (!hash_equals($resetRecord->token, hash('sha256', $request->token))) {
return back()->with('error', 'Token không hợp lệ.');
}

// Kiểm tra token có hết hạn không (24 giờ)
if (now()->diffInHours($resetRecord->created_at) > 24) {
return back()->with('error', 'Link reset mật khẩu đã hết hạn.');
}

// Cập nhật mật khẩu người dùng
$user = User::where('email', $request->email)->first();
$user->update(['password' => Hash::make($request->password)]);

// Xóa token reset
\DB::table('password_reset_tokens')->where('email', $request->email)->delete();

Log::info('Password reset successfully for: ' . $request->email);

return redirect()->route('login')->with('success', 'Mật khẩu đã được đặt lại. Vui lòng đăng nhập.');
} catch (\Exception $e) {
Log::error('Reset password failed: ' . $e->getMessage());
return back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
}
}
}
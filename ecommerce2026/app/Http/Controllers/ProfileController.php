<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Mail\ChangePasswordOtp;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang hồ sơ cá nhân.
     */
    public function index()
    {
        $user = Auth::user();

        // Nếu là admin hoặc nhân viên giao hàng, hiển thị trang hồ sơ dành riêng cho nhân viên
        if (in_array($user->role, ['admin', 'delivery'])) {
            return view('profile.staff', compact('user'));
        }

        // Lấy lịch sử đơn hàng của user, sắp xếp mới nhất lên đầu
        $orders = $user->orders()->latest()->get();
        
        return view('profile.index', compact('user', 'orders'));
    }

    /**
     * Cập nhật thông tin cơ bản.
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Đồng bộ sang bảng staffs nếu đây là tài khoản nhân sự
        $staff = \App\Models\Staff::where(function ($query) use ($user) {
            if (!empty($user->cccd) && $user->cccd !== 'Chưa cập nhật') {
                $query->orWhere('cccd', $user->cccd);
            }
            if (!empty($user->phone) && $user->phone !== 'Chưa cập nhật') {
                $query->orWhere('phone', $user->phone);
            }
            if (!empty($user->name)) {
                $query->orWhere('name', $user->name);
            }
        })->first();

        if ($staff) {
            $staff->update([
                'name'    => $request->name,
                'phone'   => $request->phone ?: $staff->phone,
                'address' => $request->address ?: $staff->address,
            ]);
        }

        return redirect()->back()->with('success', 'Đã cập nhật thông tin thành công!');
    }

    /**
     * Tải lên và cập nhật ảnh đại diện (avatar).
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();
        $newAvatar = null;

        if ($request->filled('avatar_base64')) {
            $base64 = $request->input('avatar_base64');
            @list($type, $file_data) = explode(';', $base64);
            @list(, $file_data)      = explode(',', $file_data);
            $imageName = 'avatar_' . time() . '.jpg';
            
            // Xóa ảnh cũ nếu có
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            Storage::disk('public')->put('avatars/' . $imageName, base64_decode($file_data));
            $newAvatar = 'avatars/' . $imageName;
            $user->update(['avatar' => $newAvatar]);
        } elseif ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240', // Max 10MB
            ], [
                'avatar.required' => 'Vui lòng chọn ảnh đại diện.',
                'avatar.image' => 'Tệp tải lên phải là hình ảnh.',
                'avatar.mimes' => 'Hệ thống hỗ trợ định dạng: jpeg, png, jpg, gif, webp, svg.',
                'avatar.max' => 'Dung lượng ảnh tối đa là 10MB.',
            ]);

            // Xóa ảnh cũ nếu có
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Lưu ảnh mới
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $newAvatar = $avatarPath;
            $user->update(['avatar' => $newAvatar]);
        }

        if ($newAvatar) {
            // Đồng bộ avatar sang bảng staffs nếu có nhân sự tương ứng
            $staff = \App\Models\Staff::where(function ($query) use ($user) {
                if (!empty($user->cccd) && $user->cccd !== 'Chưa cập nhật') {
                    $query->orWhere('cccd', $user->cccd);
                }
                if (!empty($user->phone) && $user->phone !== 'Chưa cập nhật') {
                    $query->orWhere('phone', $user->phone);
                }
                if (!empty($user->name)) {
                    $query->orWhere('name', $user->name);
                }
            })->first();

            if ($staff) {
                $staff->update(['avatar' => $newAvatar]);
            }

            return redirect()->back()->with('success', 'Ảnh đại diện đã được cập nhật đồng bộ thành công!');
        }

        return redirect()->back()->with('error', 'Vui lòng chọn ảnh!');
    }

    /**
     * Tải lên và cập nhật ảnh bìa (banner) với hỗ trợ crop.
     */
    public function updateBanner(Request $request)
    {
        $user = Auth::user();
        $newBanner = null;

        try {
            if ($request->filled('banner_base64')) {
                $base64 = $request->input('banner_base64');
                @list($type, $file_data) = explode(';', $base64);
                @list(, $file_data)      = explode(',', $file_data);
                $imageName = 'banner_' . time() . '.jpg';
                
                // Xóa ảnh banner cũ nếu có trong storage
                if ($user->banner && Storage::disk('public')->exists($user->banner)) {
                    Storage::disk('public')->delete($user->banner);
                }
                
                Storage::disk('public')->put('banners/' . $imageName, base64_decode($file_data));
                $newBanner = 'banners/' . $imageName;
                $user->update(['banner' => $newBanner]);
            } elseif ($request->hasFile('banner')) {
                $request->validate([
                    'banner' => 'required|file|mimes:jpeg,png,jpg,gif,webp,svg,mp4,webm,ogg|max:204800',
                ], [
                    'banner.required' => 'Vui lòng chọn ảnh hoặc video nền.',
                    'banner.file'     => 'Tệp tải lên không hợp lệ.',
                    'banner.mimes'    => 'Hệ thống hỗ trợ định dạng: jpeg, png, jpg, gif, webp, svg, mp4, webm, ogg.',
                    'banner.max'      => 'Dung lượng tệp tối đa là 200MB.',
                ]);

                // Xóa banner cũ nếu có
                if ($user->banner && Storage::disk('public')->exists($user->banner)) {
                    Storage::disk('public')->delete($user->banner);
                }

                // Lưu banner mới
                $bannerPath = $request->file('banner')->store('banners', 'public');
                $newBanner = $bannerPath;
                $user->update(['banner' => $newBanner]);
            }

            if ($newBanner) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success'    => true,
                        'message'    => 'Nền profile (ảnh / video) đã được cập nhật thành công!',
                        'banner_url' => $user->fresh()->banner_url,
                        'is_video'   => $user->fresh()->is_banner_video,
                    ]);
                }
                return redirect()->back()->with('success', 'Nền profile (ảnh / video) đã được cập nhật thành công!');
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng chọn tệp ảnh hoặc video!'], 422);
            }
            return redirect()->back()->with('error', 'Vui lòng chọn ảnh hoặc video nền!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMsg = collect($e->errors())->flatten()->first() ?? 'Dữ liệu không hợp lệ.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Lỗi khi tải lên: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Lỗi tải lên: ' . $e->getMessage());
        }
    }

    /**
     * Bắt đầu quy trình đổi mật khẩu: Xác thực mật khẩu cũ & gửi OTP qua email.
     */
    public function sendPasswordOtp(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có tối thiểu 8 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
            'new_password.different' => 'Mật khẩu mới không được trùng với mật khẩu hiện tại.',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['current_password' => ['Mật khẩu hiện tại không chính xác.']]
                ], 422);
            }
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.'])->withInput();
        }

        if (empty($user->email)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản chưa được cấu hình email để nhận mã xác thực OTP.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Tài khoản chưa được cấu hình email để nhận mã OTP.');
        }

        // Tạo mã OTP 6 chữ số
        $otp = (string) mt_rand(100000, 999999);

        // Lưu thông tin đổi mật khẩu tạm vào session (mật khẩu cũ trong DB vẫn giữ nguyên)
        session([
            'password_change_pending' => [
                'user_id'             => $user->id,
                'new_password'        => Hash::make($request->new_password),
                'otp'                 => $otp,
                'expires_at'          => now()->addMinutes(15),
                'resend_available_at' => now()->addSeconds(60),
            ]
        ]);

        // Gửi email OTP
        try {
            Mail::to($user->email)->send(new ChangePasswordOtp($otp, $user->name));
            Log::info("Change password OTP sent to: {$user->email}");
        } catch (\Exception $e) {
            Log::error("Send change password OTP failed: " . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể gửi email chứa mã OTP. Vui lòng kiểm tra lại kết nối mạng hoặc thử lại sau.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Không thể gửi email xác thực OTP. Vui lòng thử lại sau.');
        }

        $maskedEmail = $this->maskEmail($user->email);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Mã OTP gồm 6 chữ số đã được gửi đến email ' . $maskedEmail . '. Vui lòng nhập mã để hoàn tất đổi mật khẩu.',
                'masked_email' => $maskedEmail,
            ]);
        }

        return redirect()->back()
            ->with('show_password_otp_modal', true)
            ->with('masked_email', $maskedEmail)
            ->with('success', 'Mã xác thực OTP đã được gửi đến email ' . $maskedEmail . '. Vui lòng kiểm tra hộp thư.');
    }

    /**
     * Cập nhật mật khẩu: Hỗ trợ cả form thông thường (chuyển tiếp sang sendPasswordOtp).
     */
    public function updatePassword(Request $request)
    {
        return $this->sendPasswordOtp($request);
    }

    /**
     * Xác thực mã OTP và lưu mật khẩu mới.
     */
    public function verifyPasswordOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Vui lòng nhập mã xác thực OTP.',
            'otp.size'     => 'Mã OTP phải gồm đúng 6 chữ số.',
        ]);

        $pending = session('password_change_pending');

        if (!$pending || $pending['user_id'] !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên đổi mật khẩu đã hết hạn hoặc không tồn tại. Vui lòng thực hiện lại từ đầu.'
            ], 400);
        }

        if (now()->gt($pending['expires_at'])) {
            session()->forget('password_change_pending');
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP đã hết hạn (quá 15 phút). Vui lòng yêu cầu mã mới.'
            ], 400);
        }

        if (trim($request->otp) !== $pending['otp']) {
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP không chính xác. Mật khẩu hiện tại của bạn vẫn được giữ nguyên.'
            ], 422);
        }

        // OTP hợp lệ -> Chính thức cập nhật mật khẩu mới
        $user = Auth::user();
        $user->update([
            'password' => $pending['new_password'],
        ]);

        // Dọn dẹp session
        session()->forget('password_change_pending');
        Log::info("Password successfully updated via OTP for user ID: {$user->id}");

        return response()->json([
            'success' => true,
            'message' => '🎉 Mật khẩu đã được cập nhật thành công!'
        ]);
    }

    /**
     * Gửi lại mã OTP đổi mật khẩu (có rate limit 60 giây).
     */
    public function resendPasswordOtp(Request $request)
    {
        $pending = session('password_change_pending');

        if (!$pending || $pending['user_id'] !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên đổi mật khẩu đã hết hạn. Vui lòng nhập lại mật khẩu mới.'
            ], 400);
        }

        if (now()->lt($pending['resend_available_at'])) {
            $remaining = now()->diffInSeconds($pending['resend_available_at']);
            return response()->json([
                'success' => false,
                'message' => "Vui lòng chờ {$remaining} giây nữa trước khi gửi lại mã OTP."
            ], 429);
        }

        // Tạo OTP mới
        $otp = (string) mt_rand(100000, 999999);
        $pending['otp'] = $otp;
        $pending['expires_at'] = now()->addMinutes(15);
        $pending['resend_available_at'] = now()->addSeconds(60);
        session(['password_change_pending' => $pending]);

        $user = Auth::user();
        try {
            Mail::to($user->email)->send(new ChangePasswordOtp($otp, $user->name));
            Log::info("Change password OTP resent to: {$user->email}");
        } catch (\Exception $e) {
            Log::error("Resend change password OTP failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi lại email. Vui lòng kiểm tra lại kết nối mạng hoặc thử lại sau.'
            ], 500);
        }

        return response()->json([
            'success'      => true,
            'message'      => 'Mã OTP mới đã được gửi lại vào email của bạn.',
            'masked_email' => $this->maskEmail($user->email),
        ]);
    }

    /**
     * Rút gọn email hiển thị dạng bảo mật (vd: du***1@gmail.com).
     */
    private function maskEmail($email)
    {
        if (!$email || !str_contains($email, '@')) return '***@***';
        list($username, $domain) = explode('@', $email, 2);
        $len = strlen($username);
        if ($len <= 2) {
            $maskedUser = substr($username, 0, 1) . '***';
        } else {
            $maskedUser = substr($username, 0, 2) . str_repeat('*', min(4, max(1, $len - 3))) . substr($username, -1);
        }
        return $maskedUser . '@' . $domain;
    }

    /**
     * Bắt buộc đổi mật khẩu (Dành cho tài khoản cấp lần đầu).
     */
    public function showForceChangePassword()
    {
        // Chặn nếu user không cần đổi
        if (!Auth::user()->must_change_password) {
            return redirect()->route('welcome');
        }
        return view('auth.force-change-password');
    }

    public function forceUpdatePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        $user->update([
            'password' => $request->new_password,
            'must_change_password' => false,
        ]);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Mật khẩu đã được cập nhật. Chào mừng!');
        } elseif ($user->role === 'delivery') {
            return redirect()->route('delivery.index')->with('success', 'Mật khẩu đã được cập nhật. Chào mừng!');
        }
        return redirect()->route('welcome')->with('success', 'Mật khẩu đã được cập nhật!');
    }
    public function confirmOrderReceived(\App\Models\Order $order)
    {
        // Kiểm tra xem đơn hàng có thuộc về user hiện tại không
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        // Cập nhật trạng thái
        $order->update([
            'status' => 'completed',
            'delivery_status' => 'customer_confirmed',
        ]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã xác nhận. Đơn hàng đã được đánh dấu là hoàn thành!');
    }
}

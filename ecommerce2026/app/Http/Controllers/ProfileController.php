<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

        return redirect()->back()->with('success', 'Đã cập nhật thông tin thành công!');
    }

    /**
     * Tải lên và cập nhật ảnh đại diện (avatar).
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

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
            $user->update(['avatar' => 'avatars/' . $imageName]);
            
            return redirect()->back()->with('success', 'Ảnh đại diện đã được cập nhật!');
        } elseif ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            ]);

            // Xóa ảnh cũ nếu có
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Lưu ảnh mới
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $avatarPath]);
            
            return redirect()->back()->with('success', 'Ảnh đại diện đã được cập nhật!');
        }

        return redirect()->back()->with('error', 'Vui lòng chọn ảnh!');
    }

    /**
     * Cập nhật mật khẩu.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed', // field name for confirmation should be new_password_confirmation
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Mật khẩu đã được cập nhật thành công!');
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
            'password' => Hash::make($request->new_password),
            'must_change_password' => false,
        ]);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Mật khẩu đã được cập nhật. Chào mừng!');
        } elseif ($user->role === 'delivery') {
            return redirect()->route('delivery.index')->with('success', 'Mật khẩu đã được cập nhật. Chào mừng!');
        }
        return redirect()->route('welcome')->with('success', 'Mật khẩu đã được cập nhật!');
    }
}

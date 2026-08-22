<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $qrCode = Setting::where('key', 'payment_qr_code')->first();
        return view('admin.settings.index', compact('qrCode'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $setting = Setting::firstOrCreate(['key' => 'payment_qr_code']);

        if ($request->hasFile('qr_code')) {
            if ($setting->value) {
                Storage::disk('public')->delete($setting->value);
            }
            $path = $request->file('qr_code')->store('settings', 'public');
            $setting->update(['value' => $path]);
        }

        return back()->with('success', 'Mã QR đã được cập nhật thành công!');
    }
}

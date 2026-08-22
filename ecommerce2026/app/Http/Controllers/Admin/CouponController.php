<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('id', 'desc')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
            'required_tier' => 'nullable|string',
            'is_auto_apply' => 'required|boolean',
        ]);

        Coupon::create($request->all());

        return redirect()->back()->with('success', 'Đã thêm Voucher mới thành công!');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
            'required_tier' => 'nullable|string',
            'is_auto_apply' => 'required|boolean',
        ]);

        $coupon->update($request->all());

        return redirect()->back()->with('success', 'Đã cập nhật Voucher thành công!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->back()->with('success', 'Đã xóa Voucher thành công!');
    }
}

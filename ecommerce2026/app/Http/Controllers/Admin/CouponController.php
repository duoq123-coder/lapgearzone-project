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
            'code'                => 'required|string|unique:coupons,code',
            'type'                => 'required|in:fixed,percent',
            'value'               => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_order_value'     => 'required|numeric|min:0',
            'usage_limit'         => 'nullable|integer|min:1',
            'starts_at'           => 'nullable|date',
            'ends_at'             => 'nullable|date|after_or_equal:starts_at',
            'is_active'           => 'required|boolean',
            'required_tier'       => 'nullable|string',
            'is_auto_apply'       => 'required|boolean',
        ]);

        $data = $this->prepareCouponData($request);

        Coupon::create($data);

        return redirect()->back()->with('success', 'Đã thêm Voucher mới thành công!');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'                => 'required|string|unique:coupons,code,' . $coupon->id,
            'type'                => 'required|in:fixed,percent',
            'value'               => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_order_value'     => 'required|numeric|min:0',
            'usage_limit'         => 'nullable|integer|min:1',
            'starts_at'           => 'nullable|date',
            'ends_at'             => 'nullable|date|after_or_equal:starts_at',
            'is_active'           => 'required|boolean',
            'required_tier'       => 'nullable|string',
            'is_auto_apply'       => 'required|boolean',
        ]);

        $data = $this->prepareCouponData($request);

        $coupon->update($data);

        return redirect()->back()->with('success', 'Đã cập nhật Voucher thành công!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->back()->with('success', 'Đã xóa Voucher thành công!');
    }

    /**
     * Chuẩn hóa dữ liệu coupon trước khi lưu database.
     */
    protected function prepareCouponData(Request $request): array
    {
        $data = $request->only([
            'code',
            'type',
            'value',
            'max_discount_amount',
            'min_order_value',
            'usage_limit',
            'starts_at',
            'ends_at',
            'is_active',
            'required_tier',
            'is_auto_apply',
        ]);

        $data['code'] = strtoupper(trim($data['code']));

        // Xử lý các giá trị rỗng thành null để MySQL lưu đúng định dạng
        if (empty($data['starts_at'])) {
            $data['starts_at'] = null;
        }

        if (empty($data['ends_at'])) {
            $data['ends_at'] = null;
        }

        if (empty($data['max_discount_amount']) || $data['type'] === 'fixed') {
            $data['max_discount_amount'] = null;
        }

        if (empty($data['usage_limit'])) {
            $data['usage_limit'] = null;
        }

        if (empty($data['required_tier'])) {
            $data['required_tier'] = null;
        }

        return $data;
    }
}

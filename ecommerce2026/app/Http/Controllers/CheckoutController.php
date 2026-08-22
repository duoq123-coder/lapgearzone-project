<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $discountAmount = Session::get('discount_amount', 0);
        $couponCode = Session::get('coupon_code');
        $finalTotal = max(0, $total - $discountAmount);

        return view('checkout.index', compact('cartItems', 'total', 'discountAmount', 'couponCode', 'finalTotal'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'payment_method' => 'required|in:vnpay,cod_install',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $discountAmount = Session::get('discount_amount', 0);
        $couponCode = Session::get('coupon_code');
        $finalTotal = max(0, $total - $discountAmount);

        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_price' => $finalTotal,
            'coupon_code' => $couponCode,
            'discount_amount' => $discountAmount,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
        ]);

        // Tạo order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Xóa giỏ hàng và session coupon
        Cart::where('user_id', Auth::id())->delete();
        
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon) {
                $coupon->increment('used');
            }
        }
        Session::forget(['coupon_code', 'discount_amount']);

        if ($request->payment_method === 'cod_install') {
            // Đặt mặc định cash_remitted = false cho đơn COD (nhân viên chưa nộp tiền về công ty)
            $order->update(['cash_remitted' => false]);
            return redirect()->route('welcome')->with('success', 'Đặt hàng thành công! Nhân viên sẽ liên hệ để giao hàng và lắp đặt tận nơi.');
        }

        return redirect()->route('checkout.payment', $order->id);
    }

    public function payment(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $qrCode = Setting::where('key', 'payment_qr_code')->first();

        return view('checkout.payment', compact('order', 'qrCode'));
    }
}

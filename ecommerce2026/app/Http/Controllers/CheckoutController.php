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
            'payment_method' => 'required|in:payos,vnpay,cod_install',
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

        $paymentMethod = $request->payment_method === 'vnpay' ? 'payos' : $request->payment_method;
        $initialStatus = ($paymentMethod === 'cod_install') ? 'processing' : 'pending';

        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_price' => $finalTotal,
            'coupon_code' => $couponCode,
            'discount_amount' => $discountAmount,
            'status' => $initialStatus,
            'payment_method' => $paymentMethod,
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
            return redirect()->route('welcome')->with('success', 'Đặt hàng thành công! Nhân viên sẽ liên hệ để giao hàng tận nơi.');
        }

        // Phương thức thanh toán tự động PayOS
        $payOSService = app(\App\Services\PayOSService::class);
        if ($payOSService->isConfigured()) {
            try {
                $paymentLink = $payOSService->createPaymentLink($order);
                if (!empty($paymentLink->checkoutUrl)) {
                    return redirect($paymentLink->checkoutUrl);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('PayOS redirect error: ' . $e->getMessage());
            }
        }

        return redirect()->route('checkout.payment', $order->id);
    }

    public function payment(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status === 'paid') {
            return redirect()->route('checkout.payos.success', ['order_id' => $order->id]);
        }

        $qrCode = Setting::where('key', 'payment_qr_code')->first();
        $payOSService = app(\App\Services\PayOSService::class);
        $payOSConfigured = $payOSService->isConfigured();

        // Thử lấy link PayOS nếu chưa có
        $payOSCheckoutUrl = null;
        if ($payOSConfigured) {
            try {
                if (!$order->payos_order_code) {
                    $link = $payOSService->createPaymentLink($order);
                    $payOSCheckoutUrl = $link->checkoutUrl ?? null;
                } else {
                    $info = $payOSService->getPaymentLinkInformation($order->payos_order_code);
                    if ($info && isset($info->status) && $info->status === 'PAID') {
                        $order->update(['status' => 'paid']);
                        return redirect()->route('checkout.payos.success', ['order_id' => $order->id]);
                    }
                }
            } catch (\Exception $e) {
                // Tiếp tục hiển thị trang thanh toán PayOS
            }
        }

        return view('checkout.payment', compact('order', 'qrCode', 'payOSConfigured', 'payOSCheckoutUrl'));
    }
}

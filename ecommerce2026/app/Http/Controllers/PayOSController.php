<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PayOSService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PayOSController extends Controller
{
    protected PayOSService $payOSService;

    public function __construct(PayOSService $payOSService)
    {
        $this->payOSService = $payOSService;
    }

    /**
     * Webhook tiếp nhận thông báo thanh toán tự động từ PayOS
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        Log::info('PayOS Webhook received: ', $payload);

        // Trường hợp PayOS gửi request kiểm tra/xác nhận Webhook endpoint (Test ping)
        if (isset($payload['desc']) && str_contains(strtolower($payload['desc']), 'webhook')) {
            return response()->json([
                'success' => true,
                'message' => 'PayOS Webhook endpoint is active and verified.',
            ], 200);
        }

        try {
            // Xác minh tính toàn vẹn và chữ ký của Webhook
            $verifiedData = $this->payOSService->verifyWebhookData($payload);

            if ($verifiedData) {
                $orderCode = $verifiedData->orderCode ?? null;
                $code      = $verifiedData->code ?? '00';

                // Code "00" nghĩa là giao dịch thanh toán thành công
                if ($orderCode && $code === '00') {
                    $order = Order::where('payos_order_code', $orderCode)->first()
                        ?? Order::find($orderCode);

                    if ($order) {
                        if ($order->status !== 'paid') {
                            $order->update(['status' => 'paid']);
                            Log::info("PayOS Webhook: Đơn hàng #{$order->id} đã được tự động xác nhận thanh toán thành công!", [
                                'order_id'   => $order->id,
                                'order_code' => $orderCode,
                                'amount'     => $verifiedData->amount ?? 0,
                            ]);
                        }
                    } else {
                        Log::warning("PayOS Webhook: Không tìm thấy đơn hàng với orderCode: {$orderCode}");
                    }
                }
            }

            return response()->json(['success' => true], 200);
        } catch (Exception $e) {
            Log::error('PayOS Webhook verification failed: ' . $e->getMessage(), ['payload' => $payload]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid signature or payload: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Khách hàng quay về sau khi hoàn tất thanh toán trên PayOS
     */
    public function success(Request $request)
    {
        $orderId   = $request->query('order_id');
        $orderCode = $request->query('orderCode');
        $status    = $request->query('status');

        $order = null;
        if ($orderId) {
            $order = Order::find($orderId);
        } elseif ($orderCode) {
            $order = Order::where('payos_order_code', $orderCode)->first();
        }

        if ($order) {
            // Kiểm tra và cập nhật trạng thái nếu PayOS báo đã thanh toán
            if ($status === 'PAID' || $request->query('code') === '00') {
                if ($order->status !== 'paid') {
                    $order->update(['status' => 'paid']);
                }
            } else {
                // Đối soát trực tiếp với PayOS API để đảm bảo chắc chắn (hữu ích cho localhost)
                if ($order->payos_order_code && $order->status !== 'paid') {
                    $paymentInfo = $this->payOSService->getPaymentLinkInformation($order->payos_order_code);
                    if ($paymentInfo && isset($paymentInfo->status) && $paymentInfo->status === 'PAID') {
                        $order->update(['status' => 'paid']);
                    }
                }
            }

            return view('checkout.payos_success', compact('order'));
        }

        return redirect()->route('welcome')->with('success', 'Thanh toán đơn hàng thành công!');
    }

    /**
     * Khách hàng bấm Hủy thanh toán trên giao diện PayOS
     */
    public function cancel(Request $request)
    {
        $orderId = $request->query('order_id');
        $order   = $orderId ? Order::find($orderId) : null;

        return view('checkout.payos_cancel', compact('order'));
    }

    /**
     * API kiểm tra trạng thái thanh toán thời gian thực (cho frontend polling nếu cần)
     */
    public function checkStatus(Order $order)
    {
        if (Auth::check() && $order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Nếu đơn hàng chưa paid, thử query PayOS API 1 lần
        if ($order->status !== 'paid' && $order->payos_order_code) {
            $paymentInfo = $this->payOSService->getPaymentLinkInformation($order->payos_order_code);
            if ($paymentInfo && isset($paymentInfo->status) && $paymentInfo->status === 'PAID') {
                $order->update(['status' => 'paid']);
            }
        }

        return response()->json([
            'order_id' => $order->id,
            'status'   => $order->status,
            'is_paid'  => $order->status === 'paid',
        ]);
    }
}

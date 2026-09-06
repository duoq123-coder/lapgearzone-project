<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PayOS\PayOS;

class PayOSService
{
    protected ?PayOS $payOS = null;

    /**
     * Khởi tạo PayOS client từ cấu hình CSDL hoặc file .env
     */
    public function __construct()
    {
        $clientId    = Setting::getValue('payos_client_id') ?: config('payos.client_id');
        $apiKey      = Setting::getValue('payos_api_key') ?: config('payos.api_key');
        $checksumKey = Setting::getValue('payos_checksum_key') ?: config('payos.checksum_key');

        if (!empty($clientId) && !empty($apiKey) && !empty($checksumKey)) {
            $this->payOS = new PayOS($clientId, $apiKey, $checksumKey);
        }
    }

    /**
     * Kiểm tra xem các khóa API PayOS đã được cấu hình đầy đủ chưa
     */
    public function isConfigured(): bool
    {
        return $this->payOS !== null;
    }

    /**
     * Lấy client PayOS
     */
    public function getClient(): ?PayOS
    {
        return $this->payOS;
    }

    /**
     * Lấy các thông số cấu hình hiện tại (để hiển thị trong Admin)
     */
    public static function getCredentials(): array
    {
        return [
            'client_id'    => Setting::getValue('payos_client_id') ?: config('payos.client_id', ''),
            'api_key'      => Setting::getValue('payos_api_key') ?: config('payos.api_key', ''),
            'checksum_key' => Setting::getValue('payos_checksum_key') ?: config('payos.checksum_key', ''),
        ];
    }

    /**
     * Tạo link thanh toán PayOS cho đơn hàng
     */
    public function createPaymentLink(Order $order)
    {
        if (!$this->isConfigured()) {
            throw new Exception('Cổng thanh toán PayOS chưa được cấu hình Client ID, API Key hoặc Checksum Key.');
        }

        // Tạo orderCode số nguyên duy nhất cho PayOS (yêu cầu số nguyên, tối đa 9007199254740991)
        // Dùng 6 số cuối của timestamp + 3 số cuối ID đơn hàng
        $timestampPart = intval(substr(time(), -6));
        $orderPart     = intval($order->id % 1000);
        $orderCode     = intval($timestampPart . str_pad($orderPart, 3, '0', STR_PAD_LEFT));

        $order->update(['payos_order_code' => $orderCode]);

        // Chuẩn bị danh sách sản phẩm
        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'name'     => Str::limit($item->product->name ?? 'LapGearZone Laptop', 40, ''),
                'quantity' => (int) $item->quantity,
                'price'    => (int) $item->price,
            ];
        }

        if (empty($items)) {
            $items[] = [
                'name'     => 'Don hang #' . $order->id,
                'quantity' => 1,
                'price'    => (int) $order->total_price,
            ];
        }

        // Mô tả tối đa 25 ký tự, không dấu, không ký tự đặc biệt
        $description = 'Thanh toan DH' . $order->id;
        if (strlen($description) > 25) {
            $description = substr($description, 0, 25);
        }

        $paymentData = [
            'orderCode'   => $orderCode,
            'amount'      => (int) $order->total_price,
            'description' => $description,
            'buyerName'   => $order->name,
            'buyerPhone'  => $order->phone,
            'buyerAddress'=> Str::limit($order->address, 100, ''),
            'items'       => $items,
            'returnUrl'   => route('checkout.payos.success') . '?order_id=' . $order->id,
            'cancelUrl'   => route('checkout.payos.cancel') . '?order_id=' . $order->id,
        ];

        try {
            $response = $this->payOS->paymentRequests->create($paymentData);

            if (isset($response->paymentLinkId)) {
                $order->update(['payos_payment_link_id' => $response->paymentLinkId]);
            }

            return $response;
        } catch (Exception $e) {
            Log::error('PayOS Create Payment Link Error: ' . $e->getMessage(), [
                'order_id'   => $order->id,
                'order_code' => $orderCode,
            ]);
            throw $e;
        }
    }

    /**
     * Tra cứu thông tin link thanh toán từ PayOS
     */
    public function getPaymentLinkInformation($orderCode)
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            return $this->payOS->paymentRequests->get($orderCode);
        } catch (Exception $e) {
            Log::error('PayOS Get Payment Info Error: ' . $e->getMessage(), ['order_code' => $orderCode]);
            return null;
        }
    }

    /**
     * Xác minh dữ liệu Webhook từ PayOS
     */
    public function verifyWebhookData(array $payload)
    {
        if (!$this->isConfigured()) {
            throw new Exception('PayOS chưa được cấu hình.');
        }

        return $this->payOS->webhooks->verify($payload);
    }

    /**
     * Đăng ký và xác nhận Webhook URL với PayOS API
     */
    public function confirmWebhook(string $webhookUrl)
    {
        if (!$this->isConfigured()) {
            throw new Exception('PayOS chưa được cấu hình.');
        }

        return $this->payOS->webhooks->confirm($webhookUrl);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\PayOSService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    protected PayOSService $payOSService;

    public function __construct(PayOSService $payOSService)
    {
        $this->payOSService = $payOSService;
    }

    public function index()
    {
        $credentials = PayOSService::getCredentials();
        $payosClientId    = $credentials['client_id'];
        $payosApiKey      = $credentials['api_key'];
        $payosChecksumKey = $credentials['checksum_key'];

        $isConfigured = !empty($payosClientId) && !empty($payosApiKey) && !empty($payosChecksumKey);
        $webhookUrl   = url('/api/payos/webhook');

        $bankAccountNumber = Setting::getValue('bank_account_number', '03468844158888');
        $bankName          = Setting::getValue('bank_name', 'MB Bank (Quân Đội)');
        $bankAccountName   = Setting::getValue('bank_account_name', 'NGUYEN QUY DUONG');

        return view('admin.settings.index', compact(
            'payosClientId',
            'payosApiKey',
            'payosChecksumKey',
            'isConfigured',
            'webhookUrl',
            'bankAccountNumber',
            'bankName',
            'bankAccountName'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'payos_client_id'     => 'nullable|string|max:255',
            'payos_api_key'       => 'nullable|string|max:255',
            'payos_checksum_key'  => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_name'           => 'nullable|string|max:100',
            'bank_account_name'   => 'nullable|string|max:100',
        ]);

        // Lưu thông tin tài khoản ngân hàng
        if ($request->has('bank_account_number')) {
            Setting::setValue('bank_account_number', trim($request->input('bank_account_number')));
        }
        if ($request->has('bank_name')) {
            Setting::setValue('bank_name', trim($request->input('bank_name')));
        }
        if ($request->has('bank_account_name')) {
            Setting::setValue('bank_account_name', trim($request->input('bank_account_name')));
        }

        // Lưu thông tin cấu hình PayOS
        if ($request->has('payos_client_id')) {
            Setting::setValue('payos_client_id', trim($request->input('payos_client_id')));
        }
        if ($request->has('payos_api_key')) {
            Setting::setValue('payos_api_key', trim($request->input('payos_api_key')));
        }
        if ($request->has('payos_checksum_key')) {
            Setting::setValue('payos_checksum_key', trim($request->input('payos_checksum_key')));
        }

        return back()->with('success', 'Cài đặt cổng thanh toán đã được cập nhật thành công!');
    }

    /**
     * Xác nhận và đăng ký Webhook URL với PayOS
     */
    public function confirmPayOSWebhook(Request $request)
    {
        $webhookUrl = $request->input('webhook_url', url('/api/payos/webhook'));

        try {
            $payOSService = app(PayOSService::class);
            if (!$payOSService->isConfigured()) {
                return back()->with('error', 'Vui lòng lưu Client ID, API Key và Checksum Key trước khi xác nhận Webhook.');
            }

            $response = $payOSService->confirmWebhook($webhookUrl);

            return back()->with('success', "Xác nhận Webhook URL với PayOS thành công! ({$webhookUrl})");
        } catch (Exception $e) {
            return back()->with('error', 'Lỗi xác nhận Webhook với PayOS: ' . $e->getMessage() . '. Nếu bạn đang chạy trên localhost, vui lòng dùng ngrok/localtunnel để có link HTTPS công khai.');
        }
    }
}

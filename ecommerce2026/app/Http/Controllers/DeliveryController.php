<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\ProductExport;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function index()
    {
        // Get user profile (Delivery staff)
        $user = Auth::user();

        // Get assigned orders
        $allOrders = Order::where('delivery_staff_id', $user->id)
            ->with(['items.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $currentTasks = $allOrders->whereIn('delivery_status', ['assigned', 'issue']);
        $historyTasks = $allOrders->whereIn('delivery_status', ['completed', 'done', 'cancelled']);

        return view('delivery.index', compact('user', 'currentTasks', 'historyTasks'));
    }

    public function reportIssue(Request $request, $orderId)
    {
        $request->validate([
            'delivery_issue' => 'required|string|max:1000',
            'issue_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        $order = Order::findOrFail($orderId);

        // Verify that this order belongs to this delivery staff or admin
        if ($order->delivery_staff_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền cập nhật đơn hàng này.');
        }

        $proofPaths = [];

        if ($request->hasFile('issue_images')) {
            $path = public_path('uploads/delivery_proofs');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            foreach ($request->file('issue_images') as $file) {
                $filename = time() . '_issue_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $filename);
                $proofPaths[] = 'uploads/delivery_proofs/' . $filename;
            }
        }

        $existingProofs = $order->delivery_proof ?? [];
        if (!is_array($existingProofs)) {
            $existingProofs = json_decode($existingProofs, true) ?? [];
        }
        $allProofs = array_merge($existingProofs, $proofPaths);

        $order->update([
            'delivery_issue' => $request->delivery_issue,
            'delivery_status' => 'issue',
            'delivery_proof' => $allProofs,
        ]);

        return redirect()->back()->with('success', 'Đã gửi báo cáo sự cố thành công!');
    }

    public function uploadProof(Request $request, $orderId)
    {
        $request->validate([
            'proof_images' => 'required|array|min:1',
            'proof_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB per image
        ]);

        $order = Order::findOrFail($orderId);

        // Verify that this order belongs to this delivery staff or admin
        if ($order->delivery_staff_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền cập nhật đơn hàng này.');
        }

        $proofPaths = [];

        if ($request->hasFile('proof_images')) {
            $path = public_path('uploads/delivery_proofs');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            foreach ($request->file('proof_images') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $filename);
                $proofPaths[] = 'uploads/delivery_proofs/' . $filename;
            }
        }

        // Merge with existing proofs if any, or overwrite. Let's just overwrite for now or merge
        $existingProofs = $order->delivery_proof ?? [];
        if (!is_array($existingProofs)) {
            $existingProofs = json_decode($existingProofs, true) ?? [];
        }
        $allProofs = array_merge($existingProofs, $proofPaths);

        $order->update([
            'delivery_proof' => $allProofs,
            'delivery_status' => 'completed',
        ]);

        return redirect()->back()->with('success', 'Đã tải lên minh chứng giao hàng/lắp đặt thành công!');
    }
}

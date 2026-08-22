<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Toggle wishlist status for a product.
     */
    public function toggle(Product $product)
    {
        $userId = Auth::id();
        $wishlist = Wishlist::where('user_id', $userId)->where('product_id', $product->id)->first();

        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
            $message = 'Đã xóa khỏi danh sách yêu thích.';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $product->id,
            ]);
            $status = 'added';
            $message = 'Đã thêm vào danh sách yêu thích!';
        }

        if (request()->ajax()) {
            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller 
{ 
    /** 
     * 1. Hiển thị danh sách danh mục trong Admin (Đã thêm Tìm kiếm)
     */ 
    public function index(Request $request) 
    { 
        $query = Category::query();

        // Kiểm tra nếu có từ khóa tìm kiếm thì lọc theo tên danh mục
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Đổi 'desc' thành 'asc' để ID sắp xếp từ Min -> Max (1, 2, 3...)
        $categories = $query->orderBy('id', 'asc')->paginate(10)->withQueryString(); 

        return view('admin.categories.index', compact('categories')); 
    } 
 
    /** 
     * 2. Hiển thị form thêm mới danh mục
     */ 
    public function create() 
    { 
        return view('admin.categories.create'); 
    } 
 
    /** 
     * 3. Xử lý lưu danh mục mới vào database
     */ 
    public function store(Request $request) 
    { 
        $validatedData = $request->validate([ 
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'nullable|string|max:255',
        ]); 
 
        Category::create($validatedData); 
 
        return redirect()->route('admin.categories.index') 
            ->with('success', 'Thêm danh mục thành công.'); 
    } 
 
    /** 
     * 4. Hiển thị chi tiết danh mục
     */ 
    public function show(Category $category) 
    { 
        return view('admin.categories.show', compact('category'));
    } 
 
    /** 
     * 5. Hiển thị form chỉnh sửa danh mục
     */ 
    public function edit(Category $category) 
    { 
        return view('admin.categories.edit', compact('category')); 
    } 
 
    /** 
     * 6. Xử lý cập nhật danh mục
     */ 
    public function update(Request $request, Category $category) 
    { 
        $validatedData = $request->validate([ 
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:255',
        ]); 
 
        $category->update($validatedData); 
 
        return redirect()->route('admin.categories.index') 
            ->with('success', 'Cập nhật danh mục thành công.'); 
    } 
 
    /** 
     * 7. Xóa danh mục
     */ 
    public function destroy(Category $category) 
    { 
        // Kiểm tra xem danh mục có sản phẩm liên quan không trước khi xóa 
        if ($category->products()->exists()) { 
            return redirect()->route('admin.categories.index') 
                ->with('error', 'Không thể xóa danh mục đang chứa sản phẩm.');
        } 
 
        $category->delete(); 
 
        return redirect()->route('admin.categories.index') 
            ->with('success', 'Xóa danh mục thành công.'); 
    } 
}
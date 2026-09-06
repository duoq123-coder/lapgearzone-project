<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    /**
     * Display the Hero Banner management page.
     */
    public function index()
    {
        $bannerType     = Setting::getValue('hero_banner_type', 'video');
        $bannerVideo    = Setting::getValue('hero_banner_video', 'videos/laptop_hero.mp4');
        $bannerImage    = Setting::getValue('hero_banner_image', 'images/laptop_hero_banner.jpg');
        $bannerBrand    = Setting::getValue('hero_banner_brand', 'LapGearZone');
        $bannerBadge    = Setting::getValue('hero_banner_badge', 'Flagship Workstation & Gaming 2026');
        $bannerHeadline = Setting::getValue('hero_banner_headline', 'Đẳng Cấp Laptop Cho Không Gian Đỉnh Cao');
        $bannerSubtext  = Setting::getValue('hero_banner_subtext', 'Khám phá thế hệ laptop mới nhất với vi xử lý AI tiên phong, màn hình OLED siêu sắc nét và thời lượng pin đột phá. Sẵn sàng đồng hành cùng mọi ý tưởng lớn.');
        $bannerBtnText  = Setting::getValue('hero_banner_btn_text', 'Khám phá ngay');
        $bannerBtnUrl   = Setting::getValue('hero_banner_btn_url', '#product-grid-section');
        $bannerOverlay  = Setting::getValue('hero_banner_overlay', '0.75');

        // Preset video options
        $presetVideos = [
            'videos/laptop_hero.mp4' => 'Góc làm việc hiện đại & màn hình laptop (Khuyên dùng)',
            'videos/laptop_1781.mp4' => 'Gõ phím laptop Workstation cao cấp',
            'videos/laptop_1730.mp4' => 'Laptop code & cà phê công nghệ',
        ];

        return view('admin.banner.index', compact(
            'bannerType',
            'bannerVideo',
            'bannerImage',
            'bannerBrand',
            'bannerBadge',
            'bannerHeadline',
            'bannerSubtext',
            'bannerBtnText',
            'bannerBtnUrl',
            'bannerOverlay',
            'presetVideos'
        ));
    }

    /**
     * Update the Hero Banner settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'hero_banner_type'       => 'required|in:video,image',
            'hero_banner_brand'      => 'nullable|string|max:100',
            'hero_banner_badge'      => 'nullable|string|max:150',
            'hero_banner_headline'   => 'nullable|string|max:255',
            'hero_banner_subtext'    => 'nullable|string|max:600',
            'hero_banner_btn_text'   => 'nullable|string|max:100',
            'hero_banner_btn_url'    => 'nullable|string|max:255',
            'hero_banner_overlay'    => 'nullable|numeric|between:0.1,1.0',
            'hero_banner_video_file' => 'nullable|file|mimes:mp4,webm,ogg,mov|max:102400',
            'hero_banner_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:15360',
            'hero_banner_video_url'  => 'nullable|string|max:500',
            'hero_banner_video_preset' => 'nullable|string|max:255',
        ]);

        // Upload folder
        $uploadDir = public_path('uploads/banners');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        // 1. Handle Video Upload / Preset / URL
        if ($request->hasFile('hero_banner_video_file')) {
            $videoFile = $request->file('hero_banner_video_file');
            $videoName = 'banner_video_' . time() . '.' . $videoFile->getClientOriginalExtension();
            $videoFile->move($uploadDir, $videoName);
            Setting::setValue('hero_banner_video', 'uploads/banners/' . $videoName);
        } elseif ($request->filled('hero_banner_video_url')) {
            Setting::setValue('hero_banner_video', trim($request->input('hero_banner_video_url')));
        } elseif ($request->filled('hero_banner_video_preset')) {
            Setting::setValue('hero_banner_video', trim($request->input('hero_banner_video_preset')));
        }

        // 2. Handle Image Upload
        if ($request->hasFile('hero_banner_image_file')) {
            $imageFile = $request->file('hero_banner_image_file');
            $imageName = 'banner_img_' . time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move($uploadDir, $imageName);
            Setting::setValue('hero_banner_image', 'uploads/banners/' . $imageName);
        }

        // 3. Save Text & Configuration Settings
        Setting::setValue('hero_banner_type', $request->input('hero_banner_type', 'video'));
        Setting::setValue('hero_banner_brand', trim($request->input('hero_banner_brand', 'LapGearZone')));
        Setting::setValue('hero_banner_badge', trim($request->input('hero_banner_badge', 'Flagship Workstation & Gaming 2026')));
        Setting::setValue('hero_banner_headline', trim($request->input('hero_banner_headline', 'Đẳng Cấp Laptop Cho Không Gian Đỉnh Cao')));
        Setting::setValue('hero_banner_subtext', trim($request->input('hero_banner_subtext', 'Khám phá thế hệ laptop mới nhất với vi xử lý AI tiên phong, màn hình OLED siêu sắc nét và thời lượng pin đột phá. Sẵn sàng đồng hành cùng mọi ý tưởng lớn.')));
        Setting::setValue('hero_banner_btn_text', trim($request->input('hero_banner_btn_text', 'Khám phá ngay')));
        Setting::setValue('hero_banner_btn_url', trim($request->input('hero_banner_btn_url', '#product-grid-section')));
        Setting::setValue('hero_banner_overlay', $request->input('hero_banner_overlay', '0.75'));

        return back()->with('success', 'Banner trang chủ đã được cập nhật thành công!');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chạy AdminUserSeeder
        $this->call(AdminUserSeeder::class);

        // Tạo tài khoản test customer
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // Tạo danh mục mẫu
        $catDienthoai = Category::updateOrCreate(['name' => 'Điện thoại & Tablet']);
        $catLaptop = Category::updateOrCreate(['name' => 'Laptop & PC']);
        $catGaming = Category::updateOrCreate(['name' => 'Laptop Gaming']);
        $catPhukien = Category::updateOrCreate(['name' => 'Phụ kiện công nghệ']);
        $catSmarthome = Category::updateOrCreate(['name' => 'Thiết bị thông minh']);
        $catMonitors = Category::updateOrCreate(['name' => 'Màn hình & TV']);

        // --- CÁC SẢN PHẨM MẪU CHUYÊN NGHIỆP ---

        // 1. Điện thoại & Tablet
        Product::updateOrCreate(
            ['name' => 'iPhone 16 Pro Max 256GB'],
            [
                'description' => "Đột phá công nghệ với viền màn hình mỏng nhất lịch sử Apple. Trang bị chip A18 Pro tiến trình 3nm tối tân và Camera Control cảm ứng hoàn toàn mới. Khung sườn làm từ Titan cấp độ 5 siêu bền và nhẹ.",
                'quantity' => 12,
                'price' => 34990000,
                'category_id' => $catDienthoai->id
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Samsung Galaxy Z Fold6 5G'],
            [
                'description' => "Đỉnh cao công nghệ màn hình gập mỏng và nhẹ nhất từ trước đến nay. Tích hợp sâu các tính năng Galaxy AI thông minh hỗ trợ phiên dịch trực tiếp và khoanh vùng tìm kiếm. Màn hình Dynamic AMOLED 2X rực rỡ.",
                'quantity' => 8,
                'price' => 41990000,
                'category_id' => $catDienthoai->id
            ]
        );

        Product::updateOrCreate(
            ['name' => 'iPad Pro M4 11-inch (WiFi/256GB)'],
            [
                'description' => "Máy tính bảng mỏng nhất hành tinh tích hợp màn hình Ultra Retina XDR Tandem OLED đột phá. Trang bị chip Apple M4 thế hệ mới đem đến hiệu năng đồ họa nhanh gấp 4 lần so với chip thế hệ trước.",
                'quantity' => 15,
                'price' => 26490000,
                'category_id' => $catDienthoai->id
            ]
        );

        // 2. Laptop & PC
        Product::updateOrCreate(
            ['name' => 'MacBook Pro M4 14-inch (16GB/512GB)'],
            [
                'description' => "Mẫu laptop chuyên nghiệp tối tân được nâng cấp lên chip Apple M4 mạnh mẽ vượt trội. Màn hình Liquid Retina XDR độ sáng cực cao hỗ trợ hiển thị ngoài trời hoàn hảo. Thời lượng pin cực khủng lên tới 24 tiếng sử dụng.",
                'quantity' => 10,
                'price' => 39990000,
                'category_id' => $catLaptop->id
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Laptop Lenovo ThinkPad X1 Carbon Gen 12'],
            [
                'description' => "Tiêu chuẩn vàng cho laptop doanh nhân cao cấp làm từ sợi carbon siêu bền bỉ. Trang bị bộ vi xử lý Intel Core Ultra 7 tích hợp AI xử lý mượt mà mọi tác vụ văn phòng. Bàn phím ThinkPad trứ danh mang lại cảm giác gõ tốt nhất.",
                'quantity' => 6,
                'price' => 48990000,
                'category_id' => $catLaptop->id
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Asus Zenbook 14 OLED UX3405'],
            [
                'description' => "Laptop siêu mỏng nhẹ chỉ 1.2 kg trang bị màn hình ASUS Lumina OLED 3K 120Hz rực rỡ. Hiệu năng đỉnh cao từ chip Intel Core Ultra 5 và hệ thống âm thanh Harman Kardon cho trải nghiệm làm việc giải trí tuyệt hảo.",
                'quantity' => 14,
                'price' => 24990000,
                'category_id' => $catLaptop->id
            ]
        );

        // 3. Laptop Gaming
        Product::updateOrCreate(
            ['name' => 'ASUS ROG Strix G16 G614JV'],
            [
                'description' => "Quái thú chiến game đích thực sở hữu chip Intel Core i7-13650HX và card đồ họa NVIDIA RTX 4060. Hệ thống tản nhiệt ROG Intelligent Cooling 3 quạt độc quyền giúp duy trì hiệu năng đỉnh cao liên tục.",
                'quantity' => 7,
                'price' => 32490000,
                'category_id' => $catGaming->id
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Acer Predator Helios Neo 16 PH16'],
            [
                'description' => "Laptop gaming quốc dân thế hệ mới sở hữu màn hình IPS 2K+ 165Hz chuẩn màu đồ họa. Trang bị cấu hình cực khủng Intel Core i7-14700HX và card rời RTX 4060 cân mượt mà mọi tựa game AAA bom tấn hiện nay.",
                'quantity' => 9,
                'price' => 35990000,
                'category_id' => $catGaming->id
            ]
        );

        Product::updateOrCreate(
            ['name' => 'MSI Raider GE78 HX 14VGG'],
            [
                'description' => "Đỉnh cao laptop gaming hi-end trang bị cấu hình vô địch Intel Core i9-14900HX cùng GPU RTX 4070. Dải đèn LED Mystic Light huyền ảo bao quanh thân máy tạo điểm nhấn phong cách gaming cực chất.",
                'quantity' => 3,
                'price' => 64990000,
                'category_id' => $catGaming->id
            ]
        );

        // 4. Phụ kiện công nghệ
        Product::updateOrCreate(
            ['name' => 'Tai nghe Apple AirPods Max USB-C'],
            [
                'description' => "Tai nghe chụp tai Over-ear cao cấp mang lại âm thanh trung thực chuẩn Hi-Fi tuyệt hảo. Thiết kế đệm tai bằng lưới dệt êm ái cùng hệ thống Khử tiếng ồn chủ động (ANC) mang đến không gian âm nhạc tĩnh lặng tuyệt đối.",
                'quantity' => 20,
                'price' => 13490000,
                'category_id' => $catPhukien->id
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Chuột Logitech MX Master 3S Wireless'],
            [
                'description' => "Chuột không dây công thái học tốt nhất thế giới dành cho lập trình viên và thiết kế đồ họa. Cảm biến Darkfield 8000 DPI hoạt động mượt mà trên mọi bề mặt kể cả kính. Nút cuộn MagSpeed điện từ cực nhanh.",
                'quantity' => 30,
                'price' => 2490000,
                'category_id' => $catPhukien->id
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Bàn phím cơ ASUS ROG Azoth'],
            [
                'description' => "Bàn phím cơ không dây layout 75% cao cấp nhất dành cho game thủ chuyên nghiệp. Thiết kế gasket mount cho âm thanh gõ trầm ấm mượt mà. Tích hợp màn hình hiển thị OLED nhỏ cực kỳ độc đáo và cá tính.",
                'quantity' => 15,
                'price' => 5990000,
                'category_id' => $catPhukien->id
            ]
        );

        // 5. Thiết bị thông minh
        Product::updateOrCreate(
            ['name' => 'Apple Watch Ultra 2 GPS + Cellular'],
            [
                'description' => "Đồng hồ thông minh thể thao chuyên nghiệp với khung vỏ Titanium siêu cứng 49mm chống va đập. Màn hình Always-On Retina độ sáng 3000 nits đỉnh cao. Tích hợp GPS tần số kép độ chính xác định vị hoàn hảo nhất.",
                'quantity' => 11,
                'price' => 21990000,
                'category_id' => $catSmarthome->id
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Robot hút bụi lau nhà Ecovacs Deebot T30 Pro Omni'],
            [
                'description' => "Robot hút bụi thông minh sở hữu lực hút kỷ lục 11000Pa và trạm sạc tự động sấy khô giẻ lau bằng khí nóng. Công nghệ nhận diện vật cản AI giúp làm sạch tuyệt đối mọi ngóc ngách mà không bị mắc kẹt.",
                'quantity' => 5,
                'price' => 18990000,
                'category_id' => $catSmarthome->id
            ]
        );

        // 6. Màn hình & TV
        Product::updateOrCreate(
            ['name' => 'Màn hình Dell UltraSharp U2724D 2K IPS'],
            [
                'description' => "Màn hình chuyên đồ họa 27-inch độ phân giải 2K sắc nét, hỗ trợ tần số quét 120Hz mượt mà. Tấm nền IPS Black mang lại tỷ lệ tương phản vượt trội gấp đôi tấm nền IPS thông thường, màu sắc chân thực nhất.",
                'quantity' => 12,
                'price' => 9890000,
                'category_id' => $catMonitors->id
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Smart TV LG OLED EVO C4 55 inch 4K'],
            [
                'description' => "Tuyệt tác TV màn hình OLED thế hệ mới sử dụng bộ xử lý Alpha 9 Gen 7 AI 4K tối ưu hóa hình ảnh âm thanh vượt trội. Công nghệ Brightness Booster nâng cấp độ sáng rực rỡ hơn tới 30%, tần số quét 144Hz hoàn hảo cho gaming.",
                'quantity' => 4,
                'price' => 28990000,
                'category_id' => $catMonitors->id
            ]
        );
    }
}

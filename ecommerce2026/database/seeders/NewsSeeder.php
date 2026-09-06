<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Kỷ Nguyên Laptop AI 2026: NPU Thế Hệ Mới Thay Đổi Cách Chúng Ta Làm Việc',
                'slug' => 'ky-nguyen-laptop-ai-2026-npu-the-he-moi',
                'category' => 'Kỷ Nguyên AI',
                'image' => 'images/news_ai_laptop.jpg',
                'summary' => 'Kiến trúc NPU chuyên biệt trên Intel Core Ultra và Apple Silicon cho phép chạy mượt các mô hình AI cục bộ, xử lý đồ họa thông minh mà không phụ thuộc vào kết nối đám mây.',
                'author_name' => 'Nguyễn Việt - Tech Editor',
                'read_time' => '5 phút đọc',
                'views_count' => 1240,
                'is_published' => true,
                'published_at' => now()->subDays(1),
                'content' => '
                    <p class="lead">Năm 2026 đánh dấu bước chuyển mình ngoạn mục của ngành công nghiệp máy tính xách tay với sự bùng nổ của bộ xử lý thần kinh (NPU - Neural Processing Unit) tích hợp sâu vào kiến trúc SoC.</p>

                    <h4>1. NPU là gì và tại sao lại thay đổi cuộc chơi?</h4>
                    <p>Khác với CPU đảm nhận tính toán tổng quát hay GPU chuyên gánh đồ họa nặng, NPU được thiết kế đặc thù cho các phép tính ma trận và tích lũy tích (MAC) - nền tảng của các mô hình học máy (Machine Learning) và AI tạo sinh (Generative AI). Với hiệu năng TOPS (Trillion Operations Per Second) tăng gấp 3 lần so với thế hệ tiền nhiệm, NPU thế hệ mới xử lý các tác vụ thông minh với mức tiêu thụ điện năng chỉ bằng một phần mười.</p>

                    <h4>2. Xử lý AI cục bộ (On-device AI) an toàn và tức thì</h4>
                    <p>Thay vì gửi dữ liệu nhạy cảm lên đám mây, giờ đây các tác vụ như dịch thuật thời gian thực, khử ồn âm thanh phòng họp thông minh, tự động tóm tắt văn bản và tạo hiệu ứng studio đều diễn ra ngay trên phần cứng của bạn. Điều này không chỉ bảo đảm tính bảo mật tối đa cho dữ liệu doanh nghiệp mà còn loại bỏ hoàn toàn độ trễ mạng.</p>

                    <h4>3. Thời lượng pin được bảo toàn trọn vẹn</h4>
                    <p>Trước đây, việc kích hoạt tính năng làm mờ hậu cảnh hoặc theo dõi ánh mắt camera sẽ khiến quạt laptop gầm rú và ngốn sạch pin chỉ sau vài tiếng. Nhờ NPU gánh tải độc lập, CPU và GPU được nghỉ ngơi ở trạng thái tiêu thụ điện năng tối thiểu, mang đến thời gian hội họp và làm việc suốt cả ngày dài mà máy vẫn êm ái, mát rượi.</p>

                    <blockquote class="border-start border-4 border-warning ps-3 my-4 fst-italic text-secondary">
                        "Laptop AI không chỉ đơn thuần là một công cụ phần cứng, đó là một trợ lý thông minh hiểu thói quen và tối ưu luồng công việc của bạn theo từng giây."
                    </blockquote>

                    <h4>4. Lời khuyên khi chọn mua laptop trong năm 2026</h4>
                    <p>Khi đầu tư thiết bị mới, hãy chú ý đến thông số TOPS của chip NPU (tối thiểu từ 45 TOPS trở lên) và dung lượng RAM tối thiểu 16GB hoặc 32GB để đảm bảo khả năng chạy mượt mà các mô hình ngôn ngữ lớn (LLM) cục bộ trong ít nhất 3-4 năm tới.</p>
                ',
            ],
            [
                'title' => 'OLED vs Mini-LED: Đâu Là Màn Hình Hoàn Hảo Cho Dân Sáng Tạo & Đồ Họa?',
                'slug' => 'oled-vs-mini-led-man-hinh-hoan-hao-cho-dan-sang-tao',
                'category' => 'Màn Hình & Đồ Họa',
                'image' => 'images/news_oled_display.jpg',
                'summary' => 'So sánh chi tiết độ chuẩn màu 100% DCI-P3, độ tương phản vô cực và công nghệ chống chói nano giúp tối đa hóa cảm hứng sáng tạo cho designer và editor chuyên nghiệp.',
                'author_name' => 'Hoàng Nam - Display Specialist',
                'read_time' => '4 phút đọc',
                'views_count' => 980,
                'is_published' => true,
                'published_at' => now()->subDays(3),
                'content' => '
                    <p class="lead">Hai công nghệ hiển thị đỉnh cao nhất trên laptop hiện nay - OLED và Mini-LED - đang tạo nên cuộc so kè ngoạn mục về chất lượng hình ảnh, độ sáng và khả năng tái hiện màu sắc.</p>

                    <h4>1. OLED: Màu đen tuyệt đối và độ tương phản vô cực</h4>
                    <p>OLED hoạt động dựa trên các diode phát quang hữu cơ độc lập, cho phép tắt hoàn toàn từng điểm ảnh khi thể hiện màu đen. Kết quả là tỷ lệ tương phản đạt mức vô cực (True Black), màu sắc rực rỡ và góc nhìn rộng gần như 180 độ không hề suy giảm độ bão hòa.</p>

                    <h4>2. Mini-LED: Đỉnh cao độ sáng và chuẩn mực HDR chuyên nghiệp</h4>
                    <p>Mini-LED sử dụng hàng nghìn bóng đèn LED siêu nhỏ được chia thành hàng trăm vùng làm mờ cục bộ (Local Dimming Zones). Nhờ đó, màn hình Mini-LED có thể đạt độ sáng tối đa lên tới 1.600 nits, hiển thị các nội dung HDR sống động ngay cả dưới ánh nắng gắt ngoài trời.</p>

                    <h4>3. Bảng so sánh nhanh</h4>
                    <ul>
                        <li><strong>Độ đen & Tương phản:</strong> OLED dẫn đầu nhờ khả năng tắt điểm ảnh hoàn toàn.</li>
                        <li><strong>Độ sáng đỉnh:</strong> Mini-LED vượt trội với độ sáng duy trì cao và không lo burn-in.</li>
                        <li><strong>Tốc độ phản hồi:</strong> OLED đạt dưới 0.2ms, lý tưởng cho dựng phim và chuyển động nhanh.</li>
                        <li><strong>Độ chuẩn màu:</strong> Cả hai đều đạt 100% DCI-P3 và Delta E &lt; 1 trên các dòng laptop chuyên nghiệp.</li>
                    </ul>

                    <h4>4. Lựa chọn nào phù hợp cho bạn?</h4>
                    <p>Nếu bạn làm việc chủ yếu trong studio, biên tập video điện ảnh hoặc thiết kế mỹ thuật số cần độ tương phản tuyệt đối, OLED là lựa chọn số 1. Còn nếu bạn thường xuyên di chuyển, làm việc tại quán cafe nhiều ánh sáng hoặc dựng phim HDR với độ sáng cao, Mini-LED sẽ là người bạn đồng hành hoàn hảo.</p>
                ',
            ],
            [
                'title' => 'Bí Quyết Tối Ưu Thời Lượng Pin Laptop Vượt Mốc 15 Giờ Làm Việc Liên Tục',
                'slug' => 'bi-quyet-toi-uu-thoi-luong-pin-laptop-vuot-moc-15-gio',
                'category' => 'Cẩm Nang Tối Ưu',
                'image' => 'images/news_clean_setup.jpg',
                'summary' => 'Các thiết lập điện năng ẩn, mẹo kiểm soát tiến trình nền và giải pháp sạc thông minh giúp pin bền bỉ suốt ngày dài năng động mà không cần mang theo củ sạc.',
                'author_name' => 'Lê Minh - Hardware Enthusiast',
                'read_time' => '6 phút đọc',
                'views_count' => 1560,
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'content' => '
                    <p class="lead">Bạn hoàn toàn có thể kéo dài thời lượng sử dụng của chiếc laptop yêu quý vượt mốc 15 giờ liên tục chỉ bằng một vài tinh chỉnh đơn giản nhưng cực kỳ hiệu quả sau đây.</p>

                    <h4>1. Kiểm soát các tiến trình ngầm ăn pin (Background Tasks)</h4>
                    <p>Nhiều ứng dụng như trình duyệt web, phần mềm đồng bộ cloud hoặc launcher game âm thầm chạy ngầm và duy trì các tiến trình đánh thức CPU liên tục. Hãy mở Task Manager / Activity Monitor, tắt tính năng khởi động cùng hệ điều hành (Startup apps) và đóng các tab trình duyệt không dùng đến.</p>

                    <h4>2. Bật chế độ Dynamic Refresh Rate (Tần số quét thích ứng)</h4>
                    <p>Màn hình 120Hz hay 144Hz đem lại cảm giác cuộn lướt mượt mà nhưng ngốn thêm 25-35% dung lượng pin. Hãy thiết lập chế độ thích ứng tự động hạ về 60Hz khi bạn đọc văn bản tĩnh và chỉ đẩy lên 120Hz khi cuộn trang hoặc phát chuyển động.</p>

                    <h4>3. Kích hoạt ngưỡng giới hạn sạc 80% (Battery Health Care)</h4>
                    <p>Pin Lithium-ion chịu ứng suất hóa học cao nhất khi được giữ ở mức 100% liên tục. Bằng cách kích hoạt chế độ bảo vệ pin giới hạn ở 80% trong phần mềm quản lý của hãng (Lenovo Vantage, ASUS MyASUS, Dell Power Manager...), tuổi thọ pin sẽ kéo dài hơn gấp đôi sau nhiều năm sử dụng.</p>

                    <h4>4. Tận dụng bộ giải mã phần cứng (Hardware Acceleration)</h4>
                    <p>Khi xem video YouTube hoặc họp trực tuyến, luôn bật tính năng Hardware Acceleration trong cài đặt trình duyệt để chip xử lý đồ họa tích hợp giải mã AV1/VP9 thay vì bắt CPU gồng mình tính toán thủ công.</p>
                ',
            ],
            [
                'title' => 'Thunderbolt 5 & Wi-Fi 7: Chuẩn Kết Nối Siêu Tốc Định Hình Thiết Bị 2026',
                'slug' => 'thunderbolt-5-va-wifi-7-chuan-ket-noi-sieu-toc-2026',
                'category' => 'Xu Hướng Công Nghệ',
                'image' => 'images/news_thunderbolt_wifi7.jpg',
                'summary' => 'Băng thông đột phá lên tới 120Gbps của Thunderbolt 5 cùng độ trễ siêu thấp từ Wi-Fi 7 mở ra trải nghiệm truyền xuất dữ liệu 8K và gaming không dây chưa từng có.',
                'author_name' => 'Nguyễn Việt - Tech Editor',
                'read_time' => '5 phút đọc',
                'views_count' => 850,
                'is_published' => true,
                'published_at' => now(),
                'content' => '
                    <p class="lead">Tốc độ kết nối trên laptop bước sang một trang mới với sự xuất hiện của chuẩn có dây Thunderbolt 5 và kết nối không dây thế hệ thứ 7 (Wi-Fi 7 802.11be).</p>

                    <h4>1. Thunderbolt 5: Băng thông khổng lồ 120Gbps</h4>
                    <p>Sử dụng công nghệ điều chế PAM-3 tiên tiến, Thunderbolt 5 cung cấp băng thông hai chiều tiêu chuẩn 80Gbps và có thể tăng tốc linh hoạt lên đến 120Gbps với tính năng Bandwidth Boost khi xuất hình ảnh độ phân giải cao. Giờ đây, chỉ với một sợi cáp Type-C duy nhất, bạn có thể cấp nguồn sạc lên đến 240W và kết nối đồng thời ba màn hình 4K 144Hz hoặc hai màn hình 8K HDR sắc nét.</p>

                    <h4>2. Wi-Fi 7: Kết nối không dây mượt mà như cắm dây LAN</h4>
                    <p>Wi-Fi 7 mang đến các kênh truyền siêu rộng 320MHz trên băng tần 6GHz và công nghệ MLO (Multi-Link Operation) cho phép thiết bị gửi nhận dữ liệu đồng thời trên nhiều băng tần (2.4GHz, 5GHz và 6GHz). Nhờ vậy, hiện tượng giật lag, rớt gói tin khi họp video trực tuyến hay chơi game đối kháng trực tuyến được triệt tiêu hoàn toàn.</p>

                    <h4>3. Hệ sinh thái phụ kiện đồng bộ</h4>
                    <p>Các dock cắm eGPU rời, ổ cứng SSD NVMe ngoài và màn hình chuyên nghiệp thế hệ mới tận dụng triệt để tốc độ truyền tải này, biến chiếc laptop mỏng nhẹ thành một trạm làm việc (workstation) đầy uy lực ngay khi bạn đặt máy lên bàn làm việc.</p>
                ',
            ],
        ];

        foreach ($articles as $article) {
            News::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}

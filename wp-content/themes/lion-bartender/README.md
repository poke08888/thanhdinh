# Lion Bartender — WordPress Theme

Theme thương mại điện tử cho thương hiệu chăm sóc nam giới **Lion Bartender**, dựng từ thiết kế gốc (Claude Design). Phong cách đã chốt: **nền Đêm xanh (midnight navy) + Vàng di sản + font Oswald + hero tối giản**.

## Tính năng

- **Trang chủ** (`front-page.php`): hero tối giản (logo lớn + hàng chip 6 mùi), dải tin cậy, bộ sưu tập 6 mùi, feature 3-in-1, quầy hàng (best sellers), teaser câu chuyện.
- **Cửa hàng** (`archive-lb_product.php`): lưới sản phẩm + lọc theo loại (Lăn nách / Xịt khử mùi / Dung dịch vệ sinh / Tắm gội 3-in-1 / Combo / Hộp quà) — lọc tức thì phía client.
- **Chi tiết sản phẩm** (`single-lb_product.php`): chọn 6 mùi (đổi ảnh + mô tả + tầng hương theo mùi), chọn số lượng, **Thêm vào giỏ** / **Mua ngay**, gợi ý sản phẩm cùng mùi.
- **Trang mùi hương** (`taxonomy-lb_scent.php`): tem nhãn + mô tả + tầng hương + **Set Builder** (mua nhanh nhiều sản phẩm cùng mùi: nút "+ Thêm" → bộ đếm, "Chọn trọn bộ", thanh nổi "Thêm tất cả vào giỏ").
- **Bộ sưu tập mùi** (`page-scents.php`): lưới 6 mùi.
- **Câu chuyện thương hiệu** (`page-story.php`): quote, 3 trụ cột, 4 bước "công thức phục vụ".
- **Giỏ hàng**: drawer trượt (localStorage — giữ lại khi tải lại trang), cập nhật số lượng, xóa, tính phí ship (freeship từ 299k).
- **Thanh toán** (`page-checkout.php`) + **Đặt hàng thành công** (`page-success.php`): form giao hàng, chọn phương thức (COD / chuyển khoản / ví), đặt hàng qua AJAX → lưu **Đơn hàng** trong admin.

## Kiến trúc

- **Custom Post Type** `lb_product` — sản phẩm (meta: giá, giá cũ, dung tích, loại, badge, blurb, tính năng…).
- **Taxonomy** `lb_scent` — 6 mùi hương (term meta: tông, màu, tầng hương, mô tả, ảnh tem nhãn).
- **CPT** `lb_order` — lưu đơn hàng khách đặt (xem trong WP admin → Đơn hàng).
- Dữ liệu sản phẩm + mùi từ thiết kế được **seed tự động khi kích hoạt theme** (`after_switch_theme`), và có thể chỉnh sửa trong WP admin.
- `inc/data.php` — dữ liệu gốc + đăng ký CPT/taxonomy + seeder + tạo trang.
- `inc/helpers.php` — accessor, định dạng giá, icon SVG, resolver ảnh theo mùi.
- `inc/components.php` — render thẻ sản phẩm / thẻ mùi.
- `assets/js/app.js` — giỏ hàng, drawer, chọn mùi, set builder, lọc shop, thanh toán, reveal-on-scroll, nav.

## Cài đặt

1. Copy thư mục `lion-bartender/` vào `wp-content/themes/`.
2. WP admin → **Giao diện → Theme** → kích hoạt **Lion Bartender**.
3. Khi kích hoạt, theme tự động:
   - Seed 6 mùi hương + 6 sản phẩm.
   - Tạo các trang: Mùi hương, Câu chuyện, Thanh toán, Đặt hàng thành công, trang chủ tĩnh.
   - Đặt **trang chủ tĩnh** và xả rewrite rules.
4. Nếu permalink chưa nhận, vào **Cài đặt → Đường dẫn tĩnh → Lưu** một lần.

## Sửa text trang chủ & các trang con (Tùy biến)

Vào **Giao diện → Tùy biến → “Lion Bartender — Nội dung”**. Mọi đoạn chữ cố định được gom theo nhóm, chỉnh trực tiếp và **xem trước ngay**:

- **Chung & Thanh thông báo** — dòng thông báo đầu trang.
- **Trang chủ — Hero / Dải tin cậy / Bộ sưu tập mùi / Mục 3-in-1 / Quầy hàng / Teaser câu chuyện.**
- **Trang Cửa hàng** — eyebrow, tiêu đề, mô tả.
- **Trang Câu chuyện** — trích dẫn, khởi nguồn, 3 trụ cột, 4 bước, CTA.
- **Footer** — mô tả thương hiệu, mục đăng ký, bản quyền, điều khoản.
- **Footer — Liên hệ & Mạng xã hội** — Hotline, Email, Địa chỉ (chỉ hiện khi điền) và link Instagram / Facebook / TikTok / YouTube (để trống thì icon trỏ `#`).

Một số trường cho phép **HTML cơ bản** (`<br>`, `<span class="gold-text">…`) để giữ hiệu ứng chữ vàng/xuống dòng. Để trống sẽ tự dùng nội dung mặc định. *(Nội dung sản phẩm & mùi hương sửa ở trang quản trị tương ứng, không nằm trong Tùy biến.)*

## Sửa chi tiết mùi hương (WP admin)

Vào **Sản phẩm LB → Mùi hương** (hoặc bấm "Sửa" một mùi). Mỗi mùi có các trường:

- **Ảnh đại diện** — chọn từ Thư viện Media (có nút Chọn ảnh / Xóa ảnh, xem trước). Ảnh upload sẽ thay ảnh mặc định bundled trong theme; để trống thì dùng lại ảnh theme.
- **Tông hương** — nhãn ngắn (VD: *Biển khơi*).
- **Màu đại diện** — mã hex cho chấm/viền mùi trên giao diện (kèm bảng chọn màu).
- **Mô tả mùi hương** — đoạn mô tả hiển thị ở trang mùi & chi tiết sản phẩm.
- **Các hương chính** — tầng hương, ngăn cách bằng dấu phẩy.

Danh sách mùi hương cũng có cột **Ảnh** xem nhanh. Thay đổi áp dụng ngay cho trang chủ, trang mùi, chi tiết sản phẩm và giỏ hàng.

## Sửa ảnh theo mùi (phân loại) của từng sản phẩm

Mở **Sản phẩm LB → (sửa một sản phẩm)** → meta box **“Ảnh theo mùi hương (phân loại)”**. Mỗi mùi mà sản phẩm có sẽ là một ô riêng:

- Bấm **Chọn ảnh** để gán ảnh riêng cho mùi đó (qua Thư viện Media), hoặc **Dùng mặc định** để bỏ override.
- Để trống → dùng ảnh mặc định (tem nhãn mùi / ảnh chai thật).
- Lưu ở post meta `lb_scent_images` (`scent_slug => attachment_id`).

Ảnh override được ưu tiên cao nhất ở mọi nơi: thẻ sản phẩm, trang chi tiết (đổi mùi), set builder, giỏ hàng. *(Muốn thêm mùi cho sản phẩm: tích ở ô “Mùi hương” rồi Cập nhật — các ô ảnh tương ứng sẽ hiện ra.)*

## Tích hợp WooCommerce (theo dõi đơn hàng)

Theme tích hợp WooCommerce như **phụ thuộc mềm** để quản lý đơn hàng:

- Giao diện & trang thanh toán **vẫn là theme custom** (đúng thiết kế) — không dùng cart/checkout mặc định của WooCommerce.
- Khi khách đặt hàng, hệ thống tạo một **đơn hàng WooCommerce thật** → theo dõi trong **WooCommerce → Đơn hàng** với đầy đủ: trạng thái (COD → *Đang xử lý*; chuyển khoản/ví → *Tạm giữ*), email tự động, báo cáo doanh thu, in đơn…
- Mỗi sản phẩm `lb_product` được đồng bộ thành một sản phẩm WooCommerce **ẩn** (SKU `LB-…`, không hiện trong catalog WC để khỏi trùng cửa hàng custom) làm dòng hàng trong đơn. **Mùi hương** được lưu làm meta của từng dòng hàng.
- Giá tự đồng bộ sang WooCommerce khi lưu sản phẩm trong admin.
- **Nếu chưa cài WooCommerce:** theme tự chạy fallback — lưu đơn vào CPT `lb_order` (admin → Đơn hàng) và hiện thông báo gợi ý cài WooCommerce.

Để bật: chỉ cần **cài & kích hoạt plugin WooCommerce**. Lần đầu vào admin, theme tự đồng bộ catalog sang WooCommerce.

## Ghi chú

- Checkout là luồng nhẹ tự xây phù hợp COD kiểu Việt Nam của thiết kế; WooCommerce lo phần hậu trường đơn hàng. Cần cổng thanh toán online (VNPay, Stripe…) thì cài plugin cổng tương ứng và đổi `set_status`/`payment_method` trong `inc/woocommerce.php`.
- Ảnh sản phẩm theo mùi: ưu tiên ảnh chai thật (Ocean Club 3-in-1); còn lại dùng tem nhãn từng mùi (đúng màu mùi).

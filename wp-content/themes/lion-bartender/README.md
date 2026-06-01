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

## Ghi chú

- Giỏ hàng & thanh toán dùng luồng nhẹ tự xây (phù hợp checkout COD kiểu Việt Nam của thiết kế). Có thể tích hợp WooCommerce sau nếu cần cổng thanh toán/online.
- Ảnh sản phẩm theo mùi: ưu tiên ảnh chai thật (Ocean Club 3-in-1); còn lại dùng tem nhãn từng mùi (đúng màu mùi).

<?php
/**
 * Lion Bartender — Customizer: sửa text trang chủ & các trang con.
 *
 * Mọi đoạn text cố định trên giao diện được khai báo trong lb_text_fields()
 * (kèm giá trị mặc định = đúng nội dung gốc). Template gọi lb_text()/lb_the_text()
 * /lb_the_html() để lấy giá trị (đã chỉnh trong Tùy biến hoặc mặc định).
 *
 * Vào: Bảng điều khiển → Giao diện → Tùy biến → "Lion Bartender — Nội dung".
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

/**
 * Danh mục các trường text.
 * type: 'text' (1 dòng, sanitize_text_field) | 'textarea' (cho phép HTML cơ bản: <br>, <span class>, <em>...).
 */
function lb_text_fields() {
	return array(
		/* ---- Chung & thanh thông báo ---- */
		'announce_text'      => array( 'sec' => 'lb_sec_general', 'type' => 'text', 'label' => 'Thanh thông báo (đầu trang)', 'default' => 'Freeship đơn từ 299k · Tặng cọ tắm cho mọi đơn 3-in-1' ),

		/* ---- Trang chủ: Hero ---- */
		'hero_eyebrow'       => array( 'sec' => 'lb_sec_hero', 'type' => 'text', 'label' => 'Dòng nhỏ (eyebrow)', 'default' => 'Scent · Served · Since the bar' ),
		'hero_sub'           => array( 'sec' => 'lb_sec_hero', 'type' => 'textarea', 'label' => 'Slogan (cho phép HTML)', 'default' => 'Chăm sóc nam giới chuẩn quý ông. <span class="gold-text serif">Mùi hương độc bản, đầy bản lĩnh.</span>' ),
		'hero_cta1'          => array( 'sec' => 'lb_sec_hero', 'type' => 'text', 'label' => 'Nút chính', 'default' => 'Mua ngay' ),
		'hero_cta2'          => array( 'sec' => 'lb_sec_hero', 'type' => 'text', 'label' => 'Nút phụ', 'default' => 'Khám phá mùi hương' ),

		/* ---- Trang chủ: Dải tin cậy ---- */
		'trust1_t'           => array( 'sec' => 'lb_sec_trust', 'type' => 'text', 'label' => 'Mục 1 — tiêu đề', 'default' => 'Freeship từ 299k' ),
		'trust1_s'           => array( 'sec' => 'lb_sec_trust', 'type' => 'text', 'label' => 'Mục 1 — mô tả', 'default' => 'Toàn quốc' ),
		'trust2_t'           => array( 'sec' => 'lb_sec_trust', 'type' => 'text', 'label' => 'Mục 2 — tiêu đề', 'default' => 'Lưu hương lâu' ),
		'trust2_s'           => array( 'sec' => 'lb_sec_trust', 'type' => 'text', 'label' => 'Mục 2 — mô tả', 'default' => 'Tinh dầu nước hoa' ),
		'trust3_t'           => array( 'sec' => 'lb_sec_trust', 'type' => 'text', 'label' => 'Mục 3 — tiêu đề', 'default' => 'Chính hãng 100%' ),
		'trust3_s'           => array( 'sec' => 'lb_sec_trust', 'type' => 'text', 'label' => 'Mục 3 — mô tả', 'default' => 'Đổi trả trong 7 ngày' ),

		/* ---- Trang chủ + Trang Mùi hương: Bộ sưu tập ---- */
		'scents_eyebrow'     => array( 'sec' => 'lb_sec_scents', 'type' => 'text', 'label' => 'Eyebrow', 'default' => 'Bộ sưu tập mùi hương' ),
		'scents_heading'     => array( 'sec' => 'lb_sec_scents', 'type' => 'text', 'label' => 'Tiêu đề', 'default' => 'Sáu Ly · Sáu Mùi' ),
		'scents_sub'         => array( 'sec' => 'lb_sec_scents', 'type' => 'textarea', 'label' => 'Mô tả', 'default' => 'Mỗi mùi là một quầy bar riêng. Chọn ly của bạn — và để hương dẫn lối cả ngày dài.' ),

		/* ---- Trang chủ: Feature 3-in-1 ---- */
		'feat_eyebrow'       => array( 'sec' => 'lb_sec_feature', 'type' => 'text', 'label' => 'Eyebrow', 'default' => 'Best Seller · 3 trong 1' ),
		'feat_heading'       => array( 'sec' => 'lb_sec_feature', 'type' => 'textarea', 'label' => 'Tiêu đề (HTML)', 'default' => 'Tắm · Gội · Rửa mặt<br><span class="gold-text">Trong một chai</span>' ),
		'feat_note'          => array( 'sec' => 'lb_sec_feature', 'type' => 'text', 'label' => 'Ghi chú cạnh nút', 'default' => 'Tặng kèm cọ tắm massage' ),

		/* ---- Trang chủ: Best sellers ---- */
		'best_eyebrow'       => array( 'sec' => 'lb_sec_best', 'type' => 'text', 'label' => 'Eyebrow', 'default' => 'Được chọn nhiều nhất' ),
		'best_heading'       => array( 'sec' => 'lb_sec_best', 'type' => 'text', 'label' => 'Tiêu đề', 'default' => 'Quầy Hàng' ),
		'best_link'          => array( 'sec' => 'lb_sec_best', 'type' => 'text', 'label' => 'Link "xem tất cả"', 'default' => 'Xem tất cả' ),

		/* ---- Trang chủ: Story teaser ---- */
		'steaser_eyebrow'    => array( 'sec' => 'lb_sec_steaser', 'type' => 'text', 'label' => 'Eyebrow', 'default' => 'Câu chuyện thương hiệu' ),
		'steaser_heading'    => array( 'sec' => 'lb_sec_steaser', 'type' => 'textarea', 'label' => 'Tiêu đề (HTML)', 'default' => 'Pha hương như<br><span class="gold-text">một bartender thực thụ</span>' ),
		'steaser_p1'         => array( 'sec' => 'lb_sec_steaser', 'type' => 'textarea', 'label' => 'Đoạn 1', 'default' => 'Lion Bartender ra đời từ một ý tưởng đơn giản: mùi hương của một quý ông nên được pha chế cẩn thận như một ly cocktail thượng hạng — có lớp lang, có cá tính, có cao trào.' ),
		'steaser_p2'         => array( 'sec' => 'lb_sec_steaser', 'type' => 'textarea', 'label' => 'Đoạn 2', 'default' => 'Mỗi sản phẩm là một công thức riêng, đóng trong thiết kế tem nhãn cổ điển lấy cảm hứng từ những quầy bar và tiệm cắt tóc xưa.' ),
		'steaser_btn'        => array( 'sec' => 'lb_sec_steaser', 'type' => 'text', 'label' => 'Nút', 'default' => 'Đọc câu chuyện' ),

		/* ---- Trang Shop ---- */
		'shop_eyebrow'       => array( 'sec' => 'lb_sec_shop', 'type' => 'text', 'label' => 'Eyebrow', 'default' => 'Cửa hàng' ),
		'shop_heading'       => array( 'sec' => 'lb_sec_shop', 'type' => 'text', 'label' => 'Tiêu đề', 'default' => 'Quầy Lion Bartender' ),
		'shop_sub'           => array( 'sec' => 'lb_sec_shop', 'type' => 'textarea', 'label' => 'Mô tả', 'default' => 'Bộ sưu tập chăm sóc nam giới — lăn khử mùi, tắm gội 3-in-1 và những hộp quà chất chơi.' ),

		/* ---- Trang Câu chuyện ---- */
		'story_hero_eyebrow' => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Eyebrow đầu trang', 'default' => 'Scent. Served.' ),
		'story_quote'        => array( 'sec' => 'lb_sec_story', 'type' => 'textarea', 'label' => 'Câu trích dẫn (HTML)', 'default' => '“Một quý ông được nhớ đến bởi <span class="gold-text">mùi hương</span> của anh ta.”' ),
		'story_origin_eyebrow' => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Khởi nguồn — eyebrow', 'default' => 'Khởi nguồn' ),
		'story_origin_heading' => array( 'sec' => 'lb_sec_story', 'type' => 'textarea', 'label' => 'Khởi nguồn — tiêu đề (HTML)', 'default' => 'Từ quầy bar<br><span class="gold-text">đến phòng tắm của bạn</span>' ),
		'story_origin_p1'    => array( 'sec' => 'lb_sec_story', 'type' => 'textarea', 'label' => 'Khởi nguồn — đoạn 1', 'default' => 'Lion Bartender sinh ra từ niềm tin rằng chăm sóc bản thân là một nghi thức — không phải việc làm cho có. Lấy cảm hứng từ những quầy bar cổ điển và tiệm cắt tóc xưa, chúng tôi pha chế từng mùi hương như một bartender lành nghề pha ly cocktail tủ.' ),
		'story_origin_p2'    => array( 'sec' => 'lb_sec_story', 'type' => 'textarea', 'label' => 'Khởi nguồn — đoạn 2', 'default' => 'Con sư tử đeo kính — biểu tượng của chúng tôi — đại diện cho người đàn ông hiện đại: bản lĩnh, phong trần nhưng vẫn thừa chất chơi và sự tinh tế.' ),
		'story_pillars_eyebrow' => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Trụ cột — eyebrow', 'default' => 'Vì sao chọn chúng tôi' ),
		'story_pillars_heading' => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Trụ cột — tiêu đề', 'default' => 'Pha Chuẩn · Tỏa Chất' ),
		'pillar1_t'          => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Trụ cột 1 — tiêu đề', 'default' => 'Mùi hương độc bản' ),
		'pillar1_d'          => array( 'sec' => 'lb_sec_story', 'type' => 'textarea', 'label' => 'Trụ cột 1 — mô tả', 'default' => 'Mỗi công thức được pha như một ly cocktail — có lớp hương đầu, hương giữa và hậu vị riêng biệt.' ),
		'pillar2_t'          => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Trụ cột 2 — tiêu đề', 'default' => 'Công nghệ Heat-Trigger' ),
		'pillar2_d'          => array( 'sec' => 'lb_sec_story', 'type' => 'textarea', 'label' => 'Trụ cột 2 — mô tả', 'default' => 'Hương được kích hoạt theo thân nhiệt. Càng vận động, mùi càng bung tỏa — khử mùi suốt 24 giờ.' ),
		'pillar3_t'          => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Trụ cột 3 — tiêu đề', 'default' => 'Chất lượng quý ông' ),
		'pillar3_d'          => array( 'sec' => 'lb_sec_story', 'type' => 'textarea', 'label' => 'Trụ cột 3 — mô tả', 'default' => 'Thành phần dịu nhẹ cho da nam, kiểm định an toàn, đóng gói trong thiết kế cổ điển đầy bản lĩnh.' ),
		'story_steps_eyebrow' => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Các bước — eyebrow', 'default' => 'Công thức phục vụ' ),
		'story_steps_heading' => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Các bước — tiêu đề', 'default' => 'Bốn bước · Một đẳng cấp' ),
		'step1_t'            => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Bước 1 — tiêu đề', 'default' => 'Chọn ly' ),
		'step1_d'            => array( 'sec' => 'lb_sec_story', 'type' => 'textarea', 'label' => 'Bước 1 — mô tả', 'default' => 'Tìm mùi hương hợp gu trong 6 lựa chọn đặc trưng.' ),
		'step2_t'            => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Bước 2 — tiêu đề', 'default' => 'Pha hương' ),
		'step2_d'            => array( 'sec' => 'lb_sec_story', 'type' => 'textarea', 'label' => 'Bước 2 — mô tả', 'default' => 'Công thức tinh dầu nước hoa pha tỉ lệ chuẩn bartender.' ),
		'step3_t'            => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Bước 3 — tiêu đề', 'default' => 'Phục vụ' ),
		'step3_d'            => array( 'sec' => 'lb_sec_story', 'type' => 'textarea', 'label' => 'Bước 3 — mô tả', 'default' => 'Lăn, tắm gội, rửa mặt — sạch thơm chuẩn quý ông.' ),
		'step4_t'            => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'Bước 4 — tiêu đề', 'default' => 'Tỏa sáng' ),
		'step4_d'            => array( 'sec' => 'lb_sec_story', 'type' => 'textarea', 'label' => 'Bước 4 — mô tả', 'default' => 'Bước ra ngoài với sự tự tin và dấu ấn riêng.' ),
		'story_cta_heading'  => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'CTA — tiêu đề', 'default' => 'Sẵn sàng pha ly của bạn?' ),
		'story_cta_sub'      => array( 'sec' => 'lb_sec_story', 'type' => 'textarea', 'label' => 'CTA — mô tả', 'default' => 'Khám phá 6 mùi hương đặc trưng và tìm dấu ấn riêng của một quý ông.' ),
		'story_cta_btn'      => array( 'sec' => 'lb_sec_story', 'type' => 'text', 'label' => 'CTA — nút', 'default' => 'Mua ngay' ),

		/* ---- Footer ---- */
		'footer_tagline'     => array( 'sec' => 'lb_sec_footer', 'type' => 'textarea', 'label' => 'Mô tả thương hiệu', 'default' => 'Chăm sóc nam giới chuẩn quý ông. Mùi hương độc bản — sang trọng, bụi bặm, đầy bản lĩnh.' ),
		'footer_news_heading' => array( 'sec' => 'lb_sec_footer', 'type' => 'text', 'label' => 'Đăng ký — tiêu đề', 'default' => 'Gia nhập băng sư tử' ),
		'footer_news_sub'    => array( 'sec' => 'lb_sec_footer', 'type' => 'text', 'label' => 'Đăng ký — mô tả', 'default' => 'Nhận ưu đãi sớm & tin mùi hương mới.' ),
		'footer_copyright'   => array( 'sec' => 'lb_sec_footer', 'type' => 'text', 'label' => 'Bản quyền (sau © + năm)', 'default' => 'Lion Bartender by Nerman. Đã đăng ký bản quyền.' ),
		'footer_legal'       => array( 'sec' => 'lb_sec_footer', 'type' => 'text', 'label' => 'Dòng điều khoản', 'default' => 'Điều khoản · Bảo mật · Đổi trả' ),

		/* ---- Footer: Liên hệ & Mạng xã hội ---- */
		'contact_hotline'    => array( 'sec' => 'lb_sec_contact', 'type' => 'text', 'label' => 'Hotline', 'default' => '' ),
		'contact_email'      => array( 'sec' => 'lb_sec_contact', 'type' => 'text', 'label' => 'Email liên hệ', 'default' => '' ),
		'contact_address'    => array( 'sec' => 'lb_sec_contact', 'type' => 'textarea', 'label' => 'Địa chỉ', 'default' => '' ),
		'social_ig'          => array( 'sec' => 'lb_sec_contact', 'type' => 'url', 'label' => 'Instagram URL', 'default' => '' ),
		'social_fb'          => array( 'sec' => 'lb_sec_contact', 'type' => 'url', 'label' => 'Facebook URL', 'default' => '' ),
		'social_tiktok'      => array( 'sec' => 'lb_sec_contact', 'type' => 'url', 'label' => 'TikTok URL', 'default' => '' ),
		'social_yt'          => array( 'sec' => 'lb_sec_contact', 'type' => 'url', 'label' => 'YouTube URL', 'default' => '' ),
	);
}

/** Lấy text (đã chỉnh hoặc mặc định). */
function lb_text( $key ) {
	$fields  = lb_text_fields();
	$default = isset( $fields[ $key ] ) ? $fields[ $key ]['default'] : '';
	return get_theme_mod( 'lb_' . $key, $default );
}

/** In text dạng plain (escaped). */
function lb_the_text( $key ) {
	echo esc_html( lb_text( $key ) );
}

/** In text cho phép HTML cơ bản. */
function lb_the_html( $key ) {
	echo wp_kses_post( lb_text( $key ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/* ------------------------------------------------------------------
 * Đăng ký Customizer
 * ------------------------------------------------------------------ */
add_action( 'customize_register', 'lb_customize_register' );
function lb_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'lb_panel',
		array(
			'title'    => 'Lion Bartender — Nội dung',
			'priority' => 30,
		)
	);

	$sections = array(
		'lb_sec_general' => 'Chung & Thanh thông báo',
		'lb_sec_hero'    => 'Trang chủ — Hero',
		'lb_sec_trust'   => 'Trang chủ — Dải tin cậy',
		'lb_sec_scents'  => 'Bộ sưu tập mùi (chủ + trang Mùi hương)',
		'lb_sec_feature' => 'Trang chủ — Mục 3-in-1',
		'lb_sec_best'    => 'Trang chủ — Quầy hàng',
		'lb_sec_steaser' => 'Trang chủ — Teaser câu chuyện',
		'lb_sec_shop'    => 'Trang Cửa hàng',
		'lb_sec_story'   => 'Trang Câu chuyện',
		'lb_sec_footer'  => 'Footer',
		'lb_sec_contact' => 'Footer — Liên hệ & Mạng xã hội',
	);
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'lb_panel' ) );
	}

	foreach ( lb_text_fields() as $key => $f ) {
		$sid = 'lb_' . $key;
		switch ( $f['type'] ) {
			case 'textarea':
				$sanitize = 'wp_kses_post';
				$control  = 'textarea';
				break;
			case 'url':
				$sanitize = 'esc_url_raw';
				$control  = 'url';
				break;
			default:
				$sanitize = 'sanitize_text_field';
				$control  = 'text';
		}
		$wp_customize->add_setting(
			$sid,
			array(
				'default'           => $f['default'],
				'sanitize_callback' => $sanitize,
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$sid,
			array(
				'label'   => $f['label'],
				'section' => $f['sec'],
				'type'    => $control,
			)
		);
	}
}

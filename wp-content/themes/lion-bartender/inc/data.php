<?php
/**
 * Lion Bartender — dữ liệu mùi hương & sản phẩm, custom post type / taxonomy, seeder.
 *
 * Nguồn dữ liệu gốc lấy từ thiết kế (data.jsx). Khi kích hoạt theme, dữ liệu này
 * được seed vào CPT `lb_product` và taxonomy `lb_scent` để quản lý trong WP admin.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

/* ------------------------------------------------------------------
 * Dữ liệu gốc (canonical) — dùng để seed và làm fallback.
 * ------------------------------------------------------------------ */

/** Thứ tự mùi hương hiển thị. */
function lb_scent_order() {
	return array( 'party-up', 'drunk-time', 'ocean-club', 'chill-time', 'beach-pub', 'sea-bar' );
}

/** Dữ liệu 6 mùi hương. */
function lb_seed_scents() {
	return array(
		'beach-pub' => array(
			'name'  => 'Beach Pub',
			'tone'  => 'Tươi mát',
			'color' => '#7fb4d4',
			'deep'  => '#11384e',
			'ink'   => '#0a2330',
			'notes' => array( 'Muối biển', 'Bergamot', 'Gỗ trôi' ),
			'desc'  => 'Lát cắt nắng trưa bên bờ biển — mặn mòi, the mát và phóng khoáng. Dành cho ngày dài không chùn bước.',
			'img'   => 'scent-beach-pub.png',
		),
		'ocean-club' => array(
			'name'  => 'Ocean Club',
			'tone'  => 'Biển khơi',
			'color' => '#2f86c4',
			'deep'  => '#0f2f4d',
			'ink'   => '#0a2138',
			'notes' => array( 'Hương nước', 'Rong biển', 'Xạ hương trắng' ),
			'desc'  => 'Làn hương đại dương sâu thẳm, sạch và bản lĩnh. Cuốn hút như sóng ngầm — êm mà mạnh.',
			'img'   => 'scent-ocean-club.png',
		),
		'sea-bar' => array(
			'name'  => 'Sea Bar',
			'tone'  => 'Cam chanh',
			'color' => '#d8b863',
			'deep'  => '#5a4516',
			'ink'   => '#2a2008',
			'notes' => array( 'Cam vàng', 'Gừng', 'Vetiver' ),
			'desc'  => 'Vị citrus sảng khoái pha chút gừng cay ấm. Tỉnh táo, lịch lãm, sẵn sàng cho mọi cuộc vui.',
			'img'   => 'scent-sea-bar.png',
		),
		'drunk-time' => array(
			'name'  => 'Drunk Time',
			'tone'  => 'Nồng ấm',
			'color' => '#b8413f',
			'deep'  => '#5a1518',
			'ink'   => '#2c0a0c',
			'notes' => array( 'Rum', 'Gỗ sồi', 'Vani' ),
			'desc'  => 'Hương rum gỗ sồi nồng nàn, ngọt nơi hậu vị. Quyến rũ như ly cocktail cuối ngày.',
			'img'   => 'scent-drunk-time.png',
		),
		'party-up' => array(
			'name'  => 'Party Up',
			'tone'  => 'Bí ẩn',
			'color' => '#d4af5f',
			'deep'  => '#3a2f12',
			'ink'   => '#161106',
			'notes' => array( 'Da thuộc', 'Hổ phách', 'Thuốc lá ngọt' ),
			'desc'  => 'Tầng hương da thuộc và hổ phách đậm chất quý ông. Đẳng cấp, cuốn hút, không thể bỏ qua.',
			'img'   => 'scent-party-up.png',
		),
		'chill-time' => array(
			'name'  => 'Chill Time',
			'tone'  => 'Se lạnh',
			'color' => '#3f6db0',
			'deep'  => '#16294a',
			'ink'   => '#0b1830',
			'notes' => array( 'Bạc hà', 'Oải hương', 'Tuyết tùng' ),
			'desc'  => 'Bạc hà se lạnh hoà cùng tuyết tùng trầm. Thư giãn, sạch sẽ, lắng đọng sau giờ cao điểm.',
			'img'   => 'scent-chill-time.png',
		),
	);
}

/** Dữ liệu sản phẩm. */
function lb_seed_products() {
	$all = lb_scent_order();
	return array(
		array(
			'id'        => 'deo-heat-trigger',
			'name'      => 'Lăn Khử Mùi Heat Trigger',
			'type'      => 'deodorant',
			'typeLabel' => 'Lăn nách',
			'tagline'   => 'Khô thoáng 24h · Kích hoạt theo nhiệt',
			'price'     => 89000,
			'oldPrice'  => 110000,
			'volume'    => '50ml',
			'badge'     => 'Bán chạy',
			'blurb'     => 'Công nghệ Heat-Trigger giải phóng hương theo thân nhiệt — càng vận động, càng thơm. Khóa mùi 24 giờ, không vệt trắng.',
			'features'  => array( 'Khóa mùi & mồ hôi 24 giờ', 'Kích hoạt hương theo thân nhiệt', 'Khô nhanh, không để lại vệt trắng', 'Chiết xuất dịu nhẹ cho da nam' ),
			'scents'    => $all,
			'heroImg'   => '',
		),
		array(
			'id'        => 'spray-deo',
			'name'      => 'Xịt Khử Mùi Toàn Thân',
			'type'      => 'spray',
			'typeLabel' => 'Xịt khử mùi',
			'tagline'   => 'Xịt nhanh · Thơm tức thì · Toàn thân',
			'price'     => 99000,
			'oldPrice'  => 120000,
			'volume'    => '150ml',
			'badge'     => '',
			'blurb'     => 'Dạng xịt khô thoáng, phủ hương nhanh khắp cơ thể. Tỏa hương tức thì, dùng được cả ngày — giải pháp gọn nhẹ mang theo bên mình.',
			'features'  => array( 'Phủ hương toàn thân tức thì', 'Khô thoáng, không bết dính', 'Khử mùi mồ hôi hiệu quả', 'Gọn nhẹ, tiện mang theo' ),
			'scents'    => $all,
			'heroImg'   => '',
		),
		array(
			'id'        => 'wash-men',
			'name'      => 'Dung Dịch Vệ Sinh Nam',
			'type'      => 'wash',
			'typeLabel' => 'Dung dịch vệ sinh',
			'tagline'   => 'Sạch sâu · Dịu nhẹ · Tự tin cả ngày',
			'price'     => 119000,
			'oldPrice'  => 139000,
			'volume'    => '150ml',
			'badge'     => '',
			'blurb'     => 'Làm sạch dịu nhẹ vùng nhạy cảm nam giới, cân bằng độ pH và lưu hương thoảng nhẹ. Mang lại cảm giác khô thoáng, sạch sẽ và tự tin.',
			'features'  => array( 'Làm sạch sâu, dịu nhẹ', 'Cân bằng pH vùng da nhạy cảm', 'Khử mùi & khô thoáng', 'Lưu hương đồng bộ cả bộ' ),
			'scents'    => $all,
			'heroImg'   => '',
		),
		array(
			'id'        => 'shower-3in1',
			'name'      => 'Sữa Tắm Gội Rửa Mặt 3-in-1',
			'type'      => 'shower',
			'typeLabel' => 'Tắm gội 3-in-1',
			'tagline'   => 'Một chai · Sạch toàn diện · Lưu hương',
			'price'     => 169000,
			'oldPrice'  => 199000,
			'volume'    => '330g',
			'badge'     => 'Tặng cọ tắm',
			'blurb'     => 'Tắm — gội — rửa mặt trong một chai. Làm sạch sâu, lưu hương nước hoa nhiều giờ, tặng kèm cọ tắm tạo bọt.',
			'features'  => array( '3 công dụng trong 1 chai', 'Lưu hương nước hoa lâu', 'Bọt mịn làm sạch sâu', 'Tặng kèm cọ tắm massage' ),
			'scents'    => array( 'ocean-club', 'party-up', 'drunk-time', 'chill-time' ),
			'heroImg'   => 'bottle-ocean-club.png',
		),
		array(
			'id'        => 'combo-duo',
			'name'      => 'Combo Quý Ông',
			'type'      => 'combo',
			'typeLabel' => 'Combo',
			'tagline'   => 'Lăn khử mùi + Tắm gội 3-in-1',
			'price'     => 239000,
			'oldPrice'  => 258000,
			'volume'    => 'Bộ 2 món',
			'badge'     => 'Tiết kiệm 20k',
			'blurb'     => 'Bộ đôi chăm sóc hằng ngày: lăn khử mùi Heat-Trigger và sữa tắm gội 3-in-1 cùng tông hương. Sạch thơm từ sáng tới đêm.',
			'features'  => array( '1 Lăn khử mùi 50ml', '1 Sữa tắm gội 3-in-1 330g', 'Đồng bộ mùi hương', 'Tiết kiệm hơn mua lẻ' ),
			'scents'    => array( 'ocean-club', 'party-up', 'drunk-time', 'chill-time' ),
			'heroImg'   => 'bottle-ocean-club.png',
		),
		array(
			'id'        => 'gift-set',
			'name'      => 'Hộp Quà Lion Bartender',
			'type'      => 'gift',
			'typeLabel' => 'Hộp quà',
			'tagline'   => 'Bộ sưu tập 4 mùi · Hộp thiếc cao cấp',
			'price'     => 549000,
			'oldPrice'  => 640000,
			'volume'    => 'Bộ 4 món',
			'badge'     => 'Quà tặng',
			'blurb'     => 'Hộp thiếc khắc nổi đựng trọn bộ trải nghiệm: 4 mùi hương đặc trưng trong thiết kế tem nhãn nguyên bản. Món quà chất cho hội anh em.',
			'features'  => array( '4 chai tắm gội 3-in-1', 'Hộp thiếc khắc nổi sang trọng', 'Thiệp & tem niêm phong', 'Lựa chọn quà tặng hoàn hảo' ),
			'scents'    => array( 'party-up', 'drunk-time', 'ocean-club', 'chill-time' ),
			'heroImg'   => 'promo-trio.png',
		),
	);
}

/** Bộ lọc loại sản phẩm cho trang Shop. */
function lb_shop_filters() {
	return array(
		'all'       => 'Tất cả',
		'deodorant' => 'Lăn nách',
		'spray'     => 'Xịt khử mùi',
		'wash'      => 'Dung dịch vệ sinh',
		'shower'    => 'Tắm gội 3-in-1',
		'combo'     => 'Combo',
		'gift'      => 'Hộp quà',
	);
}

/* ------------------------------------------------------------------
 * Đăng ký Custom Post Type & Taxonomy
 * ------------------------------------------------------------------ */

add_action( 'init', 'lb_register_content' );
function lb_register_content() {

	register_taxonomy(
		'lb_scent',
		'lb_product',
		array(
			'labels'            => array(
				'name'          => 'Mùi hương',
				'singular_name' => 'Mùi hương',
				'menu_name'     => 'Mùi hương',
			),
			'public'            => true,
			'hierarchical'      => false,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'mui-huong' ),
		)
	);

	register_post_type(
		'lb_product',
		array(
			'labels'       => array(
				'name'          => 'Sản phẩm',
				'singular_name' => 'Sản phẩm',
				'menu_name'     => 'Sản phẩm LB',
				'add_new_item'  => 'Thêm sản phẩm',
				'edit_item'     => 'Sửa sản phẩm',
			),
			'public'       => true,
			'has_archive'  => 'cua-hang',
			'menu_icon'    => 'dashicons-beer',
			'menu_position'=> 5,
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'san-pham' ),
			'taxonomies'   => array( 'lb_scent' ),
		)
	);

	// Đơn hàng (lưu nội bộ khi khách đặt qua trang thanh toán).
	register_post_type(
		'lb_order',
		array(
			'labels'      => array(
				'name'          => 'Đơn hàng',
				'singular_name' => 'Đơn hàng',
				'menu_name'     => 'Đơn hàng',
			),
			'public'      => false,
			'show_ui'     => true,
			'menu_icon'   => 'dashicons-cart',
			'menu_position'=> 6,
			'supports'    => array( 'title' ),
			'capability_type' => 'post',
		)
	);
}

/* ------------------------------------------------------------------
 * Seeder — chạy khi kích hoạt theme.
 * ------------------------------------------------------------------ */

add_action( 'after_switch_theme', 'lb_seed_content' );
function lb_seed_content() {
	lb_register_content();

	// Mùi hương.
	$scents = lb_seed_scents();
	foreach ( lb_scent_order() as $slug ) {
		$s = $scents[ $slug ];
		$term = term_exists( $slug, 'lb_scent' );
		if ( ! $term ) {
			$term = wp_insert_term( $s['name'], 'lb_scent', array( 'slug' => $slug ) );
		}
		if ( is_wp_error( $term ) || empty( $term['term_id'] ) ) {
			continue;
		}
		$tid = (int) $term['term_id'];
		update_term_meta( $tid, 'lb_tone', $s['tone'] );
		update_term_meta( $tid, 'lb_color', $s['color'] );
		update_term_meta( $tid, 'lb_deep', $s['deep'] );
		update_term_meta( $tid, 'lb_ink', $s['ink'] );
		update_term_meta( $tid, 'lb_notes', implode( '|', $s['notes'] ) );
		update_term_meta( $tid, 'lb_desc', $s['desc'] );
		update_term_meta( $tid, 'lb_img', $s['img'] );
	}

	// Sản phẩm.
	foreach ( lb_seed_products() as $p ) {
		$existing = get_posts(
			array(
				'post_type'   => 'lb_product',
				'name'        => $p['id'],
				'post_status' => 'any',
				'numberposts' => 1,
				'fields'      => 'ids',
			)
		);
		if ( $existing ) {
			$pid = $existing[0];
		} else {
			$pid = wp_insert_post(
				array(
					'post_type'    => 'lb_product',
					'post_name'    => $p['id'],
					'post_title'   => $p['name'],
					'post_content' => $p['blurb'],
					'post_status'  => 'publish',
				)
			);
		}
		if ( ! $pid || is_wp_error( $pid ) ) {
			continue;
		}
		update_post_meta( $pid, 'lb_type', $p['type'] );
		update_post_meta( $pid, 'lb_type_label', $p['typeLabel'] );
		update_post_meta( $pid, 'lb_tagline', $p['tagline'] );
		update_post_meta( $pid, 'lb_price', $p['price'] );
		update_post_meta( $pid, 'lb_old_price', $p['oldPrice'] );
		update_post_meta( $pid, 'lb_volume', $p['volume'] );
		update_post_meta( $pid, 'lb_badge', $p['badge'] );
		update_post_meta( $pid, 'lb_blurb', $p['blurb'] );
		update_post_meta( $pid, 'lb_features', implode( '|', $p['features'] ) );
		update_post_meta( $pid, 'lb_hero_img', $p['heroImg'] );
		update_post_meta( $pid, 'lb_order', array_search( $p['id'], wp_list_pluck( lb_seed_products(), 'id' ), true ) );
		wp_set_object_terms( $pid, $p['scents'], 'lb_scent', false );
	}

	// Tạo các trang cố định + gán trang chủ tĩnh.
	lb_seed_pages();

	flush_rewrite_rules();
}

/** Tạo trang Câu chuyện / Thanh toán / Đặt hàng thành công và đặt trang chủ tĩnh. */
function lb_seed_pages() {
	$pages = array(
		'cau-chuyen'  => array( 'Câu chuyện', 'page-story.php' ),
		'bo-suu-tap'  => array( 'Mùi hương', 'page-scents.php' ),
		'thanh-toan'  => array( 'Thanh toán', 'page-checkout.php' ),
		'dat-hang'    => array( 'Đặt hàng thành công', 'page-success.php' ),
		'trang-chu'   => array( 'Lion Bartender', '' ),
	);
	foreach ( $pages as $slug => $info ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$page_id = $existing->ID;
		} else {
			$page_id = wp_insert_post(
				array(
					'post_type'   => 'page',
					'post_name'   => $slug,
					'post_title'  => $info[0],
					'post_status' => 'publish',
				)
			);
		}
		if ( $page_id && ! is_wp_error( $page_id ) && $info[1] ) {
			update_post_meta( $page_id, '_wp_page_template', $info[1] );
		}
	}

	// Đặt trang chủ tĩnh là "trang-chu" để front-page.php hoạt động.
	$home = get_page_by_path( 'trang-chu' );
	if ( $home ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home->ID );
	}
}

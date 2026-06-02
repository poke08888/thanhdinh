<?php
/**
 * Lion Bartender theme bootstrap.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

define( 'LB_VERSION', '1.0.6' );

require get_template_directory() . '/inc/data.php';
require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/components.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/woocommerce.php';
if ( is_admin() ) {
	require get_template_directory() . '/inc/admin-scent.php';
	require get_template_directory() . '/inc/admin-product.php';
}

/* ------------------------------------------------------------------
 * Theme setup
 * ------------------------------------------------------------------ */
add_action( 'after_setup_theme', 'lb_setup' );
function lb_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 360,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	register_nav_menus(
		array(
			'primary' => 'Menu chính',
		)
	);

	// Hỗ trợ WooCommerce (dùng để theo dõi đơn hàng; giao diện vẫn là theme custom).
	add_theme_support( 'woocommerce' );
}

/* ------------------------------------------------------------------
 * Enqueue assets
 * ------------------------------------------------------------------ */
add_action( 'wp_enqueue_scripts', 'lb_assets' );
function lb_assets() {
	// Google Fonts: Oswald + Cormorant Garamond + Barlow (khớp thiết kế).
	wp_enqueue_style(
		'lb-fonts',
		'https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500;1,600&family=Barlow:wght@400;500;600&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'lion-bartender', get_stylesheet_uri(), array( 'lb-fonts' ), LB_VERSION );

	wp_enqueue_script( 'lb-app', get_template_directory_uri() . '/assets/js/app.js', array(), LB_VERSION, true );

	// Dữ liệu sản phẩm + mùi cho JS (giỏ hàng, set builder render lại trên client).
	$products = array();
	foreach ( lb_get_products() as $p ) {
		// gắn map ảnh theo mùi để JS hiển thị đúng tem nhãn.
		$imgs = array();
		foreach ( $p['scents'] as $sid ) {
			$imgs[ $sid ] = lb_product_image( $p, $sid );
		}
		$p['images']  = $imgs;
		$products[ $p['slug'] ] = $p;
	}

	$scents = array();
	foreach ( lb_get_scents() as $slug => $s ) {
		$scents[ $slug ] = array(
			'name'  => $s['name'],
			'tone'  => $s['tone'],
			'color' => $s['color'],
			'desc'  => $s['desc'],
			'notes' => $s['notes'],
			'img'   => $s['img_url'],
		);
	}

	wp_localize_script(
		'lb-app',
		'LB_DATA',
		array(
			'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
			'nonce'       => wp_create_nonce( 'lb_order' ),
			'checkoutUrl' => lb_page_url( 'thanh-toan' ),
			'successUrl'  => lb_page_url( 'dat-hang' ),
			'shopUrl'     => lb_shop_url(),
			'freeShipMin' => 299000,
			'shipFee'     => 25000,
			'products'    => $products,
			'scents'      => $scents,
		)
	);
}

/* ------------------------------------------------------------------
 * Body class — gắn data attribute phong cách đã chốt qua wrapper .app
 * (xem header/footer). Thêm class tiện ích.
 * ------------------------------------------------------------------ */
add_filter( 'body_class', 'lb_body_class' );
function lb_body_class( $classes ) {
	$classes[] = 'lb';
	return $classes;
}

/* ------------------------------------------------------------------
 * AJAX: đặt hàng (lưu vào CPT lb_order)
 * ------------------------------------------------------------------ */
add_action( 'wp_ajax_lb_place_order', 'lb_place_order' );
add_action( 'wp_ajax_nopriv_lb_place_order', 'lb_place_order' );
function lb_place_order() {
	check_ajax_referer( 'lb_order', 'nonce' );

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$address = isset( $_POST['address'] ) ? sanitize_text_field( wp_unslash( $_POST['address'] ) ) : '';
	$ward    = isset( $_POST['ward'] ) ? sanitize_text_field( wp_unslash( $_POST['ward'] ) ) : '';
	$city    = isset( $_POST['city'] ) ? sanitize_text_field( wp_unslash( $_POST['city'] ) ) : '';
	$note    = isset( $_POST['note'] ) ? sanitize_textarea_field( wp_unslash( $_POST['note'] ) ) : '';
	$pay     = isset( $_POST['pay'] ) ? sanitize_text_field( wp_unslash( $_POST['pay'] ) ) : 'cod';
	$items   = isset( $_POST['items'] ) ? json_decode( wp_unslash( $_POST['items'] ), true ) : array();

	if ( empty( $name ) || empty( $phone ) || empty( $items ) ) {
		wp_send_json_error( array( 'message' => 'Thiếu thông tin đơn hàng.' ) );
	}

	// Tính tổng phía server (an toàn — không tin giá từ client).
	$lines    = array();
	$subtotal = 0;
	foreach ( $items as $it ) {
		$slug    = isset( $it['slug'] ) ? sanitize_text_field( $it['slug'] ) : '';
		$scent   = isset( $it['scent'] ) ? sanitize_text_field( $it['scent'] ) : '';
		$qty     = isset( $it['qty'] ) ? max( 1, (int) $it['qty'] ) : 1;
		$product = lb_get_product_by_slug( $slug );
		if ( ! $product ) {
			continue;
		}
		$line_total = $product['price'] * $qty;
		$subtotal  += $line_total;
		$scent_data = lb_get_scent( $scent );
		$lines[]    = array(
			'slug'       => $slug,
			'name'       => $product['name'],
			'scent_slug' => $scent,
			'scent'      => $scent_data ? $scent_data['name'] : $scent,
			'qty'        => $qty,
			'price'      => $product['price'],
			'total'      => $line_total,
		);
	}

	if ( empty( $lines ) ) {
		wp_send_json_error( array( 'message' => 'Giỏ hàng trống.' ) );
	}

	$ship     = ( $subtotal >= 299000 ) ? 0 : 25000;
	$total    = $subtotal + $ship;
	$customer = compact( 'name', 'phone', 'email', 'address', 'ward', 'city', 'note' );

	/*
	 * Nếu có WooCommerce: tạo đơn hàng WooCommerce thật để theo dõi trong
	 * WooCommerce → Đơn hàng (trạng thái, email, báo cáo). Nếu không, dùng
	 * CPT lb_order nội bộ làm fallback.
	 */
	if ( lb_wc_active() ) {
		$result = lb_create_wc_order( $lines, $customer, $pay, $ship );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}
		wp_send_json_success(
			array(
				'order_id' => $result,
				'total'    => $total,
				'redirect' => add_query_arg( 'order', $result, lb_page_url( 'dat-hang' ) ),
			)
		);
	}

	// Fallback: CPT lb_order.
	$order_id = wp_insert_post(
		array(
			'post_type'   => 'lb_order',
			'post_status' => 'publish',
			'post_title'  => sprintf( 'Đơn của %s — %s', $name, lb_price( $total ) ),
		)
	);

	if ( is_wp_error( $order_id ) || ! $order_id ) {
		wp_send_json_error( array( 'message' => 'Không tạo được đơn hàng.' ) );
	}

	update_post_meta( $order_id, 'lb_customer', $customer );
	update_post_meta( $order_id, 'lb_items', $lines );
	update_post_meta( $order_id, 'lb_subtotal', $subtotal );
	update_post_meta( $order_id, 'lb_ship', $ship );
	update_post_meta( $order_id, 'lb_total', $total );
	update_post_meta( $order_id, 'lb_pay', $pay );
	update_post_meta( $order_id, 'lb_status', 'new' );

	wp_send_json_success(
		array(
			'order_id' => $order_id,
			'total'    => $total,
			'redirect' => add_query_arg( 'order', $order_id, lb_page_url( 'dat-hang' ) ),
		)
	);
}

/* ------------------------------------------------------------------
 * Hộp meta hiển thị chi tiết đơn hàng trong admin
 * ------------------------------------------------------------------ */
add_action( 'add_meta_boxes', 'lb_order_metabox' );
function lb_order_metabox() {
	add_meta_box( 'lb_order_details', 'Chi tiết đơn hàng', 'lb_order_metabox_render', 'lb_order', 'normal', 'high' );
}
function lb_order_metabox_render( $post ) {
	$c     = get_post_meta( $post->ID, 'lb_customer', true );
	$items = get_post_meta( $post->ID, 'lb_items', true );
	$total = get_post_meta( $post->ID, 'lb_total', true );
	$pay   = get_post_meta( $post->ID, 'lb_pay', true );
	echo '<p><strong>Khách:</strong> ' . esc_html( $c['name'] ?? '' ) . ' — ' . esc_html( $c['phone'] ?? '' ) . '</p>';
	echo '<p><strong>Địa chỉ:</strong> ' . esc_html( trim( ( $c['address'] ?? '' ) . ', ' . ( $c['ward'] ?? '' ) . ', ' . ( $c['city'] ?? '' ), ', ' ) ) . '</p>';
	if ( ! empty( $c['email'] ) ) {
		echo '<p><strong>Email:</strong> ' . esc_html( $c['email'] ) . '</p>';
	}
	if ( ! empty( $c['note'] ) ) {
		echo '<p><strong>Ghi chú:</strong> ' . esc_html( $c['note'] ) . '</p>';
	}
	echo '<p><strong>Thanh toán:</strong> ' . esc_html( $pay ) . '</p>';
	if ( $items ) {
		echo '<table style="width:100%;border-collapse:collapse" border="1" cellpadding="6"><tr><th>Sản phẩm</th><th>Mùi</th><th>SL</th><th>Thành tiền</th></tr>';
		foreach ( $items as $it ) {
			echo '<tr><td>' . esc_html( $it['name'] ) . '</td><td>' . esc_html( $it['scent'] ) . '</td><td>' . (int) $it['qty'] . '</td><td>' . esc_html( lb_price( $it['total'] ) ) . '</td></tr>';
		}
		echo '</table>';
	}
	echo '<p style="font-size:18px"><strong>Tổng cộng: ' . esc_html( lb_price( $total ) ) . '</strong></p>';
}

/* ------------------------------------------------------------------
 * Cho phép template chọn được trong admin cho các page mới
 * (đã gán tự động khi seed; đây chỉ để chắc chắn các file template tồn tại).
 * ------------------------------------------------------------------ */

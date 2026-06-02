<?php
/**
 * Lion Bartender — tích hợp WooCommerce.
 *
 * Giao diện & checkout vẫn là theme custom (đúng thiết kế). WooCommerce chỉ
 * đóng vai trò "hậu trường" để theo dõi đơn hàng: mỗi lần khách đặt, tạo một
 * đơn hàng WooCommerce thật → quản lý trong WooCommerce → Đơn hàng (trạng thái,
 * email tự động, báo cáo doanh thu...).
 *
 * Mỗi sản phẩm lb_product được đồng bộ thành một sản phẩm WooCommerce ẩn
 * (không hiện trong catalog WC, tránh trùng với cửa hàng custom) để làm dòng
 * hàng trong đơn. Mùi hương được lưu làm meta của từng dòng hàng.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

/** WooCommerce có đang hoạt động không. */
function lb_wc_active() {
	return class_exists( 'WooCommerce' ) && function_exists( 'wc_create_order' );
}

/** Nhãn phương thức thanh toán. */
function lb_pay_titles() {
	return array(
		'cod'  => 'Thanh toán khi nhận hàng (COD)',
		'bank' => 'Chuyển khoản ngân hàng',
		'momo' => 'Ví MoMo / ZaloPay',
	);
}

/**
 * Lấy (hoặc tạo) sản phẩm WooCommerce tương ứng một lb_product.
 *
 * @param array $product Dữ liệu sản phẩm (lb_product_data).
 * @param bool  $sync    Cập nhật lại giá/tên nếu sản phẩm đã tồn tại.
 * @return int|0 ID sản phẩm WooCommerce.
 */
function lb_get_or_create_wc_product( $product, $sync = false ) {
	if ( ! lb_wc_active() || empty( $product['ID'] ) ) {
		return 0;
	}

	$pid   = $product['ID'];
	$wc_id = (int) get_post_meta( $pid, 'lb_wc_product_id', true );

	if ( $wc_id && wc_get_product( $wc_id ) ) {
		if ( $sync ) {
			lb_fill_wc_product( wc_get_product( $wc_id ), $product );
		}
		return $wc_id;
	}

	$wcp   = new WC_Product_Simple();
	lb_fill_wc_product( $wcp, $product );
	$wc_id = $wcp->save();

	if ( $wc_id ) {
		update_post_meta( $pid, 'lb_wc_product_id', $wc_id );
		update_post_meta( $wc_id, '_lb_source_slug', $product['slug'] );
	}
	return (int) $wc_id;
}

/** Gán dữ liệu lb_product vào một WC_Product rồi lưu. */
function lb_fill_wc_product( $wcp, $product ) {
	$regular = $product['oldPrice'] ? $product['oldPrice'] : $product['price'];
	$wcp->set_name( $product['name'] );
	$wcp->set_status( 'publish' );
	$wcp->set_catalog_visibility( 'hidden' ); // không hiện trong catalog WC (đã có shop custom).
	$wcp->set_sku( 'LB-' . strtoupper( $product['slug'] ) );
	$wcp->set_regular_price( (string) $regular );
	if ( $product['oldPrice'] && $product['oldPrice'] > $product['price'] ) {
		$wcp->set_sale_price( (string) $product['price'] );
	}
	$wcp->set_short_description( $product['tagline'] );
	$wcp->set_description( $product['blurb'] );
	$wcp->save();
}

/** Đồng bộ giá/tên sang WC khi lưu sản phẩm trong admin. */
add_action( 'save_post_lb_product', 'lb_sync_wc_on_save', 20 );
function lb_sync_wc_on_save( $post_id ) {
	if ( ! lb_wc_active() || wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	$p = lb_product_data( $post_id );
	if ( $p ) {
		lb_get_or_create_wc_product( $p, true );
	}
}

/**
 * Tạo đơn hàng WooCommerce từ dữ liệu checkout.
 *
 * @param array  $lines    Dòng hàng đã tính toán phía server.
 * @param array  $customer Thông tin khách.
 * @param string $pay      Phương thức thanh toán.
 * @param int    $ship     Phí giao hàng.
 * @return int|WP_Error    ID đơn hàng hoặc lỗi.
 */
function lb_create_wc_order( $lines, $customer, $pay, $ship ) {
	if ( ! lb_wc_active() ) {
		return new WP_Error( 'no_wc', 'WooCommerce chưa sẵn sàng.' );
	}

	$order = wc_create_order( array( 'created_via' => 'lion-bartender' ) );
	if ( is_wp_error( $order ) ) {
		return $order;
	}

	foreach ( $lines as $line ) {
		$product = lb_get_product_by_slug( $line['slug'] );
		if ( ! $product ) {
			continue;
		}
		$wc_id = lb_get_or_create_wc_product( $product );
		$wcp   = $wc_id ? wc_get_product( $wc_id ) : null;
		if ( ! $wcp ) {
			continue;
		}
		$item_id = $order->add_product(
			$wcp,
			$line['qty'],
			array(
				'subtotal' => $line['total'],
				'total'    => $line['total'],
			)
		);
		if ( $item_id ) {
			wc_add_order_item_meta( $item_id, 'Mùi hương', $line['scent'] );
		}
	}

	// Phí giao hàng.
	$ship_item = new WC_Order_Item_Shipping();
	$ship_item->set_method_title( 'Giao hàng' );
	$ship_item->set_method_id( 'flat_rate' );
	$ship_item->set_total( (string) $ship );
	$order->add_item( $ship_item );

	// Địa chỉ.
	$addr = array(
		'first_name' => $customer['name'],
		'address_1'  => $customer['address'],
		'address_2'  => $customer['ward'],
		'city'       => $customer['city'],
		'country'    => 'VN',
		'phone'      => $customer['phone'],
		'email'      => $customer['email'],
	);
	$order->set_address( $addr, 'billing' );
	$ship_addr = $addr;
	unset( $ship_addr['email'] );
	$order->set_address( $ship_addr, 'shipping' );

	// Thanh toán.
	$titles = lb_pay_titles();
	$order->set_payment_method( $pay );
	$order->set_payment_method_title( isset( $titles[ $pay ] ) ? $titles[ $pay ] : $pay );

	if ( ! empty( $customer['note'] ) ) {
		$order->set_customer_note( $customer['note'] );
	}

	$order->calculate_totals();
	// COD → đang xử lý; chuyển khoản/ví → chờ thanh toán.
	$order->set_status( 'cod' === $pay ? 'processing' : 'on-hold' );
	$order->add_order_note( 'Đơn đặt qua giao diện Lion Bartender.' );

	$order_id = $order->save();
	if ( ! $order_id ) {
		return new WP_Error( 'order_failed', 'Không tạo được đơn hàng.' );
	}
	return (int) $order_id;
}

/**
 * Đồng bộ toàn bộ sản phẩm sang WooCommerce một lần (khi WC vừa được kích hoạt).
 */
add_action( 'admin_init', 'lb_maybe_sync_wc_catalog' );
function lb_maybe_sync_wc_catalog() {
	if ( ! lb_wc_active() ) {
		return;
	}
	if ( get_option( 'lb_wc_synced' ) ) {
		return;
	}
	foreach ( lb_get_products() as $p ) {
		lb_get_or_create_wc_product( $p, true );
	}
	update_option( 'lb_wc_synced', 1 );
}

/**
 * Thông báo gợi ý cài WooCommerce nếu chưa có.
 */
add_action( 'admin_notices', 'lb_wc_admin_notice' );
function lb_wc_admin_notice() {
	if ( lb_wc_active() || ! current_user_can( 'install_plugins' ) ) {
		return;
	}
	$url = admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' );
	echo '<div class="notice notice-info is-dismissible"><p><strong>Lion Bartender:</strong> '
		. 'Cài &amp; kích hoạt <a href="' . esc_url( $url ) . '">WooCommerce</a> để theo dõi đơn hàng khách đặt ngay trong trang quản trị '
		. '(trạng thái, email, báo cáo). Hiện đang dùng chế độ đơn hàng nội bộ.</p></div>';
}

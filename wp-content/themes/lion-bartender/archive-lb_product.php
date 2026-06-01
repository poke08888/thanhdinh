<?php
/**
 * Trang Shop — lưới sản phẩm + lọc theo loại (client-side).
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

get_header();

$products = lb_get_products();
$filters  = lb_shop_filters();
?>

<div class="view">
	<div class="wrap">
		<div class="phead">
			<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> / <b>Sản phẩm</b></div>
			<div class="eyebrow">Cửa hàng</div>
			<h1 class="h-lg" style="margin-top:8px">Quầy Lion Bartender</h1>
			<p class="lead muted" style="max-width:50ch;margin:14px auto 0">
				Bộ sưu tập chăm sóc nam giới — lăn khử mùi, tắm gội 3-in-1 và những hộp quà chất chơi.
			</p>
		</div>

		<div class="filters" id="lb-filters">
			<?php foreach ( $filters as $id => $label ) : ?>
				<button class="chip <?php echo 'all' === $id ? 'is-active' : ''; ?>" data-filter="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></button>
			<?php endforeach; ?>
		</div>

		<div class="pgrid" id="lb-shop-grid" style="padding-bottom:80px">
			<?php foreach ( $products as $p ) {
				echo lb_product_card( $p ); // phpcs:ignore
			} ?>
		</div>
	</div>
</div>

<?php
get_footer();

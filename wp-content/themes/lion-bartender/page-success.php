<?php
/**
 * Template Name: Đặt hàng thành công
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="view"><div class="wrap"><div class="success">
	<div class="success__check"><?php lb_the_icon( 'checkLg' ); ?></div>
	<div class="eyebrow" style="margin-bottom:12px">Cảm ơn quý ông</div>
	<h1 class="h-md" style="margin-bottom:16px">Đặt hàng thành công!</h1>
	<?php
	$lb_order_id = isset( $_GET['order'] ) ? absint( $_GET['order'] ) : 0;
	if ( $lb_order_id ) :
		?>
		<p class="serif" style="font-size:20px;color:var(--gold);margin-bottom:18px">Mã đơn hàng: <strong>#<?php echo esc_html( $lb_order_id ); ?></strong></p>
	<?php endif; ?>
	<p class="lead muted" style="margin-bottom:30px">
		Đơn hàng của bạn đang được pha chế. Chúng tôi sẽ gọi xác nhận trong ít phút — hãy sẵn sàng tỏa hương.
	</p>
	<?php echo lb_rule( 'max-width:240px;margin:0 auto 30px' ); // phpcs:ignore ?>
	<div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
		<a class="btn btn--gold" href="<?php echo esc_url( lb_shop_url() ); ?>">Tiếp tục mua sắm</a>
		<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>">Về trang chủ</a>
	</div>
</div></div></div>
<?php
get_footer();

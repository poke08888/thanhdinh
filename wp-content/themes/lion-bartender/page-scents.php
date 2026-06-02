<?php
/**
 * Template Name: Mùi hương (bộ sưu tập)
 * Trang liệt kê 6 mùi hương.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

get_header();

$scents = lb_get_scents();
?>

<div class="view">
	<div class="wrap" style="padding-top:30px">
		<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> / <b>Mùi hương</b></div>
	</div>

	<section class="section" id="scents">
		<div class="wrap">
			<div class="reveal" style="text-align:center;margin-bottom:48px">
				<?php echo lb_rule( 'max-width:280px;margin:0 auto 18px' ); // phpcs:ignore ?>
				<div class="eyebrow" style="margin-bottom:14px"><?php lb_the_text( 'scents_eyebrow' ); ?></div>
				<h2 class="h-lg"><?php lb_the_text( 'scents_heading' ); ?></h2>
				<p class="lead muted" style="max-width:52ch;margin:16px auto 0">
					<?php lb_the_html( 'scents_sub' ); ?>
				</p>
			</div>
			<div class="scents reveal">
				<?php foreach ( $scents as $s ) {
					echo lb_scent_card( $s ); // phpcs:ignore
				} ?>
			</div>
		</div>
	</section>
</div>

<?php
get_footer();

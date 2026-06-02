<?php
/**
 * Header: mở wrapper .app (phong cách đã chốt: Đêm xanh + Vàng), announce bar, nav.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

$lb_nav = array(
	'home'   => array( 'Trang chủ', home_url( '/' ) ),
	'shop'   => array( 'Sản phẩm', lb_shop_url() ),
	'scents' => array( 'Mùi hương', lb_scents_url() ),
	'story'  => array( 'Câu chuyện', lb_page_url( 'cau-chuyen' ) ),
);

// Xác định mục đang active.
$lb_active = 'home';
if ( is_post_type_archive( 'lb_product' ) || is_singular( 'lb_product' ) ) {
	$lb_active = 'shop';
} elseif ( is_tax( 'lb_scent' ) || is_page_template( 'page-scents.php' ) ) {
	$lb_active = 'scents';
} elseif ( is_page_template( 'page-story.php' ) ) {
	$lb_active = 'story';
}

$lb_logo = lb_asset( 'logo-wordmark.png' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="app" data-bg="midnight" data-accent="gold" data-orn="on">

	<div class="announce"><?php lb_the_icon( 'bolt' ); ?> <?php lb_the_text( 'announce_text' ); ?> <?php lb_the_icon( 'bolt' ); ?></div>

	<header class="nav" id="lb-nav">
		<div class="wrap">
			<div class="nav__bar">
				<a class="nav__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img class="nav__logo" src="<?php echo esc_url( $lb_logo ); ?>" alt="Lion Bartender" />
				</a>
				<nav class="nav__links">
					<?php foreach ( $lb_nav as $key => $item ) : ?>
						<a class="nav__link <?php echo $lb_active === $key ? 'is-active' : ''; ?>" href="<?php echo esc_url( $item[1] ); ?>"><?php echo esc_html( $item[0] ); ?></a>
					<?php endforeach; ?>
				</nav>
				<div class="nav__right">
					<button class="icon-btn nav__menu-btn" id="lb-menu-btn" aria-label="menu"><?php lb_the_icon( 'menu' ); ?></button>
					<button class="icon-btn" id="lb-cart-btn" aria-label="cart">
						<?php lb_the_icon( 'cart' ); ?>
						<span class="cart-count" id="lb-cart-count" hidden>0</span>
					</button>
				</div>
			</div>
		</div>
		<div class="nav__mobile" id="lb-mobile-menu" hidden>
			<?php foreach ( $lb_nav as $key => $item ) : ?>
				<a class="nav__mobile-link <?php echo $lb_active === $key ? 'is-active' : ''; ?>" href="<?php echo esc_url( $item[1] ); ?>"><?php echo esc_html( $item[0] ); ?> <?php lb_the_icon( 'chevron' ); ?></a>
			<?php endforeach; ?>
		</div>
	</header>

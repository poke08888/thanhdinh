<?php
/**
 * Trang chủ Lion Bartender.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

get_header();

$scents   = lb_get_scents();
$products = lb_get_products();
$logo     = lb_asset( 'logo-wordmark.png' );

// Sản phẩm 3-in-1 cho feature band.
$three = lb_get_product_by_slug( 'shower-3in1' );

$trust = array(
	array( 'truck', lb_text( 'trust1_t' ), lb_text( 'trust1_s' ) ),
	array( 'drop', lb_text( 'trust2_t' ), lb_text( 'trust2_s' ) ),
	array( 'shield', lb_text( 'trust3_t' ), lb_text( 'trust3_s' ) ),
);
?>

<div class="view">

	<!-- HERO (minimal) -->
	<section class="hero hero--minimal">
		<div class="hero__bg"></div>
		<div class="wrap">
			<div class="hero__center">
				<div class="eyebrow filigree" style="margin-bottom:20px"><?php lb_the_text( 'hero_eyebrow' ); ?></div>
				<img class="hero__logo hero__logo--big" src="<?php echo esc_url( $logo ); ?>" alt="Lion Bartender" />
				<p class="lead" style="max-width:40ch;margin:8px auto 30px">
					<?php lb_the_html( 'hero_sub' ); ?>
				</p>
				<div class="hero__cta" style="justify-content:center">
					<a class="btn btn--gold btn--lg" href="<?php echo esc_url( lb_shop_url() ); ?>"><?php lb_the_text( 'hero_cta1' ); ?> <?php lb_the_icon( 'arrow' ); ?></a>
					<a class="btn btn--ghost btn--lg" href="<?php echo esc_url( lb_scents_url() ); ?>"><?php lb_the_text( 'hero_cta2' ); ?></a>
				</div>
				<div class="hero__chips filigree">
					<?php foreach ( $scents as $slug => $s ) : ?>
						<a class="hero__chip" href="<?php echo esc_url( $s['link'] ); ?>">
							<span class="hero__chip-dot" style="background:<?php echo esc_attr( $s['color'] ); ?>"></span>
							<?php echo esc_html( $s['name'] ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- TRUST BAND -->
	<div style="border-top:1px solid var(--hair);border-bottom:1px solid var(--hair);background:rgba(0,0,0,.25)">
		<div class="wrap">
			<div class="lb-trust">
				<?php foreach ( $trust as $it ) : ?>
					<div class="lb-trust__item">
						<span style="color:var(--gold);flex:none"><?php lb_the_icon( $it[0] ); ?></span>
						<div>
							<div style="font-family:var(--font-display);text-transform:uppercase;letter-spacing:.08em;font-size:14px;font-weight:600"><?php echo esc_html( $it[1] ); ?></div>
							<div class="muted" style="font-size:12.5px"><?php echo esc_html( $it[2] ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<!-- SCENT SECTION -->
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

	<!-- FEATURE 3-IN-1 -->
	<?php if ( $three ) : ?>
	<section class="section--tight">
		<div class="wrap">
			<div class="feature gilt reveal">
				<div class="feature__grid">
					<div class="feature__media">
						<div class="hero__halo" style="width:90%;background:radial-gradient(circle, rgba(47,134,196,.3), transparent 62%)"></div>
						<img src="<?php echo esc_url( lb_asset( 'bottle-ocean-club.png' ) ); ?>" alt="3-in-1" style="position:relative;z-index:2;max-height:520px" />
					</div>
					<div class="feature__body">
						<div class="eyebrow"><?php lb_the_text( 'feat_eyebrow' ); ?></div>
						<h2 class="h-md" style="margin:12px 0 8px"><?php lb_the_html( 'feat_heading' ); ?></h2>
						<p class="muted" style="max-width:46ch"><?php echo esc_html( $three['blurb'] ); ?></p>
						<ul class="feature__list">
							<?php foreach ( $three['features'] as $f ) : ?>
								<li><?php lb_the_icon( 'check' ); ?> <?php echo esc_html( $f ); ?></li>
							<?php endforeach; ?>
						</ul>
						<div style="display:flex;gap:14px;align-items:center;flex-wrap:wrap">
							<a class="btn btn--gold" href="<?php echo esc_url( $three['link'] ); ?>">Mua <?php echo esc_html( lb_price( $three['price'] ) ); ?> <?php lb_the_icon( 'arrow' ); ?></a>
							<span class="muted" style="font-size:13px"><?php lb_the_text( 'feat_note' ); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- BEST SELLERS -->
	<section class="section">
		<div class="wrap">
			<div class="reveal" style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:38px;gap:20px;flex-wrap:wrap">
				<div>
					<div class="eyebrow" style="margin-bottom:12px"><?php lb_the_text( 'best_eyebrow' ); ?></div>
					<h2 class="h-lg"><?php lb_the_text( 'best_heading' ); ?></h2>
				</div>
				<a class="tlink" href="<?php echo esc_url( lb_shop_url() ); ?>"><?php lb_the_text( 'best_link' ); ?> <?php lb_the_icon( 'arrow' ); ?></a>
			</div>
			<div class="pgrid reveal">
				<?php foreach ( $products as $p ) {
					echo lb_product_card( $p ); // phpcs:ignore
				} ?>
			</div>
		</div>
	</section>

	<!-- STORY TEASER -->
	<section class="section--tight">
		<div class="wrap">
			<div class="story-grid reveal">
				<div>
					<div class="eyebrow" style="margin-bottom:14px"><?php lb_the_text( 'steaser_eyebrow' ); ?></div>
					<h2 class="h-md" style="margin-bottom:18px"><?php lb_the_html( 'steaser_heading' ); ?></h2>
					<p class="muted" style="margin-bottom:14px;max-width:46ch">
						<?php lb_the_html( 'steaser_p1' ); ?>
					</p>
					<p class="muted" style="margin-bottom:26px;max-width:46ch">
						<?php lb_the_html( 'steaser_p2' ); ?>
					</p>
					<a class="btn btn--ghost" href="<?php echo esc_url( lb_page_url( 'cau-chuyen' ) ); ?>"><?php lb_the_text( 'steaser_btn' ); ?> <?php lb_the_icon( 'arrow' ); ?></a>
				</div>
				<div class="story-img" style="position:relative">
					<img src="<?php echo esc_url( lb_asset( 'promo-trio-full.png' ) ); ?>" alt="Lion Bartender" />
				</div>
			</div>
		</div>
	</section>

</div>

<?php
get_footer();

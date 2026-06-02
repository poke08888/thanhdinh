<?php
/**
 * Template Name: Câu chuyện thương hiệu
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

get_header();

$pillars = array(
	array( 'drop', lb_text( 'pillar1_t' ), lb_text( 'pillar1_d' ) ),
	array( 'flame', lb_text( 'pillar2_t' ), lb_text( 'pillar2_d' ) ),
	array( 'shield', lb_text( 'pillar3_t' ), lb_text( 'pillar3_d' ) ),
);
$steps = array(
	array( lb_text( 'step1_t' ), lb_text( 'step1_d' ) ),
	array( lb_text( 'step2_t' ), lb_text( 'step2_d' ) ),
	array( lb_text( 'step3_t' ), lb_text( 'step3_d' ) ),
	array( lb_text( 'step4_t' ), lb_text( 'step4_d' ) ),
);
?>

<div class="view">
	<div class="wrap">
		<div class="crumbs" style="padding-top:30px"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> / <b>Câu chuyện</b></div>
		<div class="story-hero">
			<div class="eyebrow filigree" style="margin-bottom:18px"><?php lb_the_text( 'story_hero_eyebrow' ); ?></div>
			<?php echo lb_rule( 'max-width:300px;margin:0 auto 26px' ); // phpcs:ignore ?>
			<p class="story-quote"><?php lb_the_html( 'story_quote' ); ?></p>
		</div>
	</div>

	<div class="wrap">
		<section class="section--tight story-grid">
			<div class="story-img"><img src="<?php echo esc_url( lb_asset( 'promo-trio-full.png' ) ); ?>" alt="Lion Bartender" /></div>
			<div>
				<div class="eyebrow" style="margin-bottom:14px"><?php lb_the_text( 'story_origin_eyebrow' ); ?></div>
				<h2 class="h-md" style="margin-bottom:18px"><?php lb_the_html( 'story_origin_heading' ); ?></h2>
				<p class="muted" style="margin-bottom:14px">
					<?php lb_the_html( 'story_origin_p1' ); ?>
				</p>
				<p class="muted">
					<?php lb_the_html( 'story_origin_p2' ); ?>
				</p>
			</div>
		</section>

		<section class="section--tight">
			<div style="text-align:center;margin-bottom:44px">
				<div class="eyebrow" style="margin-bottom:12px"><?php lb_the_text( 'story_pillars_eyebrow' ); ?></div>
				<h2 class="h-lg"><?php lb_the_text( 'story_pillars_heading' ); ?></h2>
			</div>
			<div class="pillars">
				<?php foreach ( $pillars as $p ) : ?>
					<div class="pillar reveal">
						<div class="pillar__ic"><?php lb_the_icon( $p[0] ); ?></div>
						<h3><?php echo esc_html( $p[1] ); ?></h3>
						<p><?php echo esc_html( $p[2] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="section--tight">
			<div style="text-align:center;margin-bottom:40px">
				<div class="eyebrow" style="margin-bottom:12px"><?php lb_the_text( 'story_steps_eyebrow' ); ?></div>
				<h2 class="h-md"><?php lb_the_text( 'story_steps_heading' ); ?></h2>
			</div>
			<div class="steps">
				<?php foreach ( $steps as $i => $st ) : ?>
					<div class="step">
						<div class="step__n serif">0<?php echo (int) ( $i + 1 ); ?></div>
						<h4><?php echo esc_html( $st[0] ); ?></h4>
						<p><?php echo esc_html( $st[1] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="section--tight">
			<div class="gilt" style="text-align:center;padding:64px 30px;border-radius:6px;background:linear-gradient(120deg,var(--ink-2),var(--ink))">
				<img src="<?php echo esc_url( lb_asset( 'monogram.png' ) ); ?>" alt="" style="width:64px;margin:0 auto 22px" />
				<h2 class="h-md" style="margin-bottom:14px"><?php lb_the_text( 'story_cta_heading' ); ?></h2>
				<p class="lead muted" style="max-width:46ch;margin:0 auto 28px"><?php lb_the_html( 'story_cta_sub' ); ?></p>
				<a class="btn btn--gold btn--lg" href="<?php echo esc_url( lb_shop_url() ); ?>"><?php lb_the_text( 'story_cta_btn' ); ?> <?php lb_the_icon( 'arrow' ); ?></a>
			</div>
		</section>
	</div>
</div>

<?php
get_footer();

<?php
/**
 * Trang chi tiết mùi hương — Set Builder: mua nhanh nhiều sản phẩm cùng mùi.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

get_header();

$term  = get_queried_object();
$slug  = $term->slug;
$s     = lb_get_scent( $slug );

// Sản phẩm dùng mùi này (loại trừ hộp quà), theo thứ tự thiết kế.
$products = array();
foreach ( lb_get_products() as $p ) {
	if ( 'gift' !== $p['type'] && in_array( $slug, $p['scents'], true ) ) {
		$products[] = $p;
	}
}

$other_scents = array();
foreach ( lb_get_scents() as $osid => $os ) {
	if ( $osid !== $slug ) {
		$other_scents[] = $os;
	}
}
?>

<div class="view" id="lb-scent" data-scent="<?php echo esc_attr( $slug ); ?>">

	<!-- scent hero -->
	<div class="scent-hero" style="--sc:<?php echo esc_attr( $s['color'] ); ?>">
		<div class="scent-hero__glow" style="background:radial-gradient(circle, <?php echo esc_attr( $s['color'] ); ?>, transparent 64%)"></div>
		<div class="wrap" style="position:relative;z-index:2">
			<div class="crumbs" style="padding-top:30px">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> / <a href="<?php echo esc_url( lb_scents_url() ); ?>">Mùi hương</a> / <b><?php echo esc_html( $s['name'] ); ?></b>
			</div>
			<div class="scent-hero__grid">
				<div>
					<div class="eyebrow filigree" style="margin-bottom:14px">Mùi hương · <?php echo esc_html( $s['tone'] ); ?></div>
					<h1 class="h-lg" style="margin-bottom:8px"><?php echo esc_html( $s['name'] ); ?></h1>
					<p class="lead" style="max-width:40ch;margin-bottom:22px"><?php echo esc_html( $s['desc'] ); ?></p>
					<div class="pd__notes filigree" style="margin-bottom:26px">
						<?php foreach ( $s['notes'] as $n ) : ?>
							<span class="note-tag"><?php echo esc_html( $n ); ?></span>
						<?php endforeach; ?>
					</div>
					<div style="display:flex;align-items:center;gap:12px">
						<span style="width:42px;height:42px;border-radius:50%;background:<?php echo esc_attr( $s['color'] ); ?>;border:1px solid rgba(255,255,255,.2);flex:none"></span>
						<span class="muted" style="font-size:14px"><?php echo count( $products ); ?> sản phẩm dùng mùi này</span>
					</div>
				</div>
				<div class="scent-hero__label">
					<img src="<?php echo esc_url( $s['img_url'] ); ?>" alt="<?php echo esc_attr( $s['name'] ); ?>" />
				</div>
			</div>
		</div>
	</div>

	<div class="wrap">
		<!-- set builder -->
		<section class="section--tight">
			<div style="margin-bottom:24px">
				<div class="eyebrow" style="margin-bottom:10px">Mua nhanh trọn bộ</div>
				<h2 class="h-md">Trọn bộ mùi <span class="gold-text"><?php echo esc_html( $s['name'] ); ?></span></h2>
			</div>

			<div class="setbuilder" id="lb-setbuilder">
				<div class="setbuilder__head">
					<p class="muted" style="font-size:14px;margin:0">
						Chọn số lượng từng món rồi thêm tất cả vào giỏ một lần — khỏi bấm vào từng sản phẩm.
					</p>
					<div class="setbuilder__quick">
						<button class="linkbtn" id="lb-set-all">Chọn trọn bộ</button>
						<button class="linkbtn linkbtn--mut" id="lb-set-clear" hidden>Xóa chọn</button>
					</div>
				</div>

				<div class="qbuy-grid">
					<?php foreach ( $products as $p ) :
						$img  = lb_product_image( $p, $slug );
						$link = add_query_arg( 'scent', $slug, $p['link'] );
						?>
						<div class="qbuy lb-qbuy" data-slug="<?php echo esc_attr( $p['slug'] ); ?>" data-price="<?php echo esc_attr( $p['price'] ); ?>" data-old="<?php echo esc_attr( $p['oldPrice'] ? $p['oldPrice'] : $p['price'] ); ?>">
							<a class="qbuy__media" href="<?php echo esc_url( $link ); ?>">
								<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $p['name'] ); ?>" />
							</a>
							<div class="qbuy__info">
								<span class="qbuy__type"><?php echo esc_html( $p['typeLabel'] . ' · ' . $p['volume'] ); ?></span>
								<a class="qbuy__name" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $p['name'] ); ?></a>
								<div class="qbuy__price">
									<span class="now"><?php echo esc_html( lb_price( $p['price'] ) ); ?></span>
									<?php if ( $p['oldPrice'] ) : ?>
										<span class="was"><?php echo esc_html( lb_price( $p['oldPrice'] ) ); ?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="qbuy__act">
								<!-- collapsed: add button; expanded: stepper (toggled by JS) -->
								<button class="qbuy__add" data-act="add"><?php lb_the_icon( 'arrow', 'style="width:14px;height:14px;transform:rotate(-90deg)"' ); ?> Thêm</button>
								<div class="qty qty--sm" data-stepper hidden>
									<button data-act="dec" aria-label="bớt">−</button>
									<span data-qty>0</span>
									<button data-act="inc" aria-label="thêm">+</button>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- floating set bar (rendered/toggled by JS) -->
			<div class="setbar" id="lb-setbar" hidden>
				<div class="setbar__sum">
					<span class="setbar__count" id="lb-setbar-count">0 sản phẩm</span>
					<span class="setbar__price">
						<span id="lb-setbar-price">0đ</span>
						<span class="setbar__old" id="lb-setbar-old" hidden></span>
					</span>
				</div>
				<button class="btn btn--gold" id="lb-setbar-add">
					<?php lb_the_icon( 'cart', 'style="width:17px;height:17px"' ); ?> Thêm tất cả vào giỏ
				</button>
			</div>
		</section>

		<!-- switch scent -->
		<section class="section--tight" style="border-top:1px solid var(--hair)">
			<div style="margin-bottom:26px">
				<div class="eyebrow" style="margin-bottom:10px">Đổi gu</div>
				<h2 class="h-md">Khám phá mùi khác</h2>
			</div>
			<div class="scent-switch">
				<?php foreach ( $other_scents as $os ) : ?>
					<a class="scent-switch__btn" href="<?php echo esc_url( $os['link'] ); ?>">
						<span class="scent-switch__dot" style="background:<?php echo esc_attr( $os['color'] ); ?>"></span>
						<span>
							<span class="scent-switch__name"><?php echo esc_html( $os['name'] ); ?></span>
							<span class="scent-switch__tone"><?php echo esc_html( $os['tone'] ); ?></span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</section>
	</div>

</div>

<?php
get_footer();

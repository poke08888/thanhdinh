<?php
/**
 * Chi tiết sản phẩm — chọn 6 mùi (đổi ảnh + mô tả + tầng hương), số lượng, thêm giỏ / mua ngay.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

get_header();

the_post();
$product = lb_product_data( get_the_ID() );

// Mùi khởi tạo: từ query ?scent= nếu hợp lệ, không thì mùi đầu tiên.
$req   = isset( $_GET['scent'] ) ? sanitize_text_field( wp_unslash( $_GET['scent'] ) ) : '';
$scent = ( $req && in_array( $req, $product['scents'], true ) ) ? $req : $product['scents'][0];
$s     = lb_get_scent( $scent );

$hero_img   = lb_product_image( $product, $scent );
$label_based = in_array( $product['type'], array( 'deodorant', 'spray', 'wash' ), true );
$save        = $product['oldPrice'] ? $product['oldPrice'] - $product['price'] : 0;

// Sản phẩm cùng mùi (related).
$related = array();
foreach ( lb_get_products() as $rp ) {
	if ( $rp['slug'] !== $product['slug'] && 'gift' !== $rp['type'] && in_array( $scent, $rp['scents'], true ) ) {
		$related[] = $rp;
	}
}
$related = array_slice( $related, 0, 3 );
?>

<div class="view"
	id="lb-product"
	data-slug="<?php echo esc_attr( $product['slug'] ); ?>"
	data-name="<?php echo esc_attr( $product['name'] ); ?>"
	data-price="<?php echo esc_attr( $product['price'] ); ?>"
	data-scent="<?php echo esc_attr( $scent ); ?>">
	<div class="wrap">
		<div class="crumbs" style="padding-top:30px">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> / <a href="<?php echo esc_url( lb_shop_url() ); ?>">Sản phẩm</a> / <b><?php echo esc_html( $product['name'] ); ?></b>
		</div>

		<div class="pd" style="padding-bottom:40px">
			<!-- media -->
			<div class="pd__media">
				<div class="pd__media-glow" id="lb-pd-glow" style="background:radial-gradient(circle, <?php echo esc_attr( $s['color'] ); ?>, transparent 65%)"></div>
				<img id="lb-pd-img" src="<?php echo esc_url( $hero_img ); ?>" alt="<?php echo esc_attr( $product['name'] ); ?>" />
				<?php if ( $label_based ) : ?>
					<div class="thumbs">
						<?php foreach ( $product['scents'] as $sid ) :
							$ss = lb_get_scent( $sid ); ?>
							<button class="thumb lb-thumb <?php echo $sid === $scent ? 'is-active' : ''; ?>" data-scent="<?php echo esc_attr( $sid ); ?>">
								<img src="<?php echo esc_url( lb_asset( $ss['img'] ) ); ?>" alt="<?php echo esc_attr( $ss['name'] ); ?>" />
							</button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- info -->
			<div>
				<div class="pd__type eyebrow"><?php echo esc_html( $product['typeLabel'] . ' · ' . $product['volume'] ); ?></div>
				<h1 class="pd__title h-md"><?php echo esc_html( $product['name'] ); ?></h1>
				<p class="serif" style="font-size:19px;color:var(--cream-2);font-style:italic;margin-top:4px"><?php echo esc_html( $product['tagline'] ); ?></p>

				<div class="pd__price">
					<span class="now serif"><?php echo esc_html( lb_price( $product['price'] ) ); ?></span>
					<?php if ( $product['oldPrice'] ) : ?>
						<span class="was"><?php echo esc_html( lb_price( $product['oldPrice'] ) ); ?></span>
					<?php endif; ?>
					<?php if ( $save > 0 ) : ?>
						<span class="save">Tiết kiệm <?php echo esc_html( lb_price( $save ) ); ?></span>
					<?php endif; ?>
				</div>

				<p class="pd__desc"><?php echo esc_html( $product['blurb'] ); ?></p>

				<!-- scent selector -->
				<div class="pd__scent-head">
					<span class="eyebrow" style="color:var(--cream-dim)">Mùi hương</span>
					<span class="sel" id="lb-pd-sel"><?php echo esc_html( $s['name'] . ' — ' . $s['tone'] ); ?></span>
				</div>
				<div class="scent-pills">
					<?php foreach ( $product['scents'] as $sid ) :
						$ss = lb_get_scent( $sid ); ?>
						<button class="spill lb-spill <?php echo $sid === $scent ? 'is-active' : ''; ?>" data-scent="<?php echo esc_attr( $sid ); ?>">
							<span class="spill__dot" style="background:<?php echo esc_attr( $ss['color'] ); ?>"></span>
							<span class="spill__name"><?php echo esc_html( $ss['name'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>

				<!-- scent description + notes -->
				<p class="muted" id="lb-pd-desc" style="margin:18px 0 10px;font-size:14.5px;max-width:50ch"><?php echo esc_html( $s['desc'] ); ?></p>
				<div class="pd__notes" id="lb-pd-notes">
					<?php foreach ( $s['notes'] as $n ) : ?>
						<span class="note-tag"><?php echo esc_html( $n ); ?></span>
					<?php endforeach; ?>
				</div>

				<!-- buy -->
				<div class="pd__buy">
					<div class="qty" id="lb-pd-qty">
						<button data-q="-1">−</button>
						<span id="lb-pd-qtyval">1</span>
						<button data-q="1">+</button>
					</div>
					<button class="btn btn--gold btn--block" id="lb-pd-add">
						<?php lb_the_icon( 'cart', 'style="width:18px;height:18px"' ); ?> Thêm vào giỏ — <span id="lb-pd-addtotal"><?php echo esc_html( lb_price( $product['price'] ) ); ?></span>
					</button>
				</div>
				<button class="btn btn--ghost btn--block" id="lb-pd-buy">Mua ngay</button>

				<ul class="pd__features">
					<?php foreach ( $product['features'] as $f ) : ?>
						<li><?php lb_the_icon( 'check' ); ?> <?php echo esc_html( $f ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<!-- same scent -->
		<?php if ( $related ) : ?>
		<section class="section--tight">
			<div style="margin-bottom:30px">
				<div class="eyebrow" style="margin-bottom:10px">Cùng mùi <?php echo esc_html( $s['name'] ); ?></div>
				<h2 class="h-md">Trọn bộ một hương</h2>
			</div>
			<div class="pgrid">
				<?php foreach ( $related as $rp ) {
					echo lb_product_card( $rp, $scent ); // phpcs:ignore
				} ?>
			</div>
		</section>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();

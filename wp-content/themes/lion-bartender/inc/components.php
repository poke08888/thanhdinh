<?php
/**
 * Lion Bartender — render components dùng chung: ScentCard, ProductCard.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

/**
 * Thẻ mùi hương (dùng ở trang chủ + trang Mùi hương).
 *
 * @param array $s Dữ liệu mùi (từ lb_get_scent).
 */
function lb_scent_card( $s ) {
	ob_start();
	?>
	<a class="scent-card" href="<?php echo esc_url( $s['link'] ); ?>">
		<div class="scent-card__glow" style="background:radial-gradient(circle, <?php echo esc_attr( $s['color'] ); ?>, transparent 65%)"></div>
		<div class="scent-card__label">
			<img src="<?php echo esc_url( $s['img_url'] ); ?>" alt="<?php echo esc_attr( $s['name'] ); ?>" />
		</div>
		<div class="scent-card__row">
			<span class="scent-card__name"><?php echo esc_html( $s['name'] ); ?></span>
			<span class="scent-card__tone"><?php echo esc_html( $s['tone'] ); ?></span>
		</div>
		<div class="scent-card__notes">
			<span class="scent-card__dot" style="background:<?php echo esc_attr( $s['color'] ); ?>"></span>
			<?php echo esc_html( implode( ' · ', $s['notes'] ) ); ?>
		</div>
	</a>
	<?php
	return ob_get_clean();
}

/**
 * Thẻ sản phẩm (shop + trang chủ + cùng mùi).
 *
 * @param array       $p     Dữ liệu sản phẩm.
 * @param string|null $scent Mùi đang chọn (để hiện đúng ảnh & viền swatch).
 */
function lb_product_card( $p, $scent = null ) {
	$active = ( $scent && in_array( $scent, $p['scents'], true ) ) ? $scent : ( $p['scents'][0] ?? '' );
	$img    = lb_product_image( $p, $active );
	$link   = add_query_arg( 'scent', $active, $p['link'] );
	ob_start();
	?>
	<a class="pcard" href="<?php echo esc_url( $link ); ?>" data-type="<?php echo esc_attr( $p['type'] ); ?>">
		<?php if ( ! empty( $p['badge'] ) ) : ?>
			<span class="pcard__badge"><?php echo esc_html( $p['badge'] ); ?></span>
		<?php endif; ?>
		<div class="pcard__media">
			<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $p['name'] ); ?>" />
		</div>
		<div class="pcard__body">
			<span class="pcard__type"><?php echo esc_html( $p['typeLabel'] . ' · ' . $p['volume'] ); ?></span>
			<span class="pcard__name"><?php echo esc_html( $p['name'] ); ?></span>
			<span class="pcard__tag"><?php echo esc_html( $p['tagline'] ); ?></span>
			<div class="pcard__foot">
				<div>
					<span class="pcard__price"><?php echo esc_html( lb_price( $p['price'] ) ); ?></span>
					<?php if ( ! empty( $p['oldPrice'] ) ) : ?>
						<span class="pcard__old"><?php echo esc_html( lb_price( $p['oldPrice'] ) ); ?></span>
					<?php endif; ?>
				</div>
				<span class="tlink" style="font-size:11px">Xem <?php lb_the_icon( 'arrow' ); ?></span>
			</div>
		</div>
		<div class="pcard__swatches">
			<?php
			foreach ( $p['scents'] as $sid ) :
				$ss = lb_get_scent( $sid );
				if ( ! $ss ) {
					continue;
				}
				$outline = $sid === $active ? '1px solid var(--gold)' : 'none';
				?>
				<span class="pcard__swatch" title="<?php echo esc_attr( $ss['name'] ); ?>" style="background:<?php echo esc_attr( $ss['color'] ); ?>;outline:<?php echo esc_attr( $outline ); ?>;outline-offset:1px"></span>
			<?php endforeach; ?>
		</div>
	</a>
	<?php
	return ob_get_clean();
}

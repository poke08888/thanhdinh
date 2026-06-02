<?php
/**
 * Lion Bartender — helpers: accessors, icons, formatting, image resolver.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

/* ------------------------------------------------------------------
 * Asset & formatting
 * ------------------------------------------------------------------ */

/** URL ảnh trong thư mục assets/images của theme. */
function lb_asset( $filename ) {
	if ( ! $filename ) {
		return '';
	}
	return get_template_directory_uri() . '/assets/images/' . ltrim( $filename, '/' );
}

/** Định dạng giá kiểu Việt Nam: 89000 -> "89.000đ". */
function lb_price( $n ) {
	return number_format( (float) $n, 0, ',', '.' ) . 'đ';
}

/* ------------------------------------------------------------------
 * Truy xuất mùi hương
 * ------------------------------------------------------------------ */

/**
 * Giải ảnh đại diện của một mùi hương.
 * Ưu tiên ảnh upload từ Media (term meta lb_img_id), rồi tới URL/tên file lb_img
 * (tên file = ảnh bundled trong theme).
 *
 * @param int    $term_id ID term (0 nếu chưa có).
 * @param string $img     Giá trị lb_img (tên file hoặc URL).
 */
function lb_scent_img_url( $term_id, $img ) {
	if ( $term_id ) {
		$att_id = (int) get_term_meta( $term_id, 'lb_img_id', true );
		if ( $att_id ) {
			$u = wp_get_attachment_image_url( $att_id, 'full' );
			if ( $u ) {
				return $u;
			}
		}
	}
	if ( $img ) {
		return preg_match( '#^https?://#', $img ) ? $img : lb_asset( $img );
	}
	return '';
}

/**
 * Lấy dữ liệu một mùi hương theo slug. Ưu tiên từ DB (term meta),
 * fallback về dữ liệu seed nếu chưa seed.
 */
function lb_get_scent( $slug ) {
	static $cache = array();
	if ( isset( $cache[ $slug ] ) ) {
		return $cache[ $slug ];
	}

	$term = get_term_by( 'slug', $slug, 'lb_scent' );
	if ( $term && ! is_wp_error( $term ) ) {
		$notes = get_term_meta( $term->term_id, 'lb_notes', true );
		$img   = get_term_meta( $term->term_id, 'lb_img', true );
		$data  = array(
			'slug'    => $slug,
			'term_id' => $term->term_id,
			'name'    => $term->name,
			'tone'    => get_term_meta( $term->term_id, 'lb_tone', true ),
			'color'   => get_term_meta( $term->term_id, 'lb_color', true ),
			'deep'    => get_term_meta( $term->term_id, 'lb_deep', true ),
			'ink'     => get_term_meta( $term->term_id, 'lb_ink', true ),
			'notes'   => $notes ? explode( '|', $notes ) : array(),
			'desc'    => get_term_meta( $term->term_id, 'lb_desc', true ),
			'img'     => $img,
			'img_url' => lb_scent_img_url( $term->term_id, $img ),
			'link'    => get_term_link( $term ),
		);
		$cache[ $slug ] = $data;
		return $data;
	}

	// Fallback seed.
	$seed = lb_seed_scents();
	if ( isset( $seed[ $slug ] ) ) {
		$s = $seed[ $slug ];
		$s['slug']    = $slug;
		$s['img_url'] = lb_asset( $s['img'] );
		$s['link']    = home_url( '/mui-huong/' . $slug . '/' );
		$cache[ $slug ] = $s;
		return $s;
	}
	return null;
}

/** Tất cả mùi hương theo thứ tự thiết kế. */
function lb_get_scents() {
	$out = array();
	foreach ( lb_scent_order() as $slug ) {
		$s = lb_get_scent( $slug );
		if ( $s ) {
			$out[ $slug ] = $s;
		}
	}
	return $out;
}

/* ------------------------------------------------------------------
 * Truy xuất sản phẩm
 * ------------------------------------------------------------------ */

/** Chuẩn hoá một post lb_product thành mảng dữ liệu sản phẩm. */
function lb_product_data( $post ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return null;
	}
	$pid      = $post->ID;
	$features = get_post_meta( $pid, 'lb_features', true );
	$terms    = wp_get_object_terms( $pid, 'lb_scent', array( 'fields' => 'slugs' ) );

	// Sắp scent theo thứ tự thiết kế.
	$ordered = array();
	foreach ( lb_scent_order() as $slug ) {
		if ( in_array( $slug, (array) $terms, true ) ) {
			$ordered[] = $slug;
		}
	}
	if ( empty( $ordered ) ) {
		$ordered = (array) $terms;
	}

	return array(
		'ID'        => $pid,
		'slug'      => $post->post_name,
		'name'      => get_the_title( $post ),
		'type'      => get_post_meta( $pid, 'lb_type', true ),
		'typeLabel' => get_post_meta( $pid, 'lb_type_label', true ),
		'tagline'   => get_post_meta( $pid, 'lb_tagline', true ),
		'price'     => (int) get_post_meta( $pid, 'lb_price', true ),
		'oldPrice'  => (int) get_post_meta( $pid, 'lb_old_price', true ),
		'volume'    => get_post_meta( $pid, 'lb_volume', true ),
		'badge'     => get_post_meta( $pid, 'lb_badge', true ),
		'blurb'     => get_post_meta( $pid, 'lb_blurb', true ),
		'features'  => $features ? explode( '|', $features ) : array(),
		'heroImg'   => get_post_meta( $pid, 'lb_hero_img', true ),
		'scents'    => $ordered,
		'link'      => get_permalink( $post ),
	);
}

/**
 * Lấy danh sách sản phẩm (mảng dữ liệu) theo thứ tự seed.
 *
 * @param array $args Tham số WP_Query bổ sung (vd. tax_query).
 */
function lb_get_products( $args = array() ) {
	$defaults = array(
		'post_type'      => 'lb_product',
		'posts_per_page' => -1,
		'meta_key'       => 'lb_order',
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC',
	);
	$q   = new WP_Query( wp_parse_args( $args, $defaults ) );
	$out = array();
	foreach ( $q->posts as $p ) {
		$d = lb_product_data( $p );
		if ( $d ) {
			$out[] = $d;
		}
	}
	wp_reset_postdata();
	return $out;
}

/** Lấy một sản phẩm theo slug. */
function lb_get_product_by_slug( $slug ) {
	$posts = get_posts(
		array(
			'post_type'   => 'lb_product',
			'name'        => $slug,
			'numberposts' => 1,
		)
	);
	return $posts ? lb_product_data( $posts[0] ) : null;
}

/**
 * Ảnh sản phẩm theo mùi.
 * Ưu tiên ảnh chai thật khi có; còn lại dùng tem nhãn mùi (đúng màu mùi).
 */
function lb_product_image( $product, $scent ) {
	// Ưu tiên ảnh riêng theo từng mùi (phân loại) của chính sản phẩm này.
	if ( ! empty( $product['ID'] ) ) {
		$ov = get_post_meta( $product['ID'], 'lb_scent_images', true );
		if ( is_array( $ov ) && ! empty( $ov[ $scent ] ) ) {
			$u = wp_get_attachment_image_url( (int) $ov[ $scent ], 'full' );
			if ( $u ) {
				return $u;
			}
		}
	}
	if ( isset( $product['type'] ) && 'gift' === $product['type'] ) {
		return lb_asset( 'promo-trio.png' );
	}
	if ( in_array( $product['slug'], array( 'shower-3in1', 'combo-duo' ), true ) && 'ocean-club' === $scent ) {
		return lb_asset( 'bottle-ocean-club.png' );
	}
	$s = lb_get_scent( $scent );
	if ( $s && ! empty( $s['img_url'] ) ) {
		return $s['img_url'];
	}
	return lb_asset( $product['heroImg'] );
}

/* ------------------------------------------------------------------
 * Icons (SVG inline) — khớp với bộ icon trong thiết kế.
 * ------------------------------------------------------------------ */

function lb_icon( $name, $attrs = '' ) {
	$icons = array(
		'cart'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" %ATTR%><path d="M3 4h2l2.4 12.2a1 1 0 0 0 1 .8h8.7a1 1 0 0 0 1-.8L21 8H6"/><circle cx="9" cy="20" r="1.3"/><circle cx="18" cy="20" r="1.3"/></svg>',
		'menu'    => '<svg width="22" height="22" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" fill="none" %ATTR%><path d="M3 6h18M3 12h18M3 18h18"/></svg>',
		'close'   => '<svg width="22" height="22" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" fill="none" %ATTR%><path d="M6 6l12 12M18 6L6 18"/></svg>',
		'check'   => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" %ATTR%><path d="M20 6L9 17l-5-5"/></svg>',
		'checkLg' => '<svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" %ATTR%><path d="M20 6L9 17l-5-5"/></svg>',
		'arrow'   => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" %ATTR%><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
		'chevron' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" %ATTR%><path d="M9 6l6 6-6 6"/></svg>',
		'shield'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" %ATTR%><path d="M12 3l8 3v6c0 4.4-3.2 7.6-8 9-4.8-1.4-8-4.6-8-9V6z"/></svg>',
		'drop'    => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" %ATTR%><path d="M12 3s6 6.4 6 10.5A6 6 0 0 1 6 13.5C6 9.4 12 3 12 3z"/></svg>',
		'flame'   => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" %ATTR%><path d="M12 3c3 3.5 5 6 5 9a5 5 0 0 1-10 0c0-1.4.6-2.6 1.5-3.5C9 9.8 9.5 11 11 11c0-2.5-1-4 1-8z"/></svg>',
		'truck'   => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" %ATTR%><path d="M3 6h11v9H3zM14 9h4l3 3v3h-7z"/><circle cx="7" cy="18" r="1.4"/><circle cx="17.5" cy="18" r="1.4"/></svg>',
		'ig'      => '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" %ATTR%><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>',
		'fb'      => '<svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" %ATTR%><path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v2H7v3h3v6h3v-6h3l1-3h-4v-2c0-.6.4-1 1-1z"/></svg>',
		'tiktok'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" %ATTR%><path d="M16 3c.3 2 1.6 3.6 3.6 3.9v3c-1.4 0-2.7-.4-3.6-1v6.3a5.7 5.7 0 1 1-5.7-5.7c.3 0 .6 0 .9.1v3.1a2.6 2.6 0 1 0 1.8 2.5V3z"/></svg>',
		'yt'      => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" %ATTR%><path d="M22 8.2a3 3 0 0 0-2-2C18 5.7 12 5.7 12 5.7s-6 0-8 .5a3 3 0 0 0-2 2C1.7 10 1.7 12 1.7 12s0 2 .3 3.8a3 3 0 0 0 2 2c2 .5 8 .5 8 .5s6 0 8-.5a3 3 0 0 0 2-2c.3-1.8.3-3.8.3-3.8s0-2-.3-3.8zM10 15V9l5 3z"/></svg>',
		'bolt'    => '<svg class="bolt %CLASS%" viewBox="0 0 14 18" fill="currentColor" %ATTR%><path d="M8 0L0 10h5l-2 8 9-11H7z"/></svg>',
	);
	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}
	return str_replace( array( '%ATTR%', '%CLASS%' ), array( $attrs, '' ), $icons[ $name ] );
}

function lb_the_icon( $name, $attrs = '' ) {
	echo lb_icon( $name, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/** Đường rãnh trang trí (rule). */
function lb_rule( $extra_style = '', $short = false ) {
	$line = $short ? 'rule__line rule__line--short' : 'rule__line';
	return '<div class="rule filigree" style="' . esc_attr( $extra_style ) . '">'
		. '<span class="' . $line . '"></span>'
		. '<span class="dia dia--sm"></span>' . lb_icon( 'bolt' )
		. '<span class="dia"></span>' . lb_icon( 'bolt' )
		. '<span class="dia dia--sm"></span>'
		. '<span class="' . $line . '"></span></div>';
}

/** URL trang theo slug đã seed. */
function lb_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

function lb_shop_url() {
	return get_post_type_archive_link( 'lb_product' );
}

function lb_scents_url() {
	return lb_page_url( 'bo-suu-tap' );
}

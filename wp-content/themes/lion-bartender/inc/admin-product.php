<?php
/**
 * Lion Bartender — meta box "Ảnh theo mùi hương (phân loại)" cho sản phẩm.
 *
 * Cho phép gán ảnh riêng cho từng mùi của một sản phẩm. Để trống thì dùng ảnh
 * mặc định (tem nhãn mùi / ảnh chai). Lưu ở post meta `lb_scent_images`
 * dạng array( scent_slug => attachment_id ).
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

/* Nạp Media uploader + JS (dùng chung admin-scent.js) trên màn hình sửa sản phẩm. */
add_action( 'admin_enqueue_scripts', 'lb_product_admin_assets' );
function lb_product_admin_assets( $hook ) {
	if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'lb_product' !== $screen->post_type ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'lb-admin-scent', get_template_directory_uri() . '/assets/js/admin-scent.js', array( 'jquery' ), LB_VERSION, true );
}

/* Đăng ký meta box. */
add_action( 'add_meta_boxes', 'lb_product_images_metabox' );
function lb_product_images_metabox() {
	add_meta_box( 'lb_scent_images', 'Ảnh theo mùi hương (phân loại)', 'lb_product_images_box', 'lb_product', 'normal', 'high' );
}

function lb_product_images_box( $post ) {
	wp_nonce_field( 'lb_save_scent_images', 'lb_scent_images_nonce' );

	$overrides = get_post_meta( $post->ID, 'lb_scent_images', true );
	if ( ! is_array( $overrides ) ) {
		$overrides = array();
	}

	$terms   = wp_get_object_terms( $post->ID, 'lb_scent', array( 'fields' => 'slugs' ) );
	$ordered = array();
	foreach ( lb_scent_order() as $slug ) {
		if ( in_array( $slug, (array) $terms, true ) ) {
			$ordered[] = $slug;
		}
	}

	if ( empty( $ordered ) ) {
		echo '<p>Hãy chọn <strong>Mùi hương</strong> cho sản phẩm (ô “Mùi hương” bên phải) rồi bấm <strong>Cập nhật / Đăng</strong> để thêm ảnh cho từng mùi.</p>';
		return;
	}

	$product = lb_product_data( $post->ID );

	echo '<p class="description" style="margin-bottom:12px">Mỗi mùi (phân loại) có thể có ảnh riêng. Để trống sẽ dùng ảnh mặc định.</p>';
	echo '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:16px">';

	foreach ( $ordered as $slug ) {
		$s        = lb_get_scent( $slug );
		$att      = isset( $overrides[ $slug ] ) ? (int) $overrides[ $slug ] : 0;
		$is_over  = $att > 0;
		$prev_url = $is_over ? wp_get_attachment_image_url( $att, 'medium' ) : lb_product_image( $product, $slug );
		$color    = $s ? $s['color'] : '#c9a24b';
		$name     = $s ? $s['name'] : $slug;
		?>
		<div class="lb-img-field" style="border:1px solid #dcdcde;border-radius:6px;padding:12px;background:#fff">
			<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;font-weight:600">
				<span style="width:14px;height:14px;border-radius:50%;background:<?php echo esc_attr( $color ); ?>;border:1px solid rgba(0,0,0,.15);flex:none"></span>
				<?php echo esc_html( $name ); ?>
			</div>
			<div style="background:#11151f;border-radius:4px;min-height:120px;display:flex;align-items:center;justify-content:center;padding:8px;margin-bottom:8px">
				<img class="lb-img-preview" src="<?php echo esc_url( $prev_url ); ?>" alt="" style="max-height:120px;max-width:100%;<?php echo $prev_url ? '' : 'display:none;'; ?>" />
			</div>
			<input type="hidden" class="lb-img-id" name="lb_scent_images[<?php echo esc_attr( $slug ); ?>]" value="<?php echo esc_attr( $att ); ?>" />
			<button type="button" class="button button-small lb-img-pick">Chọn ảnh</button>
			<button type="button" class="button button-small lb-img-clear" <?php echo $is_over ? '' : 'style="display:none"'; ?>>Dùng mặc định</button>
		</div>
		<?php
	}
	echo '</div>';
}

/* Lưu meta. */
add_action( 'save_post_lb_product', 'lb_save_product_images' );
function lb_save_product_images( $post_id ) {
	if ( ! isset( $_POST['lb_scent_images_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['lb_scent_images_nonce'] ) ), 'lb_save_scent_images' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$in    = isset( $_POST['lb_scent_images'] ) ? (array) wp_unslash( $_POST['lb_scent_images'] ) : array();
	$clean = array();
	foreach ( $in as $slug => $id ) {
		$slug = sanitize_key( $slug );
		$id   = absint( $id );
		if ( $id ) {
			$clean[ $slug ] = $id;
		}
	}
	update_post_meta( $post_id, 'lb_scent_images', $clean );
}

<?php
/**
 * Lion Bartender — màn hình sửa chi tiết mùi hương trong WP admin.
 *
 * Thêm vào form thêm/sửa term của taxonomy `lb_scent`:
 *   - Ảnh đại diện (chọn từ Thư viện Media)
 *   - Tông hương
 *   - Màu đại diện
 *   - Mô tả mùi hương
 *   - Các hương chính (tầng hương)
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

/* Nạp Media uploader + JS trên màn hình term của lb_scent. */
add_action( 'admin_enqueue_scripts', 'lb_scent_admin_assets' );
function lb_scent_admin_assets( $hook ) {
	if ( 'edit-tags.php' !== $hook && 'term.php' !== $hook ) {
		return;
	}
	$tax = isset( $_GET['taxonomy'] ) ? sanitize_key( wp_unslash( $_GET['taxonomy'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( 'lb_scent' !== $tax ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'lb-admin-scent', get_template_directory_uri() . '/assets/js/admin-scent.js', array( 'jquery' ), LB_VERSION, true );
}

/* URL ảnh hiện tại của term (cho preview trong admin). */
function lb_scent_admin_img( $term_id ) {
	$id = (int) get_term_meta( $term_id, 'lb_img_id', true );
	if ( $id ) {
		$u = wp_get_attachment_image_url( $id, 'medium' );
		if ( $u ) {
			return $u;
		}
	}
	$f = get_term_meta( $term_id, 'lb_img', true );
	return $f ? lb_scent_img_url( $term_id, $f ) : '';
}

/* ------------------------------------------------------------------
 * Form THÊM mùi hương mới
 * ------------------------------------------------------------------ */
add_action( 'lb_scent_add_form_fields', 'lb_scent_add_fields' );
function lb_scent_add_fields() {
	?>
	<div class="form-field">
		<label>Ảnh đại diện</label>
		<div class="lb-img-field">
			<input type="hidden" name="lb_img_id" class="lb-img-id" value="" />
			<img class="lb-img-preview" src="" alt="" style="max-width:120px;display:none;border:1px solid #ddd;padding:4px;background:#fff;border-radius:4px" />
			<p>
				<button type="button" class="button lb-img-pick">Chọn ảnh</button>
				<button type="button" class="button lb-img-clear" style="display:none">Xóa ảnh</button>
			</p>
		</div>
		<p>Tem nhãn / ảnh đại diện cho mùi hương.</p>
	</div>
	<div class="form-field">
		<label for="lb_tone">Tông hương</label>
		<input name="lb_tone" id="lb_tone" type="text" value="" placeholder="VD: Biển khơi" />
	</div>
	<div class="form-field">
		<label for="lb_color">Màu đại diện</label>
		<span style="display:inline-flex;gap:8px;align-items:center">
			<input name="lb_color" id="lb_color" type="text" value="#c9a24b" placeholder="#2f86c4" style="max-width:120px" />
			<input type="color" class="lb-color-sync" value="#c9a24b" />
		</span>
	</div>
	<div class="form-field">
		<label for="lb_desc">Mô tả mùi hương</label>
		<textarea name="lb_desc" id="lb_desc" rows="4"></textarea>
	</div>
	<div class="form-field">
		<label for="lb_notes">Các hương chính</label>
		<input name="lb_notes" id="lb_notes" type="text" value="" placeholder="Hương nước, Rong biển, Xạ hương trắng" />
		<p>Ngăn cách bằng dấu phẩy.</p>
	</div>
	<?php
}

/* ------------------------------------------------------------------
 * Form SỬA mùi hương
 * ------------------------------------------------------------------ */
add_action( 'lb_scent_edit_form_fields', 'lb_scent_edit_fields' );
function lb_scent_edit_fields( $term ) {
	$tone       = get_term_meta( $term->term_id, 'lb_tone', true );
	$color      = get_term_meta( $term->term_id, 'lb_color', true );
	$color      = $color ? $color : '#c9a24b';
	$desc       = get_term_meta( $term->term_id, 'lb_desc', true );
	$notes      = get_term_meta( $term->term_id, 'lb_notes', true );
	$notes_disp = $notes ? implode( ', ', array_map( 'trim', explode( '|', $notes ) ) ) : '';
	$img_id     = (int) get_term_meta( $term->term_id, 'lb_img_id', true );
	$img_url    = lb_scent_admin_img( $term->term_id );
	?>
	<tr class="form-field">
		<th scope="row"><label>Ảnh đại diện</label></th>
		<td>
			<div class="lb-img-field">
				<input type="hidden" name="lb_img_id" class="lb-img-id" value="<?php echo esc_attr( $img_id ); ?>" />
				<img class="lb-img-preview" src="<?php echo esc_url( $img_url ); ?>" alt="" style="max-width:160px;<?php echo $img_url ? '' : 'display:none;'; ?>border:1px solid #ddd;padding:4px;background:#fff;border-radius:4px" />
				<p>
					<button type="button" class="button lb-img-pick">Chọn ảnh</button>
					<button type="button" class="button lb-img-clear" <?php echo $img_id ? '' : 'style="display:none"'; ?>>Xóa ảnh</button>
				</p>
				<p class="description">Tem nhãn / ảnh đại diện cho mùi hương. Để trống sẽ dùng ảnh mặc định trong theme.</p>
			</div>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="lb_tone">Tông hương</label></th>
		<td><input name="lb_tone" id="lb_tone" type="text" value="<?php echo esc_attr( $tone ); ?>" placeholder="VD: Biển khơi" /></td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="lb_color">Màu đại diện</label></th>
		<td>
			<span style="display:inline-flex;gap:8px;align-items:center">
				<input name="lb_color" id="lb_color" type="text" value="<?php echo esc_attr( $color ); ?>" placeholder="#2f86c4" style="max-width:120px" />
				<input type="color" class="lb-color-sync" value="<?php echo esc_attr( $color ); ?>" />
			</span>
			<p class="description">Màu dùng cho chấm/viền mùi trên giao diện.</p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="lb_desc">Mô tả mùi hương</label></th>
		<td><textarea name="lb_desc" id="lb_desc" rows="5" class="large-text"><?php echo esc_textarea( $desc ); ?></textarea></td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="lb_notes">Các hương chính</label></th>
		<td>
			<input name="lb_notes" id="lb_notes" type="text" class="large-text" value="<?php echo esc_attr( $notes_disp ); ?>" placeholder="Hương nước, Rong biển, Xạ hương trắng" />
			<p class="description">Ngăn cách bằng dấu phẩy.</p>
		</td>
	</tr>
	<?php
}

/* ------------------------------------------------------------------
 * Lưu meta khi tạo / sửa term
 * ------------------------------------------------------------------ */
add_action( 'created_lb_scent', 'lb_scent_save_meta' );
add_action( 'edited_lb_scent', 'lb_scent_save_meta' );
function lb_scent_save_meta( $term_id ) {
	// WP core đã kiểm tra quyền & nonce của màn hình term trước khi gọi hook này.
	if ( isset( $_POST['lb_img_id'] ) ) {
		update_term_meta( $term_id, 'lb_img_id', absint( $_POST['lb_img_id'] ) );
	}
	if ( isset( $_POST['lb_tone'] ) ) {
		update_term_meta( $term_id, 'lb_tone', sanitize_text_field( wp_unslash( $_POST['lb_tone'] ) ) );
	}
	if ( isset( $_POST['lb_color'] ) ) {
		$raw   = sanitize_text_field( wp_unslash( $_POST['lb_color'] ) );
		$color = sanitize_hex_color( $raw );
		update_term_meta( $term_id, 'lb_color', $color ? $color : $raw );
	}
	if ( isset( $_POST['lb_desc'] ) ) {
		update_term_meta( $term_id, 'lb_desc', sanitize_textarea_field( wp_unslash( $_POST['lb_desc'] ) ) );
	}
	if ( isset( $_POST['lb_notes'] ) ) {
		$raw   = sanitize_text_field( wp_unslash( $_POST['lb_notes'] ) );
		$parts = array_filter( array_map( 'trim', preg_split( '/[,|]/', $raw ) ) );
		update_term_meta( $term_id, 'lb_notes', implode( '|', $parts ) );
	}
}

/* Cột "Ảnh" trong bảng danh sách mùi hương. */
add_filter( 'manage_edit-lb_scent_columns', 'lb_scent_columns' );
function lb_scent_columns( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		if ( 'name' === $k ) {
			$new['lb_thumb'] = 'Ảnh';
		}
		$new[ $k ] = $v;
	}
	return $new;
}
add_filter( 'manage_lb_scent_custom_column', 'lb_scent_column_content', 10, 3 );
function lb_scent_column_content( $content, $column, $term_id ) {
	if ( 'lb_thumb' === $column ) {
		$url = lb_scent_admin_img( $term_id );
		if ( $url ) {
			$content = '<img src="' . esc_url( $url ) . '" alt="" style="width:46px;height:46px;object-fit:contain;background:#11151f;border-radius:4px;padding:3px" />';
		}
	}
	return $content;
}

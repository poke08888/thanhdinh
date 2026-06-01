<?php
/**
 * Template Name: Thanh toán
 * Form + summary render từ giỏ hàng (localStorage) bằng JS; submit qua AJAX.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

get_header();

$pays = array(
	array( 'cod', 'Thanh toán khi nhận hàng (COD)', 'Trả tiền mặt cho shipper' ),
	array( 'bank', 'Chuyển khoản ngân hàng', 'Quét QR — xác nhận tự động' ),
	array( 'momo', 'Ví MoMo / ZaloPay', 'Thanh toán qua ví điện tử' ),
);
?>

<div class="view" id="lb-checkout">
	<div class="wrap">

		<!-- empty state (hiện khi giỏ trống) -->
		<div id="lb-co-empty" hidden>
			<div class="success">
				<?php lb_the_icon( 'cart', 'style="width:48px;height:48px;margin:0 auto 20px;opacity:.4"' ); ?>
				<h2 class="h-md" style="margin-bottom:14px">Giỏ hàng trống</h2>
				<p class="muted" style="margin-bottom:26px">Thêm vài sản phẩm trước khi thanh toán nhé.</p>
				<a class="btn btn--gold" href="<?php echo esc_url( lb_shop_url() ); ?>">Tới cửa hàng</a>
			</div>
		</div>

		<!-- checkout (hiện khi có hàng) -->
		<div id="lb-co-main" hidden>
			<div class="crumbs" style="padding-top:30px"><a href="<?php echo esc_url( lb_shop_url() ); ?>">Sản phẩm</a> / <b>Thanh toán</b></div>
			<div class="phead" style="text-align:left;padding:10px 0 36px">
				<div class="eyebrow">Hoàn tất đơn hàng</div>
				<h1 class="h-md" style="margin-top:8px">Thanh toán</h1>
			</div>
			<div class="co" style="padding-bottom:80px">
				<form id="lb-co-form">
					<h3 style="font-size:18px;margin-bottom:18px">Thông tin giao hàng</h3>
					<div class="field-row">
						<div class="field"><label>Họ tên</label><input name="name" required placeholder="Nguyễn Văn A" /></div>
						<div class="field"><label>Số điện thoại</label><input name="phone" required placeholder="09xx xxx xxx" /></div>
					</div>
					<div class="field"><label>Email</label><input name="email" type="email" placeholder="ban@email.com" /></div>
					<div class="field"><label>Địa chỉ</label><input name="address" required placeholder="Số nhà, đường" /></div>
					<div class="field-row">
						<div class="field"><label>Quận / Huyện</label><input name="ward" required placeholder="Quận 1" /></div>
						<div class="field"><label>Tỉnh / Thành</label><input name="city" required placeholder="TP. Hồ Chí Minh" /></div>
					</div>
					<div class="field"><label>Ghi chú (tùy chọn)</label><textarea name="note" rows="2" placeholder="Giao giờ hành chính..."></textarea></div>

					<h3 style="font-size:18px;margin:30px 0 18px">Phương thức thanh toán</h3>
					<div id="lb-co-pays">
						<?php foreach ( $pays as $i => $o ) : ?>
							<div class="pay-opt <?php echo 0 === $i ? 'is-active' : ''; ?>" data-pay="<?php echo esc_attr( $o[0] ); ?>">
								<span class="radio"></span>
								<div>
									<div style="font-family:var(--font-display);text-transform:uppercase;letter-spacing:.06em;font-size:14px;font-weight:600"><?php echo esc_html( $o[1] ); ?></div>
									<div class="muted" style="font-size:13px"><?php echo esc_html( $o[2] ); ?></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<input type="hidden" name="pay" id="lb-co-pay" value="cod" />
					<button class="btn btn--gold btn--block btn--lg" type="submit" style="margin-top:24px" id="lb-co-submit">Đặt hàng — <span id="lb-co-btntotal">0đ</span></button>
				</form>

				<aside class="osummary">
					<h3 style="font-size:17px;margin-bottom:18px">Đơn hàng</h3>
					<div id="lb-co-items"></div>
					<div class="drawer__row" style="margin-top:16px"><span class="lbl">Tạm tính</span><span id="lb-co-subtotal">0đ</span></div>
					<div class="drawer__row"><span class="lbl">Giao hàng</span><span id="lb-co-ship">Miễn phí</span></div>
					<div class="drawer__total"><span class="lbl">Tổng</span><span class="val" id="lb-co-total">0đ</span></div>
					<p class="muted" id="lb-co-freehint" style="font-size:12.5px" hidden></p>
				</aside>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();

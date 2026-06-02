<?php
/**
 * Footer: footer brand, cart drawer template, toast, đóng wrapper .app.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

$lb_logo = lb_asset( 'logo-wordmark.png' );
?>
	<footer class="foot">
		<div class="wrap">
			<div class="foot__top">
				<div>
					<img class="foot__logo" src="<?php echo esc_url( $lb_logo ); ?>" alt="Lion Bartender" />
					<p class="muted" style="max-width:34ch;font-size:14px">
						<?php lb_the_html( 'footer_tagline' ); ?>
					</p>
					<div class="foot__social" style="margin-top:22px">
						<a href="#" aria-label="Instagram"><?php lb_the_icon( 'ig' ); ?></a>
						<a href="#" aria-label="Facebook"><?php lb_the_icon( 'fb' ); ?></a>
						<a href="#" aria-label="TikTok"><?php lb_the_icon( 'tiktok' ); ?></a>
						<a href="#" aria-label="YouTube"><?php lb_the_icon( 'yt' ); ?></a>
					</div>
				</div>
				<div class="foot__col">
					<h5>Sản phẩm</h5>
					<a href="<?php echo esc_url( lb_shop_url() ); ?>">Lăn khử mùi</a>
					<a href="<?php echo esc_url( lb_shop_url() ); ?>">Tắm gội 3-in-1</a>
					<a href="<?php echo esc_url( lb_shop_url() ); ?>">Combo &amp; Hộp quà</a>
					<a href="<?php echo esc_url( lb_scents_url() ); ?>">Bộ sưu tập mùi</a>
				</div>
				<div class="foot__col">
					<h5>Thương hiệu</h5>
					<a href="<?php echo esc_url( lb_page_url( 'cau-chuyen' ) ); ?>">Câu chuyện</a>
					<a href="<?php echo esc_url( lb_page_url( 'cau-chuyen' ) ); ?>">Cam kết chất lượng</a>
					<a href="#">Hệ thống cửa hàng</a>
					<a href="#">Liên hệ</a>
				</div>
				<div class="foot__col">
					<h5><?php lb_the_text( 'footer_news_heading' ); ?></h5>
					<p class="muted" style="font-size:14px"><?php lb_the_text( 'footer_news_sub' ); ?></p>
					<form class="foot__news" onsubmit="return false;">
						<input type="email" placeholder="Email của bạn" />
						<button class="btn btn--gold btn--sm" type="submit">Đăng ký</button>
					</form>
				</div>
			</div>
			<div class="foot__bottom">
				<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php lb_the_text( 'footer_copyright' ); ?></span>
				<span><?php lb_the_text( 'footer_legal' ); ?></span>
			</div>
		</div>
	</footer>

	<!-- Cart drawer (render bởi JS) -->
	<div id="lb-drawer-root"></div>

	<!-- Toast -->
	<div class="toast" id="lb-toast"><?php lb_the_icon( 'check' ); ?> <span id="lb-toast-msg"></span></div>

</div><!-- /.app -->
<?php wp_footer(); ?>
</body>
</html>

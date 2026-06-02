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

					<?php
					$lb_hotline = lb_text( 'contact_hotline' );
					$lb_cemail  = lb_text( 'contact_email' );
					$lb_caddr   = lb_text( 'contact_address' );
					if ( $lb_hotline || $lb_cemail || $lb_caddr ) :
						?>
						<div class="foot__contact" style="margin-top:18px;display:grid;gap:6px;font-size:14px;color:var(--cream-dim)">
							<?php if ( $lb_hotline ) : ?>
								<div>Hotline: <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $lb_hotline ) ); ?>" style="color:var(--gold)"><?php echo esc_html( $lb_hotline ); ?></a></div>
							<?php endif; ?>
							<?php if ( $lb_cemail ) : ?>
								<div>Email: <a href="mailto:<?php echo esc_attr( $lb_cemail ); ?>" style="color:var(--gold)"><?php echo esc_html( $lb_cemail ); ?></a></div>
							<?php endif; ?>
							<?php if ( $lb_caddr ) : ?>
								<div><?php echo nl2br( esc_html( $lb_caddr ) ); ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php
					$lb_socials = array(
						'ig'     => array( 'Instagram', lb_text( 'social_ig' ) ),
						'fb'     => array( 'Facebook', lb_text( 'social_fb' ) ),
						'tiktok' => array( 'TikTok', lb_text( 'social_tiktok' ) ),
						'yt'     => array( 'YouTube', lb_text( 'social_yt' ) ),
					);
					?>
					<div class="foot__social" style="margin-top:22px">
						<?php
						foreach ( $lb_socials as $icon => $sx ) :
							$href   = $sx[1] ? esc_url( $sx[1] ) : '#';
							$target = $sx[1] ? ' target="_blank" rel="noopener"' : '';
							?>
							<a href="<?php echo $href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" aria-label="<?php echo esc_attr( $sx[0] ); ?>"<?php echo $target; // phpcs:ignore ?>><?php lb_the_icon( $icon ); ?></a>
						<?php endforeach; ?>
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

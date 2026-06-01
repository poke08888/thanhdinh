<?php
/**
 * Template Name: Câu chuyện thương hiệu
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

get_header();

$pillars = array(
	array( 'drop', 'Mùi hương độc bản', 'Mỗi công thức được pha như một ly cocktail — có lớp hương đầu, hương giữa và hậu vị riêng biệt.' ),
	array( 'flame', 'Công nghệ Heat-Trigger', 'Hương được kích hoạt theo thân nhiệt. Càng vận động, mùi càng bung tỏa — khử mùi suốt 24 giờ.' ),
	array( 'shield', 'Chất lượng quý ông', 'Thành phần dịu nhẹ cho da nam, kiểm định an toàn, đóng gói trong thiết kế cổ điển đầy bản lĩnh.' ),
);
$steps = array(
	array( 'Chọn ly', 'Tìm mùi hương hợp gu trong 6 lựa chọn đặc trưng.' ),
	array( 'Pha hương', 'Công thức tinh dầu nước hoa pha tỉ lệ chuẩn bartender.' ),
	array( 'Phục vụ', 'Lăn, tắm gội, rửa mặt — sạch thơm chuẩn quý ông.' ),
	array( 'Tỏa sáng', 'Bước ra ngoài với sự tự tin và dấu ấn riêng.' ),
);
?>

<div class="view">
	<div class="wrap">
		<div class="crumbs" style="padding-top:30px"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> / <b>Câu chuyện</b></div>
		<div class="story-hero">
			<div class="eyebrow filigree" style="margin-bottom:18px">Scent. Served.</div>
			<?php echo lb_rule( 'max-width:300px;margin:0 auto 26px' ); // phpcs:ignore ?>
			<p class="story-quote">“Một quý ông được nhớ đến bởi <span class="gold-text">mùi hương</span> của anh ta.”</p>
		</div>
	</div>

	<div class="wrap">
		<section class="section--tight story-grid">
			<div class="story-img"><img src="<?php echo esc_url( lb_asset( 'promo-trio-full.png' ) ); ?>" alt="Lion Bartender" /></div>
			<div>
				<div class="eyebrow" style="margin-bottom:14px">Khởi nguồn</div>
				<h2 class="h-md" style="margin-bottom:18px">Từ quầy bar<br /><span class="gold-text">đến phòng tắm của bạn</span></h2>
				<p class="muted" style="margin-bottom:14px">
					Lion Bartender sinh ra từ niềm tin rằng chăm sóc bản thân là một nghi thức — không phải việc làm cho có. Lấy cảm hứng từ những quầy bar cổ điển và tiệm cắt tóc xưa, chúng tôi pha chế từng mùi hương như một bartender lành nghề pha ly cocktail tủ.
				</p>
				<p class="muted">
					Con sư tử đeo kính — biểu tượng của chúng tôi — đại diện cho người đàn ông hiện đại: bản lĩnh, phong trần nhưng vẫn thừa chất chơi và sự tinh tế.
				</p>
			</div>
		</section>

		<section class="section--tight">
			<div style="text-align:center;margin-bottom:44px">
				<div class="eyebrow" style="margin-bottom:12px">Vì sao chọn chúng tôi</div>
				<h2 class="h-lg">Pha Chuẩn · Tỏa Chất</h2>
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
				<div class="eyebrow" style="margin-bottom:12px">Công thức phục vụ</div>
				<h2 class="h-md">Bốn bước · Một đẳng cấp</h2>
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
				<h2 class="h-md" style="margin-bottom:14px">Sẵn sàng pha ly của bạn?</h2>
				<p class="lead muted" style="max-width:46ch;margin:0 auto 28px">Khám phá 6 mùi hương đặc trưng và tìm dấu ấn riêng của một quý ông.</p>
				<a class="btn btn--gold btn--lg" href="<?php echo esc_url( lb_shop_url() ); ?>">Mua ngay <?php lb_the_icon( 'arrow' ); ?></a>
			</div>
		</section>
	</div>
</div>

<?php
get_footer();

<?php
/**
 * Trang tĩnh mặc định.
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="view">
	<div class="wrap" style="padding:54px 0 80px">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<div class="phead" style="text-align:left">
				<h1 class="h-lg"><?php the_title(); ?></h1>
			</div>
			<div class="lb-content" style="max-width:720px;color:var(--cream-2)">
				<?php the_content(); ?>
			</div>
			<?php
		endwhile;
		?>
	</div>
</div>
<?php
get_footer();

<?php
/**
 * Fallback template (archive/blog/search).
 *
 * @package Lion_Bartender
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="view">
	<div class="wrap" style="padding:54px 0 80px">
		<div class="phead">
			<h1 class="h-lg">
				<?php
				if ( is_search() ) {
					/* translators: %s search query */
					printf( 'Kết quả cho “%s”', esc_html( get_search_query() ) );
				} else {
					the_archive_title();
				}
				?>
			</h1>
		</div>
		<?php if ( have_posts() ) : ?>
			<div style="max-width:760px;margin:0 auto">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article style="border-bottom:1px solid var(--hair);padding:26px 0">
						<a href="<?php the_permalink(); ?>"><h2 class="h-md"><?php the_title(); ?></h2></a>
						<div class="muted" style="margin-top:10px"><?php the_excerpt(); ?></div>
					</article>
					<?php
				endwhile;
				?>
			</div>
			<div style="text-align:center;margin-top:40px"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p class="muted" style="text-align:center">Không có nội dung.</p>
		<?php endif; ?>
	</div>
</div>
<?php
get_footer();

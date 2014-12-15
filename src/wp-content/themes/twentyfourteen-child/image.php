<?php
/**
 * The template for displaying image attachments
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

// Retrieve attachment metadata.
$metadata = wp_get_attachment_metadata();

get_header();
?>

	<section id="primary" class="content-area image-attachment">
		<div id="content" class="site-content" role="main">

	<?php
		// Start the Loop.
		while ( have_posts() ) : the_post();
	?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-header">
					<div class="container">
						<div class="row">
							<div class="col-md-10"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></div>
						</div>
					</div>
				</div>
				<div class="entry-content">
					<div class="container">
						<div class="row">
							<div class="col-md-8"><?php twentyfourteen_the_attached_image(); ?></div>
							<?php if ( has_excerpt() ) : ?>
							<div class="col-md-2">
								<?php the_excerpt(); ?>
							</div><!-- .entry-caption -->
							<?php endif; ?>
						</div>
					</div>
				</div>
			</article><!-- #post-## -->

		<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();

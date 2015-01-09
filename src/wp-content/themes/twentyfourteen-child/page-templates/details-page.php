<?php
/**
 * Template Name: Details Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="<?php echo $post->post_name; ?>" <?php post_class(); ?>>
				<div class="entry-header">
					<div class="container">
						<div class="row">
							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></div>
						</div>
					</div>
				</div>
			</article>

			<article id="<?php echo get_post( $post->post_parent )->post_name; ?>" <?php post_class( 'paragraph' ); ?>>
				<div class="entry-content">
					<div class="container">
						<hr/>
						<div class="row">
							<div class="col-lg-4 col-md-10 col-sm-10 col-xs-10"><h1 class="entry-title"><?php echo get_post( $post->post_parent )->post_title; ?></h1></div>
							<div class="col-lg-4 col-md-8 col-sm-8 col-xs-10"><?php echo wpautop( $post->post_content ); ?></div>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-10"><?php echo render_download_link( $post->ID ); ?></div>
						</div>
						<?php echo render_images( $post->ID ); ?>
					</div>
				</div>
			</article>

		<?php endwhile; // end of the loop. ?>

		</main>
	</div>

<?php get_footer(); ?>
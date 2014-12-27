<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<?php get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<div class="container">
				<div class="row">
					<div class="col-md-10">
						<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
							<label>
								<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
								<input id="search-field" type="search" class="search-field" placeholder="" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search', 'label' ) ?>" />
							</label>
							<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
						</form>
					</div>
				</div>
			</div>

			<?php if( isset( $_GET[ 's' ]) && empty( $_GET[ 's' ])){ ?>

			<div class="container">
				<hr/>
				<div class="row">
					<div class="col-md-10">
						<h1 class="search-label">Type and hit enter to search.</h1>
					</div>
				</div>
			</div>

			<?php } else if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>

			<?php else: ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<script type="text/javascript">
try{document.getElementById('search-field').focus();}catch(e){}
</script>

<?php get_footer(); ?>

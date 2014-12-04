<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

      <div class="container">
        <div class="row">
          <div class="col-md-10">
          <?php get_search_form( true ); ?>
          </div>
        </div>
      </div>
			<?php
      // handle empty search
      if (isset($_GET['s']) && empty($_GET['s'])){
        ?>
  			<header class="container">
  				<h1><label for="s">Type and hit enter to search.</label></h1>
  			</header>
  			<?php
      } else if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyfourteen' ), get_search_query() ); ?></h1>
			</header><!-- .page-header -->

				<?php
					// render the search results
					while ( have_posts() ) : the_post();

						get_template_part( 'content', get_post_format() );

					endwhile;

					// Previous/next post navigation.
					twentyfourteen_paging_nav();

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
			?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();

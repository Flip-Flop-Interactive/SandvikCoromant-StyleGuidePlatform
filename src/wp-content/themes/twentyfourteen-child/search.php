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

			<?php if( isset( $_GET[ 's' ]) && empty( $_GET[ 's' ])){ ?>

			<div class="container">
				<div class="row">
					<div class="col-md-10">
						<?php get_search_form( true ); ?>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-10">
						<h1><label for="s">Type and hit enter to search.</label></h1>
					</div>
				</div>
			</div>

			<?php } else if ( have_posts() ) : ?>

			<div class="container">
				<div class="row">
					<div class="col-md-10">
						<div class="search-result-title">
							<h1><?php printf( __( 'Search Results for: %s', 'twentyfourteen' ), get_search_query() ); ?></h1>
						</div>
					</div>
				</div>
			</div>

			<?php

				// render the search results
				while ( have_posts() ) : the_post();

					get_template_part( 'content', get_post_format() );

				endwhile;

			else:

				get_template_part( 'content', 'none' );

			endif;
			?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_footer();

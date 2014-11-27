<?php
/**
* content-page.php
*
* used to render an article on category pages and the front page.
*/
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header"></header><!-- .entry-header -->

	<div class="entry-content">
		<div class="container">

			<div class="row">
				<div class="col-md-4"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></div>
				<div class="col-md-4"><?php the_content(); ?></div>
				<div class="col-md-2"></div>
			</div>

		</div>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<div class="container">
			<div class="row">
				<div class="col-md-10">
					<?php edit_post_link( esc_html__( 'Edit', 'twentyfifteen' ), '<div class="edit-link">', '</div>' ); ?>
				</div>
			</div>
		</div>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
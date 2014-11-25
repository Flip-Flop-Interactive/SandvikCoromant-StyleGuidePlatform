<?php
/**
* content-page.php
*
* used to render an article on category pages and the front page.
*/
?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php twentyfifteen_post_thumbnail(); ?>

	<header class="entry-header">
		<?php // the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<div class="container">
			<?php the_content(); ?>
		</div>

		<?php
			// wp_link_pages( array(
			// 	'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'twentyfifteen' ) . '</span>',
			// 	'after'       => '</div>',
			// 	'link_before' => '<span>',
			// 	'link_after'  => '</span>',
			// ) );
		?>
	</div><!-- .entry-content -->

	<?php edit_post_link( esc_html__( 'Edit', 'twentyfifteen' ), '<footer class="entry-footer"><div class="container"><div class="row"><div class="col-md-10"><span class="edit-link">', '</span></div></div></div></footer><!-- .entry-footer -->' ); ?>

</article><!-- #post-## -->
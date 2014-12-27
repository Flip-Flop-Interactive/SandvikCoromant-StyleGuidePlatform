<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
		<div class="container">
			<hr/>
			<div class="row">
				<div class="col-md-4"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></div>
				<div class="col-md-4"><?php the_content(); ?></div>
				<div class="col-md-2"><?php echo sprintf( '<div class="entry-action"><a href="%s"><i class="icon icon_arrow-right-icon"></i> <span class="label">To Section</span></a></a>', esc_url( get_permalink())); ?></div>
			</div>
		</div>
	</div>

</article><!-- #post-## -->

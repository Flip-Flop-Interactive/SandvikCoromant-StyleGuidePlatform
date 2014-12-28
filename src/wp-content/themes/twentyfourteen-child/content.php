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
				<div class="col-lg-4 col-md-10 col-sm-10"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></div>
				<div class="col-lg-4 col-md-8 col-sm-10"><?php the_content(); ?><p></p></div>
				<div class="col-lg-2 col-md-2 col-sm-10"><?php echo sprintf( '<div class="entry-action"><a href="%s"><i class="icon icon_arrow-right-icon"></i> <span class="label">To section</span></a></a>', esc_url( get_permalink())); ?></div>
			</div>
		</div>
	</div>

</article><!-- #post-## -->

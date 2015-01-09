<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article id="<?php echo $post->post_name; ?>" <?php post_class(); ?>>
	<div class="entry-header">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></div>
			</div>
		</div>
	</div>
</article>


<?php
/**
 *
 * Loop through all second level pages
 *
 */
?>

<?php $chapters = get_pages( array( 'sort_order' => 'ASC', 'sort_column' => 'menu_order', 'parent' => get_the_ID() )); ?>
<?php foreach( $chapters as $chapter ): ?>

<article id="<?php echo $chapter->post_name; ?>" <?php post_class( 'chapter' ); ?>>
	<div class="entry-content">
		<div class="container">
			<hr/>
			<div class="row">
				<div class="col-lg-4 col-md-10 col-sm-10 col-xs-10"><h1 class="entry-title"><?php echo $chapter->post_title; ?></h1></div>
				<div class="col-lg-4 col-md-8 col-sm-8 col-xs-10"><?php echo wpautop( $chapter->post_content ); ?></div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-10"><?php echo render_download_link( $chapter->ID ); ?></div>
			</div>
			<?php echo render_images( $chapter->ID ); ?>
		</div>
	</div>
</article>


<?php
/**
 *
 * Loop through all third level pages
 *
 */
?>

<?php $paragraphs = get_pages( array( 'sort_order' => 'ASC', 'sort_column' => 'menu_order', 'parent' => $chapter->ID )); ?>
<?php foreach( $paragraphs as $paragraph ): ?>

<article id="<?php echo $paragraph->post_name; ?>" <?php post_class( 'paragraph' ); ?>>
	<div class="entry-content">
		<div class="container">
			<hr/>
			<div class="row">
				<div class="col-lg-4 col-md-10 col-sm-10 col-xs-10"><h1 class="entry-title"><?php echo $paragraph->post_title; ?></h1></div>
				<div class="col-lg-4 col-md-8 col-sm-8 col-xs-10"><?php echo wpautop( $paragraph->post_content ); ?></div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-10">
					<?php echo render_download_link( $chapter->ID ); ?>
					<?php echo render_details_link( $paragraph->ID ); ?>
				</div>
			</div>
			<?php echo render_images( $paragraph->ID ); ?>
		</div>
	</div>
</article>

<?php endforeach; ?>
<?php endforeach; ?>

<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-header">
		<div class="container">
			<div class="row">
				<div class="col-md-10"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></div>
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

<article id="post-<?php echo $chapter->ID; ?>" <?php post_class( 'chapter' ); ?>>

	<div class="entry-content">
		<div class="container">
		
			<div class="row">
				<div class="col-md-4"><h1 class="entry-title"><?php echo $chapter->post_title; ?></h1></div>
				<div class="col-md-4"><?php echo wpautop( $chapter->post_content ); ?></div>
				<div class="col-md-2"><?php echo render_download( $chapter->ID ); ?></div>
			</div>
			
			<?php echo render_small_images( $chapter->ID ); ?>
			<?php echo render_medium_images( $chapter->ID ); ?>
			<?php echo render_large_images( $chapter->ID ); ?>

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

<article id="post-<?php echo $paragraph->ID; ?>" <?php post_class( 'paragraph' ); ?>>

	<div class="entry-content">
		<div class="container">
		
			<div class="row">
				<div class="col-md-4"><h1 class="entry-title"><?php echo $paragraph->post_title; ?></h1></div>
				<div class="col-md-4"><?php echo wpautop( $paragraph->post_content ); ?></div>
				<div class="col-md-2"><?php echo render_download( $paragraph->ID ); ?></div>
			</div>
			
			<?php echo render_small_images( $paragraph->ID ); ?>
			<?php echo render_medium_images( $paragraph->ID ); ?>
			<?php echo render_large_images( $paragraph->ID ); ?>

		</div>
	</div>

</article>

<?php endforeach; ?>
<?php endforeach; ?>

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
		
      <!-- text -->
			<div class="row">
				<div class="col-md-4"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></div>
				<div class="col-md-4"><?php the_content(); ?></div>
				<div class="col-md-2"></div>
			</div>
			
      <!-- media -->
			<div class="row">
				<div class="col-md-8 post-media"><?php

          $media = sandvik_media_data(get_the_ID());
          $classes = array();

          foreach ($media as $metadata) {
            $classes = array('post-image');
            
            switch (strtolower($metadata['image_size'])) {
              case 'small':
              $classes[] = 'col-md-2';
              break;
              case 'medium':
              $classes[] = 'col-md-4';
              break;
              case 'large':
              $classes[] = 'col-md-8';
              default:
            }
            
            echo sprintf('<div class="%s"><img src="%s" width="%s" height="%s"/><div class="image-caption">%s</div></div>', join(' ', $classes), $metadata['image_url'], $metadata['image_width'], $metadata['image_height'], $metadata['image_caption']);
          }
        
				 ?></div>
				<div class="col-md-2"></div>
			</div>
		</div>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<div class="container">
			<div class="row">
				<div class="col-md-10">
					<?php edit_post_link( esc_html__( 'Edit', 'twentyfourteen' ), '<div class="edit-link">', '</div>' ); ?>
				</div>
			</div>
		</div>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
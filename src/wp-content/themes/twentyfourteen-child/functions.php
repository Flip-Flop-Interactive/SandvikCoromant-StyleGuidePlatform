<?php

if ( !function_exists( 'twentyfourteen_child_setup' ) ) :
function twentyfourteen_child_setup() {

	add_image_size( 'small-image-size', 296, 296, true );
	add_image_size( 'medium-image-size', 612, 400, true );
	add_image_size( 'large-image-size', 1244, 720, true );
}
endif; // twentyfourteen_child_setup
add_action( 'after_setup_theme', 'twentyfourteen_child_setup' );


/**
 * Global function to retrieve the relative child template directory
 */
function get_child_template_directory_uri() {
	return get_stylesheet_directory_uri();
}

/**
 * Remove initial parent theme load scripts function
 */
function remove_twentyfourteen_scripts() {
	remove_action('wp_enqueue_scripts', 'twentyfourteen_scripts');
}

/**
 * main theme asset hook
 * register assets
 */
function twentyfourteen_child_scripts() {

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style('genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.2');

	// Load our main stylesheet.
	wp_enqueue_style('twentyfourteen-style', get_template_directory_uri() . '/style.css');
	wp_enqueue_style('twentyfourteen-child-style', get_stylesheet_uri());

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style('twentyfourteen-ie', get_template_directory_uri() . '/css/ie.css', array('twentyfourteen-style', 'genericons'), '20141010');
	wp_style_add_data('twentyfourteen-ie', 'conditional', 'lt IE 9');

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style('twentyfourteen-ie7', get_template_directory_uri() . '/css/ie7.css', array('twentyfourteen-style'), '20141010');
	wp_style_add_data('twentyfourteen-ie7', 'conditional', 'lt IE 8');

	//wp_enqueue_script('twentyfourteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141010', TRUE);

	if (is_singular() && wp_attachment_is_image()) {
		wp_enqueue_script('twentyfourteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array('jquery'), '20141010');
	}

	// Load javascript libraries (i.e. jQuery, Headroom.js, etc.)
	wp_enqueue_script('javascript-libraries', get_child_template_directory_uri() . '/js/libs.js', array(), '1.0.0', TRUE);

	// Load custom javascript scripts
	wp_enqueue_script('custom-scripts', get_child_template_directory_uri() . '/js/scripts.js', array(), '1.0.0', TRUE);
}

/**
 * enable livereload on localhost
 */
function livereload() {
	if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
		if (gethostname() != 'monarch.local') {
			wp_register_script('livereload', 'http://localhost:35729/livereload.js?snipver=1', NULL, FALSE, TRUE);
			wp_enqueue_script('livereload');
		}
	}
}

/**
 * render header menu overlay
 */
function render_header_menu() {
  $menu = array();

  $args = array(
  	'sort_order' => 'ASC',
    'sort_column' => 'menu_order, post_title',
  	'hierarchical' => 1,
    'exclude' => '2',
  	'include' => '',
  	'meta_key' => '',
  	'meta_value' => '',
  	'authors' => '',
  	'child_of' => 0,
  	'parent' => -1,
  	'exclude_tree' => '',
  	'number' => '',
  	'offset' => 0,
  	'post_type' => 'page',
  	'post_status' => 'publish'
  ); 
  
  $pages = get_pages( $args );
  
  // transform the array of all page data into a hierarchical array of menu link metadata
  foreach ($pages as $page_metadata) {
    $menu[$page_metadata->post_parent][] = array(
      'post_id'	=> $page_metadata->ID,
      'post_title'	=> $page_metadata->post_title,
      'post_name'	=> $page_metadata->post_name,
      'post_parent'	=> $page_metadata->post_parent,
      'guid'	=> $page_metadata->guid,
    );
  }

	return sandvik_render_menu($menu);
}

/**
* render HTML for a hierarchical menu structure
*/
function sandvik_render_menu(&$menu, $post_parent = 0) {
	$html = '';

  if (!isset($menu[$post_parent])) {
    return $html;
  }

  $current_page_id = get_the_ID();
  $current_page_parents = get_post_ancestors($current_page_id);
  
  $classes = array('menu-link');

	// transform menu link metadata into HTML links
  foreach ($menu[$post_parent] AS $link) {
    if ($link['post_id'] == $current_page_id) {
      $classes[] = 'active';
    } else if (in_array($link['post_parent'], $current_page_parents)) {
      $classes[] = 'active-trail';
    }

    $permalink = get_permalink($link['post_id']);
    
    if ($post_parent == 0) {
      // open parent-level row
      $html .= '<div class="container menu-items"><div class="row">';
      
      $html .= sprintf('<div class="col-md-4"><h1 class="menu-item-top"><a href="%s" class="%s">%s</a></h1></div>', $permalink, implode(' ', $classes), $link['post_title']);

      if (isset($menu[$link['post_id']])) {
        // render child links recursively
        $submenu = sandvik_render_menu($menu, $link['post_id']);
      } else {
        $submenu = '';
      }

      $html .= sprintf('<div class="col-md-2 menu-items">%s</div><div class="col-md-2">&nbsp;</div>', $submenu);
      
      // close parent-level row
      $html .= '</div></div>';
      
    } else {
      // child row
      $html .= sprintf('<div class="menu-item"><a href="%s" class="%s">%s</a></div>', $permalink, implode(' ', $classes), $link['post_title']);
    }
  }

	return $html;
}

/**
* fetch media metadata using simple_fields API
* 
* @param $post_id numeric ID
* @returns an array of images, in the order specified by the admin
*/
function sandvik_media_data($post_id) {
	$data = array();
	$thumbnail_size = array();
	
	$media = simple_fields_get_post_group_values($post_id, 'Media', true, 1);
	
	if (!empty($media) && !empty($media['Image'])) {
		foreach ($media['Image'] as $index => $media_id) {
			$image_caption = $media['Image Caption'][$index];
			$image_size = $media['Image Thumbnail Size'][$index];
			$image_size = strtolower($image_size);
			
			// specify image derivative sizes
			switch ($image_size) {
				case 'small':
				$thumbnail_size = array(296, 183);
				break;
				case 'medium':
				$thumbnail_size = array(612, 378);
				break;
				case 'large':
				$thumbnail_size = array(1244, 768);
				default:
			}
			$image_tag = wp_get_attachment_image($media_id, $thumbnail_size);
			
			$data[] = compact('image_tag', 'image_caption', 'image_size');
		}
	}
	
	return $data;
}

/**
* get featured image url
*/
function get_featured_image_as_background( $post_id ) {

	$featured_image_url = wp_get_attachment_url( get_post_thumbnail_id( $post_id ));
	return sprintf( 'style="background-image:url(\'%s\');"', $featured_image_url );
}

/**
* render media metadata for responsive layout
*/
function sandvik_render_post_media($post_id) {
	$media		 = sandvik_media_data($post_id);

	$classes = array();
	$html = array();
	$lastclass = '';
	$image_size	= '';
	$index = 0;
	$index_of_type = 0;

	foreach ($media as $index => $metadata) {
	  $classes = array('post-image');

    $image_size = $metadata['image_size'];

    switch ($image_size) {
      case 'small':
      $classes[] = 'col-md-2';
      break;
      case 'medium':
      $classes[] = 'col-md-4';
      break;
      case 'large':
      default;
      $classes[] = 'col-md-8';
    }
    
	  if ($image_size != $lastclass) {
    	$index_of_type = 0;

      // start new row
      if ($index > 0) {
  	    $html[] = '</div>';
      }
	    $html[] = '<div class="row">';
	  } else {
	    // keep a count of how many images are in this row
	    ++$index_of_type;
	    
	    $box_is_ticked = false;

    	if ($box_is_ticked) {
    	  // close the row
    	}
	  }

	  $lastclass = $image_size;
	  $classes[] = $image_size;

	  $html[] = sprintf('<div class="%s">%s<div class="image-caption">%s</div></div>', join(' ', $classes), $metadata['image_tag'], $metadata['image_caption']);
	}
	
	if ($index > 0) {
		// end row
		$html[] = '</div>';
	}

	return implode("\n", $html);
}


/**
* render row with two small images
*/
function render_row_two_small_images( $id ){

	$images   = [ array( 5, 1 ), array( 5, 2 )];
	$captions = [ array( 5, 4 ), array( 5, 5 )];
	$html     = '<div class="row">';
	$html    .= '<div class ="col-md-4"></div>';

	for( $i = 0; $i < count( $images ); $i++ ) {

		$image	 = simple_fields_get_post_value( $id, $images[ $i ], true, 1 );
		$caption = simple_fields_get_post_value( $id, $captions[ $i ], true, 1 );

		if( $image[ 'url' ] != '' ){

			$html .= sprintf( '<div class="col-md-2"><a href="#" class="zoom-image" data-toggle="modal" data-target="#image" data-target-url="%s">%s</a><br/>%s</div>', $image[ 'url' ], $image[ 'image' ][ 'small-image-size' ], $caption );
		}
	}

	$html .= '</div>';
	return $html;
}



/**
* render row with four small images
*/
function render_row_four_small_images( $id ){

	$images   = [ array( 6, 1 ), array( 6, 4 ), array( 6, 7 ), array( 6, 10 )];
	$captions = [ array( 6, 2 ), array( 6, 5 ), array( 6, 8 ), array( 6, 11 )];
	$html     = '<div class="row">';

	for( $i = 0; $i < count( $images ); $i++ ) {

		$image	 = simple_fields_get_post_value( $id, $images[ $i ], true, 1 );
		$caption = simple_fields_get_post_value( $id, $captions[ $i ], true, 1 );

		if( $image[ 'url' ] != '' ){

			$html .= sprintf( '<div class="col-md-2"><a href="#" data-toggle="modal" data-target="#image">%s</a><br/>%s</div>', $image[ 'image' ][ 'small-image-size' ], $caption );
		}
	}

	$html .= '</div>';
	return $html;
}



/**
* render row with two medium images
*/
function render_row_two_medium_images( $id ){

	$images   = [ array( 7, 1 ), array( 7, 4 )];
	$captions = [ array( 7, 2 ), array( 7, 5 )];
	$html     = '<div class="row">';

	for( $i = 0; $i < count( $images ); $i++ ) {

		$image	 = simple_fields_get_post_value( $id, $images[ $i ], true, 1 );
		$caption = simple_fields_get_post_value( $id, $captions[ $i ], true, 1 );

		if( $image[ 'url' ] != '' ){

			$html .= sprintf( '<div class="col-md-4">%s<br/>%s</div>', $image[ 'image' ][ 'medium-image-size' ], $caption );
		}
	}

	$html .= '</div>';
	return $html;
}



/**
* render row with one large image
*/
function render_row_one_large_image( $id ){

	$images   = [ array( 8, 1 )];
	$captions = [ array( 8, 2 )];
	$html     = '<div class="row">';

	for( $i = 0; $i < count( $images ); $i++ ) {

		$image	 = simple_fields_get_post_value( $id, $images[ $i ], true, 1 );
		$caption = simple_fields_get_post_value( $id, $captions[ $i ], true, 1 );

		if( $image[ 'url' ] != '' ){

			$html .= sprintf( '<div class="col-md-8">%s<br/>%s</div>', $image[ 'image' ][ 'large-image-size' ], $caption );
		}
	}

	$html .= '</div>';
	return $html;
}



/**
 * register hooks
 */
add_action('init', 'remove_twentyfourteen_scripts');
add_action('wp_enqueue_scripts', 'twentyfourteen_child_scripts');
add_action('wp_enqueue_scripts', 'livereload');


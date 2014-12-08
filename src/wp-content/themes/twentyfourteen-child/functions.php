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

function show_category() {
	global $category, $categories;

	$taxonomies = array(
		'category',
	);

	$args = array(
		'orderby' => 'id',
	);

	// default category
	$category = 1;

	/**
	 * parse the current category from the URL
	 */
	if (is_category()) {
		global $wp_query;

		$category_info = $wp_query->get_queried_object();
		if (!empty($category_info)) {
			$category = $category_info->term_id;
		}
	}
	else {
		// extract category from last URL path component
		// this dirty hack seems to be the way things are done in WordPress ;(
		$path = $_SERVER['REQUEST_URI'];
		$path = explode('/', $path);
		$slug = array_pop($path);
		if (empty($slug)) {
			$slug = array_pop($path);
		}
		$category_info = get_category_by_path($slug);
		if (!empty($category_info)) {
			$category = $category_info->term_id;
		}
	}

	$categories = get_terms($taxonomies, $args);

	return $category;
}

/**
 * render header menu overlay
 */
function render_header_menu() {
	global $category, $categories;

	$html = '';

	// /**
	// * category menu
	// */

	foreach ($categories as $category_info) {
		$selected = "";
		if ($category_info->term_id == $category) {
			$selected = 'active';
		}
		$link = get_category_link($category_info->term_id);

	    $html .= '<div class="container"><div class="row">';
	    $html .= sprintf('<div class="col-md-4"><a href="%s" class="%s"><h1>%s</h1></a></div>', $link, $selected, $category_info->name);
	    $html .= sprintf('<div class="col-md-2">%s</div>', render_page_menu($category_info->term_id, $link));
	    $html .= '</div></div>';
	  }

	return $html;
}

/**
  * render submenus within header menu overlay
 */
function render_page_menu($category_id = 0, $category_link = '') {
  $args = array(
    'posts_per_page' => 20,
    'category' => $category_id,
    'orderby' => 'menu_order',
    'post_type' => 'post',
    'post_parent' => '',
    'post_status' => 'publish',
    'suppress_filters' => TRUE,
  );

  $html = '<ul class="menu">';

  $myposts = get_posts($args);
  foreach ($myposts as $post) {
    setup_postdata($post);
    //$html .= json_encode($post);
    
    //$link = $post->post_name;
    $link = $category_link . '#post-' . $post->ID;

    $html .= '<li class="menu-item">';
    $html .= sprintf('<a class="menu-link" href="%s">%s</a>', $link, $post->post_title);
    $html .= '</li>';
  }
  $html .= '</ul>';

  wp_reset_postdata();

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
function render_row_two_small_images( $post_id ){

	$image_1 = simple_fields_get_post_value( get_the_ID(), array( 5, 1 ), true, 1 );
	$image_caption_1 = simple_fields_get_post_value( get_the_ID(), array( 5, 4 ), true, 1 );

	$image_2 = simple_fields_get_post_value( get_the_ID(), array( 5, 2 ), true, 1 );
	$image_caption_2 = simple_fields_get_post_value( get_the_ID(), array( 5, 5 ), true, 1 );

	if( !empty( $image_1 ) && !empty( $image_2 )){

		if( $image_1[ 'image' ][ 'small-image-size' ] != '' && $image_2[ 'image' ][ 'small-image-size' ] != '' ){

			$html  = '<div class="row">';
			$html .= '<div class="col-md-4"></div>';
			$html .= sprintf( '<div class="col-md-2">%s<br/>%s</div>', $image_1[ 'image' ][ 'small-image-size' ], $image_caption_1 );
			$html .= sprintf( '<div class="col-md-2">%s<br/>%s</div>', $image_2[ 'image' ][ 'small-image-size' ], $image_caption_2 );
			$html .= '</div>';
			return $html;
		}
	}
}

/**
* render row with four small images
*/
function render_row_four_small_images( $post_id ){

	$image_1 = simple_fields_get_post_value( get_the_ID(), array( 6, 1 ), true, 1 );
	$image_caption_1 = simple_fields_get_post_value( get_the_ID(), array( 6, 2 ), true, 1 );

	$image_2 = simple_fields_get_post_value( get_the_ID(), array( 6, 4 ), true, 1 );
	$image_caption_2 = simple_fields_get_post_value( get_the_ID(), array( 6, 5 ), true, 1 );

	$image_3 = simple_fields_get_post_value( get_the_ID(), array( 6, 7 ), true, 1 );
	$image_caption_3 = simple_fields_get_post_value( get_the_ID(), array( 6, 8 ), true, 1 );

	$image_4 = simple_fields_get_post_value( get_the_ID(), array( 6, 10 ), true, 1 );
	$image_caption_4 = simple_fields_get_post_value( get_the_ID(), array( 6, 11 ), true, 1 );

	if( !empty( $image_1 ) && !empty( $image_2 ) && !empty( $image_3 ) && !empty( $image_4 )){

		if( $image_1[ 'image' ][ 'small-image-size' ] != '' && $image_2[ 'image' ][ 'small-image-size' ] != '' && $image_3[ 'image' ][ 'small-image-size' ] != '' && $image_4[ 'image' ][ 'small-image-size' ] != '' ){

			$html  = '<div class="row">';
			$html .= sprintf( '<div class="col-md-2">%s<br/>%s</div>', $image_1[ 'image' ][ 'small-image-size' ], $image_caption_1 );
			$html .= sprintf( '<div class="col-md-2">%s<br/>%s</div>', $image_2[ 'image' ][ 'small-image-size' ], $image_caption_2 );
			$html .= sprintf( '<div class="col-md-2">%s<br/>%s</div>', $image_3[ 'image' ][ 'small-image-size' ], $image_caption_3 );
			$html .= sprintf( '<div class="col-md-2">%s<br/>%s</div>', $image_4[ 'image' ][ 'small-image-size' ], $image_caption_4 );
			$html .= '</div>';
			return $html;
		}
	}
}

/**
* render row with two medium images
*/
function render_row_two_medium_images( $post_id ){

	$image_1 = simple_fields_get_post_value( get_the_ID(), array( 7, 1 ), true, 1 );
	$image_caption_1 = simple_fields_get_post_value( get_the_ID(), array( 7, 2 ), true, 1 );

	$image_2 = simple_fields_get_post_value( get_the_ID(), array( 7, 4 ), true, 1 );
	$image_caption_2 = simple_fields_get_post_value( get_the_ID(), array( 7, 5 ), true, 1 );

	if( !empty( $image_1 ) && !empty( $image_2 )){

		if( $image_1[ 'image' ][ 'medium-image-size' ] != '' && $image_2[ 'image' ][ 'medium-image-size' ] != '' ){

			$html  = '<div class="row">';
			$html .= sprintf( '<div class="col-md-4">%s<br/>%s</div>', $image_1[ 'image' ][ 'medium-image-size' ], $image_caption_1 );
			$html .= sprintf( '<div class="col-md-4">%s<br/>%s</div>', $image_2[ 'image' ][ 'medium-image-size' ], $image_caption_2 );
			$html .= '</div>';
			return $html;
		}
	}
}


/**
* render row with one large image
*/
function render_row_one_large_image( $post_id ){

	$image = simple_fields_get_post_value( get_the_ID(), array( 8, 1 ), true, 1 );
	$image_caption = simple_fields_get_post_value( get_the_ID(), array( 8, 2 ), true, 1 );

	if( !empty( $image )){

		if( $image[ 'image' ][ 'large-image-size' ] != '' ){

			$html  = '<div class="row">';
			$html .= sprintf( '<div class="col-md-8">%s<br/>%s</div>', $image[ 'image' ][ 'large-image-size' ], $image_caption );
			$html .= '</div>';
			return $html;
		}
	}
}



/**
 * register hooks
 */
add_action('init', 'remove_twentyfourteen_scripts');
add_action('wp_enqueue_scripts', 'twentyfourteen_child_scripts');
add_action('wp_enqueue_scripts', 'livereload');

/**
 * set globals
 */
$category = show_category();


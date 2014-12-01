<?php

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
 * render_header_menu
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
    $html .= sprintf('<div class="col-md-2">%s</div>', render_page_menu($category_info->term_id));
    $html .= '</div></div>';
  }

  return $html;
}

/**
 *
 */
function render_page_menu($category_id) {
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
    $html .= '<li class="menu-item">';
    $html .= sprintf('<a class="menu-link" href="/%s">%s</a>', $post->post_name, $post->post_title);
    //$html .= json_encode($post);
    $html .= '</li>';
  }
  $html .= '</ul>';

  wp_reset_postdata();

  return $html;
}

/**
* fetch media metadata using simple_fields API
*/
function sandvik_media_data($post_id) {
  $data = array();
  $keys = array();
  
  $media = simple_fields_get_post_group_values($post_id, 'Media', true, 1);
  
  if (!empty($media) && !empty($media['Image'])) {
    foreach ($media['Image'] as $index => $media_id) {
      $image_caption = $media['Image Caption'][$index];
      $image_size = $media['Image Thumbnail Size'][$index];
      $image_size = strtolower($image_size);
      
      $size = $image_size == 'small' ? 'thumbnail' : $image_size;
      list($image_url, $image_width, $image_height) = wp_get_attachment_image_src($media_id, $size);
      
      // save the keys for sorting the data later
      switch ($image_size) {
        case 'small':
        $keys[$index] = 0;
        break;
        case 'medium':
        $keys[$index] = 1;
        break;
        case 'large':
        $keys[$index] = 2;
        default:
      }

      $data[] = compact('image_url', 'image_width', 'image_height', 'image_caption', 'image_size');
    }
  }
  
  // sort the data using the image sizes, order = small, medium, large
  array_multisort($keys, SORT_ASC, $data);
  
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
	$media     = sandvik_media_data($post_id);

	$classes = array();
	$html = array();
	$lastclass = '';
	$newclass  = '';

	foreach ($media as $metadata) {
	  $classes = array('post-image');

	  switch ($metadata['image_size']) {
	    case 'small':
	      $newclass = 'col-md-2';
	      break;

	    case 'medium':
	      $newclass = 'col-md-4';
	      break;

	    case 'large':
	      $newclass = 'col-md-8';
	    default:
	  }

	  $classes[] = $newclass;
	  if ($newclass != $lastclass) {
	    $classes[] = 'clearfix';
	  }

	  $classes[] = $metadata['image_size'];
	  $lastclass = $newclass;

	  $html[] = sprintf('<div class="%s"><img src="%s" width="%s" height="%s"/><div class="image-caption">%s</div></div>', join(' ', $classes), $metadata['image_url'], $metadata['image_width'], $metadata['image_height'], $metadata['image_caption']);
	}
	
	return implode("\n", $html);
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


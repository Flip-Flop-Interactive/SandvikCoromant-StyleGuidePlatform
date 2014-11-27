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

  if (is_singular() && wp_attachment_is_image()) {
    wp_enqueue_script('twentyfourteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array('jquery'), '20141010');
  }

  // Load javascript libraries (i.e. jQuery, Headroom.js, etc.)
  wp_enqueue_script('javascript-libraries', get_child_template_directory_uri() . '/js/libs.js', array(), '1.0.0', TRUE);

  // Load custom javascript scripts
  wp_enqueue_script('custom-scripts', get_child_template_directory_uri() . '/js/scripts.js', array(), '1.0.0', TRUE);

  // enable livereload on localhost
  if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
    wp_register_script('livereload', 'http://localhost:35729/livereload.js?snipver=1', NULL, FALSE, TRUE);
    wp_enqueue_script('livereload');
  }
}

/**
 * register hooks
 */
add_action('init', 'remove_twentyfourteen_scripts');
add_action('wp_enqueue_scripts', 'twentyfourteen_child_scripts');

global $category, $categories;

if (isset($_GET['cat']) && $_GET['cat'] > 0) {
  $category = (int)$_GET['cat'];
} else {
  $category = 1;
}

$taxonomies = array( 
    'category',
);

$args = array(
    'orderby'           => 'id', 
); 

$categories = get_terms($taxonomies, $args);

/**
* fetch posts by category
*/
query_posts('cat=' . $category);

?>


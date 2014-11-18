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
function remove_twentyfifteen_scripts() {
  remove_action('wp_enqueue_scripts', 'twentyfifteen_scripts');
}

/**
 * main theme asset hook
 * register assets
 */
function twentyfifteen_child_scripts() {

  // Add Genericons, used in the main stylesheet.
  wp_enqueue_style('genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.2');

  // Load our main stylesheet.
  wp_enqueue_style('twentyfifteen-style', get_template_directory_uri() . '/style.css');
  wp_enqueue_style('twentyfifteen-child-style', get_stylesheet_uri());

  // Load the Internet Explorer specific stylesheet.
  wp_enqueue_style('twentyfifteen-ie', get_template_directory_uri() . '/css/ie.css', array('twentyfifteen-style', 'genericons'), '20141010');
  wp_style_add_data('twentyfifteen-ie', 'conditional', 'lt IE 9');

  // Load the Internet Explorer 7 specific stylesheet.
  wp_enqueue_style('twentyfifteen-ie7', get_template_directory_uri() . '/css/ie7.css', array('twentyfifteen-style'), '20141010');
  wp_style_add_data('twentyfifteen-ie7', 'conditional', 'lt IE 8');

  wp_enqueue_script('twentyfifteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141010', TRUE
  );

  if (is_singular() && wp_attachment_is_image()) {
    wp_enqueue_script('twentyfifteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array('jquery'), '20141010');
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
add_action('init', 'remove_twentyfifteen_scripts');
add_action('wp_enqueue_scripts', 'twentyfifteen_child_scripts');


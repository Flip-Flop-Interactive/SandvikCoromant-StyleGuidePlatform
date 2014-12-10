<?php

define('PAGE_TOP_LEVEL_ID', 0);

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
	$menu = sandvik_get_page_hierarchy();

	return sandvik_render_menu($menu);
}

/**
* get link metadata for page hierarchy
* @returns array
*/
function sandvik_get_page_hierarchy($parent_id = -1) {
	// store in static cache for performance
	static $menu = array();
	if ( empty( $menu[$parent_id] ) ) {
		
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
			'parent' => $parent_id,
			'exclude_tree' => '',
			'number' => '',
			'offset' => 0,
			'post_type' => 'page',
			'post_status' => 'publish'
		);

		$pages = get_pages( $args );

		// transform the array of all page data into a hierarchical array of menu link metadata
		$menu[$parent_id] = sandvik_get_page_metadata($pages);
	}
	
	return $menu[$parent_id];
}

/**
* parse page data into a useful array
* @returns array
*/
function sandvik_get_page_metadata(&$pages) {
	$arr = array();
	foreach ($pages as $page_metadata) {
		$arr[$page_metadata->post_parent][$page_metadata->ID] = array(
			'post_id'	=> $page_metadata->ID,
			'post_title'	=> $page_metadata->post_title,
			'post_name'	=> $page_metadata->post_name,
			'post_parent'	=> $page_metadata->post_parent,
			'guid'	=> $page_metadata->guid,
		);
	}
	
	return $arr;
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

		if ($post_parent == PAGE_TOP_LEVEL_ID) {
			$permalink = get_permalink($link['post_id']);

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
			$permalink = get_permalink($link['post_parent']) . '#post-' . $link['post_id'];

			// child row
			$html .= sprintf('<div class="menu-item"><a href="%s" class="%s">%s</a></div>', $permalink, implode(' ', $classes), $link['post_title']);
		}
	}

	return $html;
}

/**
*
*/
function sandvik_get_top_level_pager() {
	$current_page_id = get_the_ID();
	$current_page_parents = get_post_ancestors($current_page_id);
	$current_page_parent = reset($current_page_parents);

	if (empty($current_page_parent)) {
		$current_page_parent = $current_page_id;
	}

	// fetch metadata for top-level pages
	$pages = sandvik_get_page_hierarchy(PAGE_TOP_LEVEL_ID);

	// reindex top-level array from 0
	$pages = array_values(reset($pages));
	
	foreach ($pages AS $offset => $page_metadata) {
		if ($page_metadata['post_id'] == $current_page_id) {
			
			if ($offset > 0) {
				$previous = array_slice($pages, $offset - 1, 1);
				$previous = reset($previous); // we just want the element
			} else {
				$previous = array();
			}
			$next = array_slice($pages, $offset + 1, 1);
			$next = reset($next); // we just want the element

			return compact('previous', 'next');
		}
	}
	
	return array(); // no results
}




/**
* get featured image url
*/
function get_featured_image_as_background( $post_id ) {

	$featured_image_url = wp_get_attachment_url( get_post_thumbnail_id( $post_id ));
	return sprintf( 'style="background-image:url(\'%s\');"', $featured_image_url );
}



/**
* Render multipe rows with multiple small sized images and additional captions
*/

function render_small_images( $id ){

	$limit 		= 4;
	$classes 	= "col-md-2";
	$image_size = "small-image-size";
	$collection = simple_fields_fieldgroup( 'small_images', $id );
	$rows 		= get_collection_in_rows( $collection, $limit );
	$html 		= render_row_columns( $rows, $limit, $classes, $image_size );

	return $html;
}


/**
* Render multipe rows with multiple medium sized images and additional captions
*/

function render_medium_images( $id ){

	$limit 		= 2;
	$classes 	= "col-md-4";
	$image_size = "medium-image-size";
	$collection = simple_fields_fieldgroup( 'medium_images', $id );
	$rows 		= get_collection_in_rows( $collection, $limit );
	$html 		= render_row_columns( $rows, $limit, $classes, $image_size );

	return $html;
}


/**
* Render multipe rows with multiple large sized images and additional captions
*/

function render_large_images( $id ){

	$limit 		= 1;
	$classes 	= "col-md-8";
	$image_size = "large-image-size";
	$collection = simple_fields_fieldgroup( 'large_images', $id );
	$rows 		= get_collection_in_rows( $collection, $limit );
	$html 		= render_row_columns( $rows, $limit, $classes, $image_size );

	return $html;	
}


/**
* Nest an array within a new array within params limit increments
*/

function get_collection_in_rows( $collection, $limit ){

	$rows	= [];
	$index = 0;
	$count = 0;

	foreach( $collection as $item ){

		$rows[ $index ][] = $item;

		if( $count % $limit == $limit - 1 ){
			$index += 1;
		}

		$count += 1;
	}
	return $rows;
}


/**
* Render the actual rows and columns with spacing to the right side
*/

function render_row_columns( $rows, $limit, $classes, $image_size ){

	$html = '';

	foreach( $rows as $row ){

		$html .= '<div class="row">';

		$count = $limit - count( $row );
		while( $count-- ){

			$html .= sprintf( '<div class ="%s"></div>', $classes );
		}

		foreach( $row as $column ){


			$html .= sprintf( '<div class="%s">', $classes );
			$html .= sprintf( '<a href="#" class="entry-image" data-toggle="modal" data-target="#image" data-target-url="%s">%s</a>', $column[ 'image' ][ 'url' ], $column[ 'image' ][ 'image' ][ $image_size ]);

			if( $column[ 'caption' ] != '' ){

				$html .= sprintf( '<p class="entry-image-caption">%s</p>', nl2br( $column[ 'caption' ]));
			}

			$html .= '</div>';
		}

		$html .= '</div>';
	}

	return $html;	
}



function mydie( $obj ){

	die( sprintf( '<pre style="%s">%s</pre>', "width:100%;background-color:#fff;color:#111;", json_encode( $obj, JSON_PRETTY_PRINT )));
}


/**
 * register hooks
 */
add_action( 'init', 'remove_twentyfourteen_scripts' );
add_action( 'wp_enqueue_scripts', 'twentyfourteen_child_scripts' );
add_action( 'wp_enqueue_scripts', 'livereload' );


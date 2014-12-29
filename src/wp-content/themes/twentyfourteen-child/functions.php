<?php

/**
 * Remove all unnecessary image sizes from media library
 */
function filter_image_sizes( $sizes ){
		
	unset( $sizes[ 'thumbnail' ]);
	unset( $sizes[ 'medium' ]);
	unset( $sizes[ 'large' ]);
	unset( $sizes[ 'post-thumbnail' ]);
	unset( $sizes[ 'twentyfourteen-full-width' ]);
	return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'filter_image_sizes' );

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
add_action( 'init', 'remove_twentyfourteen_scripts' );

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
add_action( 'wp_enqueue_scripts', 'twentyfourteen_child_scripts' );

/**
 * enable livereload on localhost
 */
function livereload() {

	if( in_array( $_SERVER[ 'REMOTE_ADDR' ], array( '127.0.0.1', '::1' ))){

		if( gethostname() != 'monarch.local' ){

			wp_register_script( 'livereload', 'http://localhost:35729/livereload.js?snipver=1', NULL, FALSE, TRUE );
			wp_enqueue_script( 'livereload' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'livereload' );

/**
 * render menu - to be used on main page and overlay
 */
function render_menu() {

	$arguments = array(
		'menu' 			  => 'Navigation',
		'container_class' => 'container menu-items',
		'items_wrap'	  => '%3$s',
		'depth'			  => 0,
		'walker'		  => new Menu_Walker()
	);
	return wp_nav_menu( $arguments );
}

/**
 * Custom Walker_Nav_Menu to walk through navigation setup in back-end
 */
class Menu_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = array() ){

		if( $depth == 0 ){

			$output .= '<div class="col-lg-4 col-md-4 col-sm-5 col-xs-5"><ul class="menu-item-list">';
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ){

		if( $depth == 0 ){

			$output .= '</ul></div>';
		}
	}

	public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ){

		if( $depth == 0 ){

			$output .= sprintf( '<hr/><div class="row"><div class="col-lg-4 col-md-4 col-sm-5 col-xs-5"><h1 class="menu-item-top"><a href="%s" class="%s">%s</a></h1></div>', esc_attr( $object->url ), implode( ' ', $object->classes ), esc_attr( $object->title ));
		}

		if( $depth == 1 ){

			$output .= sprintf( '<li><a href="%s" class="%s">%s</a></li>', esc_attr( $object->url ), implode( ' ', $object->classes ), esc_attr( $object->title ));
		}
	}

	public function end_el( &$output, $object, $depth = 0, $args = array() ){

		if( $depth == 0 ){

			$output .= '</div>';
		}
	}
}

/*
 *  Rewrite the query URL to support anchors within parent pages
 */
function rewrite_permalink(){

	// Check if page is not search page otherwise go ahead and rewrite url
	if( !is_search() ){

		// Check if page is not details page template otherwise go ahead and rewrite url
		if( !is_page_template( 'page-templates/details-page.php' )){

			$paragraph  = get_queried_object();
			$ancestors 	= get_post_ancestors( $paragraph->ID );

			if( $ancestors ){

				$page_id 	= $ancestors[ count( $ancestors ) -1 ];
				$page_name  = get_post( $page_id )->post_name;

				$permalink = get_bloginfo( 'url' ) . '/' . $page_name . '/#' . $paragraph->post_name;
				wp_redirect( $permalink, 301 );
			}
		}
	}
}
add_action( 'get_header', 'rewrite_permalink', 0 );

/*
 * Render multipe rows with multiple different sized images spanning over multiple columns 
 */
function render_images( $id ){

	$limit		= 8;
	$collection = simple_fields_fieldgroup( 'images', $id );
	$rows 		= get_collection_in_rows( $collection, $limit );
	$html 		= render_rows( $rows, $limit );

	return $html;
}

/*
 * Nest an array within a new array within set limit increments
 */
function get_collection_in_rows( $collection, $limit ){

	$increment 	= 0;
	$count 		= 0;
	$index 		= 0;
	$rows  		= [];
	
	foreach( $collection as $item ){

		$increment  = intval( $item[ 'column_span' ][ 'selected_value' ]);
		$count 	   += $increment;

		if( $count > $limit ){

			$index += 1;
			$count  = $increment;
		}

		$rows[ $index ][] = $item;
	}
	return $rows;
}

/*
 * Render rows within a collection of images
 */
function render_rows( $rows, $limit ){

	$html = '';
	foreach( $rows as $row ){

		$html .= '<div class="row">';
		$html .= render_spacers( $row, $limit );
		$html .= render_columns( $row );
		$html .= '</div>';
	}
	return $html;	
}

/*
 * Render spacers within a row, aligning the images to the right side
 */
function render_spacers( $row, $limit ){

	$increment 	= 0;
	$count 		= 0;
	$deduction  = 0;

	foreach( $row as $column ){

		$increment 	= intval( $column[ 'column_span' ][ 'selected_value' ]);
		$count 	   += $increment;
	}

	$deduction = $limit - $count;
	$deduction = ( $deduction >= 6 ) ? 4 : $deduction;

	if( $deduction > 0 ){

		$html = '';
		while( $deduction-- ){
			$html .= '<div class="col-lg-1 hidden-md hidden-sm"></div>';
		}
		return $html;
	}
}

/*
 * Render columns within a row
 */
function render_columns( $row ){

	$classes = array(
		'1' => 'col-lg-1 col-md-2 col-sm-5',
		'2' => 'col-lg-2 col-md-4 col-sm-10',
		'4' => 'col-lg-4 col-md-4 col-sm-10',
		'8' => 'col-lg-8 col-md-8 col-sm-10',
	);

	$html = '';
	foreach( $row as $column ){

		$selected_value = $column[ 'column_span' ][ 'selected_value' ];
		$html .= sprintf( '<div class="%s">', $classes[ $selected_value ]);

		// Check if an image is selected, otherwise just render the column
		if(( $column[ 'image' ][ 'url' ] != '' )){
			$html .= sprintf( '<a href="%s">', $column[ 'image' ][ 'url' ]);
			$html .= ( $column[ 'stroke' ] != '' ) ? sprintf( '<img src="%s" width="%s" height="%s" class="stroke" />', $column[ 'image' ][ 'url' ], $column[ 'image' ][ 'metadata' ][ 'width' ], $column[ 'image' ][ 'metadata' ][ 'height' ]) : sprintf( '<img src="%s" width="%s" height="%s" />', $column[ 'image' ][ 'url' ], $column[ 'image' ][ 'metadata' ][ 'width' ], $column[ 'image' ][ 'metadata' ][ 'height' ]);
			$html .= '</a>';
			$html .= ( $column[ 'caption' ] != '' ) ? sprintf( '<p class="entry-caption">%s</p>', nl2br( $column[ 'caption' ])) : '';
		}
		
		$html .= '</div>';
	}
	return $html;
}

/*
 * Render the download section link within a page, chapter or paragraph
 */
function render_download_link( $id ){

	$download = simple_fields_value( 'file', $id );

	if( $download && $download[ 'url' ] != '' ){

		$html = sprintf( '<div class="entry-action"><a href="%s" target="_blank" class="hidden-sm hidden-xs"><i class="icon icon_download-icon"></i> <span class="label">Download section</span></a></div>', $download[ 'url' ]);
		return $html;
	}
}

/*
 * Render the details link within a paragraph
 */
function render_details_link( $id ){

	$html  = '';
	$pages = get_pages( array( 'parent' => $id ));

	foreach( $pages as $page ){

		$html .= sprintf( '<div class="entry-action"><a href="%s" class="hidden-sm hidden-xs"><i class="icon icon_arrow-right-icon"></i> <span class="label">%s</span></a></div>', get_page_link( $page->ID ), get_the_title( $page->ID ));
	}

	return $html;
}

/*
 * Render the back link used in the footer
 */
function render_back_link(){

	// Check if page is not details page template otherwise go ahead and rewrite url
	if( is_page_template( 'page-templates/details-page.php' )){

		$page 	= get_queried_object();
		$id 	= get_post( $page->post_parent )->ID;
		$html   = sprintf('<a href="%s"><i class="icon icon_arrow-left-icon"></i> <span class="label">%s</span></a>', get_page_link( $id ), 'Back to section' );
		return $html;
	}
}

/*
 * Render the previous link used in the footer
 */
function render_prev_link(){

	if( !is_search() ){

		$id 	= get_sibling_page_id( -1 );
		$html   = ( isset( $id )) ? sprintf('<a href="%s"><i class="icon icon_arrow-left-icon"></i> <span class="label">%s</span></a>', get_page_link( $id ), get_the_title( $id )) : '';
		return $html;
	}
}

/*
 * Render the next link used in the footer
 */
function render_next_link(){

	if( !is_search() ){

		$id 	= get_sibling_page_id( 1 );
		$html   = ( isset( $id )) ? sprintf('<a href="%s"><i class="icon icon_arrow-right-icon"></i> <span class="label">%s</span></a>', get_page_link( $id ), get_the_title( $id )) : '';
		return $html;
	}
}

/*
 * Get the ID of the sibling page; to be used in render_prev_link and render_next_link
 */
function get_sibling_page_id( $increment ){

	$pages 			 = get_top_level_pages( array( '2', '351' ));
	$current_page_id = get_queried_object_id();

	for( $i = 0; $i <= count( $pages ); $i++ ) {

		if( $current_page_id == $pages[ $i ]->ID ){

			return $pages[ $i + $increment ]->ID;
		}
	}
}

/*
 * Get all top level pages with a allowed parameter to exclude some
 */
function get_top_level_pages( $exclude ){

	$arguments = array(

		'sort_column' => 'ID',
		'exclude'	  => $exclude,
		'parent' 	  => 0,
	);

	return get_pages( $arguments );
}



/**
 * Remove initial FluidBox plugin script
 */
function remove_fluidbox_frontend_scripts(){
	remove_action( 'wp_enqueue_scripts', 'fluidbox_frontend_scripts' );
}
add_action( 'wp_head', 'remove_fluidbox_frontend_scripts' );

/**
 * Register FluidBox frontend child scripts
 */
function fluidbox_frontend_child_scripts() {

    ob_start();
    ?>

    <script>
        (function ( $ ) {
            "use strict";

            $(document).ready( function() {

                // Add rel attribute to image links
                $("a[href$='.jpg'], a[href$='.jpeg'], a[href$='.gif'], a[href$='.png']").has("img").attr("rel", "fluidbox");

                // Enhancement for WP Galleries
                $('.gallery-item').addClass('gallery-item-fluidbox');
            });

            $(function () {

                // Init fluidbox
                $('a[rel="fluidbox"]').fluidbox({
                    overlayColor: 'rgba( 247, 247, 247, 0.95 )',
                    closeTrigger: [
                        { selector: 'window', event: 'scroll'}
                    ]
                });

            });

        }(jQuery));
    </script>

    <?php

    $out = ob_get_clean();
    echo $out;
}
add_action( 'wp_head', 'fluidbox_frontend_child_scripts' );


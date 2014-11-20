<?php
/**
 * The template for displaying pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

define('DEBUG', true);

$category = 1;

if (isset($_GET['category']) && $_GET['category'] > 0) {
  $category = $_GET['category'];
}

/**
* fetch posts by category
*/
query_posts('cat=' . $category);


/**
* render page
*/
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

<?php
if (DEBUG) {
   $categories = array(
     'Activities' => 3,
     'Our Brand' => 1,
     'Toolbox' => 2,
   );

   $category = $categories['Activities'];

  // @FIXME -- DEBUGGING MENU
  echo '<div id="debug-bar" style="position:absolute;top:200px;z-index:100;right:0;width:100%;text-align:center;">';
  echo '<h3><ul id="debug-menu">';
  foreach($categories as $category_name => $category_id) {
    echo sprintf('<li style="display:inline-block;padding:0 1em;"><a href="?category=%d">%s</a></li>', $category_id, $category_name);
  }
  echo '</ul></h3></div>';
  // END DEBUGGING
}

?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
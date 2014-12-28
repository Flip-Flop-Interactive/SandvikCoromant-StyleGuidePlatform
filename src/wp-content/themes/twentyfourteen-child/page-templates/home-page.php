<?php
/**
 * Template Name: Home Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

?>


<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script>(function(){document.documentElement.className='js'})();</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div style="position: fixed; width: 100%; height: 100%;" data-vide-bg="mp4: <?php echo dirname( get_bloginfo( 'stylesheet_url' )); ?>/videos/background-video, ogv: <?php echo dirname( get_bloginfo( 'stylesheet_url' )); ?>/videos/background-video, webm: <?php echo dirname( get_bloginfo( 'stylesheet_url' )); ?>/videos/background-video, poster: <?php echo dirname( get_bloginfo( 'stylesheet_url' )); ?>/images/background-video" data-vide-options="loop: true, muted: true, position: 0% 0%, posterType: jpg, resize: true"></div>

	<div id="menu">
		<div class="modal-dialog">
			<div class="container menu-header">
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
						<a href="/" rel="home" title="<?php bloginfo( 'name' ); ?>"><div class="logo"><i class="icon icon_sandvik-coromant-icon"></i></div></a>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3"></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><a href="/?s=" title="Search"><i class="icon icon_search-icon"></i></a></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"><a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Information' ))); ?>" title="Information"><i class="icon icon_info-icon"></i></a></div>
					<div class="col-lg-2 col-md-2 hidden-sm hidden-xs"></div>
				</div>
			</div>
			<?php echo render_menu(); ?>
		</div>
	</div>

	<?php wp_footer(); ?>

</body>
</html>

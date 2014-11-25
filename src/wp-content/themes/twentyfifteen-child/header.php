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

	<!-- Modal: Search -->
	<div class="modal fade" id="search" role="dialog" aria-hidden="true"></div>

	<!-- Modal: Menu -->
	<div class="modal fade" id="menu" role="dialog" aria-hidden="true">
		
		<div class="container">
			<div class="row">
				<div class="col-md-2">
					<div class="logo"><a href="/" class="logotype" rel="home" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'name' ); ?></a></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4">
					<h1>Our Brand</h1>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4">
					<h1>Toolbox</h1>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4">
					<h1>Activities</h1>
				</div>
			</div>

				<?php
					// global $category, $categories;

					// /**
					// * category menu
					// */
					// foreach($categories as $category_info) {
					//   $selected = "";
					//   if ($category_info->term_id == $category) {
					//     $selected = 'active';
					//   }
					//   $link = get_category_link( $category_info->term_id );
					  
					//   echo sprintf('<div class="col-md-2 category-link"><a href="%s" class="%s">%s</a></div>', $link, $selected, $category_info->name);
					// }
				?>
				
			</div>
		</div>
	

	</div>

	<header id="header" class="site-header headroom" role="banner">
		<div class="container">
			<div class="row">
				<div class="col-md-2">
					<div class="logo"><a href="/" class="logotype" rel="home" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'name' ); ?></a></div>
				</div>
				<div class="col-md-2"></div>
				<div class="col-md-2"><a href="/" title="Search" data-toggle="modal" data-target="#search"><i class="icon icon_search-icon"></i></a></div>
				<div class="col-md-2"><a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Info' ) ) ); ?>" title="Info"><i class="icon icon_info-icon"></i></a></div>
				<div class="col-md-2"><a href="/" title="Menu" data-toggle="modal" data-target="#menu"><i class="icon icon_menu-icon"></i></a></div>

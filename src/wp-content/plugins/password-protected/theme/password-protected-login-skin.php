<?php

/**
 * Based roughly on wp-login.php @revision 19414
 * http://core.trac.wordpress.org/browser/trunk/wp-login.php?rev=19414
 */

global $wp_version, $Password_Protected, $error, $is_iphone;

nocache_headers();
header( 'Content-Type: ' . get_bloginfo( 'html_type' ) . '; charset=' . get_bloginfo( 'charset' ) );

// Set a cookie now to see if they are supported by the browser.
setcookie( TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN );
if ( SITECOOKIEPATH != COOKIEPATH ) {
	setcookie( TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN );
}

// If cookies are disabled we can't log in even with a valid password.
if ( isset( $_POST['testcookie'] ) && empty( $_COOKIE[ TEST_COOKIE ] ) ) {
	$Password_Protected->errors->add( 'test_cookie', __( "<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href='http://www.google.com/cookies.html'>enable cookies</a> to use WordPress.", 'password-protected' ) );
}

// Obey privacy setting
add_action( 'password_protected_login_head', 'noindex' );

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>

<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php echo apply_filters( 'password_protected_wp_title', get_bloginfo( 'name' ) ); ?></title>

<?php

if ( version_compare( $wp_version, '3.9-dev', '>=' ) ) {
	wp_admin_css( 'login', true );
} else {
	wp_admin_css( 'wp-admin', true );
	wp_admin_css( 'colors-fresh', true );
}

if ( $is_iphone ) {
	?>
	<meta name="viewport" content="width=320; initial-scale=0.9; maximum-scale=1.0; user-scalable=0;" />
	<style type="text/css" media="screen">
	.login form, .login .message, #login_error { margin-left: 0px; }
	.login #nav, .login #backtoblog { margin-left: 8px; }
	.login h1 a { width: auto; }
	#login { padding: 20px 0; }
	</style>
	<?php
}

do_action( 'login_enqueue_scripts' );
do_action( 'password_protected_login_head' );

?>

<?php wp_head(); ?>

</head>

<body class="login login-password-protected login-action-password-protected-login wp-core-ui" <?php echo get_featured_image_as_background( get_page_by_title( 'Home' )->ID ); ?>>

	<header id="header" class="site-header headroom" role="banner">
		<div class="container">
			<div class="row">
				<div class="col-md-2">
					<div class="logo"><a href="/" rel="home" title="<?php bloginfo( 'name' ); ?>"><i class="icon icon_sandvik-coromant-icon"></i></a></div>
				</div>
				<div class="col-md-2"></div>
				<div class="col-md-2"></div>
				<div class="col-md-2"></div>
				<div class="col-md-2"></div>
			</div>
		</div>
	</header>

	<div id="page" class="hfeed site">
		<div id="content" class="site-content">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<div class="container">
						<div class="row">
							<div class="col-md-4">
								
								<h1>Welcome</h1>

							</div>
							<div class="col-md-6">

								<h1>Sum vent, culpa aut es rem quo vol- laboria dunt prerectorem volupta dis- seditis a simincit auda verrovi duciat aspicabo. Abore voluptatus, et quidu- ciatem.</h1>
							
								<div id="login">
									<?php do_action( 'password_protected_before_login_form' ); ?>
									<form name="loginform" id="loginform" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">
										<input type="password" name="password_protected_pwd" id="password_protected_pass" class="input" value="" placeholder="Log in here." size="20" tabindex="20" /></label>
										<input type="hidden" name="testcookie" value="1" />
										<input type="hidden" name="password-protected" value="login" />
										<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $_REQUEST['redirect_to'] ); ?>" />
									</form>
									<?php do_action( 'password_protected_after_login_form' ); ?>
								</div>

								<?php do_action( 'password_protected_login_messages' ); ?>

							</div>
						</div>
					</div>

				</div>
			</div>
	    </div>
	</div>

<script type="text/javascript">
try{document.getElementById('password_protected_pass').focus();}catch(e){}
if(typeof wpOnload=='function')wpOnload();
</script>

<?php do_action( 'login_footer' ); ?>

<div class="clear"></div>

<?php wp_footer(); ?>

</body>
</html>
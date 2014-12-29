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

<body class="login login-password-protected login-action-password-protected-login wp-core-ui">

	<div style="position: fixed; width: 100%; height: 100%;" data-vide-bg="mp4: <?php echo dirname( get_bloginfo( 'stylesheet_url' )); ?>/videos/background-video, ogv: <?php echo dirname( get_bloginfo( 'stylesheet_url' )); ?>/videos/background-video, webm: <?php echo dirname( get_bloginfo( 'stylesheet_url' )); ?>/videos/background-video, poster: <?php echo dirname( get_bloginfo( 'stylesheet_url' )); ?>/images/background-video" data-vide-options="loop: true, muted: true, position: 0% 0%, posterType: jpg"></div>

	<div id="menu">
		<div class="modal-dialog">
			
			<div class="container menu-header">
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
						<a href="/" rel="home" title="<?php bloginfo( 'name' ); ?>"><div class="logo"><i class="icon icon_sandvik-coromant-icon"></i></div></a>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
				</div>
			</div>

			<!-- Manage the content of this page within pages section in admin, title: "Welcome" -->

			<div class="container">
				<hr/>
				<div class="row">
					<div class="col-lg-4 col-md-8 col-sm-10">
						<h1><?php echo get_post( 2 )->post_title; ?></h1>
					</div>
					<div class="col-lg-6 col-md-8 col-sm-10">
						<h1><?php echo get_post( 2 )->post_content; ?></h1>
						<div id="login">
							<h1 id="call_to_action">Log in here.</h1>
							<?php do_action( 'password_protected_before_login_form' ); ?>
							<form name="loginform" id="loginform" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post" autocomplete="off">
								<input type="password" name="password_protected_pwd" id="password_protected_pass" class="input" value="" size="20" tabindex="20" /></label>
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

<script type="text/javascript">

var call_to_action 			= document.getElementById( 'call_to_action' );
var password_protected_pass = document.getElementById( 'password_protected_pass' );
var login_error				= document.getElementById( 'login_error' );

call_to_action.onclick = function(){
	call_to_action.style.display 			= 'none';
	password_protected_pass.style.display 	= 'block';
	password_protected_pass.focus();

	if( login_error ){

		login_error.style.display 			= 'none';
	}
};

if( typeof wpOnload == 'function' ){
	wpOnload();
}

</script>

<?php do_action( 'login_footer' ); ?>

<div class="clear"></div>

<?php wp_footer(); ?>

</body>
</html>
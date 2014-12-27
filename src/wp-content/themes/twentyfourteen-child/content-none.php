<?php
/**
 * The template for displaying a "No posts found" message
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<div class="container">
				<hr/>
				<div class="row">
					<div class="col-md-10">
						<h1>Ready to publish your first post?</h1>
					</div>
				</div>
			</div>

	<?php elseif ( is_search() ) : ?>

			<div class="container">
				<hr/>
				<div class="row">
					<div class="col-md-10">
						<h1>No results found.<br/>Try another key word.</h1>
					</div>
				</div>
			</div>

	<?php else : ?>

			<div class="container">
				<hr/>
				<div class="row">
					<div class="col-md-10">
						<h1>It seems we can't find what you're looking for.</h1>
					</div>
				</div>
			</div>

	<?php endif; ?>

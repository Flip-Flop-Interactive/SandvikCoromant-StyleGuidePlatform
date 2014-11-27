<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
    </div>
	</div><!-- .site-content -->

	<?php
		foreach( get_categories() as $all_cat ){ $cat_ids[] = $all_cat->term_id; }
		$this_cat = get_query_var( 'cat' );
		$this_cat_position = array_search( $this_cat, $cat_ids );
	?>

	<footer id="footer" class="site-footer headroom" role="contentinfo">
		<div class="container">
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-2"></div>
				<div class="col-md-2">
					
				<?php 
					$prev_cat_position = $this_cat_position -1;
					if( $prev_cat_position >= 0 ){ $prev_cat_id = array_slice( $cat_ids, $prev_cat_position, 1 );
					echo '<a href="' . get_category_link( $prev_cat_id[ 0 ]) . '"><i class="icon icon_arrow-left-icon"></i> <label>' . get_category( $prev_cat_id[ 0 ])->name . '</label></a>'; }
				?>

				</div>
				<div class="col-md-2">
					
				<?php 
					$next_cat_position = $this_cat_position +1;
					if( $next_cat_position < count( $cat_ids )){ $next_cat_id = array_slice( $cat_ids, $next_cat_position, 1 );
					echo '<a href="' . get_category_link( $next_cat_id[ 0 ]) . '"><i class="icon icon_arrow-right-icon"></i> <label>' . get_category( $next_cat_id[ 0 ])->name . '</label></a>'; }
				?>

				</div>
				<div class="col-md-2"><a href="/" title="To the top"><i class="icon icon_arrow-top-icon"></i> <label>To the top</label></a></div>
			</div>
		</div>
	</footer>
	<?php wp_footer(); ?>
</body>
</html>

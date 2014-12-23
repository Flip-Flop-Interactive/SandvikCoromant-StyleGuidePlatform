<?php

// find index of $current_page_parent in $pages
$pager_data = sandvik_get_top_level_pager();

?>
			</div>
	</div><!-- .site-content -->

	<footer id="footer" class="site-footer headroom" role="contentinfo">
		<div class="container">
			<hr/>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-5"></div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-5"></div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-5"><?php echo render_prev_link(); ?></div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-5"><?php echo render_next_link(); ?></div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-5"><a href="/" title="To the top" id="scroll_to_top"><i class="icon icon_arrow-top-icon"></i> <span class="label">To the top</span></a></div>
			</div>
		</div>
	</footer>

	<?php wp_footer(); ?>
	
</body>
</html>

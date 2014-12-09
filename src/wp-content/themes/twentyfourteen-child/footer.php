<?php

// find index of $current_page_parent in $pages
$pager_data = sandvik_get_top_level_pager();

?>
	    </div>
	</div><!-- .site-content -->

	<footer id="footer" class="site-footer headroom" role="contentinfo">
		<div class="container">
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-2"></div>
				<div class="col-md-2"><?php 
				  if (!empty($pager_data) && !empty($pager_data['previous']) && !empty($pager_data['previous']['post_title'])) {
            $link = $pager_data['previous'];
            $permalink = get_permalink($link['post_id']);
            $title = $link['post_title'];
					  echo sprintf('<a href="%s"><i class="icon icon_arrow-left-icon"></i> <label>%s</label></a>', $permalink, $title);
				  }
				?></div>
				<div class="col-md-2"><?php 
  				  if (!empty($pager_data) && !empty($pager_data['next']) && !empty($pager_data['next']['post_title'])) {
              $link = $pager_data['next'];
              $permalink = get_permalink($link['post_id']);
              $title = $link['post_title'];
  					  echo sprintf('<a href="%s"><i class="icon icon_arrow-right-icon"></i> <label>%s</label></a>', $permalink, $title);
  				  }
				?></div>
				<div class="col-md-2"><a href="/" title="To the top"><i class="icon icon_arrow-top-icon"></i> <label>To the top</label></a></div>
			</div>
		</div>
	</footer>
	<?php wp_footer(); ?>
</body>
</html>

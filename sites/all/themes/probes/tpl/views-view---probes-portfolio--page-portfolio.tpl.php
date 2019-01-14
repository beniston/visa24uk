<?php
	if(isset($_GET['style'])) {
		$portfolio_style = $_GET['style'];
	} else $portfolio_style = theme_get_setting('portfolio_style', 'probes');
	if(empty($portfolio_style)) $portfolio_style = '2cols';
?>
<?php 
	if ($portfolio_style == '2cols'):
		print views_embed_view('_probes_portfolio','block_portfolio_2columns');
	elseif ($portfolio_style == '3cols'):
		print views_embed_view('_probes_portfolio','block_portfolio_3columns');
	elseif ($portfolio_style == '4cols' || $portfolio_style == 'sidebar'):
		print views_embed_view('_probes_portfolio','block_portfolio_4columns');
	elseif ($portfolio_style == 'fullwidth'):
		print views_embed_view('_probes_portfolio','block_portfolio_fullwidth');
	elseif ($portfolio_style == 'masonry1'):
		print views_embed_view('_probes_portfolio','block_portfolio_masonry_style1');
	elseif ($portfolio_style == 'masonry2'):
		print views_embed_view('_probes_portfolio','block_portfolio_masonry_style2');
	else:
		print views_embed_view('_probes_portfolio','block_portfolio_slider');
	endif;
?>
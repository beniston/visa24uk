<?php
	$page_slogan = theme_get_setting('portfolio_slogan', 'probes');
	if(empty($page_slogan)) $page_slogan = variable_get('site_slogan');
	$bg_page = theme_get_setting('portfolio_title_background','probes');
	if(!empty($bg_page)) {
		$bg_page = file_create_url(file_load($bg_page)->uri);
	} else $bg_page = '';
	$header_layout = theme_get_setting('portfolio_header_layout', 'probes');
	$header_class = theme_get_setting('portfolio_header_class', 'probes');
	$topbar = theme_get_setting('portfolio_topbar_enable', 'probes');
	$topbar_class = theme_get_setting('portfolio_topbar_class', 'probes');
	$topbar_background_color = theme_get_setting('portfolio_topbar_background_color', 'probes');
	$footer_layout = theme_get_setting('portfolio_footer_layout', 'probes');
	$footer_class = theme_get_setting('page_default_footer_class', 'probes');
	$footer_bg_img = theme_get_setting('portfolio_footer_background_image','probes');
	if(!empty($footer_bg_img)) {
		$footer_bg_img = file_create_url(file_load($footer_bg_img)->uri);
	} else $footer_bg = '';
	$footer_bg_color = theme_get_setting('portfolio_footer_backgorund', 'probes');
	$footer_image = theme_get_setting('portfolio_footer_backgorund', 'probes');
	$page_header_class = theme_get_setting('portfolio_page_header_class', 'probes');
	if(isset($_GET['style'])) {
		$portfolio_style = $_GET['style'];
	} else $portfolio_style = theme_get_setting('portfolio_style', 'probes');
	if(empty($portfolio_style)) $portfolio_style = '2cols';
?>
<?php require_once(drupal_get_path('theme','probes').'/tpl/header.tpl.php');?>
<div class="page-header <?php print $page_header_class; ?>" <?php if($bg_page) print 'style="background:url('.$bg_page.') no-repeat scroll center top;background-size: cover;" '; ?>>
	<div class="container">
		<div class="col-md-6 left-padd0">
			<h3 class="font30 nomargin white uppercase"><?php print drupal_get_title(); ?></h3>
			<?php if($page_slogan && !strpos($page_header_class, 'noslogan')): ?>
			<h4 class="font18 white font-thin"><?php print $page_slogan; ?></h4>
			<?php endif; ?>
		</div>
		<div class="col-md-6">
		<?php if($breadcrumb): ?>
			<div class="pagenation <?php if(strpos($page_header_class, 'noslogan')) print 'one'; ?>">
				<?php print $breadcrumb; ?>
			</div>
		<?php endif; ?>
		</div>
	</div>
</div>
<!-- end page header -->
<?php if($page['content'] && $portfolio_style != 'fullwidth'): ?>
<div class="section-lg <?php if($portfolio_style != 'masonry1' && $portfolio_style != 'masonry2' && $portfolio_style != 'slider') print 'one'; ?>">	
  	<div class="container">
	<?php if($portfolio_style != 'sidebar'): ?>
		<?php
			if (!empty($tabs['#primary']) || !empty($tabs['#secondary'])):
			print render($tabs);
			endif;
			print $messages;
		?>
		<?php print render($page['content']); ?>
	<?php else: ?>
		<div class="row">
			<div class="col-md-9">
				<?php
					if (!empty($tabs['#primary']) || !empty($tabs['#secondary'])):
					print render($tabs);
					endif;
					print $messages;
				?>
				<?php print render($page['content']); ?>
			</div>
			<?php if($page['sidebar']): ?>
			<div class="col-md-3">
				<?php print render($page['sidebar']); ?>
			</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
  	</div>
</div>
<?php elseif($page['content']): ?>
<div class="section-lg">
	<?php
		if (!empty($tabs['#primary']) || !empty($tabs['#secondary'])):
		print render($tabs);
		endif;
		print $messages;
	?>
	<?php print render($page['content']); ?>
</div>
<?php endif; ?>
<?php if($page['section']): ?>
	<?php print render($page['section']); ?>	
<?php endif; ?>
<?php require_once(drupal_get_path('theme','probes').'/tpl/footer.tpl.php');?>
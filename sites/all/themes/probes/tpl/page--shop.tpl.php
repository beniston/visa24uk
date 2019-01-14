<?php
	$shop_slogan = theme_get_setting('shop_slogan', 'probes');
	$bg_page = theme_get_setting('shop_background_breadcrumb','probes');
	if(!empty($bg_page)) {
		$bg_page = file_create_url(file_load($bg_page)->uri);
	} else $bg_page = '';
	$page_header_class = theme_get_setting('shop_page_header_class', 'probes');
	$header_layout = theme_get_setting('shop_header_layout', 'probes');
	$header_class = theme_get_setting('shop_header_class', 'probes');
	$topbar = theme_get_setting('shop_topbar_enable', 'probes');
	$topbar_class = theme_get_setting('shop_topbar_class', 'probes');
	$topbar_background_color = theme_get_setting('shop_topbar_background_color', 'probes');
	$footer_layout = theme_get_setting('shop_footer_layout', 'probes');
	$footer_class = theme_get_setting('shop_footer_class', 'probes');
	$footer_bg_img = theme_get_setting('shop_footer_background_image','probes');
	if(!empty($footer_bg_img)) {
		$footer_bg_img = file_create_url(file_load($footer_bg_img)->uri);
	} else $footer_bg = '';
	$footer_bg_color = theme_get_setting('blog_footer_backgorund_color', 'probes');
	$footer_image = theme_get_setting('blog_footer_backgorund', 'probes');
?>
<?php require_once(drupal_get_path('theme','probes').'/tpl/header.tpl.php');?>
<div class="page-header seven <?php print $page_header_class; ?>" <?php if($bg_page) print 'style="background:url('.$bg_page.') no-repeat scroll center top;"'; ?>>
	<div class="container">
		<div class="col-md-6 left-padd0">
			<h3 class="font30 nomargin uppercase"><?php print drupal_get_title(); ?></h3>
		<?php if(!empty($shop_slogan)): ?>
			<h4 class="font18 font-thin"><?php print $shop_slogan; ?></h4>
		<?php endif; ?>
		</div>
		<div class="col-md-6">
		<?php if($breadcrumb): ?>
			<div class="pagenation">
				<?php print $breadcrumb; ?>
			</div>
		<?php endif; ?>
		</div>
	</div>
</div>
<?php if($page['content']): ?>
<div class="section-lg">
	<div class="container">
		<?php
			if (!empty($tabs['#primary']) || !empty($tabs['#secondary'])):
				print render($tabs);
			endif;
			print $messages;
			unset($page['content']['system_main']['default_message']);
		?>
		<?php print render($page['content']); ?>
	</div>
</div>
<?php endif; ?>
<?php if($page['section']): ?>
	<?php print render($page['section']); ?>	
<?php endif; ?>
<?php require_once(drupal_get_path('theme','probes').'/tpl/footer.tpl.php');?>
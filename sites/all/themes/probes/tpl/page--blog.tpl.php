<?php
	$blog_slogan = theme_get_setting('blog_slogan', 'probes');
	$bg_page = theme_get_setting('blog_background_breadcrumb','probes');
	if(!empty($bg_page)) {
		$bg_page = file_create_url(file_load($bg_page)->uri);
	} else $bg_page = '';
	if(isset($_GET['style'])) {
		$blog_layout = $_GET['style'];
	} else $blog_layout =theme_get_setting('blog_layout_style', 'probes');
	if(empty($blog_layout) || isset($node)) $blog_layout = 'standard';
	$header_layout = theme_get_setting('blog_header_layout', 'probes');
	$header_class = theme_get_setting('blog_header_class', 'probes');
	$topbar = theme_get_setting('blog_topbar_enable', 'probes');
	$topbar_class = theme_get_setting('blog_topbar_class', 'probes');
	$topbar_background_color = theme_get_setting('blog_topbar_background_color', 'probes');
	$footer_layout = theme_get_setting('blog_footer_layout', 'probes');
	$footer_class = theme_get_setting('blog_footer_class', 'probes');
	$footer_bg_img = theme_get_setting('blog_footer_background_image','probes');
	if(!empty($footer_bg_img)) {
		$footer_bg_img = file_create_url(file_load($footer_bg_img)->uri);
	} else $footer_bg = '';
	$footer_bg_color = theme_get_setting('blog_footer_backgorund_color', 'probes');
	$footer_image = theme_get_setting('blog_footer_backgorund', 'probes');
	$page_header_class = theme_get_setting('blog_page_header_class', 'probes');
?>
<?php require_once(drupal_get_path('theme','probes').'/tpl/header.tpl.php');?>
<div class="page-header <?php print $page_header_class; ?>" <?php if($bg_page) print 'style="background:url('.$bg_page.') no-repeat scroll center top;" '; ?>>
	<div class="container">
		<div class="col-md-6 left-padd0">
			<h3 class="font30 nomargin white uppercase"><?php print drupal_get_title(); ?></h3>
			<h4 class="font18 white font-thin"><?php print $blog_slogan; ?></h4>
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
<!-- end page header -->
<?php if($blog_layout == 'fullwidth' || $blog_layout == '3cols'): ?>
	<?php if($page['content']): ?>
	<div class="section-lg">
		<div class="container">
			<div class="row">
			<?php
				if (!empty($tabs['#primary']) || !empty($tabs['#secondary'])):
					print render($tabs);
				endif;
				print $messages;
				unset($page['content']['system_main']['default_message']);
			?>
			<?php unset($page['content']['system_main']['pager']); ?>
			<?php print render($page['content']); ?>
			<?php if(theme('pager')): ?>
				<?php
					$pager = str_replace('‹ previous', ' ‹ ', theme('pager'));
					$pager = str_replace('« first', ' « ', $pager);
					$pager = str_replace('last »', ' » ', $pager);
					$pager = str_replace('next ›', ' › ', $pager);
				?>
				<div class="pagenation-blog">
					<?php print $pager; ?>
				</div>
			<?php endif; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
<?php else: ?>
<div class="section-lg one">
  	<div class="container">
    	<div class="row">
		<?php if($page['content']): ?>
			<div class="col-md-9">
				<?php
					if (!empty($tabs['#primary']) || !empty($tabs['#secondary'])):
						print render($tabs);
					endif;
					print $messages;
				?>
				<?php unset($page['content']['system_main']['pager']); ?>
				<?php print render($page['content']); ?>
			<?php if(theme('pager')): ?>
				<?php
					$pager = str_replace('‹ previous', ' ‹ ', theme('pager'));
					$pager = str_replace('« first', ' « ', $pager);
					$pager = str_replace('last »', ' » ', $pager);
					$pager = str_replace('next ›', ' › ', $pager);
				?>
				<div class="pagenation-blog">
					<?php print $pager; ?>
				</div>
			<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if($page['sidebar']): ?>
			<div class="col-md-3">
				<?php print render($page['sidebar']); ?>
			</div>
		<?php endif; ?>
    	</div>
    </div>
</div>
<?php endif; ?>
<?php if($page['section']): ?>
	<?php print render($page['section']); ?>	
<?php endif; ?>
<?php require_once(drupal_get_path('theme','probes').'/tpl/footer.tpl.php');?>
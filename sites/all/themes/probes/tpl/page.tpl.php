<?php
	if(isset($node) && !empty($node->body['und'][0]['summary']) && $node->type == 'page') {
		$page_slogan = $node->body['und'][0]['summary'];
	} else $page_slogan = variable_get('site_slogan');
	if(isset($node->field_page_header_background) && !empty($node->field_page_header_background)) {
		$bg_page = file_create_url($node->field_page_header_background['und'][0]['uri']);
	} else {
		$bg_page = theme_get_setting('page_title_background','probes');
		if(!empty($bg_page)) {
			$bg_page = file_create_url(file_load($bg_page)->uri);
		} else $bg_page = '';
	}
	$header_layout = theme_get_setting('page_default_header_layout', 'probes');
	$header_class = theme_get_setting('page_default_header_class', 'probes');
	$topbar = theme_get_setting('page_default_topbar_enable', 'probes');
	$topbar_class = theme_get_setting('page_default_topbar_class', 'probes');
	$topbar_background_color = theme_get_setting('page_default_topbar_background_color', 'probes');
	$footer_layout = theme_get_setting('page_default_footer_layout', 'probes');
	$footer_class = theme_get_setting('page_default_footer_class', 'probes');
	$footer_bg_img = theme_get_setting('page_default_footer_background_image','probes');
	if(!empty($footer_bg_img)) {
		$footer_bg_img = file_create_url(file_load($footer_bg_img)->uri);
	} else $footer_bg = '';
	$footer_bg_color = theme_get_setting('page_default_footer_backgorund_color', 'probes');
	$footer_image = theme_get_setting('page_default_footer_backgorund', 'probes');
	if(isset($node->field_page_header_class	) && !empty($node->field_page_header_class	)) {
		$page_header_class = $node->field_page_header_class['und'][0]['value'];
	} else $page_header_class = theme_get_setting('page_default_page_header_class', 'probes');
	if(isset($node->field_sidebar) && !empty($node->field_sidebar)) {
		$sidebar = $node->field_sidebar['und'][0]['value'];
	} else $sidebar = 'fullwidth';
	if(isset($node->field_content_class) && !empty($node->field_content_class)) {
		$content_class = $node->field_content_class['und'][0]['value'];
	} else $content_class = '';
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
<?php if($page['page_section']): ?>
	<?php print render($page['page_section']); ?>	
<?php endif; ?>
<?php if(isset($node) && !empty($node->body['und'][0]['value'])): ?>
<div class="section-lg <?php print $content_class; ?>">
  	<div class="container">
    	<div class="row">	
		<?php if($sidebar =='fullwidth'): ?>
			<?php
				if (!empty($tabs['#primary']) || !empty($tabs['#secondary'])):
					print render($tabs);
				endif;
				print $messages;
			?>
			<?php print render($page['content']); ?>
		<?php elseif($sidebar == 'sidebar'): ?>
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
		<?php else: ?>
			<?php if($page['sidebar']): ?>
			<div class="col-md-3">
				<?php print render($page['sidebar']); ?>
			</div>
			<?php endif; ?>
			<div class="col-md-9">
			<?php
				if (!empty($tabs['#primary']) || !empty($tabs['#secondary'])):
					print render($tabs);
				endif;
				print $messages;
			?>
			<?php unset($page['content']['system_main']['pager']); ?>
			<?php print render($page['content']); ?>
			</div>
		<?php endif; ?>
		</div>
	</div>
</div>
<?php elseif(isset($node) && empty($node->body['und'][0]['value'])): ?>
	<?php
		if (!empty($tabs['#primary']) || !empty($tabs['#secondary'])):
		print render($tabs);
		endif;
		print $messages;
	?>
	<?php unset($page['content']['system_main']['pager']); ?>
	<?php print render($page['content']); ?>
<?php else: ?>
<div class="section-lg system">
  	<div class="container">	
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
</div>
<?php endif; ?>
<?php if($page['section']): ?>
	<?php print render($page['section']); ?>	
<?php endif; ?>
<?php require_once(drupal_get_path('theme','probes').'/tpl/footer.tpl.php');?>
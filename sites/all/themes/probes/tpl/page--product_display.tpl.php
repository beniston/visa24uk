<?php
	$shop_slogan = theme_get_setting('shop_slogan', 'probes');
	$bg_page = theme_get_setting('shop_background_breadcrumb','probes');
	if(!empty($bg_page)) {
		$bg_page = file_create_url(file_load($bg_page)->uri);
	} else $bg_page = '';
	if(isset($node->field_sidebar) && !empty($node->field_sidebar)) {
		$sidebar = $node->field_sidebar['und'][0]['value'];
	} else $sidebar = 'fullwidth';
	$page_header_class = theme_get_setting('shop_page_header_class', 'probes');
?>
<?php require_once(drupal_get_path('theme','probes').'/tpl/header.tpl.php');?>
<div class="page-header <?php print $page_header_class; ?>" <?php if($bg_page) print 'style="background:url('.$bg_page.') no-repeat scroll center top;"'; ?>>
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
<?php if($sidebar == 'fullwidth'): ?>
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
<?php elseif($sidebar == 'sidebar'): ?>
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
				<?php print render($page['content']); ?>
			</div>
		<?php endif; ?>
		<?php if($page['sidebar']): ?>
			<div class="col-md-3 nophone">
				<?php print render($page['sidebar']); ?>
			</div>
		<?php endif; ?>
		</div>
	</div>
</div>
<?php else: ?>
<div class="section-lg one">
  	<div class="container">
    	<div class="row">
		<?php if($page['sidebar']): ?>
			<div class="col-md-3 nophone">
				<?php print render($page['sidebar']); ?>
			</div>
		<?php endif; ?>
		<?php if($page['content']): ?>
			<div class="col-md-9">
			<?php
				if (!empty($tabs['#primary']) || !empty($tabs['#secondary'])):
					print render($tabs);
				endif;
				print $messages;
			?>
				<?php print render($page['content']); ?>
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
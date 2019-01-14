<?php
	if(isset($node->field_footer_layout) && !empty($node->field_footer_layout)) {
		$footer_layout = $node->field_footer_layout['und'][0]['value'];
	} else $footer_layout = 'layout5';
	if(isset($node->field_menu_style) && !empty($node->field_menu_style)) {
		$menu_style = $node->field_menu_style['und'][0]['value'];
	} else $menu_style = 'style1';
?>
<?php if($menu_style != 'style3'): ?>
	<?php require_once(drupal_get_path('theme','probes').'/tpl/header-onepage.tpl.php');?>
<?php endif; ?>
<div class="skroll-content">
	<?php
	if (!empty($tabs['#primary']) || !empty($tabs['#secondary'])):
		print render($tabs);
	endif;
	print $messages;
	unset($page['content']['system_main']['default_message']);
	?>
	<?php print render($page['content']); ?>
	<?php if($page['slider']): ?>
  		<?php print render($page['slider']); ?>
	<?php endif; ?>
	<?php if($menu_style == 'style3'): ?>
		<?php require_once(drupal_get_path('theme','probes').'/tpl/header-onepage.tpl.php');?>
	<?php endif; ?>
	<?php if($page['section_one']): ?>
		<?php print render($page['section_one']); ?>	
	<?php endif; ?>
	<section>
		<?php require_once(drupal_get_path('theme','probes').'/tpl/footer.tpl.php');?>
	</section>
</div>

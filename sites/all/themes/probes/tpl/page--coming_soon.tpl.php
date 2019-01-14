<?php
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
	if(isset($node->field_coming_style	) && !empty($node->field_coming_style	)) {
		$coming_style = $node->field_coming_style['und'][0]['value'];
	} else $coming_style = 'style2';
	if(isset($node->field_images) && !empty($node->field_images)) {
		$bg_page = file_create_url($node->field_images['und'][0]['uri']);
	}
	if(isset($node->field_content_class) && !empty($node->field_content_class)) {
		$content_class = $node->field_content_class['und'][0]['value'];
	} else $content_class = '';
	if(!empty($node->field_end_date)) {
		$date_time = $node->field_end_date['und'][0]['value'];
		$year = date("Y", strtotime($date_time));
		$month = date("n", strtotime($date_time));
		$day = date("j", strtotime($date_time));
		$hour = date("G", strtotime($date_time));
		$second = intval(date("s", strtotime($date_time)));
		$minutes = intval(date("i", strtotime($date_time)));
	}
?>
<?php if ($coming_style == 'style2'): ?>
	<?php require_once(drupal_get_path('theme','probes').'/tpl/header.tpl.php');?>
	<?php if($page['content']): ?>
	<div class="countdown-header <?php print $content_class; ?>" <?php if($bg_page) print 'style="background:url('.$bg_page.');"';?>>
  		<div class="container">
			<?php
				if (!empty($tabs['#primary']) || !empty($tabs['#secondary'])):
					print render($tabs);
				endif;
				print $messages;
			?>
			<?php print render($page['content']); ?>
			<?php if(isset($date_time)): ?>
			<input type="hidden" id="date_time" data-year="<?php print $year; ?>" data-month="<?php print $month; ?>" data-day="<?php print $day; ?>" data-hour="<?php print $hour; ?>" data-minutes="<?php print $minutes; ?>" data-second="<?php print $second; ?>">
			<?php endif; ?>
  		</div>
  	</div>
	<?php endif; ?>
	<?php require_once(drupal_get_path('theme','probes').'/tpl/footer.tpl.php');?>
<?php endif; ?>

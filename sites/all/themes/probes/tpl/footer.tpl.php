<?php global $base_url; ?>
<?php
	if (!isset($footer_layout) || empty($footer_layout)) {
		if (isset($node->field_footer_layout) && !empty($node->field_footer_layout)) {
			$footer_layout = $node->field_footer_layout['und'][0]['value'];
		} else $footer_layout = theme_get_setting('footer_layout', 'probes');
		if (empty($footer_layout)) $footer_layout = 'layout1';
	}
	if(!isset($footer_bg_img) || empty($footer_bg_img)) {
		if(isset($node->field_backgorund_image) && !empty($node->field_backgorund_image)) {
			$footer_bg_uri = $node->field_backgorund_image['und'][0]['uri'];
			$footer_bg_img = file_create_url($footer_bg_uri);
		} else {
			$footer_bg_img = theme_get_setting('footer_background_image','probes');
			if(!empty($footer_bg_img)) {
				$footer_bg_img = file_create_url(file_load($footer_bg_img)->uri);
			} else $footer_bg_img = '';
		}
	}
	
	if (!isset($footer_bg_color) || empty($footer_bg_color)) {
		if (isset($node->field_footer_background_color) && !empty($node->field_footer_background_color)) {
			$footer_bg_color = $node->field_footer_background_color['und'][0]['jquery_colorpicker'];
		} else $footer_bg_color = theme_get_setting('footer_backgorund_color', 'probes');
	}
	if(!isset($footer_image) || empty($footer_image)) {
		if(isset($node->field_footer_background) && !empty($node->field_footer_background)) {
			$footer_image = $node->field_footer_background['und'][0]['value'];
		} else $footer_image = theme_get_setting('footer_backgorund', 'probes');
	}		
	if(empty($footer_image)) $footer_image = 'off';
	if(!isset($footer_class) || empty($footer_class)) {
		if(isset($node->field_footer_class) && !empty($node->field_footer_class)) {
			$footer_class = $node->field_footer_class['und'][0]['value'];
		} else $footer_class = theme_get_setting('footer_class', 'probes');
	}	
	if(empty($footer_class)) $footer_class = '';
	if($footer_image == 'on') {
		$footer_class .= ' footer-bg';
	} else $footer_class .= ' footer-bg-color';
	if(!empty($footer_bg_color)) $footer_class .= ' background-color';
	$footer_copyright = theme_get_setting('footer_copyright_message', 'probes');

?>
<?php if($footer_layout == 'layout1'){ ?>
<footer class="<?php print $footer_class; ?>" <?php if($footer_image == 'on' && !empty($footer_bg_img)) print 'style="background:url('.$footer_bg_img.');"'; ?> <?php if(!empty($footer_bg_color)) print 'data-bg-color="#'.$footer_bg_color.'"'; ?>>
	<div class="container">
			<div class="row">
				<div class="col-md-3">
                    <?php if ($page['footer_first']):?>
                    <div class="footer-first">
                    <?php print render($page['footer_first']); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-3">
                    <?php if ($page['footer_second']):?>
                    <div class="footer-second">
                    <?php print render($page['footer_second']); ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-3">
                    <?php if ($page['footer_third']):?>
                    <div class="footer-third">
                    <?php print render($page['footer_third']); ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-3">
                    <?php if ($page['footer_fourth']):?>
                    <div class="footer-fourth">
                    <?php print render($page['footer_fourth']); ?>
                    </div>
                    <?php endif; ?>
                </div>																															
			</div>
		</div>
</footer>
	
	<?php if(!empty($footer_copyright)): ?>
	<div class="copyrights">
	    <div class="container">
	      	<div class="row">
	      		<div class="col-md-6 m-top1">
					<?php print $footer_copyright; ?>
	      		</div>
	      		<?php if($page['footer_copyright']): ?>
	      		<div class="col-md-6">
					<?php print render($page['footer_copyright']); ?>
	      		</div>
	      		<?php endif; ?>
	      	</div>
	    </div>
	</div>
	<?php endif; ?>
<?php } elseif($footer_layout == 'layout2'){ ?>
	<?php if($page['footer2']): ?>
	<footer class="bg-texture2 <?php print $footer_class; ?>" <?php if($footer_image == 'on' && !empty($footer_bg_img)) print 'style="background:url('.$footer_bg_img.');"'; ?>>
		<div class="container">
			<div class="row">
			<?php if($page['footer_header']): ?>
				<?php print render($page['footer_header']); ?>
			<?php endif; ?>
			<?php if($page['footer2']): ?>
				<?php print render($page['footer2']); ?>
			<?php endif; ?>
			</div>
		</div>
	</footer>
	<?php endif; ?>
	<?php if(!empty($footer_copyright)): ?>
	<div class="copyrights">
		<div class="container">
			<div class="row text-center">
			<?php if($page['footer_copyright']): ?>
				<?php print render($page['footer_copyright']); ?>
			<?php endif; ?>
				<p><?php print $footer_copyright; ?></p>
			</div>
		</div>
	</div>
	<?php endif; ?>
<?php } elseif($footer_layout == 'layout3') { ?>
	<?php if($page['footer3']): ?>
	<footer class="bg-texture1 <?php print $footer_class; ?>" <?php if($footer_image == 'on' && !empty($footer_bg_img)) print 'style="background:url('.$footer_bg_img.');"'; ?>>
		<div class="container">
			<div class="row">
			<?php if($page['footer_header']): ?>
				<?php print render($page['footer_header']); ?>
			<?php endif; ?>
			<?php if($page['footer3']): ?>
				<?php print render($page['footer3']); ?>
			<?php endif; ?>
			</div>
		</div>
	</footer>
	<?php endif; ?>
	<?php if(!empty($footer_copyright)): ?>
	<div class="copyrights one">
    	<div class="container text-center">
			<div class="row"> <?php print $footer_copyright; ?> </div>
    	</div>
    </div>
	<?php endif; ?>
<?php } elseif($footer_layout == 'layout4') { ?>
	<?php if($page['footer4'] || $page['footer_header']): ?>
	<footer class="<?php print $footer_class; ?>">
  		<div class="container">
    		<div class="row">
	    		<?php if($page['footer_header']): ?>
					<?php print render($page['footer_header']); ?>
				<?php endif; ?>
	    		<?php if($page['footer4']): ?>
					<?php print render($page['footer4']); ?>
				<?php endif; ?>
			</div>
		</div>
	</footer>
	<?php endif; ?>
	<?php if(!empty($footer_copyright)): ?>
	<div class="copyrights">
    	<div class="container">
			<div class="row">
				<div class="col-md-6 m-top1"><?php print $footer_copyright; ?></div>
				<?php if($page['footer_copyright']): ?>
				<div class="col-md-6 text-right">
					<?php print render($page['footer_copyright']); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
    </div>
	<?php endif; ?>
<?php } else { ?>
	<?php if($page['footer5'] || $page['footer_header']): ?>
	<div class="bg-texture1 <?php print $footer_class; ?>" <?php if($footer_image == 'on' && !empty($footer_bg_img)) print 'style="background:url('.$footer_bg_img.');"'; ?>>
		<div class="container">
			<div class="row">
				<?php if($page['footer_header']): ?>
				<?php print render($page['footer_header']); ?>
				<?php endif; ?>
				<?php if($page['footer5']): ?>
				<?php print render($page['footer5']); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php if($page['footer5_center']): ?>
		<?php print render($page['footer5_center']); ?>
	<?php endif; ?>
	<?php if(!empty($footer_copyright)): ?>
	<div class="copyrights one">
		<div class="container text-center">
			<div class="row"> <?php print $footer_copyright; ?> </div>
		</div>
	</div>
	<?php endif; ?>
	<?php endif; ?>
<?php } ?>

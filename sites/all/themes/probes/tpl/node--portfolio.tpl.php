<?php
	global $base_url;
	$image_style1 = 'image_130x80';
	$image_style2 = 'image_1170x500';
	$image_style3 = 'image_760x360';
	if(!empty($node->field_images)) {
		$n = count($node->field_images['und']);
	} else $n = 0;
?>
<?php if(!$page){ ?>
	<div class="cbp-item"> <a href="<?php print $node_url; ?>" class="cbp-caption" rel="nofollow">
		<div class="cbp-caption-defaultWrap"> <img src="<?php print image_style_url($image_style3, $node->field_images['und'][0]['uri']); ?>" alt="<?php print $title; ?>"> </div>
		<div class="cbp-caption-activeWrap"></div>
		</a> <a href="<?php print $node_url; ?>" class="cbp-l-grid-work-title cbp-singlePage" rel="nofollow"><?php print $title; ?></a>
	</div>
<?php } else { ?>
	<div class="carousel_holder">
	    <div id="sync1" class="owl-carousel">
	    <?php foreach ($node->field_images['und'] as $key => $value) {
	    	$image_big = image_style_url($image_style2, $node->field_images['und'][$key]['uri']);
	    ?>
	    	<div class="item fullwide"><img src="<?php print $image_big; ?>" alt="<?php print $node->field_images['und'][$key]['alt']; ?>"></div>
	    <?php } ?>
	    </div>
	    <div id="sync2" class="owl-carousel">
	    <?php foreach ($node->field_images['und'] as $key => $value) {
	    	$image_small = image_style_url($image_style1, $node->field_images['und'][$key]['uri']);
	    ?>
	    	<div class="item"><img src="<?php print $image_small; ?>" alt="<?php print $node->field_images['und'][$key]['alt']; ?>" /> </div>
	    <?php } ?>
	    </div>
	</div>
	<div>&nbsp;</div>
	<div class="cbp-l-project-container">
	    <div class="cbp-l-project-desc">
	        <div class="cbp-l-project-desc-title font20"><?php print t('Project Description'); ?></div>
	        <div class="cbp-l-project-desc-text">
	        <?php
				hide($content['links']);
				hide($content['field_tags']);
				hide($content['comments']);
				hide($content['field_portfolio_categories']);
				hide($content['field_images']);
				hide($content['field_topic']);
				hide($content['field_client']);
				hide($content['field_portfolio_layout']);
				hide($content['field_post_format']);
				hide($content['field_topic']);
				hide($content['field_link_site']);
				print render($content);
			?>
	        </div>
	    </div>
	    <div class="cbp-l-project-details">
	        <div class="cbp-l-project-details-title font20"><?php print t('Project Details'); ?></div>
	        <ul class="cbp-l-project-details-list">
	            <li><strong><?php print t('Client'); ?></strong><?php print $node->field_client['und'][0]['value']; ?></li>
	            <li><strong><?php print t('Date'); ?></strong><?php print format_date($created, 'custom', 'd F, Y') ?></li>
	            <li class="cats"><strong><?php print t('Categories'); ?></strong><?php print strip_tags(render($content['field_portfolio_categories']), '<a>'); ?></li>
	        </ul>
	        <a href="<?php print $node->field_link_site['und'][0]['value']; ?>" target="_blank" class="cbp-l-project-details-visit"><?php print t('visit the site'); ?></a>
	    </div>
	</div>

<?php } ?>
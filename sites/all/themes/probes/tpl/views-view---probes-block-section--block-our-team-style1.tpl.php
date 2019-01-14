<?php
	global $base_url;
	$rows = trim ($rows);
	$rows = str_replace(' ', '', $rows);
	$rows = explode ( ',' , $rows);
	$i = 0;
	$l = count($rows) - 1;
	$img_style = 'image_400x400';
?>
<div class="container">
<?php if($rows): ?>
	<div class="row">
	<?php if($header): ?>
		<div class="title1 text-center">
		 	<?php print $header; ?>
		</div>
	<?php endif; ?>
		<div class="col-md-12">
			<?php
			$j = 1;
			for ($i=0; $i < $l; $i++) {
				$nid = $rows[$i];
				$node = node_load($nid);
				$title = $node->title;
				$summary = $node->body['und'][0]['summary'];
				$summary = trim_text($summary, 150);
				if(!empty($node->field_position)) {
					$position = $node->field_position['und'][0]['value'];
				} else 	$position = '';			
				$img = image_style_url($img_style, $node->field_image['und'][0]['uri']);
				if(!empty($node->field_social_network)) {
					$social_network = $node->field_social_network['und'][0]['value'];
				} else 	$social_network = '';	
				
			?>
			<div class="col-md-6 team-list nopadding">
			<?php if($j%4 == 1 || $j%4 == 2): ?>
				<div class="col-md-6 imgbox team-image nopadding"> <img src="<?php print $img; ?>" alt="<?php print $title; ?>" class="img-responsive"> </div>
				<div class="col-md-6">
					<h2 class="font-black uppercase font20 m-top4"><?php print $title; ?></h2>
					<h5 class="blue"><?php print $position; ?></h5>
					<p><?php print $summary; ?></p>
					<?php print $social_network; ?>
				</div>
			<?php else: ?>
				<div class="col-md-6">
					<h2 class="font-black uppercase font20 m-top4"><?php print $title; ?></h2>
					<h5 class="blue"><?php print $position; ?></h5>
					<p><?php print $summary; ?></p>
					<?php print $social_network; ?>
				</div>
				<div class="col-md-6 imgbox team-image nopadding"> <img src="<?php print $img; ?>" alt="<?php print $title; ?>" class="img-responsive"> </div>
			<?php endif; ?>
			</div>
			<?php $j++; ?>
			<?php } ?>
		</div>
	</div>
<?php endif; ?>
</div>


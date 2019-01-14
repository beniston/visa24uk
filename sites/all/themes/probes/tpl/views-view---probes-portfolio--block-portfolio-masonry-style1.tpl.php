<?php
	global $base_url;
	$rows = trim ($rows);
	$rows = str_replace(' ', '', $rows);
	$rows = explode ( ',' , $rows);
	$i = 0;
	$l = count($rows) - 1;
	$img_style1 = 'image_380x570';
	$img_style2 = 'image_380x360';
	$name = 'portfolio_categories';
    $portfolio_voc = taxonomy_vocabulary_machine_name_load($name);
    $tree = taxonomy_get_tree($portfolio_voc->vid);
?>
<?php if($rows): ?>
	<div id="js-filters-masonry" class="cbp-l-filters-alignRight">
		<div data-filter="*" class="cbp-filter-item-active cbp-filter-item"> <?php print t('All'); ?>
			<div class="cbp-filter-counter"></div>
		</div>
	<?php foreach ($tree as $term) { ?>
		<div data-filter=".<?php print $term->tid; ?>" class="cbp-filter-item"> <?php print $term->name; ?>
          <div class="cbp-filter-counter"></div>
        </div>
	<?php } ?>
	</div>
	<div id="js-grid-masonry" class="cbp">
	<?php
		$j = 1;
		for ($i=0; $i < $l; $i++) {
		$nid = $rows[$i];
		$node = node_load($nid);
		$title = $node->title;
		if($j==1 || $j==4 || $j==5 || $j==9 || $j==14) {
			$img_thumb = image_style_url($img_style1, $node->field_images['und'][0]['uri']);
		} else $img_thumb = image_style_url($img_style2, $node->field_images['und'][0]['uri']);
		$img_full = file_create_url($node->field_images['und'][0]['uri']);
		$options = array('absolute' => TRUE);
		$node_url = url('node/' . $node->nid, $options);
		$author = $node->name;
		$array_tid = '';			
		$items = field_get_items('node', $node, 'field_portfolio_categories');
		foreach ($items as $key => $value) {
			$array_tid .= $items[$key]['tid'].' ';
		}
		$j++;		
	?>
		<div class="cbp-item <?php print $array_tid; ?>">
			<a class="cbp-caption cbp-lightbox" data-title="<?php print $title; ?><br>by <?php print $author; ?>" href="<?php print $img_full; ?>">
				<div class="cbp-caption-defaultWrap"> <img src="<?php print $img_thumb; ?>" alt="<?php print $title; ?>"> </div>
				<div class="cbp-caption-activeWrap">
					<div class="cbp-l-caption-alignCenter">
						<div class="cbp-l-caption-body">
							<div class="cbp-l-caption-title"><?php print $title; ?></div>
							<div class="cbp-l-caption-desc"><?php print t('By ').$author; ?></div>
						</div>
					</div>
				</div>
			</a>
		</div>
	<?php } ?>
	</div>
	<?php if($pager): ?>
	<div class="pagenation-portfolio">
		<?php print $pager; ?>
	</div>
	<?php endif; ?>
<?php endif; ?>
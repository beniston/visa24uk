<?php
	$name = 'portfolio_categories';
    $portfolio_voc = taxonomy_vocabulary_machine_name_load($name);
    $tree = taxonomy_get_tree($portfolio_voc->vid);
?>
<?php print render($title_prefix); ?>
<?php if($header): ?>
	<?php print $header; ?>
<?php endif; ?>
<div class="container">
	<div id="js-filters-awesome-work" class="cbp-l-filters-work">
		<div data-filter="*" class="cbp-filter-item-active cbp-filter-item"> <?php print t('SHOW ALL'); ?> </div>
		<?php foreach ($tree as $term) { ?>
		<div data-filter=".<?php print $term->tid; ?>" class="cbp-filter-item"> <?php print $term->name; ?>
          	<div class="cbp-filter-counter"></div>
        </div>
	<?php } ?>
	</div>
<?php if($rows): ?>
	<div id="js-grid-awesome-work" class="cbp cbp-l-grid-work">
		<?php print $rows; ?>
	</div>
<?php endif; ?>
</div>

<?php if($rows): ?>
<div id="rated" class="tab-pane" role="tabpanel">
	<?php
		$rows = str_replace('class="btn black-button font-bold font18 uppercase', 'class="btn btn-primary add-to-cart', $rows);
		$rows = str_replace('value="Add to cart', 'value="&#xf07a; Add to cart', $rows);
	?>
	<?php print $rows; ?>
</div>
<?php endif; ?>
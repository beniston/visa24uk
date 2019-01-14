<?php print render($title_prefix); ?>
<div class="container">
	<div class="row">
	<?php if($footer): ?>
		<div class="col-md-3 nophone">
			<?php print $footer; ?>
		</div>
	<?php endif; ?>
	<?php if($rows): ?>
		<div class="col-md-9 pro-list">
			<?php if($header): ?>
				<?php print $header; ?>
			<?php endif; ?>
			<?php
				$rows = str_replace('class="btn black-button font-bold font18 uppercase', 'class="btn btn-primary add-to-cart', $rows);
				$rows = str_replace('value="Add to cart', 'value="&#xf07a; Add to cart', $rows);
			?>
			<?php print $rows; ?>
		</div>
	<?php endif; ?>
	</div>
</div>
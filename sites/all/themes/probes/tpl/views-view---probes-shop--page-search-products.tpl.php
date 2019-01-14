<?php print render($title_prefix); ?>
<div class="container">
    <div class="row">
      	<div class="col-md-12">
			<?php if($header): ?>
				<?php print $header; ?>
			<?php endif; ?>
			<?php if($rows): ?>
				<?php
					$rows = str_replace('class="btn black-button font-bold font18 uppercase', 'class="btn btn-primary add-to-cart', $rows);
					$rows = str_replace('value="Add to cart', 'value="&#xf07a; Add to cart', $rows);
				?>
				<?php print $rows; ?>
			<?php endif; ?>
			<?php if ($pager): ?>
    			<?php print $pager; ?>
  			<?php endif; ?>
		</div>
	</div>
</div>
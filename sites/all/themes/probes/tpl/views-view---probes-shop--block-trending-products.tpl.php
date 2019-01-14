<?php print render($title_prefix); ?>
<div class="container">
	<div class="row">
	<?php if($header): ?>
		<?php print $header; ?>
	<?php endif; ?>
		<div class="text-center">
			<?php if($footer): ?>
				<?php print $footer; ?>
			<?php endif; ?>
			<?php if($rows): ?>
			<div class="tab-content tab-style4">
				<div id="home" class="tab-pane active" role="tabpanel">
					<?php
						$rows = str_replace('class="btn black-button font-bold font18 uppercase', 'class="btn btn-primary add-to-cart', $rows);
						$rows = str_replace('value="Add to cart', 'value="&#xf07a; Add to cart', $rows);
					?>
					<?php print $rows; ?>
				</div>
				<?php if ($attachment_after): ?>
    				<?php print $attachment_after; ?>
  				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
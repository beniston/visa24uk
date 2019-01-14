<?php print render($title_prefix); ?>
<div class="container">
	<?php if($header): ?>
		<?php print $header; ?>
	<?php endif; ?>
	<?php if($rows): ?>
	<div id="js-grid-slider-projects" class="cbp">
		<?php print $rows; ?>
	</div>
	<?php endif; ?>
</div>
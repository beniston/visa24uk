<?php print render($title_prefix); ?>
<div class="container">
	<div class="">
	<?php if($header): ?>
		<div class="title1 text-center">
			<?php print $header; ?>
		</div>
	<?php endif; ?>
	<?php if($rows): ?>
		<div id="js-grid-lightbox-gallery" class="cbp m-top2">
			<?php print $rows; ?>
		</div>
	<?php endif; ?>
	</div>
</div>
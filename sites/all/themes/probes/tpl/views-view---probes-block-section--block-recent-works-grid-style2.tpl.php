<?php print render($title_prefix); ?>
<div class="container">
	<?php if($header): ?>
		<?php print $header; ?>
		<div class="title-line wide50 color m-top2"></div>
	<?php endif; ?>
	<?php if($rows): ?>
	<div class="container">
        <div id="js-grid-lightbox-gallery" class="cbp">
			<?php print $rows; ?>
		</div>
	</div>
	<?php endif; ?>
</div>
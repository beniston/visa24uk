<?php print render($title_prefix); ?>
<div class="container">
    <div class="row">
	<?php if($header): ?>
		<div class="col-sm-3 m-bottom4">
			<?php print $header; ?>
		</div>
	<?php endif; ?>
	<?php if($rows): ?>
		<?php print $rows; ?>
	<?php endif; ?>
	</div>
</div>
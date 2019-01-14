<?php print render($title_prefix); ?>
<div class="container">
<?php if($rows): ?>
	<?php if($header): ?>
		<?php print $header; ?>
	<?php endif; ?>
	<div class="row">
		<?php print $rows; ?>
	</div>
<?php endif; ?>
</div>
<?php print render($title_prefix); ?>
<div class="container">
	<div class="row">
	<?php if($footer): ?>
		<?php print $footer; ?>
	<?php endif; ?>
	<?php if($rows): ?>
		<div class="col-md-8 col-sm-8">
			<?php if($header): ?>
				<?php print $header; ?>
			<?php endif; ?>
			<?php print $rows; ?>
		</div>
	<?php endif; ?>
	</div>
</div>
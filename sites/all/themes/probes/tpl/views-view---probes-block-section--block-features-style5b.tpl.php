<?php print render($title_prefix); ?>
<div class="container">
<?php if($rows): ?>
	<div class="row">
		<div class="col-sm-6">
			<?php if($header): ?>
				<?php print $header; ?>
			<?php endif; ?>
			<?php print $rows; ?>
		</div>
		<?php if($footer): ?>
			<?php print $footer; ?>
		<?php endif; ?>
	</div>
<?php endif; ?>
</div>
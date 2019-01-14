<?php print render($title_prefix); ?>
<div class="container">
	<div class="">
	<?php if($header): ?>
		<div class="title1 text-center">
			<?php print $header; ?>
		</div>
	<?php endif; ?>
	<?php if($rows): ?>
		<div class="tab-style1">
		<?php if ($attachment_before): ?>
			<?php $attachment_before = str_replace_first('<li role="presentation" class="uppercase">', '<li role="presentation" class="active uppercase">', $attachment_before); ?>
			<?php print $attachment_before; ?>
		<?php endif; ?>
		<?php $rows = str_replace_first('class="tab-pane"', 'class="tab-pane active"', $rows); ?>
			<div class="tab-content m-top5">
				<?php print $rows; ?>
			</div>
		</div>
	<?php endif; ?>
	</div>
</div>
<?php print render($title_prefix); ?>
<div class="container">
    <div class="row">
		<div class="col-md-6">
		<?php if($header): ?>
			<?php print $header; ?>
		<?php endif; ?>
		<?php if($rows): ?>
			<?php print $rows; ?>
		<?php endif; ?>
		</div>
		<?php if($footer): ?>
			<?php print $footer; ?>
		<?php endif; ?>
	</div>
</div>
<?php print render($title_prefix); ?>
<div class="container">
	<?php if($header): ?>
		<?php print $header; ?>
	<?php endif; ?>
	<?php if($rows): ?>
		<?php print $rows; ?>
	<?php endif; ?>
</div>
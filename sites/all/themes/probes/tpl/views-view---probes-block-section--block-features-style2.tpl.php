<?php print render($title_prefix); ?>
<div class="container">
	<?php if($header): ?>
		<?php print $header; ?>
		<div class="title-line wide50 color m-top2"></div>
	<?php endif; ?>
	<?php if($rows): ?>
		<?php print $rows; ?>
	<?php endif; ?>
</div>
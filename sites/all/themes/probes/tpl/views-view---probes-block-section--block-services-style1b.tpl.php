<?php print render($title_prefix); ?>
<div class="container">
	<div class="row">
	<?php if($header): ?>
		<?php print $header; ?>
	<?php endif; ?>
	<?php if($rows): ?>
	<?php
		$old = '<div class="col-feature-box m-bottom6 first">';
		$new = '</div><div class="col-md-6"><div class="col-feature-box m-bottom6">';
		$rows = trim(str_replace($old, $new, $rows));
		$rows = substr($rows, 6);
		$rows = $rows.'</div>';
	?>
		<?php print $rows; ?>
	<?php endif; ?>
	</div>
</div>
<?php print render($title_prefix); ?>
<div class="container">
	<div class="row">
	<?php if($header): ?>
		<div class="col-md-4">
		<?php print $header; ?>
		</div>
	<?php endif; ?>
	<?php if($rows): ?>
	<?php
		$old = '<div class="col-lg-12 col-sm-12 left-padd0 m-bottom2 first">';
		$new = '</div><div class="col-md-4"><div class="col-lg-12 col-sm-12 left-padd0 m-bottom2">';
		$rows = trim(str_replace($old, $new, $rows));
		$rows = substr($rows, 6);
		$rows = $rows.'</div>';
	?>
		<?php print $rows; ?>
	<?php endif; ?>
	</div>
</div>
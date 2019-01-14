<?php print render($title_prefix); ?>
<div class="container">
    <div class="row">
	<?php if($header): ?>
		<?php print $header; ?>
	<?php endif; ?>
	<?php if($rows): ?>
		<?php
			$old = '<div class="content-feature-2 font-left first">';
			$new = '</div><div class="col-md-6"><div class="content-feature-2 font-left m-top5">';
			$rows = trim(str_replace($old, $new, $rows));
			$rows = str_replace_first('class="content-feature-2 font-left', 'class="content-feature-2 font-left active', $rows);
			$rows = substr($rows, 6);
			$rows = $rows.'</div>';
		?>
		<?php print $rows; ?>
	<?php endif; ?>
	</div>
</div>
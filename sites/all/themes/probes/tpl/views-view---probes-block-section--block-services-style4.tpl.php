<?php print render($title_prefix); ?>
<div class="container">
	<?php if($header): ?>
		<?php print $header; ?>
	<?php endif; ?>
	<?php if($footer): ?>
		<?php print $footer; ?>
	<?php endif; ?>
	<?php if($rows): ?>
	<div class="col-md-7 right">
		<?php print str_replace_first('class="col-md-6 m-bottom4 col-amg-services', 'class="col-md-6 m-bottom4 col-amg-services active', $rows); ?>
	</div>
	<?php endif; ?>
</div>
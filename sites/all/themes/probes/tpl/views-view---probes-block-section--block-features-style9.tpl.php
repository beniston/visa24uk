<?php print render($title_prefix); ?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-6">
		<?php if($header): ?>
			<?php print $header; ?>
		<?php endif; ?>
		<?php if($rows): ?>
			<?php $rows = str_replace_first('class="content-feature-col', 'class="content-feature-col active', $rows); ?>
			<?php print $rows; ?>
		<?php endif; ?>
		</div>
		<?php if($footer): ?>
			<?php print $footer; ?>
		<?php endif; ?>
	</div>
</div>
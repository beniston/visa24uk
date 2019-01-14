<?php print render($title_prefix); ?>
<div class="two-color-box-img">
    <div class="container">
      	<div class="col-md-6 m-bottom3 left-padd0">
		<?php if($header): ?>
			<?php print $header; ?>
		<?php endif; ?>
		<?php if($rows): ?>
		<?php print $rows; ?>
		<?php endif; ?>
		</div>
		<div class="col-md-6 no-left-padd nophone right-padd4 m-bottom3">&nbsp;</div>
	</div>
</div>
<?php print render($title_prefix); ?>
<div class="two-boxes-bgimg">
    <div class="container">
    	<div class="col-md-6 left-padd0 right-padd4 m-bottom3">
			<?php if($header): ?>
				<?php print $header; ?>
			<?php endif; ?>
			<?php if($rows): ?>
				<?php print $rows; ?>
			<?php endif; ?>
		</div>
		<div class="col-md-6 nophone no-right-padd m-left85"></div>
	</div>
</div>
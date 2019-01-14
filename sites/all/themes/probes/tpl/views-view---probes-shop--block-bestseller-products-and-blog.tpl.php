<?php print render($title_prefix); ?>
<div class="container">
	 <div class="row">
      	<div class="col-md-3">
		 	<?php if($header): ?>
				<?php print $header; ?>
			<?php endif; ?>
			<?php if($rows): ?>
			<div class="recommend-content">
				<?php print $rows; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php if($footer): ?>
		<div class="col-md-9 pro-list">
			<?php print $footer; ?>
		</div>
		<?php endif; ?>
	</div>
</div>
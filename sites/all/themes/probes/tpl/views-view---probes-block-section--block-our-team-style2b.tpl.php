<?php print render($title_prefix); ?>
<div class="container">
	<?php if($rows): ?>
	<div class="row">
		<?php if($header): ?>
		<?php print $header; ?>
		<?php endif; ?>
		<?php print str_replace_first('class="team-thumb', 'class="team-thumb active',$rows); ?>
	</div>
	<?php endif; ?>
</div>
<?php
	$form['mail']['#attributes']['placeholder'] = 'Enter your Email';
	$form['mail']['#attributes']['class'] = array('form-control');
	$form['submit']['#attributes']['class'] = array('btn btn-primary');
	$form['submit']['#value'] = decode_entities('SUBSCRIBE!');
?>
<div class="input-group">
<?php if ($message): ?>
	<h2 class="uppercase"><?php print $message; ?></h2>
<?php endif; ?>	
	<?php print render($form); ?>
</div>
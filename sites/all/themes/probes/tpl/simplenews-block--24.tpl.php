<?php
	$form['mail']['#attributes']['placeholder'] = 'Enter your Email';
	$form['mail']['#attributes']['class'] = array('form-control required email');
	$form['submit']['#attributes']['class'] = array('btn btn-default uppercase');
	$form['submit']['#value'] = 'Go';
?>
<?php if ($message): ?>
	<p class="m-bottom2"><?php print $message; ?></p>
<?php endif; ?>
<div class="input-group input-group-lg one divcenter newsletter">
	<?php print render($form); ?>
</div>
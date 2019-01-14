<?php
	$form['mail']['#attributes']['placeholder'] = 'Enter your Email';
	$form['mail']['#attributes']['class'] = array('form-control email');
	$form['submit']['#attributes']['class'] = array('btn btn-default uppercase');
	$form['submit']['#value'] = 'Subscribe Now';
?>
<div class="container">
	<?php if ($message): ?>
	<div class="title1 text-center">
	    <h2 class="uppercase"><?php print $message; ?></h2>
	</div>
	  <?php endif; ?>
	<div class="input-group input-group-lg divcenter newsletter">
		<span class="input-group-addon"><i class="fa fa-paper-plane"></i></span>
		<?php print render($form); ?>
	</div>
</div>
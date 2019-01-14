<?php $form['actions']['submit']['#attributes']['class'][] = 'btn black-button fullwide uppercase' ;?>
<div class="row">
	<?php print drupal_render($form['submitted']['date']); ?>
	<?php print drupal_render($form['submitted']['restime']); ?>
	<?php print drupal_render($form['submitted']['party_size']); ?>
	<div class="col-sm-3">
		<?php print drupal_render($form['actions']); ?>
	</div>
	<?php print drupal_render_children($form); ?>
</div>
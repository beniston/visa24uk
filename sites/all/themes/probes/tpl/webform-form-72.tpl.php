<?php $form['actions']['submit']['#attributes']['class'][] = 'submit-btn' ;?>
<?php print drupal_render($form['submitted']['your_name']); ?>
<?php print drupal_render($form['submitted']['email']); ?>
<?php print drupal_render($form['submitted']['subject']); ?>
<?php print drupal_render($form['submitted']['message']); ?>
<div class="col-md-12">
	<?php print drupal_render($form['actions']); ?>
</div>
<?php print drupal_render_children($form); ?>
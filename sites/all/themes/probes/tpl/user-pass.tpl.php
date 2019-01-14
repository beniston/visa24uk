<?php
	$form['actions']['submit']['#attributes']['class'][] = 'btn button';
	$form['actions']['submit']['#value'] = 'Submit';
?>
<div class="login_form">
	<div class="sky-form">
		<header class="font-slim"><?php print render($intro_text); ?></header>
		<fieldset>
			<div class="row">
				<label class="label col col-4"><?php print t('E-mail or username'); ?></label>
				<div class="col col-8">
					<label class="input"> <i class="icon-append fa fa-user"></i>
						<?php print drupal_render($form['name']); ?>
					</label>
				</div>
			</div>
		</fieldset>
		<footer>
			<?php  print drupal_render($form['actions']['submit']); ?>
		</footer>
		<?php print drupal_render_children($form); ?>
	</div>
</div>

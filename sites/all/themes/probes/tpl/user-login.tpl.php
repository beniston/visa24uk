<?php
	$form['actions']['submit']['#attributes']['class'][] = 'button';
	$form['actions']['submit']['#value'] = 'Log in';
?>
<div class="login_form">
	<div class="sky-form">
		<header class="font-slim"><?php print render($intro_text); ?></header>
		<fieldset>
			<section>
				<div class="row">
					<label class="label col col-4"><?php print t('Username'); ?> *</label>
					<div class="col col-8">
						<label class="input"> <i class="icon-append fa fa-user"></i>
							<?php  print drupal_render($form['name']); ?>
						</label>
					</div>
				</div>
			</section>
			<section>
				<div class="row">
					<label class="label col col-4"><?php print t('Password'); ?> *</label>
					<div class="col col-8">
						<label class="input"> <i class="icon-append fa fa-lock"></i>
							<?php  print drupal_render($form['pass']); ?>
						</label>
					</div>
				</div>
			</section>
		</fieldset>
		<footer>
			<div class="fright">
				<?php  print drupal_render($form['actions']['submit']); ?>
			</div>
		</footer>
        <?php print drupal_render_children($form); ?>
	</div>
</div>
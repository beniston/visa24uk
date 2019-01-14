<?php $form['actions']['submit']['#attributes']['class'][] = 'button' ;?>
<div class="sky-form">
    <fieldset>
		<div class="row">
			<section class="col col-6">
				<label class="label"><?php print t('Name'); ?></label>
				<label class="input"> <i class="icon-append fa fa-user"></i>
					<?php print drupal_render($form['submitted']['name']); ?>
				</label>
			</section>
			<section class="col col-6">
				<label class="label">E-mail</label>
				<label class="input"> <i class="icon-append fa fa-envelope-o"></i>
					<?php print drupal_render($form['submitted']['e_mail']); ?>
				</label>
			</section>
		</div>
		<section>
            <label class="label">Subject</label>
            <label class="input"> <i class="icon-append fa fa-tags"></i>
                <?php print drupal_render($form['submitted']['subject']); ?>
            </label>
        </section>
        <section>
            <label class="label">Message</label>
            <label class="textarea"> <i class="icon-append fa fa-comment"></i>
                <?php print drupal_render($form['submitted']['message']); ?>
            </label>
        </section>
		<section style="float:left;">
			<label class="checkbox">
				<?php print drupal_render($form['submitted']['contact_me_by_email']); ?>
			</label>
		</section>
	</fieldset>
	<footer>
        <?php print drupal_render($form['actions']); ?>
    </footer>
	<?php print drupal_render_children($form); ?>
</div>
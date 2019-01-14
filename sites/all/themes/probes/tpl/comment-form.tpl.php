<?php
	$form['author']['mail']['#access'] = TRUE;
	$form['author']['mail']['#description'] = FALSE;
	$form['author']['name']['#title'] = FALSE;
	$form['author']['mail']['#title'] = FALSE;
	$form['author']['comment_body']['#title'] = FALSE;
	$form['comment_body']['und'][0]['value']['#rows'] = 4;
	$form['actions']['submit']['#value'] = 'Submit Comments';
	$form['actions']['submit']['#attributes']['class'][] = 'button seven';
	$form['actions']['preview']['#access'] = FALSE;
	$form['subject']['#access'] = FALSE;
	$form['comment_body']['#required'] = TRUE;
	$form['comment_body']['und'][0]['#title'] = FALSE;
	

?>
<div class="sky-form">
	<fieldset>
        <div class="row">
            <section class="col col-6">
            	<label class="label al_left"><?php print t('Name'); ?></label>
             	<label class="input">
                	<?php  print drupal_render($form['author']['name']); ?>
                </label>
            </section>
             <section class="col col-6">
             	<label class="label al_left"><?php print t('E-mail'); ?></label>
             	<label class="input">
                	<?php  print drupal_render($form['author']['mail']); ?>
                </label>
            </section>
        </div>
		<section>
			<label class="label al_left"><?php print t('Message'); ?></label>
			<label class="textarea">
				<?php print drupal_render($form['comment_body']) ;?>
			</label>
		</section>
		<section> </section>		
	</fieldset>
	<footer>
		<?php print drupal_render($form['actions']['submit']); ?>
	</footer>
</div>
<?php //print_r($form); ?>
<?php print drupal_render_children($form); ?>
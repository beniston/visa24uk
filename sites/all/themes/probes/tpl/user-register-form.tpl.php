<?php
	$form['actions']['submit']['#attributes']['class'][] = 'btn button';
	$form['actions']['submit']['#value'] = 'Submit';
	$form['account']['mail']['#attributes']['placeholder'] = 'Email address *';
	$form['account']['name']['#attributes']['placeholder'] = 'Username *';
	$form['field_first_name']['und'][0]['value']['#attributes']['placeholder'] ='First name *';
	$form['field_last_name']['und'][0]['value']['#attributes']['placeholder'] ='Last name *';
	$form['field_company_name']['und'][0]['value']['#attributes']['placeholder'] ='Company name';
	$form['field_telephone']['und'][0]['value']['#attributes']['placeholder'] ='Telephone';


?>
<div class="reg_form">
	<div class="sky-form">
		<header><?php print render($intro_text); ?></header>
		<fieldset>
			<section>
				<label class="input">
					<?php print drupal_render($form['account']['name']); ?>
					<b class="tooltip tooltip-bottom-right"><?php print t('Needed to enter the website'); ?></b>
				</label>
			</section>
			<section>
              	<label class="input">
                	<?php print drupal_render($form['account']['mail']); ?>
                	<b class="tooltip tooltip-bottom-right">Needed to verify your account</b>
                </label>
            </section>
            <section>
              	<label class="input">
                	<?php print drupal_render($form['field_first_name']); ?>
                </label>
            </section>
			 <section>
              	<label class="input">
                	<?php print drupal_render($form['field_last_name']); ?>
                </label>
            </section>
			 <section>
              	<label class="input">
                	<?php print drupal_render($form['field_company_name']); ?>
                </label>
            </section>
			 <section>
              	<label class="input">
                	<?php print drupal_render($form['field_telephone']); ?>
                </label>
            </section>
		</fieldset>
				<div class="mandatory-field-form"><?php  print '* Mandatory field'; ?> </div> 
        <?php print drupal_render_children($form); ?>
	</div>
</div>

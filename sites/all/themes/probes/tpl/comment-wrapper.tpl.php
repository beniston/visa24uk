<?php if ($content['#node']->comment and !($content['#node']->comment == 1 and $content['#node']->comment_count)) { ?>
<div class="col-md-12">
	<h4 class="m-bottom3 font-thin font20"><?php print t('Comments'); ?>(<?php print $content['#node']->comment_count; ?>)</h4>
	<?php print render($content['comments']); ?>
	<div class="m-top3">
		<h3 class="m-bottom3 font-thin font25"><?php print t('Post a Comment') ?></h3>
		<div class="cforms">
			<?php print str_replace('resizable', '', render($content['comment_form'])); ?>
		</div>
	</div>
</div>
<!-- End Comments -->
<?php } ?>

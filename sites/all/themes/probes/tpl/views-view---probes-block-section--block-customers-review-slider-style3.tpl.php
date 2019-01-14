<?php print render($title_prefix); ?>
<div class="container">
	<div class="row">
	<?php if($header): ?>
		<?php print $header; ?>
	<?php endif; ?>
	<?php if($rows): ?>
		<?php $n = substr_count($rows, 'class="item'); ?>
		<div id="carousel-example-generics" class="carousel slide" data-ride="carousel"> 
			<!-- Indicators -->
          	<ol class="carousel-indicators">
            	<?php
					for ($i=0; $i < $n ; $i++) {
						if($i == 0) {
							$active = 'active';
						} else $active = '';
						print '<li data-target="#carousel-example-generics" data-slide-to="'.$i.'" class="'.$active.'"></li>';
					}
				?>
            </ol>
			<?php $rows = str_replace_first('class="item', 'class="item active', $rows) ?>
			<div class="carousel-inner" role="listbox">
				<?php print $rows; ?>
			</div>
		</div>
	<?php endif; ?>
	</div>
</div>
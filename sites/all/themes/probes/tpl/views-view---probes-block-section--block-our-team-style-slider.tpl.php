<?php print render($title_prefix); ?>
<div class="container m-bottom5">
	<?php if($header): ?>
		<?php print $header; ?>
	<?php endif; ?>
	<?php if($rows): ?>
		<?php
			$old = '<div class="col-md-6 col-sm-6 first">';
			$new = '</div><div class="item"><div class="col-md-6 col-sm-6">';
			$rows = trim(str_replace($old, $new, $rows));
			$rows = substr($rows, 6);
			$rows = str_replace_first('class="item', 'class="item active', $rows);
			$rows = $rows.'</div>';
			$n = substr_count($rows, 'class="item');
		?>
	<div class="row">
        <div class="col-test-5 text-center">
          	<div id="carousel-example-generics" class="carousel slide" data-ride="carousel">
          		<!-- Indicators -->
	            <ol class="carousel-indicators style">
	            <?php for ($i=0; $i < $n ; $i++) {
					if($i == 0) {
						$active = 'active';
					} else $active = '';
					print '<li data-target="#carousel-example-generics" data-slide-to="'.$i.'" class="'.$active.'"></li>';
				}
				?>
	            </ol>
				<!-- Wrapper for slides -->
            	<div class="carousel-inner" role="listbox">
					<?php print $rows; ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
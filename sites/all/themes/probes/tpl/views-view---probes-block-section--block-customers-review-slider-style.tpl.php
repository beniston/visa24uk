<div class="container">
<?php if($rows): ?>
	<?php $n = substr_count($rows, 'class="item'); ?>
	<div class="row">
        <div data-ride="carousel" class="carousel slide col-test-4" id="carousel-example-generics">
        	<!-- Indicators -->
          	<ol class="carousel-indicators">
			<?php for ($i=0; $i < $n ; $i++) {
					if($i == 0) {
						$active = 'active';
					} else $active = '';
					print '<li data-target="#carousel-example-generics" data-slide-to="'.$i.'" class="'.$active.'"></li>';
				}
			?>
	        </ol>
	         <div role="listbox" class="carousel-inner">
				<?php print $rows; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
</div>
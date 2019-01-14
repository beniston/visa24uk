<?php print render($title_prefix); ?>
<div class="container">
    <div class="">
		<div class="col-md-6 nopadding"> <i class="fa fa-quote-left font150"></i>
		<?php if($header): ?>
			<div class="sec-titiel test m-bottom5 m-top-6">
				<?php print $header; ?>
			</div>
		<?php endif; ?>
		<?php if($rows): ?>
		<?php $n = substr_count($rows, 'class="item'); ?>
			<div class="col-test-3">
            	<div id="carousel-example-generics" class="carousel slide" data-ride="carousel">
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
        <?php endif; ?>
		</div>
		<?php if($footer): ?>
			<?php print $footer; ?>
		<?php endif; ?>
    </div>
</div>
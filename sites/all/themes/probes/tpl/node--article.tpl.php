<?php
/**
 * @file
 * Default theme implementation to display a node.
 */
	
	global $base_root, $base_url;
	if(isset($_GET['style'])) {
		$blog_layout = $_GET['style'];
	} else $blog_layout =theme_get_setting('blog_layout_style', 'probes');
	$user = user_load($uid); // Make sure the user object is fully loaded
	if(isset($node->field_images) && !empty($node->field_images)) {
    	$ni = count($node->field_images['und']);
    	$img_uri  = $node->field_images['und'][0]['uri'];
	    $imageone = image_style_url('image_1170x350', $img_uri);
	    $image_crop = image_style_url('image_540x400', $img_uri);
  	} else{
  		$imageone = '';
  		$ni = 0;
  	}
  	if(!$page){ ?>
  		<?php if($blog_layout == 'fullwidth'): ?>
		<div class="col-md-12">
		<?php if($ni == 1): ?>
			<img src="<?php print $imageone; ?>" alt="<?php print $title; ?>" class="img-responsive">
		<?php elseif($ni > 1): ?>
			<div id="carousel-example-generic1" class="carousel slide" data-ride="carousel">
				<!-- Indicators -->
	          	<ol class="carousel-indicators">
	          	<?php for ($i=0; $i < $ni; $i++) { 
	          		if($i == 0) {
	          			$class = 'active';
	          		} else $class= '';
	          		print '<li data-target="#carousel-example-generic1" data-slide-to="'.$i.'" class="'.$class.'"></li>';
	          	} ?>
	          	</ol>
	          	<!-- Wrapper for slides -->
	          	<div class="carousel-inner" role="listbox">
	          	<?php for ($i=0; $i < $ni; $i++) {
	          		if($i == 0) {
	          			$class = 'active';
	          		} else $class= '';
	          		$img_uri = $node->field_images['und'][$i]['uri'];
	          		$img = image_style_url('image_1170x350', $img_uri);
	          		print '<div class="item '.$class.'"><img src="'.$img.'" alt="'.$title.'" class="img-responsive"></div>';
	            }
	            ?>
	          	</div>
			</div>
		<?php endif; ?>
			<h3 class="m-bottom2 m-top4 font-thin font30"><?php print $title; ?></h3>
	        <p>
	        	<?php
					hide($content['links']);
					hide($content['field_tags']);
					hide($content['comments']);
					hide($content['field_categories']);
					hide($content['field_images']);
					hide($content['field_subtitle']);
					hide($content['field_featured']);
					hide($content['field_topic']);
					hide($content['field_post_format']);
					hide($content['field_topic']);
					hide($content['field_media_upload']);
					print render($content);
				?>
	        </p>
	        <div class="post-info m-top2 m-bottom5"><i class="fa fa-user"></i> <?php print $name; ?> <?php print t('on'); ?> <?php print format_date($created, 'custom', 'd M Y, g:iA') ?> <span class="right"><i class="fa fa-comments"></i> <a href="<?php print $node_url; ?>"><?php print $comment_count ?></a> &nbsp;/&nbsp; <span class="tags-categories"><?php print strip_tags(render($content['field_categories']), '<a>'); ?></span></span> </div>
		</div>
		<?php elseif($blog_layout == '3cols'): ?>
		<div class="col-md-4 col-sm-6">
			<img src="<?php print $image_crop; ?>" alt="<?php print $title; ?>" class="img-responsive">
			<h3 class="m-bottom1 m-top3 font20"><?php print $title; ?></h3>
        	<div class="m-bottom1"><i class="fa fa-user"></i> <?php print $name; ?> <?php print t('on'); ?> <?php print format_date($created, 'custom', 'd M Y, g:iA') ?></div>
	        <p>
	        	<?php
					hide($content['links']);
					hide($content['field_tags']);
					hide($content['comments']);
					hide($content['field_categories']);
					hide($content['field_images']);
					hide($content['field_featured']);
					hide($content['field_subtitle']);
					hide($content['field_topic']);
					hide($content['field_post_format']);
					hide($content['field_topic']);
					hide($content['field_media_upload']);
					print trim_text(render($content), 200);
				?>
	        </p>
	        <div class="post-info m-top1 m-bottom5">
	        	<span class="tags-categories"><?php print strip_tags(render($content['field_categories']), '<a>'); ?></span>
	        </div>
		</div>
		<?php else: ?>
		<div>
			<img src="<?php print $imageone; ?>" alt="<?php print $title; ?>" class="img-responsive">
          	<h3 class="m-bottom2 m-top4 font-thin font30"><?php print $title; ?></h3>
          	<p>
          		<?php
					hide($content['links']);
					hide($content['field_tags']);
					hide($content['comments']);
					hide($content['field_categories']);
					hide($content['field_images']);
					hide($content['field_subtitle']);
					hide($content['field_featured']);
					hide($content['field_topic']);
					hide($content['field_post_format']);
					hide($content['field_topic']);
					hide($content['field_media_upload']);
					print trim_text(render($content), 200);
				?>
          	</p>
          	<div class="post-info m-top2 m-bottom5">
          		<i class="fa fa-user"></i> <?php print $name; ?> <?php print t('on'); ?> <?php print format_date($created, 'custom', 'd M Y, g:iA') ?> <span class="right"><i class="fa fa-comments"></i> <a href="<?php print $node_url; ?>"><?php print $comment_count ?></a> &nbsp;/&nbsp; <span class="tags-categories"><?php print strip_tags(render($content['field_categories']), '<a>'); ?></span></span> 
          	</div>
        </div>
		<?php endif; ?>
<?php } else { ?>
	<?php if($ni > 0): ?>
		<img src="<?php print $imageone; ?>" alt="<?php print $title; ?>" class="img-responsive">
	<?php endif; ?>
		<h3 class="m-bottom2 m-top4 font-thin font30"><?php print $title; ?></h3>
		<?php
			hide($content['links']);
			hide($content['field_tags']);
			hide($content['comments']);
			hide($content['field_categories']);
			hide($content['field_images']);
			hide($content['field_subtitle']);
			hide($content['field_featured']);
			hide($content['field_topic']);
			hide($content['field_post_format']);
			hide($content['field_topic']);
			hide($content['field_media_upload']);
			print render($content);
		?>
		<div class="post-info m-top2 m-bottom5">
			<i class="fa fa-user"></i> <?php print $name; ?> <?php print t('on'); ?> <?php print format_date($created, 'custom', 'd M Y, g:iA') ?> <span class="right"><i class="fa fa-comments"></i> <a href="<?php print $node_url; ?>"><?php print $comment_count ?></a> &nbsp;/&nbsp; <span class="tags-categories"><?php print strip_tags(render($content['field_tags']), '<a>'); ?></span></span>
		</div>
		<?php print render($content['comments']); ?>
<?php } ?>
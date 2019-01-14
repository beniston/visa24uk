<?php
	$twitter_profile = $settings['widget_twitter_profile'];
	$twitter_display_count = $settings['widget_twitter_display_limit'];
	$path = base_path().drupal_get_path('module', 'nv_twitter');
?>

<div id="twitter-feed" class="tweett" data-displaylimit="<?php print $twitter_display_count ?>" data-twitterprofile="<?php print $twitter_profile ?>" data-path-mod="<?php print $path; ?>">
	
</div>

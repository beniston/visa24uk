<!DOCTYPE html>
<html lang="<?php print $language->language; ?>">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-KRZP84V');
		</script>
<!-- End Google Tag Manager -->
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title><?php print $head_title; ?></title>
		
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<?php print $styles; ?>
		<?php print $head; ?>
		<?php
			//Tracking code
			$tracking_code = theme_get_setting('general_setting_tracking_code', 'probes');
			print $tracking_code;
			//Custom css
			$custom_css = theme_get_setting('custom_css', 'probes');
			if(!empty($custom_css)):
		?>
			<style type="text/css" media="all">
			<?php print $custom_css;?>
			</style>
		<?php endif; ?>
		<?php
			$site_layout = theme_get_setting('probes_site_layout', 'probes');
    		if(empty($site_layout)) $site_layout = 'wide';
    		if($site_layout == 'boxed') {
    			$bg_pattern = theme_get_setting('bg_patterns_boxed','probes');
				if(!empty($bg_pattern)) {
					$bg_pattern = file_create_url(file_load($bg_pattern)->uri);
				}
    		}
    		$disable_switch = theme_get_setting('probes_disable_switch', 'probes');
			if(empty($disable_switch)) $disable_switch = 'off';
			if($disable_switch == 'on'):
		?>
		<!-- Style Switcher Colors -->
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/colors/red.css" title="red" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/colors/orange.css" title="orange" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/colors/olive.css" title="olive" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/colors/green.css" title="green" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/colors/light-red.css" title="lightred" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/colors/violet.css" title="violet" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/colors/sea.css" title="sea" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/colors/lightblue.css" title="lightblue" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/colors/lightgreen.css" title="lightgreen" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-default.css" title="pattern-default" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-one.css" title="pattern-one" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-two.css" title="pattern-two" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-three.css" title="pattern-three" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-four.css" title="pattern-four" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-five.css" title="pattern-five" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-six.css" title="pattern-six" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-seven.css" title="pattern-seven" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-eight.css" title="pattern-eight" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-nine.css" title="pattern-nine" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-ten.css" title="pattern-ten" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-eleven.css" title="pattern-eleven" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-twelve.css" title="pattern-twelve" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-thirteen.css" title="pattern-thirteen" />
		<link rel="alternate stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/bg-patterns/pattern-fourteen.css" title="pattern-fourteen" />
		<?php endif; ?>
	</head>
	<body class="<?php print $classes;?>" <?php print $attributes;?> <?php if(isset($bg_pattern)) print 'style="background-image:url('.$bg_pattern.');"'; ?>>
		<div id="skip-link">
			<a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
		</div>
		<div class="site-wrapper">
			<?php print $page_top; ?><?php print $page; ?><?php print $page_bottom; ?>
		</div>
		<!-- end site wrapper --> 
		<?php if($disable_switch == 'on'): ?>
		<!-- style switcher -->
		<div id="style-selector">
		    <div class="style-selector-wrapper"> <span class="title"><?php print t('Choose Theme Options'); ?></span>
		        <div> <span class="title-sub2"><?php print t('Choose Layout'); ?></span>
		            <div class="styles">
		                <ul class="layout-style">
		                    <li><a href="#" class="wide btn btn-default black"><?php print t('Wide'); ?></a></li>
		                    <li><a href="#" class="boxed btn btn-default black"><?php print t('Boxed'); ?></a></li>
		                </ul>
		            </div>
		            <span class="title-sub2"><?php print t('Predefined Color Skins'); ?></span>
		            <ul class="styles">
		                <li><a href="#" onClick="setActiveStyleSheet('blue'); return false;" title="Blue"><span class="pre-color-skin1"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('red'); return false;" title="Red"><span class="pre-color-skin2"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('orange'); return false;" title="Orange"><span class="pre-color-skin3"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('olive'); return false;" title="Olive"><span class="pre-color-skin4"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('green'); return false;" title="Green"><span class="pre-color-skin5"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('lightred'); return false;" title="Light Red"><span class="pre-color-skin6"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('violet'); return false;" title="Violet"><span class="pre-color-skin7"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('sea'); return false;" title="Sea"><span class="pre-color-skin8"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('lightblue'); return false;" title="Light Blue"><span class="pre-color-skin9"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('lightgreen'); return false;" title="Light Green"><span class="pre-color-skin10"></span></a></li>
		            </ul>
		            <!-- end Predefined Color Skins -->
		            <span class="title-sub2"><?php print t('BG Patterns for Boxed'); ?></span>
		            <ul class="styles noborrder">
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-default'); return false;"><span class="bg-patterns1"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-one'); return false;"><span class="bg-patterns2"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-two'); return false;"><span class="bg-patterns3"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-three'); return false;"><span class="bg-patterns4"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-four'); return false;"><span class="bg-patterns5"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-five'); return false;"><span class="bg-patterns6"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-six'); return false;"><span class="bg-patterns7"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-seven'); return false;"><span class="bg-patterns8"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-eight'); return false;"><span class="bg-patterns9"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-nine'); return false;"><span class="bg-patterns10"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-ten'); return false;"><span class="bg-patterns11"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-eleven'); return false;"><span class="bg-patterns12"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-twelve'); return false;"><span class="bg-patterns13"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-thirteen'); return false;"><span class="bg-patterns14"></span></a></li>
		                <li><a href="#" onClick="setActiveStyleSheet('pattern-fourteen'); return false;"><span class="bg-patterns15"></span></a></li>
		            </ul>
		            <!-- end BG Patterns -->
		            <a href="#" class="close icon-chevron-right"><i class="fa fa-wrench"></i></a></div>
		    </div>
		</div>
		<!-- end style switcher -->
		<?php endif; ?>
		<a href="#" class="scrollup"></a>
		<!-- JS begin -->
		<?php if(isset($node_type) && $node_type == 'product_display'):?>	
			<?php print str_replace('jquery.cubeportfolio.min.js', 'jquery.cubeportfolio.min1.js', $scripts); ?>
		<?php else: ?>
			<?php print $scripts; ?>
		<?php endif; ?>
	</body>
</html>
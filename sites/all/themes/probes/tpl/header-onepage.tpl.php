<?php
	global $base_url;
	$site_name = variable_get('site_name');
	if(isset($node->field_menu_style) && !empty($node->field_menu_style)) {
		$menu_style = $node->field_menu_style['und'][0]['value'];
	} else $menu_style = 'style1';
	if (isset($node->field_logo) && !empty($node->field_logo)) {
        $logo = file_create_url($node->field_logo['und'][0]['uri']);
    }
?>
<?php if($menu_style == 'style1'): ?>
	<header class="header">
	    <div class="container">
	        <!-- Menu -->
	        <div class="navbar yamm navbar-default">
	            <div class="container">
	                <div class="navbar-header">
	                    <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
	                <?php if($logo): ?>
	                    <a href="<?php print $base_url; ?>" class="navbar-brand logo logo-white"><img src="<?php print $logo; ?>" alt="<?php print $site_name;?>"></a>
	                <?php endif; ?>
	                </div>
	                <div id="navbar-collapse-1" class="navbar-collapse collapse pull-right">
	                    <nav>
	                        <div class="skroll-header">
	                            <ul class="nav navbar-nav onemenu">
	                                <li class="skroll-active"><a href="#"><?php print t('Home'); ?></a></li>
	                            </ul>
	                        </div>
	                    </nav>
	                </div>
	            </div>
	        </div>
	    </div>
	</header>
<?php else: ?>
	<header class="header whitebg headr-style-1">
	    <div class="container">
	        <!-- Menu -->
	        <div class="navbar yamm navbar-default">
	            <div class="container">
	                <div class="navbar-header">
	                    <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
	                    <?php if($logo): ?>
	                    <a href="<?php print $base_url; ?>" class="navbar-brand logo"><img src="<?php print $logo; ?>" alt="<?php print $site_name;?>"></a>
	                <?php endif; ?>
	                </div>
	                <div id="navbar-collapse-1" class="navbar-collapse collapse pull-right">
	                    <nav>
	                        <div class="skroll-header">
	                            <ul class="nav navbar-nav onemenu">
	                                <li class="skroll-active"><a href="#"><?php print t('Home'); ?></a></li>
	                            </ul>
	                        </div>
	                    </nav>
	                </div>
	            </div>
	        </div>
	    </div>
	</header>	
	<!-- end Header -->
<?php endif; ?>
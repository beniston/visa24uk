<?php

    global $base_url;

    if (!isset($header_layout) || empty($header_layout)) {

        if (isset($node->field_header_layout) && !empty($node->field_header_layout)) {

            $header_layout = $node->field_header_layout['und'][0]['value'];

        } else $header_layout = theme_get_setting('header_layout', 'probes');

        if (empty($header_layout)) $header_layout = 'layout1';

    }

    if (!isset($header_class) || empty($header_class)) {

        if (isset($node->field_header_class) && !empty($node->field_header_class)) {

            $header_class = $node->field_header_class['und'][0]['value'];

        } elseif(isset($node->field_header_class) && empty($node->field_header_class)) {

            $header_class = '';

        } else $header_class = theme_get_setting('header_class', 'probes');

        if (empty($header_class)) $header_class = '';

    }
    if(isset($logo)):
        if (isset($node->field_logo) && !empty($node->field_logo)) {

            $logo = file_create_url($node->field_logo['und'][0]['uri']);

        }
    endif;

    if (!isset($topbar) || empty($topbar)) {

        if (isset($node->field_top_bar) && !empty($node->field_top_bar)) {

            $topbar = $node->field_top_bar['und'][0]['value'];

        } else $topbar = theme_get_setting('topbar_enable', 'probes');

        if (empty($topbar)) $topbar = 'on';

    }

    if (!isset($topbar_class) || empty($topbar_class)) {

        if (isset($node->field_topbar_class) && !empty($node->field_topbar_class)) {

            $topbar_class = $node->field_topbar_class['und'][0]['value'];

        } else $topbar_class = theme_get_setting('topbar_class', 'probes');

        if (empty($topbar_class)) $topbar_class = '';

    }

    if (!isset($topbar_background_color) || empty($topbar_background_color)) {

        if (isset($node->field_top_bar_background) && !empty($node->field_top_bar_background)) {

            $topbar_background_color = $node->field_top_bar_background['und'][0]['jquery_colorpicker'];

        } else $topbar_background_color = theme_get_setting('topbar_background_color', 'probes');

        if (empty($topbar_background_color)) $topbar_background_color = '';

    }

    if(!empty($topbar_background_color)) $topbar_class .= ' background-color';

    $site_name = variable_get('site_name');



?>



<?php if ($header_layout == 'layout1'): ?>

    <?php if ($topbar == 'on' && $page['topbar']): ?>

    <!-- topnav -->

    <div class="col-topbar <?php print $topbar_class; ?>" <?php if(!empty($topbar_background_color)) print 'data-bg-color="#'.$topbar_background_color.'"'; ?>>

        <div class="container">

            <?php print render($page['topbar']); ?>

        </div>

    </div>

    <!-- topnav end -->

    <?php endif; ?>

    <header class="header whitebg headr-style-1 <?php print $header_class; ?>" style="height:140px;">

        <div class="container">

            <!-- Menu -->

            <div class="navbar yamm navbar-default">
                <!-- New address -->
<div class="container">
                  <p style="padding:10px 20px; background:#b3e000;">
Visa24! A one stop destination for all your Visa needs!
We have offices in London and Manchester. Please choose your nearest office.
</p>
                </div>

                <div class="container">

                    <div class="navbar-header">

                        <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>

                        <?php if($logo): ?>

                        <a href="<?php print $base_url;?>" class="navbar-brand logo"><img src="<?php print $logo; ?>" alt="<?php print $site_name;?>"></a>

                        <?php endif; ?>

                    </div>

                <?php if($page['main_menu']): ?>

                    <div id="navbar-collapse-1" class="navbar-collapse collapse pull-right">

                        <?php print render($page['main_menu']); ?>

                    </div>

                <?php endif; ?>

                </div>

            </div>

        </div>

    </header>

<?php elseif($header_layout == 'layout2'): ?>

    <?php if ($topbar == 'on' && $page['topbar']): ?>

    <!-- topnav -->

    <div class="col-topbar <?php print $topbar_class; ?>" <?php if(!empty($topbar_background_color)) print 'data-bg-color="#'.$topbar_background_color.'"'; ?>>

        <div class="container">

            <?php print render($page['topbar']); ?>

        </div>

    </div>

    <!-- topnav end -->

    <?php endif; ?>

    <header class="header headr-style-2 <?php print $header_class; ?>">

        <div class="container">

            <!-- Menu -->

            <div class="navbar yamm navbar-default">

                <div class="container">

                    <div class="navbar-header">

                        <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>

                        <?php if($logo): ?>

                        <a href="<?php print $base_url;?>" class="navbar-brand logo logo-white"><img src="<?php print $logo; ?>" alt="<?php print $site_name;?>"></a>

                        <?php endif; ?>

                    </div>

                <?php if($page['main_menu']): ?>

                    <div id="navbar-collapse-1" class="navbar-collapse collapse pull-right">

                        <?php print render($page['main_menu']); ?>

                    </div>

                <?php endif; ?>

                </div>

            </div>

        </div>

    </header>

<?php elseif($header_layout == 'layout3'): ?>

    <?php if ($topbar == 'on' && ($page['topbar'] || $logo)): ?>

    <!-- topnav -->

    <div class="col-topbar-white <?php print $topbar_class; ?>" <?php if(!empty($topbar_background_color)) print 'data-bg-color="#'.$topbar_background_color.'"'; ?>>

        <div class="container">

        <?php if($logo): ?>

            <div class="col-md-6 nopadding m-bottom2 m-top1 mob-ove">

                <a href="<?php print $base_url;?>" class="navbar-brand logo bus-page"><img src="<?php print $logo; ?>" alt="<?php print $site_name;?>"></a>

            </div>

        <?php endif; ?>

            <?php print render($page['topbar']); ?>

        </div>

    </div>

    <!-- topnav end -->

    <?php endif; ?>

    <header class="header headr-style-3 <?php print $header_class; ?>">

        <div class="container">

            <!-- Menu -->

            <div class="navbar yamm navbar-default">

                <div class="container">

                    <div class="navbar-header">

                        <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>

                    </div>

                <?php if($page['main_menu']): ?>

                    <div id="navbar-collapse-1" class="navbar-collapse collapse dark-color pull-left nopadding">

                        <?php print render($page['main_menu']); ?>

                    </div>

                <?php endif; ?>

                <?php if($page['right_header']): ?>

                    <?php print render($page['right_header']); ?>

                <?php endif; ?>

                </div>

            </div>

        </div>

    </header>

<?php elseif($header_layout == 'layout4'): ?>

    <header class="header headr-style-4 <?php print $header_class; ?>">

        <!-- Menu -->

        <div class="navbar yamm navbar-default">

            <div class="navbar-header">

                <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>

            <?php if($logo): ?>

                <a href="<?php print $base_url;?>" class="navbar-brand logo logo-white"><img src="<?php print $logo; ?>" alt="<?php print $site_name;?>"></a>

            <?php endif; ?>

            </div>

        <?php if($page['main_menu']): ?>

            <div id="navbar-collapse-1" class="navbar-collapse collapse pull-right">

                <?php print render($page['main_menu']); ?>

            </div>

        <?php endif; ?>

        </div>

    </header>

<?php elseif($header_layout == 'layout5'): ?>

    <?php if ($topbar == 'on' && $page['topbar']): ?>

    <!-- topnav -->

    <div class="col-topbar <?php print $topbar_class; ?>" <?php if(!empty($topbar_background_color)) print 'data-bg-color="#'.$topbar_background_color.'"'; ?>>

        <div class="container">

            <?php print render($page['topbar']); ?>

        </div>

    </div>

    <!-- topnav end -->

    <?php endif; ?>

    <?php if($logo || $page['right_header5']): ?>

    <div class="col-topbar-white noborder">

        <div class="container">

        <?php if($logo): ?>

            <div class="col-md-6 nopadding m-bottom2 m-top5px mob-ove">

                <a href="<?php print $base_url; ?>" class="navbar-brand logo"><img src="<?php print $logo; ?>" alt="<?php print $site_name;?>"></a>

            </div>

        <?php endif; ?>

        <?php if($page['right_header5']): ?>

            <?php print render($page['right_header5']); ?>

        <?php endif; ?>

        </div>

    </div>

    <?php endif; ?>

    <header class="header headr-style-3 shop <?php print $header_class; ?>">

        <div class="container">

            <!-- Menu -->

            <div class="navbar yamm navbar-default">

                <div class="container">

                    <div class="navbar-header">

                        <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>

                    </div>

                <?php if($page['main_menu']): ?>

                    <div id="navbar-collapse-1" class="navbar-collapse collapse dark-color pull-left fullwide nopadding">

                        <?php print render($page['main_menu']); ?>

                        <?php if($page['shopping_cart']): ?>

                            <?php print render($page['shopping_cart']); ?>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>

                </div>

            </div>

        </div>

    </header>

<?php endif; ?>

<!-- end Header -->

<?php if($page['slider']): ?>

  <?php print render($page['slider']); ?>

<?php endif; ?>

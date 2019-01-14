<?php

function probes_form_system_theme_settings_alter(&$form, &$form_state) {

    $form['#submit'][] = 'probes_settings_form_submit';

    // Get all themes.
    $themes = list_themes();
    // Get the current theme
    $active_theme = $GLOBALS['theme_key'];
    $form_state['build_info']['files'][] = str_replace("/$active_theme.info", '', $themes[$active_theme]->filename) . '/theme-settings.php';

    $theme_path = drupal_get_path('theme', 'probes');
    $form['settings'] = array(
        '#type' => 'vertical_tabs',
        '#title' => t('Theme settings'),
        '#weight' => 2,
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
        '#attached' => array(
            'css' => array(
                drupal_get_path('theme', 'probes') . '/css/admin.css',
                drupal_get_path('theme', 'probes') . '/css/drupalet_base/theme_color.css',
            ),
            'js' => array(
                drupal_get_path('theme', 'probes') . '/js/drupalet_admin/admin.js',
                drupal_get_path('theme', 'probes') . '/js/image_preview.js'
            ),
        ),
    );

    //General setting
    $form['settings']['general_setting'] = array(
        '#type' => 'fieldset',
        '#title' => t('General Settings'),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
    );

     $form['settings']['general_setting']['general_setting_tracking_code'] = array(
        '#type' => 'textarea',
        '#title' => t('Tracking Code'),
        '#default_value' => theme_get_setting('general_setting_tracking_code', 'probes'),
    );

    //Header setting
    $form['settings']['header'] = array(
        '#type' => 'fieldset',
        '#title' => t('Header settings'),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
    );

    $form['settings']['header']['header_layout'] = array(

        '#title' => t('Header layout'),
        '#type' => 'select',
        '#options' => array(
        'layout1' => t('Layout 1'),
        'layout2' => t('Layout 2'),
        ),
        '#default_value' => theme_get_setting('header_layout', 'probes'),

    );

    $form['settings']['header']['header_class'] = array(

        '#title' => t('Header class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('header_class', 'probes'),

    );

     $form['settings']['header']['topbar_enable'] = array(

        '#title' => t('Top bar enable'),
        '#type' => 'select',
        '#options' => array(
        'on' => t('ON'),
        'off' => t('OFF'),
        ),
        '#default_value' => theme_get_setting('topbar_enable', 'probes'),

    );

    $form['settings']['header']['topbar_class'] = array(

        '#title' => t('Topbar class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('topbar_class', 'probes'),

    );

    $form['settings']['header']['topbar_background_color'] = array(
        '#type' => 'jquery_colorpicker',
        '#title' => t('Topbar background color'),
        '#default_value' => theme_get_setting('topbar_background_color', 'probes'),
    );

    //Page default setting
    $form['settings']['page_default'] = array(
        '#type' => 'fieldset',
        '#title' => t('Page settings'),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
    );

    $form['settings']['page_default']['page_default_header_layout'] = array(

        '#title' => t('Header layout'),
        '#type' => 'select',
        '#options' => array(
        'layout1' => t('Layout 1'),
        'layout2' => t('Layout 2'),
        'layout3' => t('Layout 3'),
        'layout4' => t('Layout 4'),
        'layout5' => t('Layout 5'),
        ),
        '#default_value' => theme_get_setting('page_default_header_layout', 'probes'),

    );

    $form['settings']['page_default']['page_default_header_class'] = array(

        '#title' => t('Header class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('page_default_header_class', 'probes'),

    );

     $form['settings']['page_default']['page_default_topbar_enable'] = array(

        '#title' => t('Top bar enable'),
        '#type' => 'select',
        '#options' => array(
        'on' => t('ON'),
        'off' => t('OFF'),
        ),
        '#default_value' => theme_get_setting('page_default_topbar_enable', 'probes'),

    );

    $form['settings']['page_default']['page_default_topbar_background_color'] = array(
        '#type' => 'jquery_colorpicker',
        '#title' => t('Topbar background color'),
        '#default_value' => theme_get_setting('page_default_topbar_background_color', 'probes'),
    );

    $form['settings']['page_default']['page_default_topbar_class'] = array(

        '#title' => t('Topbar class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('page_default_topbar_class', 'probes'),

    );

    $form['settings']['page_default']['page_title_background'] = array(
        '#type'     => 'managed_file',
        '#title'    => t('Page title background image'),
        '#required' => FALSE,
        '#upload_location' => 'public://background-icon/',
        '#default_value' => theme_get_setting('page_title_background','probes'),
        '#progress_indicator' => 'throbber',
        '#progress_message' => 'Uploading ...',
        '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg'),
        ),
    );

    $form['settings']['page_default']['page_header_class'] = array(

        '#title' => t('Page header class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('page_header_class', 'probes'),

    );

    $form['settings']['page_default']['page_default_footer_layout'] = array(
        '#title' => t('Footer layout'),
        '#type' => 'select',
        '#options' => array(
        'layout1' => t('Layout 1'),
        'layout2' => t('Layout 2'),
        'layout3' => t('Layout 3'),
        'layout4' => t('Layout 4'),
        ),
        '#default_value' => theme_get_setting('page_default_footer_layout', 'probes'),
    );

    $form['settings']['page_default']['page_default_footer_backgorund'] = array(
        '#title' => t('Footer background image display'),
        '#type' => 'select',
        '#options' => array(
        'on' => t('ON'),
        'off' => t('OFF'),
        ),
        '#default_value' => theme_get_setting('page_default_footer_backgorund', 'probes'),
    );

    $form['settings']['page_default']['page_default_footer_background_image'] = array(
        '#type'     => 'managed_file',
        '#title'    => t('Footer background image upload'),
        '#required' => FALSE,
        '#upload_location' => 'public://background-icon/',
        '#default_value' => theme_get_setting('page_default_footer_background_image','probes'),
        '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg'),
        ),
    );

    $form['settings']['page_default']['page_default_footer_backgorund_color'] = array(
        '#type' => 'jquery_colorpicker',
        '#title' => t('Footer background color'),
        '#default_value' => theme_get_setting('page_default_footer_backgorund_color', 'probes'),
    );

    $form['settings']['page_default']['page_default_footer_class'] = array(
        '#type' => 'textfield',
        '#title' => t('Footer class'),
        '#default_value' => theme_get_setting('page_default_footer_class', 'probes'),
    );


    //Portfolio setting
    $form['settings']['portfolio'] = array(
        '#type' => 'fieldset',
        '#title' => t('Portfolio settings'),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
     );

    $form['settings']['portfolio']['portfolio_style'] = array(

        '#title' => t('Portfolio style'),
        '#type' => 'select',
        '#options' => array(
        '2cols' => t('Portfolio 2 Columns'),
        '3cols' => t('Portfolio 3 Columns'),
        '4cols' => t('Portfolio 3 Columns'),
        'sidebar' => t('Portfolio Sidebar'),
        'fullwidth' => t('Portfolio Full Width'),
        'masonry1' => t('Portfolio Masonry 1'),
        'masonry2' => t('Portfolio Masonry 2'),
        'slider' => t('Portfolio slider'),
        ),
        '#default_value' => theme_get_setting('portfolio_style', 'probes'),

    );

    $form['settings']['portfolio']['portfolio_slogan'] = array(

        '#title' => t('Portfolio slogan'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('portfolio_slogan', 'probes'),

    );

    $form['settings']['portfolio']['portfolio_header_layout'] = array(

        '#title' => t('Header layout'),
        '#type' => 'select',
        '#options' => array(
        'layout1' => t('Layout 1'),
        'layout2' => t('Layout 2'),
        'layout3' => t('Layout 3'),
        'layout4' => t('Layout 4'),
        'layout5' => t('Layout 5'),
        ),
        '#default_value' => theme_get_setting('portfolio_header_layout', 'probes'),

    );

    $form['settings']['portfolio']['portfolio_header_class'] = array(

        '#title' => t('Header class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('portfolio_header_class', 'probes'),

    );

     $form['settings']['portfolio']['portfolio_topbar_enable'] = array(

        '#title' => t('Top bar enable'),
        '#type' => 'select',
        '#options' => array(
        'on' => t('ON'),
        'off' => t('OFF'),
        ),
        '#default_value' => theme_get_setting('portfolio_topbar_enable', 'probes'),

    );

    $form['settings']['portfolio']['portfolio_topbar_background_color'] = array(
        '#type' => 'jquery_colorpicker',
        '#title' => t('Topbar background color'),
        '#default_value' => theme_get_setting('portfolio_topbar_background_color', 'probes'),
    );

    $form['settings']['portfolio']['portfolio_topbar_class'] = array(

        '#title' => t('Topbar class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('portfolio_topbar_class', 'probes'),

    );

    $form['settings']['portfolio']['portfolio_title_background'] = array(
        '#type'     => 'managed_file',
        '#title'    => t('Page title background image'),
        '#required' => FALSE,
        '#upload_location' => 'public://background-icon/',
        '#default_value' => theme_get_setting('portfolio_title_background','probes'),
        '#progress_indicator' => 'throbber',
        '#progress_message' => 'Uploading ...',
        '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg'),
        ),
    );

    $form['settings']['portfolio']['portfolio_page_header_class'] = array(

        '#title' => t('Page header class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('portfolio_page_header_class', 'probes'),

    );

    $form['settings']['portfolio']['portfolio_footer_layout'] = array(
        '#title' => t('Footer layout'),
        '#type' => 'select',
        '#options' => array(
        'layout1' => t('Layout 1'),
        'layout2' => t('Layout 2'),
        'layout3' => t('Layout 3'),
        'layout4' => t('Layout 4'),
        ),
        '#default_value' => theme_get_setting('portfolio_footer_layout', 'probes'),
    );

    $form['settings']['portfolio']['portfolio_footer_backgorund'] = array(
        '#title' => t('Footer background image display'),
        '#type' => 'select',
        '#options' => array(
        'on' => t('ON'),
        'off' => t('OFF'),
        ),
        '#default_value' => theme_get_setting('portfolio_footer_backgorund', 'probes'),
    );

    $form['settings']['portfolio']['portfolio_footer_background_image'] = array(
        '#type'     => 'managed_file',
        '#title'    => t('Footer background image upload'),
        '#required' => FALSE,
        '#upload_location' => 'public://background-icon/',
        '#default_value' => theme_get_setting('portfolio_footer_background_image','probes'),
        '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg'),
        ),
    );

    $form['settings']['portfolio']['portfolio_footer_backgorund_color'] = array(
        '#type' => 'jquery_colorpicker',
        '#title' => t('Footer background color'),
        '#default_value' => theme_get_setting('page_default_footer_backgorund_color', 'probes'),
    );

    $form['settings']['portfolio']['portfolio_footer_class'] = array(
        '#type' => 'textfield',
        '#title' => t('Footer class'),
        '#default_value' => theme_get_setting('portfolio_footer_class', 'probes'),
    );

  
    //Shop setting
    $form['settings']['shop'] = array(
        '#type' => 'fieldset',
        '#title' => t('Shop settings'),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
    );

    $form['settings']['shop']['shop_slogan'] = array(

        '#title' => t('Shop slogan'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('shop_slogan', 'probes'),

    );

    $form['settings']['shop']['shop_header_layout'] = array(

        '#title' => t('Header layout'),
        '#type' => 'select',
        '#options' => array(
        'layout1' => t('Layout 1'),
        'layout2' => t('Layout 2'),
        'layout3' => t('Layout 3'),
        'layout4' => t('Layout 4'),
        'layout5' => t('Layout 5'),
        ),
        '#default_value' => theme_get_setting('shop_header_layout', 'probes'),

    );

    $form['settings']['shop']['shop_header_class'] = array(

        '#title' => t('Header class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('shop_header_class', 'probes'),

    );

     $form['settings']['shop']['shop_topbar_enable'] = array(

        '#title' => t('Top bar enable'),
        '#type' => 'select',
        '#options' => array(
        'on' => t('ON'),
        'off' => t('OFF'),
        ),
        '#default_value' => theme_get_setting('shop_topbar_enable', 'probes'),

    );

    $form['settings']['shop']['shop_topbar_class'] = array(

        '#title' => t('Topbar class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('shop_topbar_class', 'probes'),

    );

    $form['settings']['shop']['shop_topbar_background_color'] = array(
        '#type' => 'jquery_colorpicker',
        '#title' => t('Topbar background color'),
        '#default_value' => theme_get_setting('shop_topbar_background_color', 'probes'),
    );


    $form['settings']['shop']['shop_background_breadcrumb'] = array(
        '#type'     => 'managed_file',
        '#title'    => t('Shop breadcrumb background '),
        '#required' => FALSE,
        '#upload_location' => 'public://background-icon/',
        '#default_value' => theme_get_setting('shop_background_breadcrumb','probes'),
        '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg'),
        ),
    );

    $form['settings']['shop']['shop_page_header_class'] = array(

        '#title' => t('Page header class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('shop_page_header_class', 'probes'),

    );

    $form['settings']['shop']['shop_footer_layout'] = array(
        '#title' => t('Footer layout'),
        '#type' => 'select',
        '#options' => array(
        'layout1' => t('Layout 1'),
        'layout2' => t('Layout 2'),
        'layout3' => t('Layout 3'),
        'layout4' => t('Layout 4'),
        ),
        '#default_value' => theme_get_setting('shop_footer_layout', 'probes'),
    );

    $form['settings']['shop']['shop_footer_backgorund'] = array(
        '#title' => t('Footer background image display'),
        '#type' => 'select',
        '#options' => array(
        'on' => t('ON'),
        'off' => t('OFF'),
        ),
        '#default_value' => theme_get_setting('shop_footer_backgorund', 'probes'),
    );

    $form['settings']['shop']['shop_footer_background_image'] = array(
        '#type'     => 'managed_file',
        '#title'    => t('Footer background image upload'),
        '#required' => FALSE,
        '#upload_location' => 'public://background-icon/',
        '#default_value' => theme_get_setting('shop_footer_background_image','probes'),
        '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg'),
        ),
    );

    $form['settings']['shop']['shop_footer_backgorund_color'] = array(
        '#type' => 'jquery_colorpicker',
        '#title' => t('Footer background color'),
        '#default_value' => theme_get_setting('shop_footer_backgorund_color', 'probes'),
    );

    $form['settings']['shop']['shop_footer_class'] = array(
        '#type' => 'textfield',
        '#title' => t('Footer class'),
        '#default_value' => theme_get_setting('blog_footer_class', 'probes'),
    );


    //Blog setting

    $form['settings']['blog'] = array(
        '#type' => 'fieldset',
        '#title' => t('Blog settings'),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
    );

    $form['settings']['blog']['blog_layout_style'] = array(

        '#title' => t('Blog layout style'),
        '#type' => 'select',
        '#options' => array(
            'standard' => t('Standard'),
            'fullwidth' => t('Fullwidth'),
            '3cols' => t('3 Columns'),
        ),
        '#default_value' => theme_get_setting('blog_layout_style', 'probes'),

    );

    $form['settings']['blog']['blog_header_layout'] = array(

        '#title' => t('Header layout'),
        '#type' => 'select',
        '#options' => array(
        'layout1' => t('Layout 1'),
        'layout2' => t('Layout 2'),
        'layout3' => t('Layout 3'),
        'layout4' => t('Layout 4'),
        'layout5' => t('Layout 5'),
        ),
        '#default_value' => theme_get_setting('blog_header_layout', 'probes'),

    );

    $form['settings']['blog']['blog_header_class'] = array(

        '#title' => t('Header class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('blog_header_class', 'probes'),

    );

     $form['settings']['blog']['blog_topbar_enable'] = array(

        '#title' => t('Top bar enable'),
        '#type' => 'select',
        '#options' => array(
        'on' => t('ON'),
        'off' => t('OFF'),
        ),
        '#default_value' => theme_get_setting('blog_topbar_enable', 'probes'),

    );

    $form['settings']['blog']['blog_topbar_class'] = array(

        '#title' => t('Topbar class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('blog_topbar_class', 'probes'),

    );

    $form['settings']['blog']['blog_topbar_background_color'] = array(
        '#type' => 'jquery_colorpicker',
        '#title' => t('Topbar background color'),
        '#default_value' => theme_get_setting('blog_topbar_background_color', 'probes'),
    );

    $form['settings']['blog']['blog_slogan'] = array(

        '#title' => t('Blog slogan'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('blog_slogan', 'probes'),

    );

    $form['settings']['blog']['blog_background_breadcrumb'] = array(
        '#type'     => 'managed_file',
        '#title'    => t('Blog breadcrumb background '),
        '#required' => FALSE,
        '#upload_location' => 'public://background-icon/',
        '#default_value' => theme_get_setting('blog_background_breadcrumb','probes'),
        '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg'),
        ),
    );

    $form['settings']['blog']['blog_page_header_class'] = array(

        '#title' => t('Page header class'),

        '#type' => 'textfield',

        '#default_value' => theme_get_setting('blog_page_header_class', 'probes'),

    );

    $form['settings']['blog']['blog_footer_layout'] = array(
        '#title' => t('Footer layout'),
        '#type' => 'select',
        '#options' => array(
        'layout1' => t('Layout 1'),
        'layout2' => t('Layout 2'),
        'layout3' => t('Layout 3'),
        'layout4' => t('Layout 4'),
        ),
        '#default_value' => theme_get_setting('blog_footer_layout', 'probes'),
    );

    $form['settings']['blog']['blog_footer_backgorund'] = array(
        '#title' => t('Footer background image display'),
        '#type' => 'select',
        '#options' => array(
        'on' => t('ON'),
        'off' => t('OFF'),
        ),
        '#default_value' => theme_get_setting('blog_footer_backgorund', 'probes'),
    );

    $form['settings']['blog']['blog_footer_background_image'] = array(
        '#type'     => 'managed_file',
        '#title'    => t('Footer background image upload'),
        '#required' => FALSE,
        '#upload_location' => 'public://background-icon/',
        '#default_value' => theme_get_setting('blog_footer_background_image','probes'),
        '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg'),
        ),
    );

    $form['settings']['blog']['blog_footer_backgorund_color'] = array(
        '#type' => 'jquery_colorpicker',
        '#title' => t('Footer background color'),
        '#default_value' => theme_get_setting('blog_footer_backgorund_color', 'probes'),
    );

    $form['settings']['blog']['blog_footer_class'] = array(
        '#type' => 'textfield',
        '#title' => t('Footer class'),
        '#default_value' => theme_get_setting('blog_footer_class', 'probes'),
    );

    //Footer setting
    $form['settings']['footer'] = array(
        '#type' => 'fieldset',
        '#title' => t('Footer settings'),
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
    );

    $form['settings']['footer']['footer_layout'] = array(
        '#title' => t('Footer layout'),
        '#type' => 'select',
        '#options' => array(
        'layout1' => t('Layout 1'),
        'layout2' => t('Layout 2'),
        'layout3' => t('Layout 3'),
        'layout4' => t('Layout 4'),
        ),
        '#default_value' => theme_get_setting('footer_layout', 'probes'),
    );

    $form['settings']['footer']['footer_backgorund'] = array(
        '#title' => t('Footer background image display'),
        '#type' => 'select',
        '#options' => array(
        'on' => t('ON'),
        'off' => t('OFF'),
        ),
        '#default_value' => theme_get_setting('footer_backgorund', 'probes'),
    );

    $form['settings']['footer']['footer_background_image'] = array(
        '#type'     => 'managed_file',
        '#title'    => t('Footer background image upload'),
        '#required' => FALSE,
        '#upload_location' => 'public://background-icon/',
        '#default_value' => theme_get_setting('footer_background_image','probes'),
        '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg'),
        ),
    );

    $form['settings']['footer']['footer_backgorund_color'] = array(
        '#type' => 'jquery_colorpicker',
        '#title' => t('Footer background color'),
        '#default_value' => theme_get_setting('footer_backgorund_color', 'probes'),
    );

    $form['settings']['footer']['footer_class'] = array(
        '#type' => 'textfield',
        '#title' => t('Footer class'),
        '#default_value' => theme_get_setting('footer_class', 'probes'),
    );

    $form['settings']['footer']['footer_copyright_message'] = array(
        '#type' => 'textarea',
        '#title' => t('Footer copyright message'),
        '#default_value' => theme_get_setting('footer_copyright_message', 'probes'),
    );


	$form['settings']['custom_css'] = array(
		  '#type' => 'fieldset',
		  '#title' => t('Custom CSS'),
		  '#collapsible' => TRUE,
		  '#collapsed' => FALSE,
	);

	$form['settings']['custom_css']['custom_css'] = array(
		  '#type' => 'textarea',
		  '#title' => t('Custom CSS'),
		  '#default_value' => theme_get_setting('custom_css', 'probes'),
		  '#description'  => t('<strong>Example:</strong><br/>h1 { font-family: \'Metrophobic\', Arial, serif; font-weight: 400; }')
	);

    //Skin
    $form['settings']['skin'] = array(

        '#type' => 'fieldset',

        '#title' => t('Switcher Style'),

        '#collapsible' => TRUE,

        '#collapsed' => FALSE,

    );


  //Disable Switcher style;

    $form['settings']['skin']['probes_disable_switch'] = array(

        '#title' => t('Switcher style'),

        '#type' => 'select',

        '#options' => array('on' => t('ON'), 'off' => t('OFF')),

        '#default_value' => theme_get_setting('probes_disable_switch', 'probes'),

    );

    $form['settings']['skin']['probes_site_layout'] = array(

        '#title' => t('Choose Layout'),

        '#type' => 'select',

        '#options' => array('wide' => t('Wide'), 'boxed' => t('Boxed')),

        '#default_value' => theme_get_setting('probes_site_layout', 'probes'),

    );
    //BG PATTERNS FOR BOXED
    $form['settings']['skin']['bg_patterns_boxed'] = array(
        '#type'     => 'managed_file',
        '#title'    => t('BG PATTERNS FOR BOXED'),
        '#required' => FALSE,
        '#upload_location' => 'public://background-icon/',
        '#default_value' => theme_get_setting('bg_patterns_boxed','probes'),
        '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg'),
        ),
    );

    $form['settings']['skin']['built_in_skins'] = array(
        '#type' => 'radios',
        '#title' => t('Built-in Skins'),
        '#options' => array(
            'blue.css' => t('Blue'),
            'green.css' => t('Green'),
            'lightblue.css' => t('Lightblue'),
            'lightgreen.css' => t('Light green'),
            'light-red.css' => t('Light-red'),
            'olive.css' => t('Olive'),
            'orange.css' => t('Orange'),
            'red.css' => t('Red'),
            'sea.css' => t('Sea'),
            'violet.css' => t('Violet'),
        ),


        '#default_value' => theme_get_setting('built_in_skins','probes')
    );

}

function probes_settings_form_submit(&$form, $form_state) {

    $image_fid[0] = $form_state['values']['footer_background_image'];
    $image_fid[1] = $form_state['values']['shop_background_breadcrumb'];
    $image_fid[2] = $form_state['values']['blog_background_breadcrumb'];
    $image_fid[3] = $form_state['values']['page_title_background'];
    $image_fid[4] = $form_state['values']['portfolio_footer_background_image'];
    $image_fid[5] = $form_state['values']['portfolio_title_background'];
    $image_fid[6] = $form_state['values']['bg_patterns_boxed'];
    $count = count($image_fid);

    for ($i=0; $i < $count; $i++) {
        $image[$i] = file_load($image_fid[$i]);
        if (is_object($image[$i])) {
          // Check to make sure that the file is set to be permanent.
            if ($image[$i]->status == 0) {
                // Update the status.
                $image[$i]->status = FILE_STATUS_PERMANENT;
                // Save the update.
                file_save($image[$i]);
                // Add a reference to prevent warnings.
                file_usage_add($image[$i], 'probes', 'theme', 1);
            }
        }
    }

}

<?php

global $base_url;



function probes_preprocess_html(&$variables) {

	global $base_url;

	// GOOGLE FONT

	drupal_add_css('https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,500,400italic,500italic,700,900', array('type' => 'external','scope' => 'header'));

	drupal_add_css('https://fonts.googleapis.com/css?family=Great+Vibes', array('type' => 'external','scope' => 'header'));



	//drupal_add_js('http://maps.google.com/maps/api/js?sensor=false', array('type' => 'external','scope' => 'footer'));

	if (arg(0) == 'node' && is_numeric(arg(1))) {

      $node = entity_load_unchanged('node', arg(1));

      $variables['node_type'] = $node->type;

      if (isset($node ->field_boxed['und'][0]['value']) && $node ->field_boxed['und'][0]['value']) {

        $variables['home_boxed'] = $node ->field_boxed['und'][0]['value'];

      }

      if (isset($node ->field_color_stylesheet) && $node ->field_color_stylesheet['und'][0]['value']) {

        $setting_skin = $node ->field_color_stylesheet['und'][0]['value'];

      } else {

      	$setting_skin = theme_get_setting('built_in_skins', 'probes');

      }

    } else {

    	$setting_skin = theme_get_setting('built_in_skins', 'probes');

    }

    if(arg(0) == 'blog' || (isset($node) && $node->type == 'blog')) {

    	$color = variable_get('color_option_blog', 1);

    	if(!empty($color)) $setting_skin= $color;

    }

    //Site layout

    $disable_switch = theme_get_setting('probes_disable_switch', 'probes');

	if(empty($disable_switch)) $disable_switch = 'off';

    $site_layout = theme_get_setting('probes_site_layout', 'probes');

    if(empty($site_layout)) $site_layout = 'wide';

    if($site_layout == 'boxed'){

    	$css_layout = array(

			'#tag' => 'link', // The #tag is the html tag - <link />

			'#attributes' => array( // Set up an array of attributes inside the tag

			'href' => $base_url.'/'.path_to_theme().'/css/style_boxed.css',

			'rel' => 'stylesheet',

			'type' => 'text/css',

			'id' => 'site_layout',

			'data-baseurl'=>$base_url.'/'.path_to_theme()

			),

			'#weight' => 1,

		);

		drupal_add_html_head($css_layout, 'layout');

    } elseif($disable_switch == 'on') {

    	$css_layout = array(

			'#tag' => 'link', // The #tag is the html tag - <link />

			'#attributes' => array( // Set up an array of attributes inside the tag

			'href' => $base_url.'/'.path_to_theme().'/css/style.css',

			'rel' => 'stylesheet',

			'type' => 'text/css',

			'id' => 'site_layout',

			'data-baseurl'=>$base_url.'/'.path_to_theme()

			),

			'#weight' => 1,

		);

		drupal_add_html_head($css_layout, 'layout');

    }

    // Add css skind

	if(!empty($setting_skin)){

		$skin_color = '/css/colors/'.$setting_skin;

	}	else{

		$skin_color = theme_get_setting('built_in_skins','probes');

	}

	if(!empty($skin_color)):

		$css_skin = array(

			'#tag' => 'link', // The #tag is the html tag - <link />

			'#attributes' => array( // Set up an array of attributes inside the tag

			'href' => $base_url.'/'.path_to_theme().$skin_color,

			'rel' => 'stylesheet',

			'type' => 'text/css',

			'id' => 'skin',

			'data-baseurl'=>$base_url.'/'.path_to_theme()

			),

			'#weight' => 2,

		);

		drupal_add_html_head($css_skin, 'skin');

	endif;

	if($disable_switch == 'on') {

		drupal_add_css(drupal_get_path('theme', 'probes').'/js/style-switcher/color-switcher.css', array('type' => 'external','scope' => 'header'));

		drupal_add_js(drupal_get_path('theme', 'probes').'/js/style-switcher/styleswitcher.js', array('type' => 'external','scope' => 'footer'));

	}

}



function probes_form_comment_form_alter(&$form, &$form_state) {

  $form['comment_body']['#after_build'][] = 'probes_customize_comment_form';

	// echo '<pre>'; print_r($form['#id']);echo '</pre>';

   	$form['#action']=false;

	$form['author']['name']['#required'] = TRUE;

}



function probes_customize_comment_form(&$form) {

  $form[LANGUAGE_NONE][0]['format']['#access'] = FALSE;

  return $form;

}



function probes_preprocess_page(&$vars) {



	if (isset($vars['node'])) {

		$vars['theme_hook_suggestions'][] = 'page__'. $vars['node']->type;

	}



	//404 page

	$status = drupal_get_http_header("status");

	if($status == "404 Not Found") {

		$vars['theme_hook_suggestions'][] = 'page__404';

	}



	//Taxonomy page

	if (arg(0) == 'taxonomy') {

    	$vars['theme_hook_suggestions'][] = 'page__taxonomy';

  	}

  	if (arg(0) == 'taxonomy' && arg(1) == 'term') {

    	$term = taxonomy_term_load(arg(2));

	    $vocabulary = taxonomy_vocabulary_load($term->vid);

	    $vars['theme_hook_suggestions'][] = 'page__taxonomy_' . $vocabulary->machine_name;

  	}

  	if (arg(0) == 'user') {

    	$vars['theme_hook_suggestions'][] = 'page__user';

  	}

  	if(function_exists('views_get_page_view') && views_get_page_view()) {

  		//View portfolio template	

		$view = views_get_page_view();

		if(isset($view) && $view->name =='_probes_portfolio')   {

			$vars['theme_hook_suggestions'][] = 'page__views_portfolio';

		}



		//View shop template

	  	if(isset($view) && $view->name =='_probes_shop')   {

	    	$vars['theme_hook_suggestions'][] = 'page__shop';

		}

  	}



}



function probes_preprocess_node(&$vars) {

  	if($vars['node']->type == 'product_display') {

		drupal_add_css(drupal_get_path('theme', 'probes') . "/js/cubeportfolio/css/cubeportfolio.min1.css", "theme");

		$vars['styles'] = drupal_get_css();

		// drupal_add_js(drupal_get_path('theme', 'probes') . "/js/cubeportfolio/jquery.cubeportfolio.min1.js", array('weight' => 10));

		// $vars['scripts'] = drupal_get_js();

	}

}





// Remove superfish css files.

function probes_css_alter(&$css) {

	unset($css[drupal_get_path('module', 'system') . '/system.menus.css']);

	unset($css[drupal_get_path('module', 'system') . '/system.theme.css']);



//	unset($css[drupal_get_path('module', 'system') . '/system.base.css']);

}



function probes_form_alter(&$form, &$form_state, $form_id) {

	if ($form_id == 'search_block_form') {

		//print_r($form);

		//$form['actions']['submit']['#value'] = decode_entities('&#xf002;');

		$form['actions']['submit']['#attributes']['class'][] = 'btn btn-default uppercase';

		$form['search_block_form']['#title_display'] = 'invisible'; // Toggle label visibilty

		$form['search_block_form']['#default_value'] = t(''); // Set a default value for the textfield

		$form['search_block_form']['#attributes']['id'] = array("m_search");

		$form['search_block_form']['#attributes']['placeholder'] = 'Search...';

		$form['search_block_form']['#attributes']['class'][] = 'form-control email';

		//disabled submit button

		//unset($form['actions']['submit']);

		unset($form['search_block_form']['#title']);

		$form['search_block_form']['#attributes']['onfocus'] = "if (this.value == 'Search') {this.value = '';}";

	}

	if($form_id == 'contact_site_form'){

		$form['mail']['#attributes']['class'] = array("input-contact-form");

		$form['name']['#attributes']['class'] = array("input-contact-form");

		$form['subject']['#attributes']['class'] = array("input-contact-form");

		$form['message']['#attributes']['class'] = array("message-contact-form");

		$form['actions']['submit']['#attributes']['class'] = array('btn btn-success contact-form-button');

	}

	if ($form_id == 'comment_form') {

		$form['comment_filter']['format'] = array(); // nuke wysiwyg from comments

		

	}

	



}





function probes_textarea($variables) {

  $element = $variables['element'];

  $element['#attributes']['name'] = $element['#name'];

  $element['#attributes']['id'] = $element['#id'];

  $element['#attributes']['cols'] = $element['#cols'];

  $element['#attributes']['rows'] = $element['#rows'];

  _form_set_class($element, array('form-textarea'));



  $wrapper_attributes = array(

    'class' => array('form-textarea-wrapper'),

  );



  // Add resizable behavior.

  if (!empty($element['#resizable'])) {

    $wrapper_attributes['class'][] = 'resizable';

  }



  $output = '<div' . drupal_attributes($wrapper_attributes) . '>';

  $output .= '<textarea' . drupal_attributes($element['#attributes']) . '>' . check_plain($element['#value']) . '</textarea>';

  $output .= '</div>';

  return $output;

}

function probes_breadcrumb($variables) {

	$crumbs ='';

	$breadcrumb = $variables['breadcrumb'];

	if (!empty($breadcrumb)) {

		if(isset($breadcrumb[2])) unset($breadcrumb[2]);		

		foreach($breadcrumb as $value) {



			$crumbs .= $value.' <i>/</i> ';

		}

		$crumbs .= '<span>'.drupal_get_title().'</span>';

		return $crumbs;

	}else{

		return NULL;

	}

}

//custom main menu

function probes_menu_tree__main_menu(array $variables) {

	$str = '';

	$str .= '<ul class="nav navbar-nav">';

		$str .= $variables['tree'];

	$str .= '</ul>';



	return $str;

}





/**Override Menu theme */



function probes_menu_tree__menu_top_menu($variables) {

	$str = '';

	$str .= '<ul class="top-bar-section right">';

		$str .= $variables['tree'];

	$str .= '</ul>';



	return $str;

}



function probes_menu_tree__menu_footer_menu($variables) {

	$str = '';

	$str .= '<ul>';

		$str .= $variables['tree'];

	$str .= '</ul>';



	return $str;

}



function probes_menu_tree__menu_min_menu($variables) {

	$str = '';

	$str .= '<ul class="block-center-xy">';

		$str .= $variables['tree'];

	$str .= '</ul>';



	return $str;

}



function probes_menu_tree__menu_side_menu($variables) {

	$str = '';

	$str .= '<ul class="nav navbar-nav">';

		$str .= $variables['tree'];

	$str .= '</ul>';



	return $str;

}





function probes_theme() {

  	return array(

	    'comment_form' => array(

	      	'arguments' => array('form' => NULL),

	      	'template' => 'tpl/comment-form',

	      	'render element' => 'form'

	    ),



	    'user_login' => array(

			'render element' => 'form',

			'path' => drupal_get_path('theme', 'probes') . '/tpl',

			'template' => 'user-login',

			'preprocess functions' => array(

				'probes_preprocess_user_login'

			),

		),



		'user_register_form' => array(

			'render element' => 'form',

			'path' => drupal_get_path('theme', 'probes') . '/tpl',

			'template' => 'user-register-form',

			'preprocess functions' => array(

				'probes_preprocess_user_register'

			),

		),



		'user_pass' => array(

			'render element' => 'form',

			'path' => drupal_get_path('theme', 'probes') . '/tpl',

			'template' => 'user-pass',

			'preprocess functions' => array(

				'probes_preprocess_user_pass'

			),

		),

	);

}



function probes_preprocess_user_login(&$vars) {

  	$vars['intro_text'] = t('Login form');

}

function probes_preprocess_user_register(&$vars) {

  	$vars['intro_text'] = t("Don't have an Account? Register Now!");

}

function probes_preprocess_user_pass(&$vars) {

  	$vars['intro_text'] = t('Password recovery');

}



//Themming comment form product





//Themming form add to cart



function probes_form_commerce_cart_add_to_cart_form_alter(&$form, &$form_state, $form_id) {



  //drupal_set_message($form_id);

	// print_r($form['quantity']);

	// $form['quantity']['#title'] == 'numberfield';

	// $form['submit']['#attributes']['value'] = decode_entities('&#xf217;');

	$form['submit']['#attributes']['class'] = array("btn black-button font-bold font18 uppercase");

	// $form['submit']['#attributes']['data-toggle'] = "tooltip";





}



//WEbform

function probes_form_webform_client_form_alter(&$form, &$form_state) {

  $node = $form['#node'];

  	if ($node->nid == 278) {

  		$form['#submit'][] = 'contactform_submit_handler';

		//rint_r($form['submitted']['send_a_mail_yourself']);

  	}

}

function contactform_submit_handler($form, &$form_state) {

	print 'dad';

}

function probes_preprocess_views_exposed_form(&$variables, $hook) {

	if ($variables['form']['#id'] == 'views-exposed-form--probes-shop-page-search-products') {

		$variables['form']['submit']['#value'] = decode_entities('&#xf002;');

	    unset($variables['form']['submit']['#printed']);

    	$variables['button'] = drupal_render($variables['form']['submit']);

    	

	}

}

function hook_preprocess_page(&$variables) {

       if (arg(0) == 'node' && is_numeric($nid)) {

    if (isset($variables['page']['content']['system_main']['nodes'][$nid])) {

      $variables['node_content'] =& $variables['page']['content']['system_main']['nodes'][$nid];

      if (empty($variables['node_content']['field_show_page_title'])) {

        $variables['node_content']['field_show_page_title'] = NULL;

      }

    }

  }

  

}

function probes_preprocess_region(&$variables) {

	if ($variables['region'] == 'footer') {



	}

}

function probes_preprocess_block(&$variables) {

	if ($variables['block']->region == 'footer') {

		 $count = count($variables['block']->delta);

		if($count == 2) {

			$variables['classes_array'][] = 'hello'.$count;

		} else $variables['classes_array'][] = 'bibi'.$count;



	}

}



function getRelatedPosts($ntype,$nid,$image){

	$nids = db_query("SELECT n.nid, title FROM {node} n WHERE n.status = 1 AND n.type = :type AND n.nid <> :nid ORDER BY RAND() LIMIT 0,6", array(':type' => $ntype, ':nid' => $nid))->fetchCol();

	$nodes = node_load_multiple($nids);

	$return_string = '<div class="row related-posts">' ;

	$i = 0;

	if (!empty($nodes)):

		foreach ($nodes as $node) :

			$field_image = field_get_items('node', $node, $image);

		if (!empty($field_image[0]) && $i < 3) {

			$i++;

			$return_string .= '<div class="col-sm-6 col-md-4 col-lg-4 wow fadeIn pb-50" >';

			$return_string .= '<div class="post-prev-img">';

			$return_string .= '<a href="'.url("node/" . $node->nid).'">';

			$return_string .= theme('image_style', array('style_name' => 'image_650x415', 'path' => $field_image[0]['uri'], 'attributes'=>array('alt'=>$node->title)));

			$return_string .= '</a></div>';

			$return_string .= '<div class="post-prev-title"><h3>';

			$return_string .= '<a href="'.url("node/" . $node->nid).'">'.$node->title.'</a></h3></div>';

			$return_string .= '<div class="post-prev-info">'.format_date($node->created, 'custom', 'F d').'<span class="slash-divider">/</span>';

			$return_string .= $node->name.'</div></div>';

		}

		endforeach;

	endif;

	return $return_string.'</div>';

}



function probes_menu_link(array $variables) {

  	$element = $variables['element'];

  	$sub_menu = '';

  	if($element['#original_link']['menu_name'] == 'main-menu' || $element['#original_link']['menu_name'] == 'menu-side-menu') {

  		$class_mega_sub = implode(' ',$element['#attributes']['class']);

	  	if ($element['#below'] && $element['#original_link']['depth'] == 1) {

	  		unset($element['#below']['#theme_wrappers']);

	  		$element['#attributes']['class'][] = 'dropdown level-' . $element['#original_link']['depth'];

	  		$element['#localized_options']['attributes']['class'][] = 'dropdown-toggle';

	  		$sub_menu = '<ul class="dropdown-menu" role="menu">'.drupal_render($element['#below']).'</ul>';

	  		if (strpos(url($element['#href']), 'menu-title')) {

		    	$link = substr(url($element['#href']), 13);

		    	$output = '<span class="white">' . $element['#title'] . '</span>';

			} else $output = '<a href="'.url($element['#href']).'" class="dropdown-toggle">'.$element['#title'].' <i class="fa fa-angle-down"></i></a>';  	

	  	} elseif($element['#below'] && $element['#original_link']['depth'] == 2) {

	  		//$element['#attributes']['class'][] = 'level-' . $element['#original_link']['depth'];

	  		//$output = l($element['#title'], $element['#href'], $element['#localized_options']);

	  		unset($element['#below']['#theme_wrappers']);

	  		if (strpos($class_mega_sub, 'list-unstyled')) {

	  			$sub_menu = '<ul class="'.$class_mega_sub.'" ><li><span class="menu-title">'.$element['#title'].'</span></li>'.drupal_render($element['#below']).'</ul>';

	  			$output = '';

	  		} else {

	  			$element['#attributes']['class'][] = 'dropdown-submenu mul level-' . $element['#original_link']['depth'];

	  			$output = l($element['#title'], $element['#href'], $element['#localized_options']);

	  			$sub_menu = '<ul class="dropdown-menu" >'.drupal_render($element['#below']).'</ul>';

	  		}

	  	} elseif($element['#below'] && strpos($class_mega_sub, 'mega-menu-title')) {

	  		unset($element['#below']['#theme_wrappers']);

	  		$element['#attributes']['class'][] = 'level-' . $element['#original_link']['depth'];

	  		$output = '<span class="menu-title">'.$element['#title'].'</span>';

	  		$sub_menu = drupal_render($element['#below']);



	  	}	else {

	  		$element['#attributes']['class'][] = 'level-' . $element['#original_link']['depth'];

	  		if(strpos(url($element['#href']), 'menu-title')) {

	  			$output = '<span class="menu-title">'.$element['#title'].'</span>';

	  		} else $output = l($element['#title'], $element['#href'], $element['#localized_options']);

	  	}  	



	} else {

		if ($element['#below']) {

	    	$sub_menu = drupal_render($element['#below']);

	  	}

		$output = l($element['#title'], $element['#href'], $element['#localized_options']);

	}

	return '<li' . drupal_attributes($element ['#attributes']) . '>' . $output . $sub_menu . "</li>\n";



}



function single_navigation ($ntype,$nid, $nav) {

	$current_node = node_load($nid);

	$prev_nid = db_query("SELECT n.nid FROM {node} n WHERE n.type = :type AND n.created < :created LIMIT 1", array(':created' => $current_node->created, ':type' => $ntype))->fetchField();

	$next_nid = db_query("SELECT n.nid FROM {node} n WHERE n.type = :type AND n.created > :created LIMIT 1", array(':created' => $current_node->created, ':type' => $ntype))->fetchField();

	$link = '';

	if ($prev_nid > 0 && $nav == 'prev') {

		$node = node_load($prev_nid);

	  	$link .= '<a href="'.url("node/" . $node->nid).'" class="work-prev"><span><span class="icon icon-arrows-left"></span>&nbsp;Prev</span></a>';

	} elseif ($next_nid > 0 && $nav == 'next') {

		$node = node_load($next_nid);

	  	$link .= '<a href="'.url("node/" . $node->nid).'" class="work-next"><span>Next&nbsp;<span class="icon icon-arrows-right"></span></span></a>';

	}

	return $link;



}



//Alternative search function 1 single string

if(!function_exists('str_replace_first')) {

    function str_replace_first($from, $to, $subject) {

    	$from = '/'.preg_quote($from, '/').'/';

    	return preg_replace($from, $to, $subject, 1);

	}

}



//Trim sstring

if (!function_exists('trim_text'))

{

	function trim_text($input, $length, $ellipses = true, $strip_html = true) {

	//strip tags, if desired

		if ($strip_html) {

			$input = strip_tags($input);

		}

		//no need to trim, already shorter than trim length

		if (strlen($input) <= $length) {

			return $input;

		}

		//find last space within length

		$last_space = strrpos(substr($input, 0, $length), ' ');

		$trimmed_text = substr($input, 0, $last_space);

		//add ellipses (...)

		if ($ellipses) {

			$trimmed_text .= '...';

		}

		return $trimmed_text;

	}

}




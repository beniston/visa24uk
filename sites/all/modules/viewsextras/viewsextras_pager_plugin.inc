<?php 
/**
 * The plugin to handle full pager.
 *
 * @ingroup views_pager_plugins
 */
class viewsextras_pager_plugin extends views_plugin_pager_full {

  /**
   * Provide the default form for setting options.
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['expose']['#title'] = t('Items per page links');
    $form['expose']['#description'] = t('This allows users to define their values in pager links');
  }

  function pre_execute(&$query) {
    if (isset($_GET['items_per_page'])) {
      $this->set_items_per_page($_GET['items_per_page']);
    }
  }
  
  function render($input) {
    $pager_theme = views_theme_functions('pager', $this->view, $this->display);
    $output = theme($pager_theme, array('parameters' => $input, 'element' => $this->options['id']));
    if (!empty($output)) $output = $output . $this->ipp($input);
    return $output;
  }
  
  function ipp($input) {
    $pager = $this;
    $options = explode(',', $pager->options['expose']['items_per_page_options']);
    $sanitized_options = array($pager->options['expose']['items_per_page_label']);
    $current = $pager->options['items_per_page'];
    
    $query = array();
    if (count($input)) {
      $query = drupal_get_query_parameters($input, array());
    }
    if ($query_pager = pager_get_query_parameters()) {
      $query = array_merge($query, $query_pager);
    }
    
    if (is_array($options)) {
      foreach ($options as $option) {
        $sanitized_options[intval($option)] = intval($option);
      }
      if (!empty($pager->options['expose']['items_per_page_options_all']) && !empty($pager->options['expose']['items_per_page_options_all_label'])) {
        $sanitized_options['All'] = $pager->options['expose']['items_per_page_options_all_label'];
      }      
      foreach ($sanitized_options as $key => $val) {
        if ($key == '0') continue;
        $q = array_merge($query, array('items_per_page' => $key));
        $link = l($val, $_GET['q'], array('query' => $q));
        //TODO All key isn't working when $val == $current, not sure why?
        $sanitized_options[$key] = $val == $current ? array('class' => array('pager-current'), $val) : $link;
      }
    }

    $output = theme('item_list', array('items' => $sanitized_options, 'attributes' => array('class' => array('pager'))));
    return $output;
  }
  
  function exposed_form_alter(&$form, &$form_state) {
    parent::exposed_form_alter($form, $form_state);
    
    $form['items_per_page']['#type'] = 'hidden'; //hide ipp from exposed form since we show it in the pager
    
    //if there are no other exposed form inputs, hide the buttons
    $button_keys = array('reset', 'submit');
    $show_buttons = FALSE;
    foreach ($form as $key => $item) {
      if (!in_array($key, $button_keys) && is_array($item)  && array_key_exists('#type', $item) && $item['#type'] != 'hidden') {
        $show_buttons = TRUE;
        break;
      }
    }
    if (!$show_buttons) {
      foreach ($button_keys as $key) {
        $form[$key]['#type'] = 'hidden'; 
      }
    }
  }

}
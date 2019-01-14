<?php

class viewsextras_exposed_form_plugin extends views_plugin_exposed_form {
  function summary_title() {
    return t('Views Extras');
  }

  function option_definition() {
    $options = parent::option_definition();
    $options['veft'] = array('default' => array());
    return $options;
  }

  function get_display_exposed_filters() {
    $filters = array();
    foreach ($this->display->handler->get_handlers('filter') as $filter) {
      if ($filter->options['exposed']) {
        $filters[$filter->options['expose']['identifier']] = $filter;
      }
    }
    return $filters;
  }
  
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    
    $filters = $this->get_display_exposed_filters();
    $form_type = isset($form['#type']) ? $form['#type'] : NULL;
    $options = array();
    foreach ($filters as $key => $filter) {       
      $veft_options[$key] = $this->options_form_construct($key, $filter, $form_type); // set up filter option config individually for each exposed filter
      $options[$filter->options['id']] = $this->filter_label($filter); //make an array list of exposed filters
    }
    $form['veft'] = $veft_options;
          
    $options = array_merge($options, viewsextras_controls());
    $form['veft']['fieldset'] = array(
      '#type' => 'select',
      '#title' => t('Move items to advanced fieldset'),
      '#multiple' => TRUE,
      '#options' => $options,
      '#description' => t('Move the selected items into a fieldset on the exposed form'),
      '#default_value' => @$this->options['veft']['fieldset'],
    );
    
    $form['veft']['fieldset_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Advanced Fieldset Title'),
      '#default_value' =>  $this->options['veft']['fieldset_title'] ? $this->options['veft']['fieldset_title'] : t('Advanced Options'),
    );
    
    $form['veft']['fieldset_collapsed'] = array(
      '#type' => 'checkbox',
      '#title' => t('Initially collapse advanced fieldset'),
      '#default_value' =>  $this->options['veft']['fieldset_collapsed'],
    );
    
    $form['veft']['fieldset_collapsed_inline'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show advanced fieldset inline when collapsed'),
      '#default_value' =>  $this->options['veft']['fieldset_collapsed_inline'],
    );
  }
  
  function options_form_construct($key, $filter, $form_type) {
    $form = array();
    
    $options = (isset($this->options['veft'][$key])) ? $this->options['veft'][$key] : array();

    $dom_id = 'edit-exposed-form-options-' . 
              (isset($form_type) && $form_type == 'fieldset' ? 'viewsextras-' : '') . 
              'veft-' . str_replace('_', '-', $key) . '-veft-selective';
              
    $form['veft_selective'] = array(
      '#type' => 'checkbox',
      '#title' => t('!labelLimit filter options by the result set', array('!label' => '<b>' . $this->filter_label($filter) . '</b>: ')),
      '#default_value' => !empty($options) ? $options['veft_selective'] : FALSE,
    );
    $ops = array(
      'veft_active' => t('Further limit filter options by exposed form input'),
      'veft_count' => t('Append result count - append the number of results to each filter option'),
      'veft_autosubmit' => t('Autosubmit - autosubmit the form when a user changes this filter'),
      'veft_hide_empty' => t('Hide if empty - hide this filter if there are no more options'),
      'veft_optional' => t('Force optional - forces filter options to include an "Any" value.'),
    );
    foreach ($ops as $op_key => $op_val) {
      $form[$op_key] = array(
        '#type' => 'checkbox',
        '#title' => $op_val,
        '#default_value' => isset($options[$op_key]) ? $options[$op_key] : TRUE,
        '#dependency' => array($dom_id => array(1),),
      );
    }
    return $form;
  }  
  
  function filter_label($filter) {
    // Don't use $filter->definition['title'] as label, if the filters are from
    // the same type they are going to read the same in the UI.
    // Use instead the filter's options
    $label = (isset($filter->options['ui_name']) && $filter->options['ui_name'] != '')
                  ? $filter->options['ui_name'] : (isset($filter->options['expose']['label']) && 
                  !empty($filter->options['expose']['label']) ? $filter->options['expose']['label'] : 
                  t('No label found'));

    return '"' . $filter->definition['group'] . ': ' . $label . '"';
  }
  
  function exposed_form_alter(&$form, &$form_state) {
    if (isset($this->view->veft_filter_object)) return; //if this is one of our filter views, we don't do anything here
    
    parent::exposed_form_alter($form, $form_state);
    
    drupal_alter('viewsextras_pre_exposed_form', $form, $form_state);
    $this->options_reduce($form, $form_state, $this->options['veft']);
    drupal_alter('viewsextras_post_exposed_form', $form, $form_state);
       
    //if advanced fieldset options are set, we store them on the form for use in hook_views_exposed_form
    $settings = isset($this->options['veft']) ? $this->options['veft'] : $this->options['viewsextras']['veft'];
    if (isset($settings) && !empty($settings['fieldset']) && count($settings['fieldset']) > 0) {
      $form['#parameters']['veft_settings'] = $settings;
      $form['#parameters']['veft_view'] = $this->view;
    }
  }
  
  function options_reduce(&$form, $form_state, $settings) {
    static $guard = FALSE;
    if ($guard) return;
    $guard = TRUE;

    // loop thru filters and look for our active settings.
    $active = 0;
    foreach ($form_state['view']->filter as $filter_id => &$filter) {
      if (empty($filter->options['exposed'])) continue;
      $active++; // count active exposed filters
      if (empty($settings[$filter_id]['veft_selective'])) continue;
  
      // Form element is designated by the element ID which is user-configurable.
      $filter_element = $form['#info']["filter-$filter_id"]['value'];
      
      // Execute a clone of the view with a few changes: * no grouped fields for multiple values, * no distinct, * no paging, * no caching
      $view_clone = $filter->view->clone_view();
      if (empty($settings[$filter_id]['veft_active'])) {
        $view_clone->set_exposed_input(array('dummy' => TRUE));
      }
      else {//TODO isn't this already true, do we need it?
        $view_clone->set_exposed_input($filter->view->exposed_input);
      }
  
      // Fix case of exposed form in block for view with arguments.
      //TODO not sure why we need static.  test this with form in block to confirm
      if ($filter->view->display_handler->get_option('exposed_block') && !empty($filter->view->argument)) {
        static $arguments;
        if (!empty($filter->view->args)) {
          // Remember the arguments because next time we're here we'll need them.
          $arguments = $filter->view->args;
        }
        else if (!empty($arguments)) {
          $view_clone->set_arguments($arguments);
        }
      }
  
      //
      $items = $view_clone->get_items('field', $filter->view->current_display);
      foreach ($items as $item) {
        if (!empty($item['multiple']['group'])) {
          $item['multiple'] = array(
            'group' => FALSE,
            'multiple_number' => '',
            'multiple_from' => '',
            'multiple_reversed' => FALSE,
          );
          $view_clone->set_item($filter->view->current_display, 'field', $item['field'], $item);
        }
      } 
      $view_clone->display_handler->set_option('distinct', FALSE);
      
      //Views cache cids contain an md5 hash of many of the view's settings (@see views_plugin_cache.inc->get_results_key())  
      //Because of this I don't think we need to disable cache, but if so uncomment the line below 
      //$view_clone->display_handler->set_option('cache', array('type' => 'none',));
      $view_clone->set_display($filter->view->current_display);
      $view_clone->veft_filter_object = &$filter; //we use this to alter the view query based on the current filter in hook_views_query_alter.
      $view_clone->veft_filter_formitem = &$form[$filter_element]; //pointer to the filter form item
      $view_clone->veft_filter_count = $settings[$filter_id]['veft_count'];
      $view_clone->set_items_per_page(0);
      $old_ipp = NULL;
      if (isset($_GET['items_per_page'])) { //TODO: there must be a better way to kill pagination, but some pagers ignore the above setting and get value from the url param      
        $old_ipp = $_GET['items_per_page'];
        unset($_GET['items_per_page']);
      }    
      $view_clone->preview();
      if (isset($old_ipp)) {
        $_GET['items_per_page'] = $old_ipp;
      }

      $options = isset($view_clone->veft_filter_options) ? $view_clone->veft_filter_options : NULL;
  
      // Don't continue if we're displaying a summary.
      if (!empty($view_clone->build_info['summary'])) {
        $guard = FALSE;
        return;
      }
      
      //$var = $field_id ? $view_clone->field[$field_id] : NULL;
      //drupal_alter('view_exposedformtweaks_filterops', $options, $view_clone->filter[$filter_id], $var, $oids);
      $any_label = variable_get('views_exposed_filter_any_label', 'new_any') == 'old_any' ? '<Any>' : t('- Any -');
      


      if (in_array($form[$filter_element]['#type'], array('select', 'checkboxes', 'radios'))) {
        if ($filter->options['expose']['required'] != 1) {
          $options['All'] = $any_label;
        }
        
        $selected = $view_clone->exposed_raw_input[$filter_element];
        if (isset($selected) && is_array($selected)) {
          $options = array_merge($options, $selected); //preserve any currently selected options - we don't ever want to remove those
        }
        $options = _viewsextras_filterops_reduce($form[$filter_element]['#options'], $options);
      }
      else {
        if (!is_array($options)) {
          $options = array(); 
        }
        else {
          asort($options);
        }       

        if ($filter->options['expose']['required'] != 1 || !empty($settings[$filter_id]['veft_optional'])) {
          $options = array('' => $any_label) + $options;
        }

        //TODO it would be nice to choose multiple and radio/checkbox as well here
        $form[$filter_element]['#type'] = 'select';
        $form[$filter_element]['#multiple'] = FALSE;
        $form[$filter_element]['#default_value'] = 'All';
        $form[$filter_element]['#validated'] = TRUE; // avoid invalid selection error
        unset($form[$filter_element]['#size']);
      }
      
      $form[$filter_element]['#options'] = $options;
      
      if (!empty($settings[$filter_id]['veft_hide_empty']) && (empty($options) || array_keys($options) === array('All') || array_keys($options) === array(''))) {
        $form[$filter_element]['#access'] = FALSE;
        $form["{$filter_element}_op"]['#access'] = FALSE;
        unset($form['#info']["filter-$filter_element"]);
        $active--;
      }
  
      if ($settings[$filter_id]['veft_autosubmit']) {
        $form[$filter_element]['#attributes']['class'][] = 'ctools-auto-submit';
      }
    }
  
    //not sure why, but this used to require the ctools-use-ajax class as well, but now adding that class breaks things so it's no longer included
    $form['submit']['#attributes']['class'][] = 'ctools-auto-submit-click';
    $form['#access'] = $active > 0; // hide whole form if all exposed filters are hidden
    $guard = FALSE;
  }
}
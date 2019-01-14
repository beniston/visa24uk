<?php

/**
 * Implements hook_views_plugins().
 */
function viewsextras_views_plugins() {
  return array(
    'exposed_form' => array(
      'viewsextras_exposed_form' => array(
        'title' => t('Views Extras'),
        'help' => t('Includes collapisble advanced fieldset and improved filter option lists'),
        'handler' => 'viewsextras_exposed_form_plugin',
        'uses row plugin' => FALSE,
        'uses fields' => TRUE,
        'uses options' => TRUE,
        'type' => 'normal',
        'parent' => 'basic',
      ),
    ),
    'pager' => array(
      'viewsextras_pager' => array(
        'title' => t('Paged output, full pager with items per page links'),
        'short title' => t('Full Extra'),
        'help' => t('Paged output, full pager with items per page links'),
        'handler' => 'viewsextras_pager_plugin',
        'help topic' => 'pager-full',
        'uses options' => TRUE,
      ),
    ),
  );
}

<?php
/**
 * @file
 * Forms for Webcam Snapshot example module.
 */

/**
 * Form builder function for webcam snapshot example.
 */
function webcam_snapshot_example_form($form_state) {
  return array(
    'snapshot' => array(
      '#type' => 'webcam_snapshot',
      '#title' => t('Your photo'),
      '#description' => t('Centre yourself within the frame and then click "Capture" to take a snapshot. You may resize the frame as appropriate.'),
      '#default_value' => isset($form_state['storage']['snapshot']) ? $form_state['storage']['snapshot'] : NULL,
      '#camera_width' => 0,
      '#camera_height' => 720,
      '#video_width' => 0,
      '#video_height' => 360,
      '#image_width' => 240,
      '#image_height' => 320,
      '#selection' => array(
        'top' => 20,
        'left' => 200,
        'right' => 440,
        'bottom' => 340,
      ),
      '#overlay_opacity' => 0.7,
    ),
    'save' => array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    ),
  );
}

/**
 * Form submission function for webcam snapshot example.
 */
function webcam_snapshot_example_form_submit($form, &$form_state) {
  drupal_set_message(t('Image uploaded: <img src="@src" />', array('@src' => $form_state['values']['snapshot'])));
  $form_state['storage']['snapshot'] = $form_state['values']['snapshot'];
}

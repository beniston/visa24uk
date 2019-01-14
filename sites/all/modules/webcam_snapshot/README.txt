CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Recommended modules
 * Installation
 * Configuration
 * Troubleshooting
 * FAQ
 * Maintainers
 
INTRODUCTION
------------

The Webcam Snapshot module provides a form element for taking snapshots from a
webcam. It requires a browser that supports getUserMedia.

There is also a formatter for file and image fields, allowing images to be saved
from user created content types.

 * For a full description of the module, visit the project page:
   https://www.drupal.org/sandbox/mdryan/2096387

 * To submit bug reports and feature suggestions, or to track changes:
   https://www.drupal.org/project/issues/2096387

REQUIREMENTS
------------

This module requires a browser which supports getUserMedia.

INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module. See:
   https://drupal.org/documentation/install/modules-themes/modules-7
   for further information.
   
CONFIGURATION
-------------
If you are using this module to provide a widget for a file upload type then
ensure you set the list of allowed file extensions to jpg in the field instance
settings to allow the generated file to be saved. You may also configure the
size of the video stream along with the ratio and initial size of the selected
area for capture.

If you are using this module in a custom form then look at the
webcam_snapshot_demo module for guidance as to how to use the module.

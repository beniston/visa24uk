/**
 * @file
 * Extend jQuery with a webcam_snapshot plugin then initialise with Drupal settings.
 */

(function ($) {
  'use strict';
  $.webcam_snapshot = function (object, options) {

    // Add default settings.
    var settings = $.extend({
      cameraWidth: 800,
      cameraHeight: 0,
      videoWidth: 0,
      videoHeight: 480,
      selection: {
        top: 20,
        left: 100,
        right: 180,
        bottom: 140
      },
      overlayOpacity: 0.7,
      destinationSize: [120, 160],
      $destination: '',
      $preview: '',
      $trigger: ''
    }, options);
    settings.selectionAspectRatio = settings.destinationSize[0] / settings.destinationSize[1];

    // Remove vendor prefixes.
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.oGetUserMedia || navigator.msGetUserMedia;
    window.URL = window.URL || window.webkitURL;

    var localMediaStream = null;
    var canPlay = false;
    var selectionStart = [0, 0];

    var $video = $(object);
    var $message = $('<div>' + Drupal.t('Loading...') + '</div>').css({
      position: 'absolute',
      background: '#FFF',
      opacity: '.5',
      left: '1px',
      top: '1px'
    });
    var $wrapper = $('<div />').css('position', 'relative').height($video.height());
    var $overlay = $('<canvas />').css('position', 'absolute');
    var overlay_ctx = $overlay[0].getContext('2d');
    var $destination_canvas = $('<canvas />').css('display', 'none');

    // Display a message with optional timeout.
    var setMessage = function (message, timeout) {
      $message.html(message).css('display', 'block');
      if (timeout) {
        window.setTimeout(function () {
          $message.fadeOut();
        }, timeout);
      }
    };

    var showTryAgainButton = function () {
      var $retry = $("<br /><input type='button' value='" + Drupal.t('Try again') + "' />");
      $message.append($retry);
      $retry.click(requestCamAccess);
    };

    // Try and get access to the webcam.
    var requestCamAccess = function () {
      if (navigator.getUserMedia) {
        setMessage(Drupal.t('Attempting to start video...'));
        showTryAgainButton();
        navigator.getUserMedia({video: {mandatory: {minWidth: settings.cameraWidth, minHeight: settings.cameraHeight}}, audio: false}, onSuccess, onError);
      }
      else {
        setMessage(Drupal.t('HTML5 video from a webcam is not supported by your browser.'));
      }
    };

    // When we get access to the webcam.
    var onSuccess = function (stream) {
      localMediaStream = stream;
      setMessage(Drupal.t('Video running...'), 1500);
      if (navigator.mozGetUserMedia) {
        $video[0].mozSrcObject = stream;
      }
      else {
        $video[0].src = window.URL.createObjectURL(stream);
      }
      $video[0].play();
    };

    // If we don't get access to the webcam.
    var onError = function (error) {
      setMessage(Drupal.t("Permission denied starting video stream.<br />On Chrome make sure you didn't accidentally blacklist yourself at:<br />chrome://settings/contentExceptions#media-stream"));
      showTryAgainButton();
    };

    // Simulate a camera flash (badly).
    var flash = function () {
      overlay_ctx.globalAlpha = .8;
      overlay_ctx.fillStyle = '#DDDDDD';
      overlay_ctx.fillRect(0, 0, settings.videoWidth, settings.videoHeight);
      window.setTimeout(updateOverlay, 150);
    };

    // Take a picture.
    var snapshot = function () {
      if (localMediaStream) {
        setMessage(Drupal.t('Taking photo'), 1000);
        flash();
        var sx = $video[0].videoWidth / settings.videoWidth * settings.selection.left;
        var sy = $video[0].videoHeight / settings.videoHeight * settings.selection.top;
        var swidth = $video[0].videoWidth / settings.videoWidth * (settings.selection.right - settings.selection.left);
        var sheight = $video[0].videoHeight / settings.videoHeight * (settings.selection.bottom - settings.selection.top);
        $destination_canvas[0].getContext('2d').drawImage($video[0], sx, sy, swidth, sheight, 0, 0, settings.destinationSize[0], settings.destinationSize[1]);
        var data = $destination_canvas[0].toDataURL('image/jpeg');
        if (settings.$preview) {
          settings.$preview.attr('src', data);
        }
        if (settings.$destination) {
          settings.$destination.val(data);
        }
      }
    };

    // Rescale the video, overlay and wrapper that holds them.
    var rescale = function () {
      // For some reason dimensions are not always available as soon as canplay fires so keep trying until they are.
      if ($video[0].videoHeight * $video[0].videoWidth === 0) {
        setTimeout(rescale, 50);
      }
      else {
        if (Number(settings.videoHeight) === 0 && Number(settings.videoWidth) === 0) {
          // No dimensions specified so use full size stream.
          settings.videoHeight = $video[0].videoHeight;
          settings.videoWidth = $video[0].videoWidth;
        }
        else if (Number(settings.videoHeight) === 0) {
          // Only a width was specified.
          settings.videoHeight = $video[0].videoHeight / ($video[0].videoWidth / settings.videoWidth);
        }
        else if (Number(settings.videoWidth) === 0) {
          // Only a height was specified.
          settings.videoWidth = $video[0].videoWidth / ($video[0].videoHeight / settings.videoHeight);
        }
        $wrapper.height(settings.videoHeight);
        $wrapper.width(settings.videoWidth);
        $video.width(settings.videoWidth);
        $video.height(settings.videoHeight);
        // CSS size of canvas element.
        $overlay.width(settings.videoWidth);
        $overlay.height(settings.videoHeight);
        // Size of the canvas itself.
        $overlay[0].width = settings.videoWidth;
        $overlay[0].height = settings.videoHeight;
        updateOverlay();
      }
    };

    // Draw the selection box.
    var updateOverlay = function () {
      overlay_ctx.globalAlpha = settings.overlayOpacity;
      overlay_ctx.fillStyle = '#000000';
      overlay_ctx.strokeStyle = '#FFFFFF';
      overlay_ctx.lineWidth = 3;
      overlay_ctx.clearRect(0, 0, settings.videoWidth, settings.videoHeight);
      overlay_ctx.fillRect(0, 0, settings.videoWidth, settings.videoHeight);
      var width = settings.selection.right - settings.selection.left;
      var height = settings.selection.bottom - settings.selection.top;
      overlay_ctx.clearRect(settings.selection.left, settings.selection.top, width, height);
      overlay_ctx.strokeRect(settings.selection.left, settings.selection.top, width, height);
    };

    // Insert elements into the document.
    var init_dom = function () {
      $video.wrap($wrapper).css('position', 'absolute');
      $wrapper = $video.parent();
      $overlay.insertAfter($video);
      $destination_canvas.insertAfter($wrapper);
      $message.insertAfter($overlay);
    };

    // Rescale on the first canplay event.
    // canplay keeps firing on some platforms apparently, but we don't want to keep rescaling.
    var onCanPlay = function (event) {
      if (!canPlay) {
        rescale();
        canPlay = true;
      }
    };

    // Get the current mouse position within the overlay.
    var getMousePosition = function (event) {
      var x = event.pageX - $overlay.offset().left;
      var y = event.pageY - $overlay.offset().top;
      x = (x < 0) ? 0 : x;
      x = (x > $overlay.width()) ? $overlay.width() : x;
      y = (y < 0) ? 0 : y;
      y = (y > $overlay.height()) ? $overlay.height() : y;
      return [x, y];
    };

    // Start selection on mousedown event.
    var onMouseDown = function (event) {
      event.preventDefault();
      event.stopPropagation();
      $(document).mousemove(onMouseMove).mouseup(onMouseUp);
      selectionStart = getMousePosition(event);
      settings.selection.left = selectionStart[0];
      settings.selection.top = selectionStart[1];
      settings.selection.right = selectionStart[0];
      settings.selection.bottom = selectionStart[1];
      updateOverlay();
    };

    // Expand selection on mousemove event.
    var onMouseMove = function (event) {
      event.preventDefault();
      event.stopPropagation();
      var mousePosition = getMousePosition(event);
      if (mousePosition[0] > selectionStart[0]) {
        // Expand to the right.
        settings.selection.left = selectionStart[0];
        settings.selection.right = mousePosition[0];
      }
      else {
        // Expand to the left.
        settings.selection.right = selectionStart[0];
        settings.selection.left = mousePosition[0];
      }
      if (mousePosition[1] > selectionStart[1]) {
        // Expand down.
        settings.selection.top = selectionStart[1];
        settings.selection.bottom = mousePosition[1];
      }
      else {
        // Expand up.
        settings.selection.bottom = selectionStart[1];
        settings.selection.top = mousePosition[1];
      }
      if (settings.selectionAspectRatio !== 0) {
        var width = settings.selection.right - settings.selection.left;
        var height = settings.selection.bottom - settings.selection.top;
        if (width / height > settings.selectionAspectRatio) {
          // Too narrow.
          width = height * settings.selectionAspectRatio;
          if (mousePosition[0] > selectionStart[0]) {
            // Expanding to the right.
            settings.selection.right = settings.selection.left + width;
            if (settings.selection.right > settings.videoWidth) {
              // We'd have gone off the screen, so set width to max and limit height.
              settings.selection.right = settings.videoWidth;
              height = width / settings.selectionAspectRatio;
              settings.selection.bottom = settings.selection.top + height;
            }
          }
          else {
            // Expanding to the left.
            settings.selection.left = settings.selection.right - width;
            if (settings.selection.left < 0) {
              settings.selection.left = 0;
              height = width / settings.selectionAspectRatio;
              settings.selection.bottom = settings.selection.top + height;
            }
          }
        }
        else {
          // Too tall.
          height = width / settings.selectionAspectRatio;
          if (mousePosition[1] > selectionStart[1]) {
            // Expanding down.
            settings.selection.bottom = settings.selection.top + height;
            if (settings.selection.bottom > settings.videoHeight) {
              // We'd have gone off the screen, so set height to max and limit width.
              settings.selection.bottom = settings.videoHeight;
              width = height * settings.selectionAspectRatio;
              settings.selection.right = settings.selection.right + width;
            }
          }
          else {
            // Expanding upwards.
            settings.selection.top = settings.selection.bottom - height;
            if (settings.selection.top < 0) {
              settings.selection.top = 0;
              height = width / settings.selectionAspectRatio;
              settings.selection.right = settings.selection.right + width;
            }
          }
        }
      }
      updateOverlay();
    };

    // Stop selection on mouseup event.
    var onMouseUp = function (event) {
      event.preventDefault();
      event.stopPropagation();
      $(document).unbind('mousemove');
      $(document).unbind('mouseup');
      updateOverlay();
    };

    $video[0].addEventListener('canplay', onCanPlay, false);
    $video[0].autoplay = true;
    $destination_canvas[0].width = settings.destinationSize[0];
    $destination_canvas[0].height = settings.destinationSize[1];
    init_dom();
    requestCamAccess();
    updateOverlay();
    $overlay.mousedown(onMouseDown);
    if (settings.$trigger) {
      settings.$trigger.click(snapshot);
    }
  };

  $.fn.webcam_snapshot = function (options) {
    this.each(function () {
      $.webcam_snapshot(this, options);
    });
    return this;
  };
})(jQuery);

Drupal.behaviors.webcam_snapshot = {
  attach: function (context) {
    'use strict';
    var options = Drupal.settings.webcam_snapshot;
    var $destination = jQuery('#' + options.id);
    options.$destination = $destination;
    options.$trigger = $destination.parent().find("input[type|='button']");
    options.$preview = $destination.parent().find('img');
    $destination.parent().find('video').webcam_snapshot(options);
  }
};

(function ($, Drupal) {
// Find all iframes
  var $allIframes = $(".owl-carousel-video-item"),

    // The element that is fluid width
    $fluidEl = $(".page-content");

// Figure out and save aspect ratio for each video
  $allIframes.each(function () {

    $(this)
      .data('aspectRatio', this.height / this.width)

      // and remove the hard coded width/height
      .removeAttr('height')
      .removeAttr('width');
  });

// When the window is resized
  $(window).resize(function () {

    var newWidth = $fluidEl.width();

    // Resize all ifra,es according to their own aspect ratio
    $allIframes.each(function () {
      var $el = $(this);
      $el
        .width(newWidth)
        .height(newWidth * $el.data('aspectRatio'));
    });

// Kick off one resize to fix all iframes on page load
  }).resize();
})(jQuery, Drupal);
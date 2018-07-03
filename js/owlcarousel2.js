(function ($) {

  'use strict';

  Drupal.behaviors.owl = {
    attach: function (context, settings) {
      var keyed_settings = settings.owlcarousel_settings;
      $(document).ready(function () {
        var keys = [];
        for (var key in keyed_settings) {
          if (keyed_settings.hasOwnProperty(key)) {
            keys.push(key);
            var owl_settings = keyed_settings[key];
            $('#owlcarousel2-id-' + key).owlCarousel({
              video: true,
              loop: owl_settings.loop === 'true',
              margin: 10,
              nav: owl_settings.nav === 'true',
              items: parseInt(owl_settings.items_per_slide),
              autoplay: owl_settings.autoplay === 'true',
              autoplaySpeed: parseInt(owl_settings.autoplaySpeed),
              autoplayTimeout: parseInt(owl_settings.autoplayTimeout),
              dots: owl_settings.dots === 'true',
              lazyLoad: owl_settings.lazyLoad === 'true',
              autoplayHoverPause: owl_settings.autoplayHoverPause === 'true',
              animateIn: owl_settings.animateIn,
              animateOut: owl_settings.animateOut,
              dotClass: owl_settings.dotClass ? owl_settings.dotClass : 'owl-dot',
              dotsClass: owl_settings.dotsClass ? owl_settings.dotsClass : 'owl-dots'
            });

            // Video adjust
            var videoThumb = $('#owlcarousel2-id-' + key + ' .owl-video-tn');
            videoThumb.each(function () {
              // Change video quality to max
              var image = $(this).css('background-image');
              var arr = image.split('/');

              if (arr[2] === 'img.youtube.com') {
                arr[arr.length - 1] = 'maxresdefault.jpg';
                image = arr.join('/');
                $(this).css('background-image', image);
              }
            });
          }
        }

        $(window).resize(function () {
          for (var key in keys) {
            if (keys.hasOwnProperty(key)) {
              // Adjust video Height
              var videoItem = $('#owlcarousel2-id-' + keys[key] + ' .owl-carousel-video-item');
              var videoHeight = 0;

              // Check if there is also an image in carousel.
              var itemImage = $('#owlcarousel2-id-' + keys[key] + ' .item-image');
              var biggestImageHeight = 0;

              // itemImage.each(function () {
              //   biggestImageHeight = biggestImageHeight < itemImage.first().height() ? itemImage.first().height() : biggestImageHeight;
              // });
              for (var item in itemImage) {
                if (itemImage.hasOwnProperty(item)) {
                  biggestImageHeight = biggestImageHeight < itemImage.first().height() ? itemImage.first().height() : biggestImageHeight;
                }
              }

              if (biggestImageHeight > 0) {
                videoHeight = biggestImageHeight;
              }
              else {
                videoHeight = videoItem.first().width() * (1080 / 1920);
              }

              resizeVideo(videoItem, videoHeight);

            }
          }
        }).resize();

        /**
         * Resize the video element.
         *
         * @param videoItem
         * @param videoHeight
         */
        function resizeVideo(videoItem, videoHeight) {
          videoItem.each(function () {
            $(this).css('height', videoHeight + 'px');
          });
        }

      });
    }
  };
})(jQuery);

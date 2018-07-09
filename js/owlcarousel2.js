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
              margin: parseInt(owl_settings.margin ? owl_settings.margin : 0),
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
              dotsClass: owl_settings.dotsClass ? owl_settings.dotsClass : 'owl-dots',
              center: owl_settings.center === 'true',
              mouseDrag: owl_settings.mouseDrag !== 'false',
              touchDrag: owl_settings.touchDrag !== 'false',
              stagePadding: parseInt(owl_settings.stagePadding ? owl_settings.stagePadding : 0),
              navText: [owl_settings.previousText ? owl_settings.previousText : '<', owl_settings.previousText ? owl_settings.nextText : '>']
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
              var itemImage = $('#owlcarousel2-id-' + keys[key] + ' .owlcarousel2-item-image');
              var biggestImageHeight = 0;

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

        /**
         * Apply inner node position.
         */
        $('.owlcarousel2-node-inner').each(function () {
          $(this).css('top', this.getAttribute('data-owl-top'));
          $(this).css('right', this.getAttribute('data-owl-right'));
          $(this).css('bottom', this.getAttribute('data-owl-bottom'));
          $(this).css('left', this.getAttribute('data-owl-left'));
        });

        /**
         * Apply title color.
         */
        $('.owlcarousel-node-title-link').each(function () {
          $(this).css('color', this.getAttribute('data-owl-title-color'));
        });

        /**
         * Apply content color.
         */
        $('.owlcarousel-node-content').each(function () {
          $(this).css('color', this.getAttribute('data-owl-content-color'));
        });

        /**
         * Apply content background color.
         */
        $('.owlcarousel-node-box').each(function () {
          $(this).css('background-color', this.getAttribute('data-owl-background-color'));
        });


        /**
         * Change the video play behavior to configure the video options.
         */
        $('.owl-video-wrapper').bind('DOMSubtreeModified', function () {
          $('iframe').each(function () {
            // Check if it's youtube video
            if (this.src.indexOf('www.youtube.com') !== -1) {
              var videoUrl = this.src.split('?')[0];
              var videoId = this.src.split('?')[1].split('v=')[1];
              var newVideoUrl = videoUrl + '?autoplay=1&v=' + videoId;

              $('.owl-carousel-video-item').each(function () {
                newVideoUrl += (this.getAttribute('data-youtube-controls') !== '0') ? '&controls=' + this.getAttribute('data-youtube-controls') : '';
                newVideoUrl += (this.getAttribute('data-youtube-showinfo') !== '0') ? '&showinfo=' + this.getAttribute('data-youtube-showinfo') : this.getAttribute('data-youtube-showinfo');
                // Special behavior for rel attribute. Default must be 0.
                newVideoUrl += (this.getAttribute('data-youtube-rel') !== '1') ? '&rel=0' : '&rel=1';
                newVideoUrl += (this.getAttribute('data-youtube-loop') !== '0') ? '&loop=' + this.getAttribute('data-youtube-loop') + '&playlist=' + videoId : '';
              });

              this.src = newVideoUrl;
            }
          });
        });


      });
    }
  };
})(jQuery);

(function ($) {
  Drupal.behaviors.owl = {
    attach: function (context, settings) {
      // alert(settings.myVar);
      var owl_settings = settings.owlcarousel_settings;
      console.log(owl_settings);
      $(document).ready(function () {
        $('.owl-carousel').owlCarousel({
          video: true,
          loop: owl_settings.loop == "true",
          margin: 10,
          nav: owl_settings.nav == "true",
          items: parseInt(owl_settings.items_per_slide),
          autoplay: owl_settings.autoplay  == "true",
          autoplaySpeed: parseInt(owl_settings.autoplaySpeed),
          autoplayTimeout: parseInt(owl_settings.autoplayTimeout),
          dots: owl_settings.dots == "true",
          lazyLoad: owl_settings.lazyLoad == "true",
          animateIn: owl_settings.animateIn,
          animateOut: owl_settings.animateOut,
          dotClass: owl_settings.dotClass ? owl_settings.dotClass : 'owl-dot',
          dotsClass: owl_settings.dotsClass ? owl_settings.dotsClass : 'owl-dots'

          // video: true,
          // animateIn: owl_settings.animateIn,
          // animateOut: owl_settings.animateOut,
          // autoplay: owl_settings.autoplay,
          // autoplaySpeed: owl_settings.autoplaySpeed,
          // autoplayTimeout: owl_settings.autoplayTimeout,
          // items: owl_settings.items_per_slide,
          // lazyLoad: owl_settings.lazyLoad,
          // dots: owl_settings.dots,
          // nav: owl_settings.nav,
          // loop: owl_settings.loop
        });
          // {
          // loop: true,
          // margin: 10,
          // nav: false,
          // items: 1,
          // autoplay: false,
          // autoplaySpeed: 2000,
          // autoplayTimeout: 5000,
          // video: true,
          // dots: true,
          // lazyLoad: false,
          // animateIn: '',//'flipInX', //
          // animateOut: ''//'slideOutDown', //Type: String/Boolean Default: false Class for CSS3 animation out.
          //

          // ,responsive:{
          //   0:{
          //     items:1
          //   },
          //   600:{
          //     items:3
          //   },
          //   1000:{
          //     items:5
          //   }
          // }


        // });

        // Video adjust
        var videoThumb = $(".owl-video-tn");
        videoThumb.each(function () {
          // Change video quality to max
          var image = $(this).css('background-image');
          var arr = image.split("/");

          // TODO do the same fot vimeo thumbnail.
          if (arr[2] === "img.youtube.com") {
            arr[arr.length - 1] = "maxresdefault.jpg\")";
            image = arr.join('/');
            $(this).css('background-image', image);
          }
        });

        $(window).resize(function () {
          // Adjust video Height
          var videoItem = $(".owl-carousel-video-item");
          var videoHeight = 0;

          // Check if there is also an image in carousel.
          var itemImage = $(".item-image");
          var biggestImageHeight = 0;

          itemImage.each(function () {
            biggestImageHeight = biggestImageHeight < itemImage.first().height() ? itemImage.first().height() : biggestImageHeight;
          });

          if (biggestImageHeight > 0) {
            videoHeight = biggestImageHeight;
          }
          else {
            videoHeight = videoItem.first().width() * (1080 / 1920);
          }

          videoItem.each(function () {
            $(this).css("height", videoHeight + 'px');
          });

        }).resize();
      });

    }
  };
})(jQuery);

/**********To Top**********/
$(function(){
    $(document).on( 'scroll', function(){
        if ($(window).scrollTop() > 100) {
            $('.scroll-top-wrapper').addClass('show');
        } else {
            $('.scroll-top-wrapper').removeClass('show');
        }
    });
    $('.scroll-top-wrapper').on('click', scrollToTop);
});
function scrollToTop() {
    verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
    element = $('body');
    offset = element.offset();
    offsetTop = offset.top;
    $('html, body').animate({scrollTop: offsetTop}, 500, 'linear');
}

/**********Active Section Menu********/
(function($) {
	"use strict";
   // onepage nav
   var navclose = $('#onepage-menu');
   if(navclose.length){
       $(".nav-menu li a").on("click", function () {
           if ($(".showhide").is(":visible")) {
               $(".showhide").trigger("click");
           }
       });
       if ($.fn.onePageNav) {
           $(".nav-menu").onePageNav({
               currentClass: "current-menu-item"
           });
       }
   }

})(jQuery);

/**********Fixed nav********/
var nums = $('.main_nav').offset().top;
$(window).scroll(function() {
	if ($(window).scrollTop() > nums) {
		$('.main_nav').addClass('fixednav');
	} else {
		$('.main_nav').removeClass('fixednav');
		nums = $('.main_nav').offset().top;
	}
});

/**********Video Modal********/
$(document).ready(function () {
  var $videoSrc;
  $('.popup-videos').click(function () {
    $videoSrc = $(this).data("src");
  });
  //console.log($videoSrc);
  $('#videomodal').on('shown.bs.modal', function (e) {
    $("#videourl").attr('src', $videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
  });
  $('#videomodal').on('hide.bs.modal', function (e) {
    $("#videourl").attr('src', $videoSrc);
  });
});

// onepage nav
var navclose = $('#onepage-menu');
if (navclose.length) {
  $(".mn_nav li a").on("click", function () {
    if ($(".showhide").is(":visible")) {
      $(".showhide").trigger("click");
    }
  });

  if ($.fn.onePageNav) {
    $(".mn_nav").onePageNav({
      currentClass: "current-menu-item"
    });
  }
}

/**********Multilavel Dropdown Menu********/
$('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
  if (!$(this).next().hasClass('show')) {
    $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
  }
  var $subMenu = $(this).next('.dropdown-menu');
  $subMenu.toggleClass('show');
  $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
    $('.dropdown-submenu .show').removeClass('show');
  });
  return false;
});


$('.number').each(function () {
  $(this).prop('Counter',0).animate({
      Counter: $(this).text()
  }, {
    //chnage count up speed here
      duration: 4000,
      easing: 'swing',
      step: function (now) {
          $(this).text(Math.ceil(now));
      }
  });
});

/*===================================*
testimonial_slider
*===================================*/
$( window ).on( "load", function() {
	$('.carousel_slider').each( function() {
		var $carousel = $(this);
		$carousel.owlCarousel({
			dots : $carousel.data("dots"),
			loop : $carousel.data("loop"),
			margin: $carousel.data("margin"),
			items: $carousel.data("items"),
			mouseDrag: $carousel.data("mouse-drag"),
			touchDrag: $carousel.data("touch-drag"),
			center: $carousel.data("center"),
			autoHeight: $carousel.data("autoheight"),
			rewind: $carousel.data("rewind"),
			nav: $carousel.data("nav"),
			navText: ['<i class="las la-arrow-left"></i>','<i class="las la-arrow-right"></i>'],
			autoplay : $carousel.data("autoplay"),
			animateIn : $carousel.data("animate-in"),
			animateOut: $carousel.data("animate-out"),
			autoplayTimeout : $carousel.data("autoplay-timeout"),
			smartSpeed: $carousel.data("smart-speed"),
			responsive: $carousel.data("responsive")
		});
	});
});
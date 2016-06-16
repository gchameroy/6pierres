/*
* Template Name: Unify - Responsive Bootstrap Template
* Version: 1.9
* Author: @htmlstream
* Website: http://htmlstream.com
*/

var App = function() {

	function handleHeader() {
		// jQuery to collapse the navbar on scroll
		if ($('.navbar').offset().top > 150) {
			// $('.navbar-fixed-top').addClass('top-nav-collapse');
		}
		$(window).scroll(function() {
			if ($('.navbar').offset().top > 150) {
				// $('.navbar-fixed-top').addClass('top-nav-collapse');
			} else {
				// $('.navbar-fixed-top').removeClass('top-nav-collapse');
			}
		});

		var $offset = 0;
		$offset = $(".navbar-fixed-top").height()+12;
		// jQuery for page scrolling feature - requires jQuery Easing plugin
		$('.page-scroll a').bind('click', function(event) {
			var $position = $($(this).attr('href')).offset().top;
			$('html, body').stop().animate({
				scrollTop: $position - $offset
			}, 600);
			event.preventDefault();
		});

		var $scrollspy = $('body').scrollspy({target: '.navbar-fixed-top', offset: $offset+2});

		// Collapse Navbar When It's Clickicked
		$(window).scroll(function() {
			$('.navbar-collapse.in').collapse('hide');
		});
	}

  return {
    init: function() {
      handleHeader();
    },


  };

}();

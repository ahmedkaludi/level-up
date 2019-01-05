jQuery( document ).ready( function($) {
	'use strict';
	// Back to top
	var _amountScrolled = $( '.levelup-back-to-top' ).data( 'start-scroll' ),
		_scrollSpeed = $( '.levelup-back-to-top' ).data( 'scroll-speed' ),
		_button = $( 'a.levelup-back-to-top' ),
		_window = $( window );

	_window.scroll(function() {
		if ( _window.scrollTop() > _amountScrolled ) {
			$( _button ).css({
				'opacity': '1',
				'visibility': 'visible'
			});
		} else {
			$( _button ).css({
				'opacity': '0',
				'visibility' : 'hidden'
			});
		}
	});

	$( _button ).on( 'click', function( e ) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: 0
		}, _scrollSpeed);
	});

	// Open header widgets

	$('.header-widget-toggle').on('click', function() {
		$(this).toggleClass('toggle-collapsed');
		$('.levelup-header-widget-area').toggleClass('header-widget-open');
	});

	_window.scroll(function() {
		if ( _window.scrollTop() > 300 ) {
			$('.toggle-collapsed').css({
				'opacity': '0',
				'visibility': 'hidden'
			});
		} else {
			$('.toggle-collapsed').css({
				'opacity': '1',
				'visibility' : 'visible'
			});
		}
	});


	// smooth scroll for anchors
   $('.nav-menu a[href^=#]:not([href=#])').on('click',function (e) {
	    e.preventDefault();

	    var target = this.hash,
	    $target = $(target);

	    $('html, body').stop().animate({
	        'scrollTop': $target.offset().top 
	    }, 800, 'swing');

	    return false;

	});


	// smooth scroll for anchors
   $('a.scroll-down, a.scroll-to-comments').on('click',function (e) {
	    e.preventDefault();
	    var stickyNavbarHeight = $('.levelup-navbar-fixed-top').height();
	    var target = this.hash,
	    $target = $(target);

	    $('html, body').stop().animate({
	        'scrollTop': $target.offset().top - stickyNavbarHeight
	    }, 800, 'swing');

	    return false;

	});


  	// Sticky menu

	  $(window).scroll(function() {
	    var scrollPos = $(window).scrollTop(),
	    	navbar='levelup-navbar-static-top';
			if ($('.levelup-navbar').hasClass('levelup-navbar-fixed-top')) {
			    navbar = $('.levelup-navbar-fixed-top');
			} else if ($('.levelup-navbar').hasClass('levelup-navbar-transparent-sticky-top')) {
			    navbar = $('.levelup-navbar-transparent-sticky-top');
			} else{
				return false;
			}

	    if (scrollPos > 350) {
	      navbar.addClass('levelup-sticky-navbar');
	    } else {
	      navbar.removeClass('levelup-sticky-navbar');
	    }
	  });


	// Calculate blog header height

	var topbarHeight = $('.levelup-topbar').height();
	var logobarHeight = $('.levelup-logobar').height();
	var navbarHeight = $('.levelup-navbar:not(.levelup-navbar-transparent-top)').height();
	var transparentNavbarHeight = $('.levelup-navbar-transparent-top').height();
	var headerHeight = (topbarHeight + logobarHeight + navbarHeight) + 50;
	var transparentHeaderHeight = (topbarHeight + logobarHeight + transparentNavbarHeight);

   $('.blog-header').css({ 'height': $(window).height() - headerHeight });
   $(window).on('resize', function() {

        $('.blog-header').css({ 'height': $(window).height() - headerHeight });
        $('body').css({ 'width': $(window).width() })
   });

	// Transparent menu
	
	// $('body:not(.single-post) .site-header + *, .single-blog-header').css({ 'padding-top': transparentHeaderHeight });


   // Header parallax

	$(window).scroll(function(e){
	  parallax();
	});


	function parallax() {
	  var scrollPosition = $(window).scrollTop();
	  $('.page-header.blog-header .header-content').css('margin-top', (0 - (scrollPosition * .8)) + 'px');
	}


	// On scroll blur header

   (function() {
      $(window).scroll(function() {
        var oVal;
        oVal = $(window).scrollTop() / 350;
        return $(".header-overlay").css("opacity", oVal);
        });

      }).call(this);


});

// Overlay search

(function(window) {

	'use strict';

	var openCtrl = document.getElementById('btn-search'),
		closeCtrl = document.getElementById('btn-search-close'),
		searchContainer = document.querySelector('.levelup-search-overlay'),
		inputWrapper = document.querySelector(".search--input-wrapper"),
		inputSearch = document.querySelector('.search__input');

	function init() {
		initEvents();	
	}

	function initEvents() {
		if(openCtrl){
			openCtrl.addEventListener('click', openSearch);
			closeCtrl.addEventListener('click', closeSearch);
			document.addEventListener('keyup', function(ev) {
				// escape key.
				if( ev.keyCode == 27 ) {
					closeSearch();
				}
			});
		}
	}

	function openSearch() {
		if(searchContainer){
			searchContainer.classList.add('search--open');
			inputSearch.focus();
		}
	}

	function closeSearch() {
		searchContainer.classList.remove('search--open');
		inputSearch.blur();
		inputSearch.value = '';
		inputWrapper.setAttribute("data-text", '');
	}

	if(searchContainer){
		inputSearch.addEventListener("keyup", event => {
		inputWrapper.setAttribute("data-text", event.target.value);
		})
	};

	init();

})(window);
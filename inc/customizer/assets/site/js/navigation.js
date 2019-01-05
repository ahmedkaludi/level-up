(function($) {
    $.fn.levelupNav = function(options) {
        var levelupMenu = $(this),
            settings = $.extend({
                format: "dropdown",
                sticky: false
            }, options);
        return this.each(function() {
            $(this).find(".levelup-nav-btn").on('click', function() {
                $(this).toggleClass('menu-opened');
                var levelupNavMenu = $(this).next('ul.nav-menu');
                if (levelupNavMenu.hasClass('open')) {
                    levelupNavMenu.slideToggle().removeClass('open');
                } else {
                    levelupNavMenu.slideToggle().addClass('open');
                    if (settings.format === "dropdown") {
                        levelupNavMenu.find('ul').show();
                    }
                }
            });
            levelupMenu.find('li ul').parent().addClass('has-sub');
            multiTg = function() {
                levelupMenu.find(".has-sub").prepend('<span class="submenu-button"></span>');
                levelupMenu.find('.submenu-button').on('click', function() {
                    $(this).toggleClass('submenu-opened');
                    if ($(this).siblings('ul').hasClass('open')) {
                        $(this).siblings('ul').removeClass('open').slideToggle();
                    } else {
                        $(this).siblings('ul').addClass('open').slideToggle();
                    }
                });
            };
            if (settings.format === 'multitoggle') multiTg();
            else levelupMenu.addClass('dropdown');
            if (settings.sticky === true) levelupMenu.css('position', 'fixed');
            resizeFix = function() {
                var mediasize = 767;
                if ($(window).width() > mediasize) {
                    levelupMenu.find('ul').show();
                }
                if ($(window).width() <= mediasize) {
                    levelupMenu.find('ul').hide().removeClass('open');
                }
            };
            resizeFix();
            return $(window).on('resize', resizeFix);
        });
    };
})(jQuery);

(function($) {
    $(document).ready(function() {
        $(".main-navigation").levelupNav({
            format: "multitoggle"
        });
    });
    $(document).ready(function() {
        $(".topbar-navigation").levelupNav({
            format: "multitoggle"
        });
    });
})(jQuery);
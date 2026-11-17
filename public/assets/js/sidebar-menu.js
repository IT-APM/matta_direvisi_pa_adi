$(".toggle-nav").click(function () {
    $('#sidebar-links .nav-menu').css("left", "0px");
});
$(".mobile-back").click(function () {
    $('#sidebar-links .nav-menu').css("left", "-410px");
});
$(".page-wrapper").attr("class", "page-wrapper " + localStorage.getItem('page-wrapper'));
if (localStorage.getItem("page-wrapper") === null) {
    $(".page-wrapper").addClass("compact-wrapper");
}

// left sidebar and vertical menu
if ($('#pageWrapper').hasClass('compact-wrapper')) {
    // Only append according-menu if it has a submenu
    jQuery('.sidebar-title').each(function () {
        if (jQuery(this).next('.sidebar-submenu').length > 0 && jQuery(this).find('.according-menu').length === 0) {
            jQuery(this).append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
        }
    });

    jQuery('.sidebar-title').off('click').on('click', function (e) {
        var $this = jQuery(this);
        var $submenu = $this.next('.sidebar-submenu');

        // If it's a direct link with no submenu, allow normal navigation
        if ($submenu.length === 0) {
            return;
        }

        e.preventDefault();
        var isOpen = $submenu.is(':visible');

        // Close other open submenus
        jQuery('.sidebar-title').not($this).removeClass('active')
            .find('.according-menu i').removeClass('fa-angle-down').addClass('fa-angle-right');
        jQuery('.sidebar-submenu').not($submenu).slideUp(200);

        if (isOpen) {
            $this.removeClass('active');
            $this.find('.according-menu i').removeClass('fa-angle-down').addClass('fa-angle-right');
            $submenu.slideUp(200);
        } else {
            $this.addClass('active');
            $this.find('.according-menu i').removeClass('fa-angle-right').addClass('fa-angle-down');
            $submenu.slideDown(200);
        }
    });

    jQuery('.sidebar-submenu, .menu-content').hide();

    // 2nd level submenus
    jQuery('.submenu-title').each(function () {
        if (jQuery(this).next('.nav-sub-childmenu, .submenu-content').length > 0 && jQuery(this).find('.according-menu').length === 0) {
            jQuery(this).append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
        }
    });

    jQuery('.submenu-title').off('click').on('click', function (e) {
        var $this = jQuery(this);
        var $subchild = $this.next('.nav-sub-childmenu, .submenu-content');
        if ($subchild.length === 0) return;

        e.preventDefault();
        var isOpen = $subchild.is(':visible');

        jQuery('.submenu-title').not($this).removeClass('active')
            .find('.according-menu i').removeClass('fa-angle-down').addClass('fa-angle-right');
        jQuery('.nav-sub-childmenu, .submenu-content').not($subchild).slideUp(200);

        if (isOpen) {
            $this.removeClass('active');
            $this.find('.according-menu i').removeClass('fa-angle-down').addClass('fa-angle-right');
            $subchild.slideUp(200);
        } else {
            $this.addClass('active');
            $this.find('.according-menu i').removeClass('fa-angle-right').addClass('fa-angle-down');
            $subchild.slideDown(200);
        }
    });

    jQuery('.submenu-content').hide();
} else if ($('#pageWrapper').hasClass('horizontal-wrapper')) {
     var contentwidth = jQuery(window).width();
    if ((contentwidth) < 992) {
                $('#pageWrapper').removeClass('horizontal-wrapper').addClass('compact-wrapper');
            $('.page-body-wrapper').removeClass('horizontal-menu').addClass('sidebar-icon');
            jQuery('.submenu-title').append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
            jQuery('.submenu-title').click(function () {
                jQuery('.submenu-title').removeClass('active');
                jQuery('.submenu-title').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
                jQuery('.submenu-content').slideUp('normal');
                if (jQuery(this).next().is(':hidden') == true) {
                    jQuery(this).addClass('active');
                    jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');
                    jQuery(this).next().slideDown('normal');
                } else {
                    jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
                }
            });
            jQuery('.submenu-content').hide();

            jQuery('.sidebar-title').append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
            jQuery('.sidebar-title').click(function () {
                jQuery('.sidebar-title').removeClass('active');
                jQuery('.sidebar-title').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
                jQuery('.sidebar-submenu, .menu-content').slideUp('normal');
                if (jQuery(this).next().is(':hidden') == true) {
                    jQuery(this).addClass('active');
                    jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');
                    jQuery(this).next().slideDown('normal');
                } else {
                    jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
                }
            });
            jQuery('.sidebar-submenu, .menu-content').hide();
            }
    
} else if ($('#pageWrapper').hasClass('compact-sidebar')) {
    
    var contentwidth = jQuery(window).width();
    if ((contentwidth) > 992) {
            $('<div class="bg-overlay1"></div>').appendTo($('body'));
    }

    jQuery('.sidebar-title').click(function () {
        jQuery('.sidebar-title').removeClass('active');
        $(".bg-overlay1").removeClass("active");
        jQuery('.sidebar-submenu').removeClass('close-submenu').slideUp('normal');
        jQuery('.sidebar-submenu, .menu-content').slideUp('normal');
        jQuery('.menu-content').slideUp('normal');

        if (jQuery(this).next().is(':hidden') == true) {
            jQuery(this).addClass('active');
            jQuery(this).next().slideDown('normal');
            $(".bg-overlay1").addClass("active");

             $(".bg-overlay1").on("click", function () {
                 jQuery('.sidebar-submenu, .menu-content').slideUp('normal');
                $(this).removeClass("active");
            });
        } 
        if ((contentwidth) < '992') { 
            $(".bg-overlay").addClass("active");
        }
        
    });
    jQuery('.sidebar-submenu, .menu-content').hide();
    jQuery('.submenu-title').append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
    jQuery('.submenu-title').click(function () {
        jQuery('.submenu-title').removeClass('active').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
        jQuery('.submenu-content').slideUp('normal');
        if (jQuery(this).next().is(':hidden') == true) {
            jQuery(this).addClass('active');
            jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');
            jQuery(this).next().slideDown('normal');
        } else {
            jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
        }
    });
    jQuery('.submenu-content').hide();
} 

// toggle sidebar
$nav = $('.sidebar-wrapper');
$header = $('.page-header');
$toggle_nav_top = $('.toggle-sidebar');
$toggle_nav_top.click(function () {
    $nav.toggleClass('close_icon');
    $header.toggleClass('close_icon');
    $(window).trigger('overlay');
});

$(window).on('overlay', function() {
    $bgOverlay = $(".bg-overlay");
    $isHidden = $nav.hasClass('close_icon');
    if ($(window).width() <= 991 && !$isHidden && $bgOverlay.length === 0) {
        $('<div class="bg-overlay active"></div>').appendTo($('body'));
    }

    if ($isHidden && $bgOverlay.length > 0) {
        $bgOverlay.remove();
    }
});

$('.sidebar-wrapper .back-btn').on('click', function (e) {
    $(".page-header").toggleClass("close_icon");
    $(".sidebar-wrapper").toggleClass("close_icon");
    $(window).trigger('overlay');
});

$("body").on("click", ".bg-overlay", function (){
    $header.addClass("close_icon");
    $nav.addClass("close_icon");
    $(this).remove();
});

/////

$body_part_side = $('.body-part');
$body_part_side.click(function () {
    $toggle_nav_top.attr('checked', false);
    $nav.addClass('close_icon');
    $header.addClass('close_icon');
});

//    responsive sidebar
var $window = $(window);
var widthwindow = $window.width();
(function ($) {
    "use strict";
    if (widthwindow <= 991) {
        $toggle_nav_top.attr('checked', false);
        $nav.addClass("close_icon");
        $header.addClass("close_icon");
    }
})(jQuery);
$(window).resize(function () {
    var widthwindaw = $window.width();
    if (widthwindaw <= 991) {
        $toggle_nav_top.attr('checked', false);
        $nav.addClass("close_icon");
        $header.addClass("close_icon");
    } else {
        $toggle_nav_top.attr('checked', true);
        $nav.removeClass("close_icon");
        $header.removeClass("close_icon");
    }
});

// horizontal arrows
var view = $("#sidebar-menu");
var move = "500px";
var leftsideLimit = -500

// var Windowwidth = jQuery(window).width();
// get wrapper width
var getMenuWrapperSize = function () {
    return $('.sidebar-wrapper').innerWidth();
}
var menuWrapperSize = getMenuWrapperSize();

if ((menuWrapperSize) >= '1660') {
    var sliderLimit = -3000
    
} else if ((menuWrapperSize) >= '1440') {
    var sliderLimit = -3600
} else {
    var sliderLimit = -4200
}

$("#left-arrow").addClass("disabled");
$("#right-arrow").click(function () {
    var currentPosition = parseInt(view.css("marginLeft"));
    if (currentPosition >= sliderLimit) {
        $("#left-arrow").removeClass("disabled");
        view.stop(false, true).animate({
            marginLeft: "-=" + move
        }, {
            duration: 400
        })
        if (currentPosition == sliderLimit) {
            $(this).addClass("disabled");
            console.log("sliderLimit", sliderLimit);
        }
    }
});

$("#left-arrow").click(function () {
    var currentPosition = parseInt(view.css("marginLeft"));
    if (currentPosition < 0) {
        view.stop(false, true).animate({
            marginLeft: "+=" + move
        }, {
            duration: 400
        })
        $("#right-arrow").removeClass("disabled");
        $("#left-arrow").removeClass("disabled");
        if (currentPosition >= leftsideLimit) {
            $(this).addClass("disabled");
        }
    }

});

// page active
if ($('#pageWrapper').hasClass('compact-wrapper')) {
    $(".sidebar-wrapper nav").find("a").removeClass("active");
    $(".sidebar-wrapper nav").find("li").removeClass("active");

    var current = window.location.pathname;
    var currentUrl = window.location.href;

    $(".sidebar-wrapper nav ul li a").each(function () {
        var href = $(this).attr("href");
        if (!href || href === '#' || href.indexOf('javascript:') === 0) return;

        var isMatch = false;
        try {
            var urlObj = new URL(href, window.location.origin);
            if (urlObj.pathname === current || (urlObj.pathname !== '/' && current.indexOf(urlObj.pathname) !== -1)) {
                isMatch = true;
            }
        } catch(e) {
            var link = href.length > 21 ? href.substring(21) : href;
            if (current.indexOf(link) !== -1 || currentUrl.indexOf(href) !== -1) {
                isMatch = true;
            }
        }

        if (isMatch) {
            $(this).addClass('active');
            $(this).closest('li').addClass('active');

            var $parentSub = $(this).closest('.sidebar-submenu');
            if ($parentSub.length) {
                $parentSub.show();
                var $parentTitle = $parentSub.prev('.sidebar-title');
                $parentTitle.addClass('active');
                $parentTitle.find('.according-menu i').removeClass('fa-angle-right').addClass('fa-angle-down');
            }

            var $parentChildSub = $(this).closest('.nav-sub-childmenu, .submenu-content');
            if ($parentChildSub.length) {
                $parentChildSub.show();
                var $parentSubTitle = $parentChildSub.prev('.submenu-title');
                $parentSubTitle.addClass('active');
                $parentSubTitle.find('.according-menu i').removeClass('fa-angle-right').addClass('fa-angle-down');
            }
            return false;
        }
    });
}


$('.left-header .mega-menu .nav-link').on('click', function (event) {
    event.stopPropagation();
    $(this).parent().children('.mega-menu-container').toggleClass("show");
});

$('.left-header .level-menu .nav-link').on('click', function (event) {
    event.stopPropagation();
    $(this).parent().children('.header-level-menu').toggleClass("show");
});

$(document).click(function(){
    $('.mega-menu-container').removeClass("show");
    $('.header-level-menu').removeClass("show");
});

$(window).scroll(function() { 
    var scroll = $(window).scrollTop();
    if (scroll >= 50) {
        $('.mega-menu-container').removeClass('show');
        $('.header-level-menu').removeClass('show');
    }
});

$('.left-header .level-menu .nav-link').click(function() {
    if($('.mega-menu-container').hasClass("show")) {
         $('.mega-menu-container').removeClass("show");
    }
});

$('.left-header .mega-menu .nav-link').click(function() {
    if($('.header-level-menu').hasClass("show")) {
         $('.header-level-menu').removeClass("show");
    }
});


$(document).ready(function(){
    $(".outside").click(function(){
        $(this).find(".menu-to-be-close").slideToggle("fast");
    });
});
$(document).on("click", function(event){
    var $trigger = $(".outside");
    if($trigger !== event.target && !$trigger.has(event.target).length){
        $(".menu-to-be-close").slideUp("fast");
    }            
});


$('.left-header .link-section > div').on('click', function (e) {
    if ($(window).width() <= 1199) {
        $(".left-header .link-section > div").removeClass("active");
        $(this).toggleClass("active");
        $(this).parent().children('ul').toggleClass("d-block").slideToggle();
    }
});

if ($(window).width() <= 1199) {
    $(".left-header .link-section").children('ul').css('display', 'none');
    $(this).parent().children('ul').toggleClass("d-block").slideToggle();
}
// if ($(window).width() <= 991) {
//     $('.sidebar-wrapper .back-btn').on('click', function (e) {
//         $(".page-header").toggleClass("close_icon");
//         $(".sidebar-wrapper").toggleClass("close_icon");
//     });
// }

// active link (neutralized auto-scroll animation so sidebar starts cleanly at top)
// if($('.simplebar-wrapper .simplebar-content-wrapper') && $('#pageWrapper').hasClass('compact-wrapper')) {
//   $('.simplebar-wrapper .simplebar-content-wrapper').animate({
//       scrollTop: $('.simplebar-wrapper .simplebar-content-wrapper a.active').offset().top - 400
//   }, 1000);
// }

// Profile badge dropdown toggle (click & outside-click)
$(document).on('click', '.profile-nav .profile-badge', function(e) {
    e.stopPropagation();
    $(this).closest('.profile-nav').toggleClass('active');
});
$(document).on('click', function(e) {
    if (!$(e.target).closest('.profile-nav').length) {
        $('.profile-nav').removeClass('active');
    }
});


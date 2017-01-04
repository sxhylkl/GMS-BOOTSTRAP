
function handleSidebarMenu() {

    jQuery('.page-sidebar').on('click', 'li > a', function (e) {
        if ($(this).next().hasClass('sub-menu') == false) {
            if ($('.btn-navbar').hasClass('collapsed') == false) {
                $('.btn-navbar').click();
            }
            return;
        }

        var parent = $(this).parent().parent();

        parent.children('li.open').children('a').children('.arrow').removeClass('open');
        parent.children('li.open').children('.sub-menu').slideUp(200);
        parent.children('li.open').removeClass('open');

        var sub = jQuery(this).next();
        if (sub.is(":visible")) {
            jQuery('.arrow', jQuery(this)).removeClass("open");
            jQuery(this).parent().removeClass("open");
            sub.slideUp(200, function () {
                handleSidebarAndContentHeight();
            });
        } else {
            jQuery('.arrow', jQuery(this)).addClass("open");
            jQuery(this).parent().addClass("open");
            sub.slideDown(200, function () {
                handleSidebarAndContentHeight();
            });
        }

        e.preventDefault();
    });

    // handle ajax links
    jQuery('.page-sidebar').on('click', ' li > a.ajaxify', function (e) {
        e.preventDefault();
        App.scrollTop();

        var url = $(this).attr("href");
        var menuContainer = jQuery('.page-sidebar ul');
        var pageContent = $('.page-content');
        var pageContentBody = $('.page-content .page-content-body');

        menuContainer.children('li.active').removeClass('active');
        menuContainer.children('arrow.open').removeClass('open');

        $(this).parents('li').each(function () {
            $(this).addClass('active');
            $(this).children('a > span.arrow').addClass('open');
        });
        $(this).parents('li').addClass('active');

        App.blockUI(pageContent, false);

        $.post(url, {}, function (res) {
            App.unblockUI(pageContent);
            pageContentBody.html(res);
            App.fixContentHeight(); // fix content height
            App.initUniform(); // initialize uniform elements
        });
    });
}


function handleSidebarAndContentHeight() {
    var content = $('.page-content');
    var sidebar = $('.page-sidebar');
    var body = $('body');
    var height;

    if (body.hasClass("page-footer-fixed") === true && body.hasClass("page-sidebar-fixed") === false) {
        var available_height = $(window).height() - $('.footer').height();
        if (content.height() <  available_height) {
            content.attr('style', 'min-height:' + available_height + 'px !important');
        }
    } else {
        if (body.hasClass('page-sidebar-fixed')) {
            height = _calculateFixedSidebarViewportHeight();
        } else {
            height = sidebar.height()-50;
        }
        if (height >= content.height()) {
            content.attr('style', 'min-height:' + height + 'px !important');
        }
        //sidebar.attr('style','padding-top:50px!')
    }
}
function _calculateFixedSidebarViewportHeight () {
    var sidebarHeight = $(window).height() - $('.header').height() + 1;
    if ($('body').hasClass("page-footer-fixed")) {
        sidebarHeight = sidebarHeight - $('.footer').height();
    }
    return sidebarHeight;
}
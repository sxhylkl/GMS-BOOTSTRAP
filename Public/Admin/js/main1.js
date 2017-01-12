function handleSidebarMenu() {
    jQuery('.page-sidebar').on('click', 'li > a', function (e) {
        handleSidebarSelect($(this));
        if (jQuery(this).next().hasClass('sub-menu') == false) {
            if (jQuery('.btn-navbar').hasClass('collapsed') == false) {
                jQuery('.btn-navbar').click();
            }
            return;
        }
        var parent = jQuery(this).parent().parent();

        parent.children('li.open').children('a').children('.icon').removeClass('open');
        parent.children('li.open').children('.sub-menu').slideUp(200);
        parent.children('li.open').removeClass('open');

        var sub = jQuery(this).next();
        if (sub.is(":visible")) {
            jQuery('.icon', jQuery(this)).removeClass("open");
            jQuery(this).parent().removeClass("open");
            sub.slideUp(200, function () {
                handleSidebarAndContentHeight();
            });
        } else {
            jQuery('.icon', jQuery(this)).addClass("open");
            jQuery(this).parent().addClass("open");
            sub.slideDown(200, function () {
                handleSidebarAndContentHeight();
            });
        }

        e.preventDefault();
    });
}
function handleSidebarAndContentHeight() {
    var content = jQuery('.page-content');
    var sidebar = jQuery('.page-sidebar');
    var body = jQuery('body');
    var height;


    height = sidebar.height() - 50;

    if (height >= content.height()) {
        content.attr('style', 'min-height:' + height + 'px !important');
    }

}


function handleSidebarSelect(clickThis) {
    // 1：菜单  2：节点
    //当点击节点时进行操作
    if ('2' == clickThis.attr('menuType')) {

        var tableInfo = {
            tabMainName: 'mainTab',
            tabName: clickThis[0].innerHTML.replace(/(^\s*)|(\s*$)/g, ''),
            tabTitle: clickThis[0].innerHTML,
            tabUrl: clickThis.attr('menuUrl'),
            tabContentMainName:'mainContent'
        };

        console.log(clickThis[0].innerHTML.replace(/(^\s*)|(\s*$)/g, ''));
        console.log(clickThis.attr('menuType'));
        console.log(clickThis.attr('menuUrl'));
        console.log(clickThis.attr('menuID'));

        handleSelectTable(tableInfo)
    }
}
/**
 * 增加标签页
 * option:
     tabMainName:tab标签页所在的容器
     tabName:当前tab的名称
     tabTitle:当前tab的标题
     tabUrl:当前tab所指向的URL地址
     tabContentMainName:content所指向的contentID
 */
function handleSelectTable(options) {

    var exists = checkTabIsExists(options.tabMainName, options.tabName);
    if (exists) {
        jQuery("#tab_a_" + options.tabName).click();
    } else {
        jQuery("#" + options.tabMainName).append('<li id="tab_li_' + options.tabName + '"><a href="#tab_content_' + options.tabName + '" data-toggle="tab" id="tab_a_' + options.tabName + '">' + options.tabTitle + '<span class="icon iconfont icon-jiantou-xia" onclick="closeTab(this);"></span></a></li>');
        //固定TAB中IFRAME高度
        // mainHeight = jQuery(document.body).height() - 5;

        var content = '';
        if (options.content) {
            content = option.content;
        } else {
            content = '<iframe src="' + options.tabUrl + '" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no" allowtransparency="yes"></iframe>';
        }
        jQuery("#" + options.tabContentMainName).append('<div id="tab_content_' + options.tabName + '" role="tabpanel" class="tab-pane" id="' + options.tabName + '">' + content + '</div>');
        jQuery("#tab_a_" + options.tabName).click();
    }
}


/**
 * 关闭标签页
 * @param button
 */
function closeTab(button) {

    //通过该button找到对应li标签的id
    var li_id = jQuery(button).parent().parent().attr('id');
    var id = li_id.replace("tab_li_", "");

    //如果关闭的是当前激活的TAB，激活他的前一个TAB
    if (jQuery("li.active").attr('id') == li_id) {
        jQuery("li.active").prev().find("a").click();
    }

    //关闭TAB
    jQuery("#" + li_id).remove();
    jQuery("#tab_content_" + id).remove();
}

/**
 * 判断是否存在指定的标签页
 * @param tabMainName
 * @param tabName
 * @returns {Boolean}
 */
function checkTabIsExists(tabMainName, tabName) {
    var tab = jQuery("#" + tabMainName + " > #tab_li_" + tabName);
    //console.log(tab.length)
    return tab.length > 0;
}


function Data_Ajax(Data_from_url, Datagrid_data, count) {
    if (count != '') {
        jQuery.messager.confirm('确定操作', count, function (flag) {
            if (flag) {
                jQuery.post(Data_from_url, {}, function (res) {
                    if (!res.status) {
                        jQuery.messager.show({title: '错误提示', msg: res.info, timeout: 2000, showType: 'slide'});
                    } else {
                        jQuery.messager.show({title: '成功提示', msg: res.info, timeout: 1000, showType: 'slide'});
                        jQuery('#' + Datagrid_data).datagrid('reload');
                        jQuery('#' + Datagrid_data).treegrid('reload');
                    }
                })
            }
        })
    } else {
        jQuery.post(Data_from_url, {}, function (res) {
            if (!res.status) {
                jQuery.messager.show({title: '错误提示', msg: res.info, timeout: 2000, showType: 'slide'});
            } else {
                jQuery.messager.show({title: '成功提示', msg: res.info, timeout: 1000, showType: 'slide'});
                jQuery('#' + Datagrid_data).datagrid('reload');
                jQuery('#' + Datagrid_data).treegrid('reload');
            }
        })
    }
}

/* 刷新页面 */
function Data_Reload(Data_Box) {
    jQuery('#' + Data_Box).datagrid('reload');
    jQuery('#' + Data_Box).treegrid('reload');
}


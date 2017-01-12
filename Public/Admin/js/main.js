/**
 * Created by 李 on 2017/1/12 0012.
 */
$(function () {

    $('.list-group').on('click', 'div > a', function (e) {
        // 1：菜单  2：节点
        //当点击节点时进行操作
        if ('2' == $(this).attr('menuType')) {

            var history = $(this).attr('menuHis');

            history = history.split(',');

            setTitle(history[0], history[1], history[2]);
            setContent($(this).attr('menuUrl'));
        }
    });


    /**
     * 设置panel 头部记录
     * @param first
     * @param second
     * @param third
     */
    function setTitle(first, second, third) {
        if (first) {

            //移除旧内容
            $('.breadcrumb > li').remove();

            html = '<li>' + first + '</li>';
            if (second) {
                html += '<li>' + second + '</li>';
            }
            if (third) {
                html += '<li>' + third + '</li>';
            }
        }
        else {
            html = '';
        }
        //添加新内容
        $('.breadcrumb').append(html)
    }

    function setContent(url) {
        if (url) {
            $('#main-content').attr('src', url);
        }
    }


});
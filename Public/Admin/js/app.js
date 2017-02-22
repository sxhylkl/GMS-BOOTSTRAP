/**
 Core script to handle the entire layout and base functions
 **/
var App = function () {
    var debug = true;
    var handleInit = function () {
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

        //默认选中第一项
        $($('.list-group').children()[0]).children()[0].click();
    };
    return {
        init: function () {
            handleInit();
        },
        // check for device touch support
        SLog: function () {
            if (debug) {
                $.each(arguments, function (index, val) {
                    console.log('debug------------>', val);
                });
            }
        },
        isTouchDevice: function () {
            try {
                document.createEvent("TouchEvent");
                return true;
            } catch (e) {
                return false;
            }
        },
        /**
         * 初始化一个table
         * @param url   远程加载数据地址
         * @param columns 表格列名称
         * @param toolbar 表格上方工具条ID
         * @param method  远程数据加载方式‘post & get’
         * @param height  表格高度
         * @param uniqueId 唯一键
         * @param singleSelect 是否为单选
         * @param pagination   是否进行分页
         */
        initTable: function (object) {
            var toolbar = object.toolbar ? object.toolbar : '';
            var method = object.method ? object.method : 'post';
            var height = object.height ? object.height : 750;
            var uniqueId = object.uniqueId ? object.uniqueId : 'id';
            var singleSelect = object.singleSelect ? false : true;
            var pagination = object.pagination ? false : true;
            $("#contentTable").bootstrapTable({
                url: object.url,         //请求后台的URL（*）
                columns: object.columns,
                method: method,                      //请求方式（*）
                toolbar: toolbar,                //工具按钮用哪个容器
                height: height,
                singleSelect: singleSelect,                  //单选选项
                uniqueId: uniqueId,                     //每一行的唯一标识，一般为主键列
                pagination: pagination,                   //是否显示分页（*）
                pageNumber: 1,                       //初始化加载第一页，默认第一页
                pageSize: 20,                       //每页的记录行数（*）
                pageList: [10, 25, 50, 100],        //可供选择的每页的行数（*）
                cache: false,                       //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
                sidePagination: "server",           //分页方式：client客户端分页，server服务端分页（*）
                showRefresh: true,                  //是否显示刷新按钮
                minimumCountColumns: 2,             //最少允许的列数
                clickToSelect: true                //是否启用点击选中行

            });
        },

        /**
         * 初始化一个对话框
         */
        initDialog: function (object) {
            var html = " <div class= modal id=" + object.id + " tabindex=-1 role=dialog aria-labelledby = exampleModalLabel>" +
                "<div class=modal-dialog role=document>" +
                "<div class=modal-content>" +
                "<div class=modal-header>" +
                "<button type=button class=close data-dismiss=modal aria-label=Close><span aria-hidden=true>&times;</span></button>" +
                "<h4 class=modal-title id=exampleModalLabel>" + object.title + "</h4>" +
                "</div>" +
                "<div id=_dialog_body_ class=modal-body></div>" +
                "<div id=_dialog_footer_ class=modal-footer>" +
                "<button type=button class=btn btn-default data-dismiss=modal>Close</button>"+
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";
            // $('.modal-body').load(object.url,function (response,status) {
            //     if (status=="success"){
            //         $('.modal-body').html(response);
            //     }
            // });
            $('body').append(html);
            if (undefined != object.url && '' != object.url) {
                $.post(object.url + "&time=" + (Date.parse(new Date())), function (data) {
                    $('#_dialog_body_').html(data);
                    if ('function' == typeof(object.onLoadSuccess)) {
                        object.onLoadSuccess(data);
                    }
                })
            }
            else {
                $('#_dialog_body_').append($(object.body).html());
                $(object.body).remove();
            }
            //$('#_dialog_body_').load(object.url);
            $('#_dialog_footer_').prepend($(object.footer).html());
            $(object.footer).remove();
            //
            // var url = arguments[1] ? arguments[1] : '';
            // var callback = arguments[2] ? arguments[2] : '';
            //
            // console.log(id,url);
            // $("#" + id).modal({remote: url});
            // $("#" + id).on('loaded.bs.modal',callback);
        },

        dialogShow: function (id) {
            $('#' + id).modal('show');
        },
        dialogClose:function (id) {
            $('#' + id).modal('close');
        }


    };

}();
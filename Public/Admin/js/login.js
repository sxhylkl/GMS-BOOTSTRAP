/**
 * Created by li on 16/12/30.
 */
$(document).ready(function () {

    $._messengerDefaults = {
        extraClasses: 'messenger-fixed messenger-theme-air messenger-on-bottom messenger-on-right'
    };
    //
    // <!-- 添加，密码输入回车后自动登录-->
    // $(document).keypress(function (event) {
    //     var key = event.which;
    //     if (key == 13) {
    //         $("[id$=loginButton]").click(); //支持firefox,IE武校
    //         //$('input:last').focus();
    //         $("[id$=loginButton]").focus();  //支持IE，firefox无效。
    //         //以上两句实现既支持IE也支持 firefox
    //     }
    // });

    $('.form-horizontal input').keypress(function (e) {
        if (e.which == 13) {
            if ($('.login-form').validate().form()) {
                window.location.href = "index.html";
            }
            return false;
        }
    });



    $('.form-horizontal').validate({
        errorElement: 'label', //default input error message container
        errorClass: 'help-inline', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
            inpUser: {
                required: true
            },
            inpPwd: {
                required: true
            }
        },

        messages: {
            inpUser: {
                required: "Username is required."
            },
            inpPwd: {
                required: "Password is required."
            }
        },

        invalidHandler: function (event, validator) { //display error alert on form submit
            $('.alert-error', $('.form-horizontal')).show();
        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('error'); // set error class to the control group
        },

        success: function (label) {
            label.closest('.form-group').removeClass('error');
            label.remove();
        },
        submitHandler: function (form) {
            // window.location.href = "index.html";
            // $.post('index.php?m=Admin&c=Public&a=login', {
            //     username: $('#inpUser').val(),
            //     password: $('#inpPwd').val()
            // }, function (data) {
            //
            // }, 'json');
        }
    });





    $('#loginButton').click(function (e) {




        // $.globalMessenger().post({
        //     message: "操作成功",//提示信息
        //     type: 'info',//消息类型。error、info、success
        //     hideAfter: 5,//多长时间消失
        //     showCloseButton:true,//是否显示关闭按钮
        //     hideOnNavigate: true //是否隐藏导航
        // });

    })


});
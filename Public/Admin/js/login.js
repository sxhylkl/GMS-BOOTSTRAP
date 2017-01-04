/**
 * Created by li on 16/12/30.
 */
$(document).ready(function () {


    $('.form-horizontal').bootstrapValidator({
        feedbackIcons: {
            valid: 'icon iconfont icon-ok',
            invalid: 'icon iconfont icon-error',
            validating: 'icon iconfont icon-error'
        },
        fields: {
            username: {
                message: '用户名验证失败',
                validators: {
                    notEmpty: {
                        message: '用户名不能为空'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: '密码不能为空'
                    }
                }
            }
        },
        submitButtons: '#loginButton[type="submit"]',
        submitHandler: function (validator, form, submitButton) {
            $.post('index.php?m=Admin&c=Public&a=login', form.serialize(), function (data) {
                // .自定义回调逻辑
                login(data);
            }, 'json');
        }
    });


    function login(res) {
        if (200 == res.Code) {
            window.location = res.Data;
        }
        else {
            $._messengerDefaults = {
                extraClasses: 'messenger-fixed messenger-theme-air messenger-on-bottom messenger-on-right'
            };

            $.globalMessenger().post({
                message: "操作失败:" + res.Msg,//提示信息
                type: 'error'//消息类型。error、info、success
            });
        }
    }
});
$(function(){
    'use strict';

    $(document).on('click', '.btn-signin', function (e) {
        e.preventDefault();
        $('#loginForm').submit();
    });

    $("#loginForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            var username = $('.username').val();
            var password =  $('.password').val();
            LoginFormSubmit(username,password);
        }
    });

    var LoginFormSubmit = function(username,password) {
        $.ajax({
            url: base_admin + "/admin/login",
            type: "POST",
            data: {
                username: username,
                password: password
            },
            success: function(response) {
                if (response.code == '200') {
                    location.reload();
                } else {
                    Lobibox.notify("warning", {
                        title: 'Notification',
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        icon: false,
                        sound: false,
                        msg: response.msg
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Lobibox.notify("warning", {
                    title: 'Notification',
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    icon: false,
                    sound: false,
                    msg: 'There was an error during processing'
                });
            }
        });
    };
});

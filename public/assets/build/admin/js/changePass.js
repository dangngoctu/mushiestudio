$(function(){
    $("#changePassWordModal").on("click",function(){
        ClearFormChangePassword();
        $('#modal-change-password').modal('show');
    });

    $(document).on('click', '#ChangePasswordBtn', function (e) {
        e.preventDefault();
        $('#ChangePasswordForm').submit();
    });

    $("#ChangePasswordForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            ChangePasswordFormSubmit();
        }
    });
});

var ClearFormChangePassword = function() {
    $('#ChangePasswordForm')[0].reset();
}

var ChangePasswordFormSubmit = function(){
    $.ajax({
        url: base_admin + "/admin/changepassword",
        type: "post",
        data: $('#ChangePasswordForm').serialize(),
        success: function(response) {
            if (response.code == '200') {
                Lobibox.notify("success", {
                    title: 'Notification',
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    icon: false,
                    sound: false,
                    msg: response.msg
                });
                $('#modal-change-password').modal('hide');
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
}
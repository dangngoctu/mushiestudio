
$(function(){
    'use strict';
    $('.table-dynamic-comment').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax": base_admin+"/admin/ajax/ajax_comment",
        "responsive": true,
        "scrollX": true,
        "pagingType": "full_numbers",
        "language": {
            searchPlaceholder: 'Search...',
            sSearch: ''
        },
		"columns": [
			{"data": "id"},
			{"data": "name"},
            {"data": "sex"},
            {"data": "city"},
            {"data": "email"},
            {"data": "phone"},
            {"data": "created"},
            {"data": "action", "searchable": false}
		],
		"columnDefs": [
            {
				targets: [0],
				class: 'text-center'
			},
            {
                orderable: false,
                targets: [1, 2, 3, 4, 5],
                class: 'text-center'
            }
		]
    });

    $(document).on('click', '.table-dynamic-comment .table-action-edit', function (e) {
        var id = $(this).attr('data-id');
        var lang = $(this).attr('data-lang');
        UpdateComment(id, lang);
    });
});

var UpdateComment = function(id, lang) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_comment?lang=" + lang + "&id=" + id,
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                $('#modal-comment #name').html(response.data.name);
                if(response.data.sex == 1){
                    $('#modal-comment #sex').html('Nam');
                } else {
                    $('#modal-comment #sex').html('Nữ');
                }
                $('#modal-comment #city').html(response.data.city.name);
                $('#modal-comment #email').html(response.data.email);
                $('#modal-comment #phone').html(response.data.phone);
                $('#modal-comment #cmnd').html(response.data.cmnd);
                $('#modal-comment #comment').html(response.data.comment);
                if(response.data.accept == 1){
                    $('#modal-comment #accept').html('Đồng ý');
                } else {
                    $('#modal-comment #accept').html('Không');
                }
                if(response.data.customer == 1){
                    $('#modal-comment #customer').html('Có');
                } else {
                    $('#modal-comment #customer').html('Không');
                }
                $('#modal-comment').modal('show');
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
            $('#TitleForm').each(function() {
                $(this).data('serialized', $(this).serialize())
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Lobibox.notify("warning", {
                title: 'Notification',
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                icon: false,
                sound: false,
                msg: response.msg
            });
        }
    });
};

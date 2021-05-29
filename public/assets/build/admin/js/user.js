var api_fileuploader_url;
$(function(){
    'use strict';

    var table_dynamic_user = $('.table-dynamic-user').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax": base_admin+"/admin/ajax/ajax_user",
        "responsive": true,
        "scrollX": true,
        "pagingType": "full_numbers",
        "language": {
            searchPlaceholder: 'Search...',
            sSearch: ''
        },
		"columns": [
			{"data": "id"},
            {"data": "email"},
            {"data": "name"},
			{"data": "action", "searchable": false}
		],
		"columnDefs": [
            {
				targets: [0],
                class: 'text-center',
                width: '50'
            },
            {
                orderable: false,
                targets: [1],
                class: 'text-center'
            },
			{
				orderable: false,
				targets: [-1],
				class: 'text-center'
			}
		]
    });

    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
    $(document).on('click', '.table-dynamic-user .table-action-delete', function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#confirm-delete-modal #id').val(id);
        $('#confirm-delete-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    $('#confirm-delete-modal').on('click', '#confirm-delete', function (e) {
        var id = $('#confirm-delete-modal #id').val();
        DeleteUser(id, table_dynamic_user);
    });
    $(document).on('click', '.table-dynamic-user .table-action-edit', function (e) {
        var id = $(this).attr('data-id');
        ClearFormUser('edit');
        UpdateUser(id);
    });
    $(document).on('click', '#addUser', function (e) {
		e.preventDefault();
        $('#UserForm')[0].reset();
        ClearFormUser('add');
		$('#modal-user').modal('show');
    });
    $(document).on('click', '#btnUser', function (e) {
        e.preventDefault();
        $('#UserForm').submit();
    });
    $("#UserForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            UserFormSubmit(table_dynamic_user);
        }
    });
    $('#UserForm').on('change input', function() {
        $('#modal-user').find('button:button.btn-primary').prop('disabled', $(this).serialize() == $(this).data('serialized'));
    });
    $('#modal-user').on('hidden.bs.modal', function (e) {
        $('#draw-button').trigger('click');
    });
});

var UserFormSubmit = function(table) {
    $('#btnUser').attr('disabled', true);
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_user",
        type: "post",
        data: $('#UserForm').serialize(),
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
                table.ajax.reload(null, true);
                // $('#btnArea').button('reset');
                $('#modal-user').modal('hide');
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

var UpdateUser = function(id) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_user?&id=" + id,
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                $('#modal-user #id').val(response.data.id);
                $('#modal-user #name').val(response.data.name);
                $('#modal-user #email').val(response.data.email);
                $('#modal-user').modal('show');
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
            $('#UserForm').each(function() {
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

var DeleteUser = function(id, table) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_user?action=delete&id=" + id,
        type: "post",
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
                table.ajax.reload(null, true);
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
            // $('#confirm-delete-modal #confirm-delete').button('reset');
            $('#confirm-delete-modal').modal('hide');
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
            // $('#confirm-delete-modal #confirm-delete').button('reset');
            $('#confirm-delete-modal').modal('hide');
        }
    });
};

var ClearFormUser = function(type) {
    $('#UserForm')[0].reset();
    $('#UserForm').parsley().reset();
    if (type == "add") {
        $('#modal-user #ttlModal').html('Add user');
        $('#modal-user #action').val('insert');
        $('#UserForm').each(function() {
            $(this).data('serialized', $(this).serialize())
        });
    } else {
        $('#modal-user #ttlModal').html('Edit user');
        $('#modal-user #action').val('update');
    }
    $('#modal-user').find('button.btn-primary').prop('disabled', true);
};
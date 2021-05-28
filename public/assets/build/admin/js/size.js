var api_fileuploader_url;
$(function(){
    'use strict';

    var table_dynamic_size = $('.table-dynamic-size').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax": base_admin+"/admin/ajax/ajax_size",
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
    $(document).on('click', '.table-dynamic-size .table-action-delete', function (e) {
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
        DeleteSize(id, table_dynamic_size);
    });
    $(document).on('click', '.table-dynamic-size .table-action-edit', function (e) {
        var id = $(this).attr('data-id');
        ClearFormSize('edit');
        UpdateSize(id);
    });
    $(document).on('click', '#addSize', function (e) {
		e.preventDefault();
        $('#SizeForm')[0].reset();
        ClearFormSize('add');
		$('#modal-size').modal('show');
    });
    $(document).on('click', '#btnSize', function (e) {
        e.preventDefault();
        $('#SizeForm').submit();
    });
    $("#SizeForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            SizeFormSubmit(table_dynamic_size);
        }
    });
    $('#SizeForm').on('change input', function() {
        $('#modal-size').find('button:button.btn-primary').prop('disabled', $(this).serialize() == $(this).data('serialized'));
    });
    $('#modal-size').on('hidden.bs.modal', function (e) {
        $('#draw-button').trigger('click');
    });
});

var SizeFormSubmit = function(table) {
    $('#btnSize').attr('disabled', true);
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_size",
        type: "post",
        data: $('#SizeForm').serialize(),
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
                $('#modal-size').modal('hide');
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

var UpdateSize = function(id) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_size?&id=" + id,
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                $('#modal-size #id').val(response.data.id);
                $('#modal-size #name').val(response.data.name);
                $('#modal-size').modal('show');
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
            $('#SizeForm').each(function() {
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

var DeleteSize = function(id, table) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_size?action=delete&id=" + id,
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

var ClearFormSize = function(type) {
    $('#SizeForm')[0].reset();
    $('#SizeForm').parsley().reset();
    if (type == "add") {
        $('#modal-size #ttlModal').html('Add size');
        $('#modal-size #action').val('insert');
        $('#SizeForm').each(function() {
            $(this).data('serialized', $(this).serialize())
        });
    } else {
        $('#modal-size #ttlModal').html('Edit size');
        $('#modal-size #action').val('update');
    }
    $('#modal-size').find('button.btn-primary').prop('disabled', true);
};
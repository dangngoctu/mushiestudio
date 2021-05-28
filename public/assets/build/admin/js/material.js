var api_fileuploader_url;
$(function(){
    'use strict';

    var table_dynamic_material = $('.table-dynamic-material').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax": base_admin+"/admin/ajax/ajax_material",
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
    $(document).on('click', '.table-dynamic-material .table-action-delete', function (e) {
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
        DeleteMaterial(id, table_dynamic_material);
    });
    $(document).on('click', '.table-dynamic-material .table-action-edit', function (e) {
        var id = $(this).attr('data-id');
        ClearFormMaterial('edit');
        UpdateMaterial(id);
    });
    $(document).on('click', '#addMaterial', function (e) {
		e.preventDefault();
        $('#MaterialForm')[0].reset();
        ClearFormMaterial('add');
		$('#modal-material').modal('show');
    });
    $(document).on('click', '#btnMaterial', function (e) {
        e.preventDefault();
        $('#MaterialForm').submit();
    });
    $("#MaterialForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            MaterialFormSubmit(table_dynamic_material);
        }
    });
    $('#MaterialForm').on('change input', function() {
        $('#modal-material').find('button:button.btn-primary').prop('disabled', $(this).serialize() == $(this).data('serialized'));
    });
    $('#modal-material').on('hidden.bs.modal', function (e) {
        $('#draw-button').trigger('click');
    });
});

var MaterialFormSubmit = function(table) {
    $('#btnMaterial').attr('disabled', true);
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_material",
        type: "post",
        data: $('#MaterialForm').serialize(),
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
                $('#modal-material').modal('hide');
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

var UpdateMaterial = function(id) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_material?&id=" + id,
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                $('#modal-material #id').val(response.data.id);
                $('#modal-material #name').val(response.data.name);
                $('#modal-material').modal('show');
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
            $('#MaterialForm').each(function() {
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

var DeleteMaterial = function(id, table) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_material?action=delete&id=" + id,
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

var ClearFormMaterial = function(type) {
    $('#MaterialForm')[0].reset();
    $('#MaterialForm').parsley().reset();
    if (type == "add") {
        $('#modal-material #ttlModal').html('Add material');
        $('#modal-material #action').val('insert');
        $('#MaterialForm').each(function() {
            $(this).data('serialized', $(this).serialize())
        });
    } else {
        $('#modal-material #ttlModal').html('Edit material');
        $('#modal-material #action').val('update');
    }
    $('#modal-material').find('button.btn-primary').prop('disabled', true);
};
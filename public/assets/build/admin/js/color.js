var api_fileuploader_url;
$(function(){
    'use strict';

    var table_dynamic_color = $('.table-dynamic-color').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax": base_admin+"/admin/ajax/ajax_color",
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
            {"data": "color"},
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
                targets: [1,2],
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
    $(document).on('click', '.table-dynamic-color .table-action-delete', function (e) {
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
        DeleteColor(id, table_dynamic_color);
    });
    $(document).on('click', '.table-dynamic-color .table-action-edit', function (e) {
        var id = $(this).attr('data-id');
        ClearFormColor('edit');
        UpdateColor(id);
    });
    $(document).on('click', '#addColor', function (e) {
		e.preventDefault();
        $('#ColorForm')[0].reset();
        ClearFormColor('add');
		$('#modal-color').modal('show');
    });
    $(document).on('click', '#btnColor', function (e) {
        e.preventDefault();
        $('#ColorForm').submit();
    });
    $("#ColorForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            ColorFormSubmit(table_dynamic_color);
        }
    });
    $('#ColorForm').on('change input', function() {
        $('#modal-color').find('button:button.btn-primary').prop('disabled', $(this).serialize() == $(this).data('serialized'));
    });
    $('#modal-color').on('hidden.bs.modal', function (e) {
        $('#draw-button').trigger('click');
    });
});

var ColorFormSubmit = function(table) {
    $('#btnColor').attr('disabled', true);
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_color",
        type: "post",
        data: $('#ColorForm').serialize(),
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
                $('#modal-color').modal('hide');
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

var UpdateColor = function(id) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_color?&id=" + id,
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                $('#modal-color #id').val(response.data.id);
                $('#modal-color #name').val(response.data.name);
                $('#modal-color #color_code').val(response.data.color_code);
                $("#colorpicker").spectrum({
                    preferredFormat: "hex",
                    color: response.data.color_code
                });
                $('#modal-color').modal('show');
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
            $('#ColorForm').each(function() {
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

var DeleteColor = function(id, table) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_color?action=delete&id=" + id,
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

var ClearFormColor = function(type) {
    $('#ColorForm')[0].reset();
    $('#ColorForm').parsley().reset();
    $("#colorpicker").spectrum({
        preferredFormat: "hex",
        color: '#000000'
    });
    if (type == "add") {
        $('#modal-color #ttlModal').html('Add color');
        $('#modal-color #action').val('insert');
        $('#ColorForm').each(function() {
            $(this).data('serialized', $(this).serialize())
        });
    } else {
        $('#modal-color #ttlModal').html('Edit color');
        $('#modal-color #action').val('update');
    }
    $('#modal-color').find('button.btn-primary').prop('disabled', true);
};
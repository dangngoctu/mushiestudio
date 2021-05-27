var api_fileuploader_url;
$(function(){
    'use strict';
    var table_dynamic_title = $('.table-dynamic-title').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax": base_admin+"/admin/ajax/ajax_title",
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
            {"data": "created"},
            {"data": "status"},
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
                targets: [1]
            },
            {
                orderable: false,
                targets: [2],
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
    $(document).on('click', '.table-dynamic-title .table-action-delete', function (e) {
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
        DeleteTitle(id, table_dynamic_title);
    });
    $(document).on('click', '.table-dynamic-title .table-action-edit', function (e) {
        var id = $(this).attr('data-id');
        var lang = $(this).attr('data-lang');
        ClearFormTitle(lang, 'edit');
        UpdateTitle(id, lang);
    });
    $(document).on('click', '#addTitle', function (e) {
		e.preventDefault();
        $('#TitleForm')[0].reset();
        var lang = $(this).attr('data-lang');
        ClearFormTitle(lang, 'add');
		$('#modal-title').modal('show');
    });
    $(document).on('click', '#btnTitle', function (e) {
        e.preventDefault();
        $('#TitleForm').submit();
    });
    $("#TitleForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            TitleFormSubmit(table_dynamic_title);
        }
    });
    $('#TitleForm').on('change input', function() {
        $('#modal-title').find('button:button.btn-primary').prop('disabled', $(this).serialize() == $(this).data('serialized'));
    });
    $('#modal-title').on('hidden.bs.modal', function (e) {
        $('#draw-button').trigger('click');
    });
});

var TitleFormSubmit = function(table) {
    $('#btnTitle').attr('disabled', true);
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_title",
        type: "post",
        data: $('#TitleForm').serialize(),
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
                $('#modal-title').modal('hide');
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

var UpdateTitle = function(id, lang) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_title?lang=" + lang + "&id=" + id,
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                $('#modal-title #id').val(response.data.id);
                $('#modal-title #name').val(response.data.news_title_translations.name);
                if(response.data.status == 1) {
                    $('#modal-title #status').prop( "checked", true );
                } else {
                    $('#modal-title #status').prop( "checked", false );
                }
                $('#modal-title').modal('show');
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

var DeleteTitle = function(id, table) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_title?action=delete&id=" + id,
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

var ClearFormTitle = function(lang, type) {
    $('#TitleForm')[0].reset();
    $('#TitleForm').parsley().reset();
    $('#modal-title #lang').val(lang);
    if (type == "add") {
        $('#modal-title #ttlModal').html('Thêm danh mục');
        $('#modal-title #action').val('insert');
        $('#TitleForm').each(function() {
            $(this).data('serialized', $(this).serialize())
        });
    } else {
        $('#modal-title #ttlModal').html('Cập nhật danh mục');
        $('#modal-title #action').val('update');
    }
    $('#modal-title').find('button.btn-primary').prop('disabled', true);
};
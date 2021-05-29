var api_fileuploader_url;
$(function(){
    'use strict';
    changeUrl($('#name'), $('#url'));
    changeUrl($('#url'), $('#url'));
    var table_dynamic_menu = $('.table-dynamic-menu').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax": base_admin+"/admin/ajax/ajax_menu",
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
            {"data": "url"},
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
                targets: [1,2]
            },
			{
				orderable: false,
				targets: [-1],
				class: 'text-center'
			}
		]
    });

    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
    $(document).on('click', '.table-dynamic-menu .table-action-delete', function (e) {
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
        DeleteMenu(id, table_dynamic_menu);
    });
    $(document).on('click', '.table-dynamic-menu .table-action-edit', function (e) {
        var id = $(this).attr('data-id');
        ClearFormMenu('edit');
        UpdateMenu(id);
    });
    $(document).on('click', '#addMenu', function (e) {
		e.preventDefault();
        $('#MenuForm')[0].reset();
        ClearFormMenu('add');
		$('#modal-menu').modal('show');
    });
    $(document).on('click', '#btnMenu', function (e) {
        e.preventDefault();
        $('#MenuForm').submit();
    });
    $("#MenuForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            MenuFormSubmit(table_dynamic_menu);
        }
    });
    $('#MenuForm').on('change input', function() {
        $('#modal-menu').find('button:button.btn-primary').prop('disabled', $(this).serialize() == $(this).data('serialized'));
    });
    $('#modal-menu').on('hidden.bs.modal', function (e) {
        $('#draw-button').trigger('click');
    });
});

var MenuFormSubmit = function(table) {
    $('#btnMenu').attr('disabled', true);
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_menu",
        type: "post",
        data: $('#MenuForm').serialize(),
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
                $('#modal-menu').modal('hide');
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

var UpdateMenu = function(id) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_menu?&id=" + id,
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                $('#modal-menu #id').val(response.data.id);
                $('#modal-menu #name').val(response.data.name);
                $('#modal-menu #url').val(response.data.url);
                $('#modal-menu').modal('show');
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
            $('#MenuForm').each(function() {
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

var DeleteMenu = function(id, table) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_menu?action=delete&id=" + id,
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

var ClearFormMenu = function(type) {
    $('#MenuForm')[0].reset();
    $('#MenuForm').parsley().reset();
    if (type == "add") {
        $('#modal-menu #ttlModal').html('Add menu');
        $('#modal-menu #action').val('insert');
        $('#MenuForm').each(function() {
            $(this).data('serialized', $(this).serialize())
        });
    } else {
        $('#modal-menu #ttlModal').html('Edit menu');
        $('#modal-menu #action').val('update');
    }
    $('#modal-menu').find('button.btn-primary').prop('disabled', true);
};

var changeUrl = function(elementConvert, elementValue) {
    elementConvert.keyup(function() {
        var val = $(this).val();
        var url = string_to_slug(val);
        elementValue.val(url);
    });
}

var string_to_slug = function(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();
    str = xoa_dau(str); // remove accents, swap ñ for n, etc
    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

    return str;
}

var xoa_dau = function(str) {
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    return str;
}
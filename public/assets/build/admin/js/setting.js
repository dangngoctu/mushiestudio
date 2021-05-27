$(function(){
    'use strict';
    var table_dynamic_setting = $('.table-dynamic-setting').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax": base_admin+"/admin/ajax/ajax_setting",
        "responsive": true,
        "scrollX": true,
        "pagingType": "full_numbers",
        "language": {
            searchPlaceholder: 'Search...',
            sSearch: ''
        },
		"columns": [
			{"data": "key"},
            {"data": "value"},
			{"data": "action", "searchable": false}
		],
		"columnDefs": [
            {
				targets: [0],
				class: 'text-center'
            },
            {
                orderable: false,
                targets: [1],
            },
			{
				orderable: false,
				targets: [-1],
				class: 'text-center'
			}
		]
    });

    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

    $(document).on('click', '.table-dynamic-setting .table-action-edit', function (e) {
        var id = $(this).attr('data-id');
        ClearFormSetting();
        UpdateSetting(id);
    });

    $(document).on('click', '#btnSetting', function (e) {
        e.preventDefault();
        $('#SettingForm').submit();
    });

    $("#SettingForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            SettingFormSubmit(table_dynamic_setting);
        }
    });
    $('#SettingForm').on('change input', function() {
        $('#modal-setting').find('button:button.btn-primary').prop('disabled', $(this).serialize() == $(this).data('serialized'));
    });
    $('#modal-setting').on('hidden.bs.modal', function (e) {
        $('#draw-button').trigger('click');
    });
});

var SettingFormSubmit = function(table) {
    var form_data = new FormData($('#SettingForm')[0]);
    $('#btnSetting').attr('disabled', true);
    $.ajax({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: form_data,
        cache:false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        dataType: "json",
        type: "post",
        url: base_admin+"/admin/ajax/ajax_setting",
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
                $('#modal-setting').modal('hide');
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

var UpdateSetting = function(id) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_setting?key=" + id,
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                $('#modal-setting #key').val(response.data.key);
                $('#modal-setting #value').val(response.data.value);
                
                $('#modal-setting').modal('show');
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
            $('#SettingForm').each(function() {
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

var ClearFormSetting = function() {
    $('#SettingForm')[0].reset();
    $('#SettingForm').parsley().reset();
    $('#modal-setting #ttlModal').html('Setting');
    $('#modal-setting').find('button.btn-primary').prop('disabled', true);
};
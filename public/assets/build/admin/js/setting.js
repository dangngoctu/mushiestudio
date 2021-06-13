var api_img_url;
$(function(){
    if(typeof api_img_url !== 'undefined') {
        api_img_url.reset();
        api_img_url.destroy();
    }

    fileuploader('input#url_img', 1, 10, ['jpg','jpeg','png']);
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

var fileuploader = function (element, number, size, array_type) {
    if($(element).length > 0) {
        $(element).fileuploader({
            limit: number,
            fileMaxSize: size,
            extensions: array_type,
            changeInput: ' ',
            theme: 'thumbnails',
            enableApi: true,
            addMore: true,
            thumbnails: {
                box: '<div class="fileuploader-items">' + '<ul class="fileuploader-items-list">' + '<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner"><i>+</i></div></li>' + '</ul>' + '</div>',
                item: '<li class="fileuploader-item file-has-popup">' + '<div class="fileuploader-item-inner">' + '<div class="type-holder">${extension}</div>' + '<div class="actions-holder">' + '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i></i></a>' + '</div>' + '<div class="thumbnail-holder">' + '${image}' + '<span class="fileuploader-action-popup"></span>' + '</div>' + '<div class="content-holder"><h5>${name}</h5></div>' + '<div class="progress-holder">${progressBar}</div>' + '</div>' + '</li>',
                item2: '<li class="fileuploader-item file-has-popup">' + '<div class="fileuploader-item-inner">' + '<div class="type-holder">${extension}</div>' + '<div class="actions-holder">' + '<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i></i></a>' + '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i></i></a>' + '</div>' + '<div class="thumbnail-holder">' + '${image}' + '<span class="fileuploader-action-popup"></span>' + '</div>' + '<div class="content-holder"><h5>${name}</h5></div>' + '<div class="progress-holder">${progressBar}</div>' + '</div>' + '</li>',
                startImageRenderer: true,
                canvasImage: true,
                _selectors: {
                    list: '.fileuploader-items-list',
                    item: '.fileuploader-item',
                    start: '.fileuploader-action-start',
                    retry: '.fileuploader-action-retry',
                    remove: '.fileuploader-action-remove'
                },
                onImageLoaded: function(item) {

                },
                onItemShow: function(item, listEl, parentEl, newInputEl, inputEl) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl.get(0));
                    plusInput.insertAfter(item.html)[((api.getOptions().limit && api.getAppendedFiles().length) || (api.getOptions().limit && api.getChoosedFiles().length)) >= api.getOptions().limit ? 'hide' : 'show']();
                    if (item.format == 'image') {
                        item.html.find('.fileuploader-item-icon').hide();
                    }
                },
                onItemRemove: function(html, listEl, parentEl, newInputEl, inputEl) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl.get(0));
                    html.children().animate({
                        'opacity': 0
                    }, 200, function() {
                        setTimeout(function() {
                            html.remove();
                            if (api.getFiles().length - 1 < api.getOptions().limit) {
                                plusInput.show()
                            }
                        }, 100)
                    });
                    $('#ItemForm').find('button:button').prop('disabled', $(this).serialize() == $(this).data('serialized'));
                }
            },
            dragDrop: {
                container: '.fileuploader-thumbnails-input'
            },
            editor: {
                cropper: {
                    ratio: '1:1',
                    minWidth: 128,
                    minHeight: 128,
                    showGrid: true
                }
            },
            afterRender: function(listEl, parentEl, newInputEl, inputEl) {
                var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                    api = $.fileuploader.getInstance(inputEl.get(0));
                plusInput.on('click', function() {
                    api.open()
                })
            },
            onRemove: function(item, listEl, parentEl, newInputEl, inputEl) {
                if (item.data.image_id === undefined || item.data.image_id === null) {
                    var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                        api = $.fileuploader.getInstance(inputEl);
                    if (api.getFiles().length - 1 < api.getOptions().limit) plusInput.show()
                } else {
                    type_delete = 'deletesetting';
                    $.ajax({
                        url: base_admin + "/admin/ajax/fileuploader?id=" + item.data.image_id + '&action='+type_delete,
                        type: "post",
                        success: function(response) {
                            if (response.code == '200') {
                                var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                                    api = $.fileuploader.getInstance(inputEl);
                                if (api.getFiles().length < api.getOptions().limit) plusInput.show()
                            } else {
                                Lobibox.notify("warning", {
                                    title: 'Thông báo',
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
                                title: 'Thông báo',
                                pauseDelayOnHover: true,
                                continueDelayOnInactiveTab: false,
                                icon: false,
                                sound: false,
                                msg: response.msg
                            });
                        }
                    });
                }
            }
        });
        api_img_url = $.fileuploader.getInstance(element);
    } 
}

var UpdateSetting = function(id) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_setting?key=" + id,
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                $('#modal-setting #key').val(response.data.key);
                $('#modal-setting #value').val(response.data.value);
                if(response.data.key == 'FILE'){
                    $('#modal-setting .block_img').removeClass('d-none');
                    $('#modal-setting #value').prop('readonly', true);
                    if(response.data.value != null) {
                        api_img_url.append([{
                            name: (response.data.value).substring((response.data.value).lastIndexOf('/')+1),
                            type: 'image\/jpeg',
                            file: base_admin+'/public/'+response.data.value,
                            data: {
                                url: base_admin+'/public/'+response.data.value,
                                thumbnail: base_admin+'/public/'+response.data.value,
                                image_id: response.data.id
                            }
                        }]);
                        api_img_url.updateFileList();
                    }
                } else {
                    $('#modal-setting .block_img').addClass('d-none');
                    $('#modal-setting #value').prop('readonly', false);
                }
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
    if(typeof api_img_url !== 'undefined') {
        api_img_url.reset();
        api_img_url.destroy();
    }

    fileuploader('input#url_img', 1, 10, ['jpg','jpeg','png']);
    $('#modal-setting #ttlModal').html('Setting');
    $('#modal-setting').find('button.btn-primary').prop('disabled', true);
};
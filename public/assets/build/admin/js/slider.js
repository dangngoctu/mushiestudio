var api_fileuploader_url;
$(function(){
    'use strict';
    var table_dynamic_slider = $('.table-dynamic-slider').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax": base_admin+"/admin/ajax/ajax_slider",
        "responsive": true,
        "scrollX": true,
        "pagingType": "full_numbers",
        "language": {
            searchPlaceholder: 'Search...',
            sSearch: ''
        },
		"columns": [
			{"data": "id"},
			{"data": "url"},
            {"data": "place"},
            {"data": "created"},
            {"data": "status"},
			{"data": "action", "searchable": false}
		],
		"columnDefs": [
            {
				targets: [0],
				class: 'text-center'
			},
            {
                orderable: false,
                targets: [1, 2, 3],
                class: 'text-center'
            },
			{
				orderable: false,
				targets: [-1],
				class: 'text-center'
			}
		]
    });

    $('#place_id').select2({
        allowClear: true,
        minimumResultsForSearch: Infinity
    });
    if(typeof api_fileuploader_url !== 'undefined') {
        api_fileuploader_url.reset();
        api_fileuploader_url.destroy();
    }
    fileuploader('input#logo');
    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
    $(document).on('click', '.table-dynamic-slider .table-action-delete', function (e) {
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
        DeleteSlider(id, table_dynamic_slider);
    });
    $(document).on('click', '.table-dynamic-slider .table-action-edit', function (e) {
        var id = $(this).attr('data-id');
        var lang = $(this).attr('data-lang');
        ClearFormSlider(lang, 'edit');
        UpdateSlider(id, lang);
    });
    $(document).on('click', '#addSlider', function (e) {
		e.preventDefault();
        $('#SliderForm')[0].reset();
        var lang = $(this).attr('data-lang');
        ClearFormSlider(lang, 'add');
		$('#modal-slider').modal('show');
    });
    $(document).on('click', '#btnSlider', function (e) {
        e.preventDefault();
        $('#SliderForm').submit();
    });
    $("#SliderForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            SliderFormSubmit(table_dynamic_slider);
        }
    });
    $('#SliderForm').on('change input', function() {
        $('#modal-slider').find('button:button.btn-primary').prop('disabled', $(this).serialize() == $(this).data('serialized'));
    });
    $('#modal-slider').on('hidden.bs.modal', function (e) {
        $('#draw-button').trigger('click');
    });
});

var fileuploader = function (element) {
    if($(element).length > 0) {
        $(element).fileuploader({
            limit: 1,
            fileMaxSize: 10,
            extensions: ['jpg', 'jpeg', 'png'],
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
                    $('#SettingForm').find('button:button').prop('disabled', $(this).serialize() == $(this).data('serialized'));
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
            }
        });
        api_fileuploader_url = $.fileuploader.getInstance('input#logo');
        // api_fileuploader_cover = $.fileuploader.getInstance('input#img_cover');
        // api_fileuploader_background = $.fileuploader.getInstance('input#img_background');
    } 
}

var SliderFormSubmit = function(table) {
    var form_data = new FormData($('#SliderForm')[0]);
    var fileListLogo = Object.keys(api_fileuploader_url.getFileList()).length;
    form_data.append('fileListLogo', fileListLogo);
    $('#btnSlider').attr('disabled', true);
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
        url: base_admin+"/admin/ajax/ajax_slider",
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
                // $('#btnGroupCategory').button('reset');
                $('#modal-slider').modal('hide');
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

var UpdateSlider = function(id, lang) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_slider?lang=" + lang + "&id=" + id,
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                $('#modal-slider #id').val(response.data.id);
                $('#modal-slider #place_id').val(response.data.place).trigger('change.select2');
                if(response.data.url != null) {
                    api_fileuploader_url.append([{
                        name: (response.data.url).substring((response.data.url).lastIndexOf('/')+1),
                        type: 'image/png',
                        file: base_admin+'/'+response.data.url,
                        data: {
                            url: base_admin+'/'+response.data.url,
                            thumbnail: base_admin+'/'+response.data.url
                        }
                    }]);
                    api_fileuploader_url.updateFileList();
                }	
                if(response.data.status == 1) {
                    $('#modal-slider #status').prop( "checked", true );
                } else {
                    $('#modal-slider #status').prop( "checked", false );
                }
                $('#modal-slider').modal('show');
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
            $('#SliderForm').each(function() {
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

var DeleteSlider = function(id, table) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_slider?action=delete&id=" + id,
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

var ClearFormSlider = function(lang, type) {
    $('#SliderForm')[0].reset();
    $('#SliderForm').parsley().reset();
    $('#modal-slider #lang').val(lang);
    $('#modal-slider #place_id').val('').trigger('change.select2');
    if(typeof api_fileuploader_url !== 'undefined') {
        api_fileuploader_url.reset();
        api_fileuploader_url.destroy();
    }
    fileuploader('input#logo');
    if (type == "add") {
        $('#modal-slider #ttlModal').html('Thêm slider');
        $('#modal-slider #action').val('insert');
        $('#SliderForm').each(function() {
            $(this).data('serialized', $(this).serialize())
        });
    } else {
        $('#modal-slider #ttlModal').html('Cập nhật slider');
        $('#modal-slider #action').val('update');
    }
    $('#modal-slider').find('button.btn-primary').prop('disabled', true);
};
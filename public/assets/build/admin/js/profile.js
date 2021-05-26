var api_fileuploader_url;
$(function(){
    'use strict';
    var table_dynamic_profile = $('.table-dynamic-profile').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax": base_admin+"/admin/ajax/ajax_profile",
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
            {"data": "job"},
            {"data": "url_image"},
            {"data": "url_facebook"},
            {"data": "url_landing"},
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
                targets: [1, 2, 3, 4, 5, 6],
                class: 'text-center'
            },
			{
				orderable: false,
				targets: [-1],
				class: 'text-center'
			}
		]
    });

    $('#sex').select2({
        allowClear: true,
        minimumResultsForSearch: Infinity
    });
    if(typeof api_fileuploader_url !== 'undefined') {
        api_fileuploader_url.reset();
        api_fileuploader_url.destroy();
    }
    fileuploader('input#logo');
    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
    $(document).on('click', '.table-dynamic-profile .table-action-delete', function (e) {
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
        DeleteProfile(id, table_dynamic_profile);
    });
    $(document).on('click', '.table-dynamic-profile .table-action-edit', function (e) {
        var id = $(this).attr('data-id');
        var lang = $(this).attr('data-lang');
        ClearFormProfile(lang, 'edit');
        UpdateProfile(id, lang);
    });
    $(document).on('click', '#addProfile', function (e) {
		e.preventDefault();
        $('#ProfileForm')[0].reset();
        var lang = $(this).attr('data-lang');
        ClearFormProfile(lang, 'add');
		$('#modal-profile').modal('show');
    });
    $(document).on('click', '#btnProfile', function (e) {
        e.preventDefault();
        $('#ProfileForm').submit();
    });
    $("#ProfileForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            ProfileFormSubmit(table_dynamic_profile);
        }
    });
    $('#ProfileForm').on('change input', function() {
        $('#modal-profile').find('button:button.btn-primary').prop('disabled', $(this).serialize() == $(this).data('serialized'));
    });
    $('#modal-profile').on('hidden.bs.modal', function (e) {
        $('#draw-button').trigger('click');
    });
});

var fileuploader = function (element) {
    if($(element).length > 0) {
        $(element).fileuploader({
            limit: 1,
            fileMaxSize: 3,
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
    } 
}

var ProfileFormSubmit = function(table) {
    var form_data = new FormData($('#ProfileForm')[0]);
    var fileListLogo = Object.keys(api_fileuploader_url.getFileList()).length;
    form_data.append('fileListLogo', fileListLogo);
    $('#btnProfile').attr('disabled', true);
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
        url: base_admin+"/admin/ajax/ajax_profile",
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
                $('#modal-profile').modal('hide');
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

var UpdateProfile = function(id, lang) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_profile?lang=" + lang + "&id=" + id,
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                $('#modal-profile #id').val(response.data.id);
                $('#modal-profile #name').val(response.data.name);
                $('#modal-profile #job').val(response.data.job);
                $('#modal-profile #url_facebook').val(response.data.url_facebook);
                $('#modal-profile #url_landing').val(response.data.url_landing);
                $('#modal-profile #sex').val(response.data.sex).trigger('change.select2');
                if(response.data.url_image != null && response.data.url_image != '') {
                    api_fileuploader_url.append([{
                        name: (response.data.url_image).substring((response.data.url_image).lastIndexOf('/')+1),
                        type: 'image/png',
                        file: base_admin+'/'+response.data.url_image,
                        data: {
                            url: base_admin+'/'+response.data.url_image,
                            thumbnail: base_admin+'/'+response.data.url_image
                        }
                    }]);
                    api_fileuploader_url.updateFileList();
                }	
                if(response.data.status == 1) {
                    $('#modal-profile #status').prop( "checked", true );
                } else {
                    $('#modal-profile #status').prop( "checked", false );
                }
                $('#modal-profile').modal('show');
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
            $('#ProfileForm').each(function() {
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

var DeleteProfile = function(id, table) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_profile?action=delete&id=" + id,
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

var ClearFormProfile = function(lang, type) {
    $('#ProfileForm')[0].reset();
    $('#ProfileForm').parsley().reset();
    $('#modal-profile #lang').val(lang);
    $('#modal-profile #sex').val('').trigger('change.select2');
    if(typeof api_fileuploader_url !== 'undefined') {
        api_fileuploader_url.reset();
        api_fileuploader_url.destroy();
    }
    fileuploader('input#logo');
    if (type == "add") {
        $('#modal-profile #ttlModal').html('Add Image Profile');
        $('#modal-profile #action').val('insert');
        $('#ProfileForm').each(function() {
            $(this).data('serialized', $(this).serialize())
        });
    } else {
        $('#modal-profile #ttlModal').html('Update Image Profile');
        $('#modal-profile #action').val('update');
    }
    $('#modal-profile').find('button.btn-primary').prop('disabled', true);
};
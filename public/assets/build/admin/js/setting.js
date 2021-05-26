var api_fileuploader_cover, api_fileuploader_background, api_fileuploader_logo;

$(function(){
    'use strict';
    UpdateSetting();
    $(document).on('click', '#btnSetting', function (e) {
        e.preventDefault();
        $('#SettingForm').submit();
    });
    $("#SettingForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            SettingFormSubmit()
        }
    });
    if(typeof api_fileuploader_logo !== 'undefined') {
        api_fileuploader_logo.reset();
        api_fileuploader_logo.destroy();
    }
    fileuploader('input#logo');
    // if(typeof api_fileuploader_cover !== 'undefined') {
    //     api_fileuploader_cover.reset();
    //     api_fileuploader_cover.destroy();
    // }
    // fileuploader('input#img_cover');
    // if(typeof api_fileuploader_background !== 'undefined') {
    //     api_fileuploader_background.reset();
    //     api_fileuploader_background.destroy();
    // }
    // fileuploader('input#img_background');
    $('#SettingForm').on('change input', function() {
        $(this).find('button:button').prop('disabled', $(this).serialize() == $(this).data('serialized'));
    }).find('button:button').prop('disabled', true);
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
        api_fileuploader_logo = $.fileuploader.getInstance('input#logo');
        // api_fileuploader_cover = $.fileuploader.getInstance('input#img_cover');
        // api_fileuploader_background = $.fileuploader.getInstance('input#img_background');
    } 
}

var UpdateSetting = function(){
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_setting",
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                $('#id').val(response.data.id);
                $('#slogan').val(response.data.slogan);
                $('#fanpage').val(response.data.fanpage);
                $('#address').val(response.data.address);
                $('#name').val(response.data.name);
                $('#phone').val(response.data.phone);
                if(response.data.url != null) {
                    api_fileuploader_logo.append([{
                        name: (response.data.url).substring((response.data.url).lastIndexOf('/')+1),
                        type: 'image/png',
                        file: base_admin+'/'+response.data.url,
                        data: {
                            url: base_admin+'/'+response.data.url,
                            thumbnail: base_admin+'/'+response.data.url
                        }
                    }]);
                    api_fileuploader_logo.updateFileList();
                }	
               
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
            $('#SettingForm').each(function() {
                $(this).data('serialized', $(this).serialize())
            });
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
};

var SettingFormSubmit = function(){
    var form_data = new FormData($('#SettingForm')[0]);
    var fileListLogo = Object.keys(api_fileuploader_logo.getFileList()).length;
    form_data.append('fileListLogo', fileListLogo);
    // var fileListCover = Object.keys(api_fileuploader_cover.getFileList()).length;
    // form_data.append('fileListCover', fileListCover);
    // var fileListBackground = Object.keys(api_fileuploader_background.getFileList()).length;
    // form_data.append('fileListBackground', fileListBackground);
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
                $('#btnSetting').attr('disabled', true);
                // table.ajax.reload(null, true);
            } else {
                Lobibox.notify("warning", {
                    title: 'Notification',
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    icon: false,
                    sound: false,
                    msg: response.msg
                });
                $('#btnSetting').attr('disabled', true);
            }
            // $('#btnNews').button('reset');
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
            $('#btnSetting').attr('disabled', true);
            // $('#btnNews').button('reset');
        }
    });
};



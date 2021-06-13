var api_img_url;
var type_delete;
$(function(){
    'use strict';
    changeUrl($('#name'), $('#url'));
    changeUrl($('#url'), $('#url'));

    if(typeof api_img_url !== 'undefined') {
        api_img_url.reset();
        api_img_url.destroy();
    }

    fileuploader('input#url_img', 1, 10, ['jpg','jpeg','png']);

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
    var form_data = new FormData($('#MenuForm')[0]);
    $('#btnItem').attr('disabled', true);
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
        url: base_admin+"/admin/ajax/ajax_menu",
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
                if(response.data.url_img != null) {
                    api_img_url.append([{
                        name: (response.data.url_img).substring((response.data.url_img).lastIndexOf('/')+1),
                        type: 'image\/jpeg',
                        file: base_admin+'/public/'+response.data.url_img,
                        data: {
                            url: base_admin+'/public/'+response.data.url_img,
                            thumbnail: base_admin+'/public/'+response.data.url_img,
                            image_id: response.data.id
                        }
                    }]);
                    api_img_url.updateFileList();
                }
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
    if(typeof api_img_url !== 'undefined') {
        api_img_url.reset();
        api_img_url.destroy();
    }

    fileuploader('input#url_img', 1, 10, ['jpg','jpeg','png']);
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
                    type_delete = 'deletemenu';
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
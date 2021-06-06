var api_fileimg_url;
var api_fileuploader_url;
$(function(){
    'use strict';
    changeUrl($('#name'), $('#url'));
    changeUrl($('#url'), $('#url'));
    if(typeof api_fileimg_url !== 'undefined') {
        api_fileimg_url.reset();
        api_fileimg_url.destroy();
    }
    if(typeof api_fileuploader_url !== 'undefined') {
        api_fileuploader_url.reset();
        api_fileuploader_url.destroy();
    }
    fileuploader1('input#img', 20, 10, ['jpg','jpeg','png'], 1);
    fileuploader1('input#img_item', 1, 10, ['jpg','jpeg','png'], 2);
    $('#menu_id, #type').select2({
        allowClear: true,
        minimumResultsForSearch: Infinity
    });

    $("#type").on("change", function($e) {
        if($(this).val() == 1){
            $('.block_album').addClass('d-none');
            $('.block_item').removeClass('d-none');
        } else {
            $('.block_album').removeClass('d-none');
            $('.block_item').addClass('d-none');
        }
    });
    var table_dynamic_category = $('.table-dynamic-category').DataTable({
		"processing": true,
        "serverSide": true,
        "ajax": base_admin+"/admin/ajax/ajax_category",
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
            {"data": "menu_id"},
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

    tinymce.init({
        selector: '.mytextarea',
        plugins: 'print preview fullpage paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
        imagetools_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        autosave_ask_before_unload: true,
        autosave_interval: "30s",
        autosave_prefix: "{path}{query}-{id}-",
        autosave_restore_when_empty: false,
        autosave_retention: "2m",
        image_advtab: true,
        content_css: [
          '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
          '//www.tiny.cloud/css/codepen.min.css'
        ],
        link_list: [
          { title: 'My page 1', value: 'http://www.tinymce.com' },
          { title: 'My page 2', value: 'http://www.moxiecode.com' }
        ],
        image_list: [
          { title: 'My page 1', value: 'http://www.tinymce.com' },
          { title: 'My page 2', value: 'http://www.moxiecode.com' }
        ],
        image_class_list: [
          { title: 'None', value: '' },
          { title: 'Some class', value: 'class-name' }
        ],
        importcss_append: true,
        height: 400,
        file_picker_callback: function (callback, value, meta) {
          /* Provide file and text for the link dialog */
          if (meta.filetype === 'file') {
            callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
          }
      
          /* Provide image and alt text for the image dialog */
          if (meta.filetype === 'image') {
            callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
          }
      
          /* Provide alternative source and posted for the media dialog */
          if (meta.filetype === 'media') {
            callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
          }
        },
        templates: [
              { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
          { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
          { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
        ],
        template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
        template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
        height: 450,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: "mceNonEditable",
        toolbar_drawer: 'sliding',
        contextmenu: "link image imagetools table",
    });

    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
    $(document).on('click', '.table-dynamic-category .table-action-delete', function (e) {
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
        DeleteCategory(id, table_dynamic_category);
    });
    $(document).on('click', '.table-dynamic-category .table-action-edit', function (e) {
        var id = $(this).attr('data-id');
        ClearFormCategory('edit');
        UpdateCategory(id);
    });
    $(document).on('click', '#addCategory', function (e) {
		e.preventDefault();
        $('#CategoryForm')[0].reset();
        ClearFormCategory('add');
		$('#modal-category').modal('show');
    });
    $(document).on('click', '#btnCategory', function (e) {
        e.preventDefault();
        $('#description_save').val(tinyMCE.get('description').getContent());
        $('#CategoryForm').submit();
    });
    $("#CategoryForm").on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()){
            CategoryFormSubmit(table_dynamic_category);
        }
    });
    $('#CategoryForm').on('change input', function() {
        $('#modal-category').find('button:button.btn-primary').prop('disabled', $(this).serialize() == $(this).data('serialized'));
    });
    $('#modal-category').on('hidden.bs.modal', function (e) {
        $('#draw-button').trigger('click');
    });
});

var fileuploader1 = function (element, number, size, array_type, type) {
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
                    $('#CategoryForm').find('button:button').prop('disabled', $(this).serialize() == $(this).data('serialized'));
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
                    if(type == 1){
                        type_delete = 'deletecategory';
                    } else {
                        type_delete = 'deletecategoryitem';
                    }
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
        if(type == 1){
            api_fileimg_url = $.fileuploader.getInstance(element);
        } else {
            api_fileuploader_url =  $.fileuploader.getInstance(element);
        }
        
    } 
}

var CategoryFormSubmit = function(table) {
    var form_data = new FormData($('#CategoryForm')[0]);
    $('#btnCategory').attr('disabled', true);
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
        url: base_admin+"/admin/ajax/ajax_category",
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
                $('#modal-category').modal('hide');
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

var UpdateCategory = function(id) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_category?&id=" + id,
        type: "get",
        success: function(response) {
            if (response.code == '200') {
                var dataID = [];
                var dataImg = [];
                $('#modal-category #id').val(response.data.id);
                $('#modal-category #name').val(response.data.name);
                $('#modal-category #url').val(response.data.url);
                $('#CategoryForm #type').val(response.data.type).trigger('change.select2');
                $('#CategoryForm #menu_id').val(response.data.menu_id).trigger('change.select2');
                $('#CategoryForm #video').val(response.data.video);
                tinyMCE.get('description').setContent(response.data.description);
                if(response.data.img != null) {
                    api_fileuploader_url.append([{
                        name: (response.data.img).substring((response.data.img).lastIndexOf('/')+1),
                        type: 'image\/jpeg',
                        file: base_admin+'/'+response.data.img,
                        data: {
                            url: base_admin+'/'+response.data.img,
                            thumbnail: base_admin+'/'+response.data.img,
                            image_id: response.data.id
                        }
                    }]);
                    api_fileuploader_url.updateFileList();
                }
                $.each(response.data.category_images, function(i, item) {
                    dataImg.push(item.url);
                    dataID.push(item.id);
                });
                $.each(dataImg, function(i, item) {
                    api_fileimg_url.append([{
                        name: item,
                        type: 'image\/jpeg',
                        size: 71135,
                        file: base_admin +'/'+ item,
                        data: {
                            url: base_admin +'/'+ item,
                            thumbnail: base_admin +'/'+ item,
                            image_id: dataID[i]
                        }
                    }]);
                });	
                if(response.data.type == 1){
                    $('.block_album').addClass('d-none');
                    $('.block_item').removeClass('d-none');
                } else {
                    $('.block_album').removeClass('d-none');
                    $('.block_item').addClass('d-none');
                }
                $('#modal-category').modal('show');
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
            $('#CategoryForm').each(function() {
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

var DeleteCategory = function(id, table) {
    $.ajax({
        url: base_admin + "/admin/ajax/ajax_category?action=delete&id=" + id,
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

var ClearFormCategory = function(type) {
    $('#CategoryForm')[0].reset();
    $('#CategoryForm').parsley().reset();
    $('#CategoryForm #type').val([]).trigger('change.select2');
    $('#CategoryForm #menu_id').val([]).trigger('change.select2');
    $('.block_album').addClass('d-none');
    $('.block_item').addClass('d-none');
    if(typeof api_fileimg_url !== 'undefined') {
        api_fileimg_url.reset();
        api_fileimg_url.destroy();
    }
    fileuploader1('input#img', 20, 10, ['jpg','jpeg','png'], 1);

    if(typeof api_fileuploader_url !== 'undefined') {
        api_fileuploader_url.reset();
        api_fileuploader_url.destroy();
    }
    fileuploader1('input#img_item', 1, 10, ['jpg','jpeg','png'], 2);
    
    if (type == "add") {
        $('#modal-category #ttlModal').html('Add category');
        $('#modal-category #action').val('insert');
        $('#CategoryForm').each(function() {
            $(this).data('serialized', $(this).serialize())
        });
    } else {
        $('#modal-category #ttlModal').html('Edit category');
        $('#modal-category #action').val('update');
    }
    $('#modal-category').find('button.btn-primary').prop('disabled', true);
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
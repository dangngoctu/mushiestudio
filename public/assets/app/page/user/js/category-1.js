var slider = [];
var element;
var is_small = false;
var sort_material,sort_size,sort_type;
$(function(){
    $('.content-slider').each(function(key,index){
        ld = $(index).lightSlider({
            item:1,
            thumbItem:9,
            slideMargin: 0,
            keyPress:true,
        });
        slider.push(ld);
    }) 
    $(document).on('click','.view-small',function(e){
        e.preventDefault();        
        is_small = true;
        $('.product-list .category-item').attr('class','col-6 col-sm-6 col-md-3 col-lg-3 category-item col-small');
        slider.forEach(function(i,k){
            i.refresh();
        })
    })

    $(document).on('click','.view-large',function(e){
        e.preventDefault();
        is_small = false;
        $('.product-list .category-item').attr('class','col-6 col-sm-6 col-md-4 col-lg-4 category-item col-large');
        slider.forEach(function(i,k){
            i.refresh();
        })
    })

    $(document).on('change','#sort_material',function(e){
        sort_material = $(this).val();
        showItemSort();
    })

    $(document).on('change','#sort_size',function(e){
        sort_size = $(this).val();
        showItemSort();
    })

    $(document).on('change','#sort_type',function(e){
        sort_type = $(this).val();
        showItemSort();
    })
})

var showItemSort = function(){
    var instance = $('.category-item').scheletrone({
        debug        : {
            log: false,
            latency: 2000
        },
    });
    var category_id = $('#category_id').val();
    var url = base_url+'/ajax/products/sort?id='+category_id;

    if(sort_material != '' && sort_material !== undefined){
        url += "&material=" + sort_material;
    }
    if(sort_size != '' && sort_size !== undefined){
        url += "&size=" + sort_size;
    }
    if(sort_type != '' && sort_type !== undefined){
        url += "&type=" + sort_type;
    }
    var html = '';
    $.ajax({
        url: url,
        type: "GET",
        success: function(response) {
            response.forEach(function(value,key){
                if(is_small){
                    html += '<div class="col-6 col-sm-6 col-md-3 col-lg-3 category-item col-small">';
                }else{
                    html += '<div class="col-6 col-sm-6 col-md-4 col-lg-4 category-item col-large">';
                }
                html += '<p>\
                        <ul class="content-slider">\
                    ';
                var img_items = value.item_images;
                img_items.forEach(function(v){
                    html +=  '<li>\
                        <a href="'+base_url+'/product/'+value.category.url+'/'+ value.slug+'">\
                            <img data-src="'+base_url+'/public/'+v.url+'" src="'+base_url+'/public/'+v.url+'" alt="'+value.name+'" class="blur-up main-img-seaction ls-is-cached lazyloaded">\
                        </a>\
                     </li>';
                });
            
                        
                html +=  '</ul>\
                    </p>\
                    <h3 class="h4"><a href="'+base_url+'/product/'+value.category.url+'/'+ value.slug+'">'+value.name+'</a></h3>\
                    <div class="rte-setting"><p><a href="">'+value.sub_name+'</a></p></div>\
                    <div class="product-price">';
                
                if(value.price_setting == 1){
                    html += '<span class="price">' +value.price + '</span>';
                }
                        
                html += '</div>\
                </div>';
            })
            $('.product-list').html(html);
            $('.content-slider').each(function(key,index){
                ld = $(index).lightSlider({
                    item:1,
                    thumbItem:9,
                    slideMargin: 0,
                    keyPress:true,
                });
                slider.push(ld);
            }) 
          
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
}
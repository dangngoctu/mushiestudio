lightGallery(document.getElementById('lightgallery'), {
    zoom : true,
    download: false,
    fullScreen: true
});
$(function(){
  
    $('.content-slider').lightSlider({
        item:1,
        thumbItem:9,
        slideMargin: 0,
        keyPress:true,
    });

    var link = $('.related-product');
    var bottom = ($('body').height() - link.position().top);

    $('.info-item .sticky').sticky({
        topSpacing: 50,
        bottomSpacing:bottom,
        className: 'info-item-pd'
    });


    $(document).on('click','.tabs-listing ul li',function(){
        $('.info-item .sticky').sticky('update');
    })
})

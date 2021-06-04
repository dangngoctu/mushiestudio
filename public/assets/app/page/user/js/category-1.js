var slider = [];
var element;
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
        $('.product-list .category-item').attr('class','col-6 col-sm-6 col-md-3 col-lg-3 category-item col-small');
        slider.forEach(function(i,k){
            i.refresh();
        })
    })

    $(document).on('click','.view-large',function(e){
        e.preventDefault();
        $('.product-list .category-item').attr('class','col-6 col-sm-6 col-md-4 col-lg-4 category-item col-large');
        slider.forEach(function(i,k){
            i.refresh();
        })
    })
})
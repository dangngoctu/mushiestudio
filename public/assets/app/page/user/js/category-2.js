
var gallery = $('.light_item');
gallery.each(function(k,v){
    lightGallery(document.getElementById($(v).attr('id')), {
        zoom : true,
        download: false,
        fullScreen: true
    });
})

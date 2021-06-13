<!-- Including Jquery -->
<script src="{{asset('public/assets/app/page/user/js/vendor/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('public/assets/app/page/user/js/vendor/modernizr-3.6.0.min.js')}}"></script>
<script src="{{asset('public/assets/app/page/user/js/vendor/jquery.cookie.js')}}"></script>
<script src="{{asset('public/assets/app/page/user/js/vendor/wow.min.js')}}"></script>
<!-- Including Javascript -->
<script src="{{asset('public/assets/app/page/user/js/bootstrap.min.js')}}"></script>
<script src="{{asset('public/assets/app/page/user/js/plugins.js')}}"></script>
<script src="{{asset('public/assets/app/page/user/js/popper.min.js')}}"></script>
<script src="{{asset('public/assets/app/page/user/js/lazysizes.js')}}"></script>
<script src="{{asset('public/assets/app/page/user/js/main.js')}}"></script>
<!--For Newsletter Popup-->
<script>

    /*--------------------------------------
        Promotion / Notification Cookie Bar 
        -------------------------------------- */
        if(Cookies.get('promotion') != 'true') {   
            $(".notification-bar").show();         
        }
        $(".close-announcement").on('click',function() {
        $(".notification-bar").slideUp();  
        Cookies.set('promotion', 'true', { expires: 1});  
        return false;
        });
</script>
    <!--End For Newsletter Popup-->
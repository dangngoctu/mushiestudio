<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
<!-- Favicon -->
<link rel="shortcut icon" href="{{asset('public/assets/images/favicon.ico')}}" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>@yield('title')</title>
<meta name="description" content="description">
<meta name="viewport" content="width=device-width, initial-scale=1">
@include('Layout.Client.main_css')
</head>


<body class="template-index home14-bags">
    <div id="pre-loader">
        <img src="{{asset('public/assets/app/page/user/images/loader.gif')}}" alt="Loading..." />
    </div>
    <div class="pageWrapper">
      
        @include('Layout.Client.header')
        <!--Body Content-->
        <div id="page-content">
            @yield('content')
        </div>
        <!--End Body Content-->
        <!--Footer-->
        @include('Layout.Client.footer')
        
    </div>
</body>
@include('Layout.Client.main_js')
@yield('js')
<!-- belle/home14-bags.html   11 Nov 2019 12:37:31 GMT -->
</html>
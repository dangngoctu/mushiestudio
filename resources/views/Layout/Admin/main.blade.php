<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{asset('/img/images_app/favico.png')}}">
    <title>@yield('page_title')</title>
   @include('Layout.Admin.main_css')
  </head>
  <body class="slim-sticky-header">
    @include('Layout.Admin.header')
    @include('Layout.Admin.navbar')
    <div class="slim-mainpanel">
      <div class="container">
        @yield('page_header')
        @yield('page_content')
      </div><!-- container -->
    </div><!-- slim-mainpanel -->
    @include('Layout.Admin.main_js')
    @include('Web.Admin.Page.changePassword')  
  </body>
</html>

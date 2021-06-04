@extends('Layout.Client.main')
@section('title','Mushie Studio')
@section('css')
    <link rel="stylesheet" href="{{asset('/assets/app/page/user/css/lightslider.min.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/app/page/user/css/lightgallery.min.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/app/page/user/css/detail.css')}}">
@endsection
@section('content')
    @include('Web.Client.product-detail.index')
@endsection

@section('js')
    <script src="{{asset('/assets/app/page/user/js/jquery.sticky.js')}}"></script>
    <script src="{{asset('/assets/app/page/user/js/lightgallery.min.js')}}"></script>
    <script src="{{asset('/assets/app/page/user/js/lg-fullscreen.min.js')}}"></script>
    <script src="{{asset('/assets/app/page/user/js/lg-zoom.min.js')}}"></script>
    <script src="{{asset('/assets/app/page/user/js/lightslider.min.js')}}"></script>
    <script src="{{asset('/assets/app/page/user/js/detail.js')}}"></script>
@endsection
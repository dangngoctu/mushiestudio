@extends('Layout.Client.main')
@section('title','Mushie Studio')
@section('css')
    <link rel="stylesheet" href="{{asset('/assets/app/page/user/css/lightgallery.min.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/app/page/user/css/category-2.css')}}">
@endsection
@section('content')
    @include('Web.Client.category-2.index')
@endsection

@section('js')
    <script src="{{asset('/assets/app/page/user/js/lightgallery.min.js')}}"></script>
    <script src="{{asset('/assets/app/page/user/js/lg-fullscreen.min.js')}}"></script>
    <script src="{{asset('/assets/app/page/user/js/lg-zoom.min.js')}}"></script>
    <script src="{{asset('/assets/app/page/user/js/category-2.js')}}"></script>
@endsection
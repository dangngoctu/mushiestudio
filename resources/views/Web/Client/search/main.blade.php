@extends('Layout.Client.main')
@section('title',$title ??  'Search')
@section('css')
    <link rel="stylesheet" href="{{asset('public/assets/app/page/user/css/lightslider.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/app/page/user/css/category-1.css')}}">
@endsection
@section('content')
    @include('Web.Client.search.index')
@endsection

@section('js')
    <script src="{{asset('public/assets/app/page/user/js/lightslider.min.js')}}"></script>
    <script src="{{asset('public/assets/app/page/user/js/category-1.js')}}"></script>
@endsection
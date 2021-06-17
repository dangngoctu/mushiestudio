@extends('Layout.Client.main')
@section('title',$title ??  'Mushie Studio')
@section('css')
    <link rel="stylesheet" href="{{asset('public/assets/app/page/user/css/lightslider.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/app/page/user/css/jquery.skeleton.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/app/page/user/css/category-1.css')}}">
@endsection
@section('content')
    @include('Web.Client.category-1.index')
@endsection

@section('js')
    <script src="{{asset('public/assets/app/page/user/js/lightslider.min.js')}}"></script>
    <script src="{{asset('public/assets/app/page/user/js/jquery.scheletrone.js')}}"></script>
    <script src="{{asset('public/assets/app/page/user/js/category-1.js')}}"></script>
@endsection
@extends('Layout.Client.main')
@section('title',$title ?? 'About Us')
@section('css')
    <link rel="stylesheet" href="{{asset('/assets/app/page/user/css/about.css')}}">
@endsection
@section('content')
    @include('Web.Client.about-us.index')
@endsection

@section('js')
    <script src="{{asset('/assets/app/page/user/js/about-us.js')}}"></script>
@endsection
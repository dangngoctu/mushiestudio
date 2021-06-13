@extends('Layout.Client.main')
@section('title',$title ??  'Home')
@section('css')
    <link rel="stylesheet" href="{{asset('public/assets/app/page/user/css/home.css')}}">
@endsection
@section('content')
    @include('Web.Client.home.index')
@endsection
@extends('Layout.Client.main')
@section('title','Mushie Studio')
@section('css')
    <link rel="stylesheet" href="{{asset('public/assets/app/page/user/css/home.css')}}">  
    <style>
        .empty-page-content h1{
            font-size: 55px;
            font-weight: 600;
        } 
        .empty-page-content {
            margin: 50px 0;
        } 
        
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">	
                <div class="empty-page-content text-center">
                    <h1>404 Page Not Found</h1>
                    <p>The page you requested does not exist.</p>
                    <p><a href="/" class="btn btn--has-icon-after">Continue shopping <i class="fa fa-caret-right" aria-hidden="true"></i></a></p>
                    </div>
            </div>
        </div>
    </div>
@endsection
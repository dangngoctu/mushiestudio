@extends('Layout.Admin.main_blank')
@section('page_title')
  Login
@endsection
@section('page_content')
    <div class="signin-wrapper">
        <div class="signin-box">
        <form id="loginForm">
                <h2 class="slim-logo text-center"><img src="{{asset('public/img/web/logo.png')}}" width="80%" alt="logo"></h2>
                <div class="form-group">
                    <input type="text" class="form-control username" placeholder="Please enter your account" required
                    data-parsley-required-message="Account is required.">
                </div><!-- form-group -->
                <div class="form-group mg-b-50">
                    <input type="password" class="form-control password" placeholder="Please enter your password" required
                    data-parsley-required-message="Password is required."
                    data-parsley-minlength="4" data-parsley-minlength-message="Password at least 4 characters.">
                </div><!-- form-group -->
                <button class="btn btn-primary btn-block btn-signin">Sign In</button>
            </form>
        </div><!-- signin-box -->
    </div><!-- signin-wrapper -->
@endsection

@section('js')
<script src="{{asset('public/assets/build/admin/js/login.js')}}"></script>
@endsection
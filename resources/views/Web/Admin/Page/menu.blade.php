@extends('Layout.Admin.main')

@section('page_title')
  Menu
@endsection


@section('page_header')
    <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">HomePage</a></li>
            <li class="breadcrumb-item active" aria-current="page">Menu</li>
        </ol>
        <h6 class="slim-pagetitle">Menu</h6>
    </div><!-- slim-pageheader -->
@endsection

@section('page_content')
    <div class="section-wrapper">
        <div class="text-right">
            <span class="btn btn-primary btn-icon mg-l-5" id="addMenu">
                <div>
                    <i class="fa fa-plus"></i>
                </div>
            </span>
        </div>
        @include('Web.Admin.Section.menu.table_menu')
        @include('Web.Admin.Section.menu.modal_menu')
    </div><!-- section-wrapper -->
    @include('Layout.Admin.modal_confirm_delete')
@endsection
@section('js')
<script src="{{asset('public/assets/build/admin/js/menu.js')}}"></script>
@endsection

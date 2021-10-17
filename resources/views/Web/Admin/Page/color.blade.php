@extends('Layout.Admin.main')

@section('page_title')
  Color
@endsection
@section('css')
    <link href="http://themepixels.me/demo/slim1.1/lib/spectrum/css/spectrum.css" rel="stylesheet">
    <style>
        .sp-container{
            z-index: 999999 !important;
        }
    </style>
@endsection


@section('page_header')
    <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">HomePage</a></li>
            <li class="breadcrumb-item active" aria-current="page">Color</li>
        </ol>
        <h6 class="slim-pagetitle">Color</h6>
    </div><!-- slim-pageheader -->
@endsection

@section('page_content')
    <div class="section-wrapper">
        <div class="text-right">
            <span class="btn btn-primary btn-icon mg-l-5" id="addColor">
                <div>
                    <i class="fa fa-plus"></i>
                </div>
            </span>
        </div>
        @include('Web.Admin.Section.color.table_color')
        @include('Web.Admin.Section.color.modal_color')
    </div><!-- section-wrapper -->
    @include('Layout.Admin.modal_confirm_delete')
@endsection
@section('js')
<script defer src="http://themepixels.me/demo/slim1.1/lib/spectrum/js/spectrum.js"></script>
<script src="{{asset('public/assets/build/admin/js/color.js')}}"></script>
@endsection

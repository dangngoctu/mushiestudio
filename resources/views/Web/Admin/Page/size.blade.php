@extends('Layout.Admin.main')

@section('page_title')
  Size
@endsection


@section('page_header')
    <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">HomePage</a></li>
            <li class="breadcrumb-item active" aria-current="page">Size</li>
        </ol>
        <h6 class="slim-pagetitle">Size</h6>
    </div><!-- slim-pageheader -->
@endsection

@section('page_content')
    <div class="section-wrapper">
        <div class="text-right">
            <span class="btn btn-primary btn-icon mg-l-5" id="addSize">
                <div>
                    <i class="fa fa-plus"></i>
                </div>
            </span>
        </div>
        @include('Web.Admin.Section.size.table_size')
        @include('Web.Admin.Section.size.modal_size')
    </div><!-- section-wrapper -->
    @include('Layout.Admin.modal_confirm_delete')
@endsection
@section('js')
<script src="{{asset('public/assets/build/admin/js/size.js')}}"></script>
@endsection

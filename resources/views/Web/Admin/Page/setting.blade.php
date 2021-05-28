@extends('Layout.Admin.main')

@section('page_title')
  Setting
@endsection

@section('page_header')
    <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">HomePage</a></li>
            <li class="breadcrumb-item active" aria-current="page">Setting</li>
        </ol>
        <h6 class="slim-pagetitle">Thiết lập</h6>
    </div><!-- slim-pageheader -->
@endsection

@section('page_content')
    <div class="section-wrapper">
        @include('Web.Admin.Section.setting.table_setting')
        @include('Web.Admin.Section.setting.modal_setting')
    </div><!-- section-wrapper -->
    @include('Layout.Admin.modal_confirm_delete')
@endsection
@section('js')
<script src="{{asset('assets/build/admin/js/setting.js')}}"></script>
@endsection

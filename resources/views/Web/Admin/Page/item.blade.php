@extends('Layout.Admin.main')

@section('page_title')
  Item
@endsection


@section('page_header')
    <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">HomePage</a></li>
            <li class="breadcrumb-item active" aria-current="page">Item</li>
        </ol>
        <h6 class="slim-pagetitle">Item</h6>
    </div><!-- slim-pageheader -->
@endsection

@section('page_content')
    <div class="section-wrapper">
        <div class="text-right">
            <span class="btn btn-primary btn-icon mg-l-5" id="addItem">
                <div>
                    <i class="fa fa-plus"></i>
                </div>
            </span>
        </div>
        @include('Web.Admin.Section.item.table_item')
        @include('Web.Admin.Section.item.modal_item')
    </div><!-- section-wrapper -->
    @include('Layout.Admin.modal_confirm_delete')
@endsection
@section('js')
<script src="{{asset('assets/build/admin/js/item.js')}}"></script>
@endsection

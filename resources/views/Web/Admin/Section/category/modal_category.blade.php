<!-- Edit modal category -->
<div class="modal fade" id="modal-category" data-backdrop="static">
    <div class="modal-dialog modal-lg w-75">
        <div class="modal-content tx-category-sm">
            <div class="modal-header pd-x-20">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><b class="fas fa-user-graduate" id="ttlModal"></b></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <form class="form-layout" id="CategoryForm">
                    <input type="hidden" id="action" name="action" value="">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="description_save" name="description_save" />
                    <div class="row">
                        <label class="col-sm-2 form-control-label">Menu<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <div id="slWrapperMenu" class="parsley-select">
                                <select class="form-control select2" style="width: 100%" id="menu_id" name="menu_id" data-placeholder="Select menu"
                                        data-parsley-class-handler="#slWrapperMenu" data-parsley-errors-container="#slErrorContainerMenu"
                                        required data-parsley-required-message="Select menu.">
                                        <option label="Select menu"></option>
                                        @foreach($menu as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                </select>
                                <div id="slErrorContainerMenu"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Type<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <div id="slWrapperType" class="parsley-select">
                                <select class="form-control select2" style="width: 100%" id="type" name="type" data-placeholder="Select type"
                                        data-parsley-class-handler="#slWrapperType" data-parsley-errors-container="#slErrorContainerType"
                                        required data-parsley-required-message="Select type.">
                                        <option label="Select type"></option>
                                        <option value="1">List</option>
                                        <option value="2">Collections</option>
                                </select>
                                <div id="slErrorContainerTitle"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Name<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                        <input type="text" class="form-control" name="name" id="name" value=""
                            maxlength="128" required data-parsley-required-message="Insert name.">
                        </div>
                    </div>
                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">URL<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                        <input type="text" class="form-control" name="url" id="url" value=""
                            maxlength="128" required data-parsley-required-message="Insert url.">
                        </div>
                    </div>
                    <div class="block_album">
                        <div class="row mg-t-30">
                            <label class="col-sm-2 form-control-label">Video</label>
                            <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <input type="text" class="form-control" name="video" id="video" value=""
                                maxlength="255" data-parsley-required-message="Insert url youtube video.">
                            </div>
                        </div>
                        <div class="row mg-t-30">
                            <label class="col-sm-2 form-control-label">List Images</label>
                            <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                                <input type="file"  accept=".jpg, .jpeg, .png" class="picupload" id="img" name="img"/>
                            </div>
                        </div>
                    </div>

                    <div class="block_item">
                        <div class="row mg-t-30">
                            <label class="col-sm-2 form-control-label">Images</label>
                            <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                                <input type="file"  accept=".jpg, .jpeg, .png" class="picupload" id="img_item" name="img_item"/>
                            </div>
                        </div>

                        <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Description</label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <textarea class="mytextarea" id="description" name="description"></textarea>
                        </div>
                    </div>
                    </div>
                    
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left"
                        data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnCategory"
                        data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
                    <i class="fa fa-plus"></i> Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Edit modal employee  -->

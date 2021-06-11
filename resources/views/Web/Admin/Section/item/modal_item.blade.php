<!-- Edit modal item -->
<div class="modal fade" id="modal-item" data-backdrop="static">
    <div class="modal-dialog modal-lg w-75">
        <div class="modal-content tx-item-sm">
            <div class="modal-header pd-x-20">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><b class="fas fa-user-graduate" id="ttlModal"></b></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <form class="form-layout" id="ItemForm">
                    <input type="hidden" id="action" name="action" value="">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="description_save" name="description_save" />
                    <input type="hidden" id="farbrics_save" name="farbrics_save" />
                    <input type="hidden" id="detail_save" name="detail_save" />
                    <div class="row">
                        <label class="col-sm-2 form-control-label">Name<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                        <input type="text" class="form-control" name="name" id="name" value=""
                            maxlength="128" required data-parsley-required-message="Insert name.">
                        </div>
                    </div>

                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Subname<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                        <input type="text" class="form-control" name="sub_name" id="sub_name" value=""
                            maxlength="128" required data-parsley-required-message="Insert Subname.">
                        </div>
                    </div>
                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">URL<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                        <input type="text" class="form-control" name="slug" id="slug" value=""
                            maxlength="128" required data-parsley-required-message="Insert slug.">
                        </div>
                    </div>
                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Price<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                        <input type="text" class="form-control" name="price" id="price" value=""
                            maxlength="128" required data-parsley-required-message="Insert Price.">
                        </div>
                    </div>

                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Category<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <div id="slWrapperCategory" class="parsley-select">
                                <select class="form-control select2" style="width: 100%" id="category_id" name="category_id" data-placeholder="Select Category"
                                        data-parsley-class-handler="#slWrapperCategory" data-parsley-errors-container="#slErrorContainerCategory"
                                        required data-parsley-required-message="Select Category.">
                                        <option label="Select Category"></option>
                                        @foreach($category as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                </select>
                                <div id="slErrorContainerCategory"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Material<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <div id="slWrapperMaterial" class="parsley-select">
                                <select class="form-control select2" style="width: 100%" id="material" name="material[]" data-placeholder="Select Material"
                                        data-parsley-class-handler="#slWrapperMaterial" data-parsley-errors-container="#slErrorContainerMaterial"
                                        required data-parsley-required-message="Select Material.">
                                        <option label="Select Material"></option>
                                        @foreach($material as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                </select>
                                <div id="slErrorContainerMaterial"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Size<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <div id="slWrapperSize" class="parsley-select">
                                <select class="form-control select2" style="width: 100%" id="size" name="size[]" data-placeholder="Select Size"
                                        data-parsley-class-handler="#slWrapperSize" data-parsley-errors-container="#slErrorContainerSize"
                                        required data-parsley-required-message="Select Size.">
                                        <option label="Select Size"></option>
                                        @foreach($size as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                </select>
                                <div id="slErrorContainerSize"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Color<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <div id="slWrapperColor" class="parsley-select">
                                <select class="form-control select2" style="width: 100%" id="color" name="color[]" data-placeholder="Select Color"
                                        data-parsley-class-handler="#slWrapperColor" data-parsley-errors-container="#slErrorContainerColor"
                                        required data-parsley-required-message="Select Color.">
                                        <option label="Select Color"></option>
                                        @foreach($color as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                </select>
                                <div id="slErrorContainerColor"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">HOT</label>
                        <div class="col-sm-01 mg-t-10 mg-sm-t-0">
                            <label class="ckbox">
                                <input type="checkbox" name="is_hot" id="is_hot">
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Thumb</label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <input type="file"  accept=".jpg, .jpeg, .png" class="picupload" id="img_thumb" name="img_thumb"/>
                        </div>
                    </div>

                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">List images</label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <input type="file"  accept=".jpg, .jpeg, .png" class="picupload" id="imgs" name="imgs"/>
                            <div class="alert alert-info" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong>Chú ý về kích thước ảnh(tỉ lệ ảnh chuẩn là 2:3)!</strong> </br>
                                *** Kích thước nên cần giống nhau cho tất cả các ảnh </br>
                                Kích thước tốt nhất: </br>
                                <ul>
                                    <li>Chiều rộng: 2000px </li>
                                    <li>Chiều cao: 3000px </li>
                                </ul>
                                Kích thước chuẩn: </br>
                                <ul>
                                    <li>Chiều rộng: 1200px </li>
                                    <li>Chiều cao: 1800px </li>
                                </ul>
                                Kích thước ổn: </br>
                                <ul>
                                    <li>Chiều rộng: 600px </li>
                                    <li>Chiều cao: 900px </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Description</label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <textarea class="mytextarea" id="description" name="description"></textarea>
                        </div>
                    </div>

                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Farbrics</label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <textarea class="mytextarea" id="farbrics" name="farbrics"></textarea>
                        </div>
                    </div>

                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Detail</label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <textarea class="mytextarea" id="detail" name="detail"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left"
                        data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnItem"
                        data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
                    <i class="fa fa-plus"></i> Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Edit modal employee  -->

<!-- Edit modal setting -->
<div class="modal fade" id="modal-setting" data-backdrop="static">
    <div class="modal-dialog modal-lg w-75">
        <div class="modal-content tx-size-sm">
            <div class="modal-header pd-x-20">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><b class="fas fa-user-graduate" id="ttlModal"></b></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <form class="form-layout" id="SettingForm">
                    <div class="row">
                        <label class="col-sm-2 form-control-label">Key<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                        <input type="text" class="form-control" name="key" id="key" value=""
                            maxlength="128" required data-parsley-required-message="Insert key." readonly>
                        </div>
                    </div>
                    <div class="row mg-t-30">
                        <label class="col-sm-2 form-control-label">Value<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                        <input type="text" class="form-control" name="value" id="value" value=""
                            maxlength="128" required data-parsley-required-message="Insert value.">
                        </div>
                    </div>
                    <div class="row mg-t-30 block_img d-none">
                        <label class="col-sm-2 form-control-label">Image</label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                            <input type="file"  accept=".jpg, .jpeg, .png" class="picupload" id="url_img" name="url_img"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left"
                        data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="btnSetting"
                        data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
                    <i class="fa fa-plus"></i> Lưu</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Edit modal employee  -->

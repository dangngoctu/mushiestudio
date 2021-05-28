<!-- Edit modal material -->
<div class="modal fade" id="modal-material" data-backdrop="static">
    <div class="modal-dialog modal-lg w-75">
        <div class="modal-content tx-material-sm">
            <div class="modal-header pd-x-20">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><b class="fas fa-user-graduate" id="ttlModal"></b></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <form class="form-layout" id="MaterialForm">
                    <input type="hidden" id="action" name="action" value="">
                    <input type="hidden" id="id" name="id" value="">
                    <div class="row">
                        <label class="col-sm-2 form-control-label">Name<span class="tx-danger">*</span></label>
                        <div class="col-sm-10 mg-t-10 mg-sm-t-0">
                        <input type="text" class="form-control" name="name" id="name" value=""
                            maxlength="128" required data-parsley-required-message="Insert name.">
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left"
                        data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnMaterial"
                        data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
                    <i class="fa fa-plus"></i> Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Edit modal employee  -->

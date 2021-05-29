<!-- Confirm modal -->
<div class="modal fade" id="confirm-delete-modal">
    <div class="modal-dialog w-25">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-x-20">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">
                    <i class="fa fa-exclamation-triangle text-red"></i> Cảnh báo</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" class="form-control" id="id" name="id" required>
                        <input type="hidden" class="form-control" id="action" name="action" required>
                        <span id="txt_confirm">Bạn có muốn xóa?</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning"
                        id="confirm-delete">Có</button>
                <button type="button" data-dismiss="modal" class="btn">Không</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Confirm modal -->

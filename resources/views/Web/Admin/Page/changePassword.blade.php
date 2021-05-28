<!-- Edit modal employee -->
<div class="modal fade" id="modal-change-password" data-backdrop="static">
    <div class="modal-dialog modal-lg w-75" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-header pd-x-20">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><i class="fas fa-user-graduate" id="ttlModal"></i>Change password</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <form class="form-layout" id="ChangePasswordForm">
                    <input type="hidden" id="action_change_pass" name="action_change_pass" value="">
                    <div class="row">
                        <label class="col-sm-3 form-control-label">Mật khẩu cũ <span class="tx-danger">*</span></label>
                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                            <input type="text" class="form-control" name="oldPassword" id="oldPassword" value=""
                                   maxlength="255" required data-parsley-required-message="Old password is required."
                                   data-parsley-minlength="6" data-parsley-minlength-message="Password at least 6 characters.">
                        </div>
                    </div>
                    <div class="row mg-t-30">
                        <label class="col-sm-3 form-control-label">Mật khẩu mới <span class="tx-danger">*</span></label>
                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                            <input type="password" class="form-control" name="newPassword" id="newPassword" value=""
                                   maxlength="20" required data-parsley-required-message="New password is required."
                                   data-parsley-minlength="6" data-parsley-minlength-message="Password at least 6 characters.">
                        </div>
                    </div>
                    <div class="row mg-t-30">
                        <label class="col-sm-3 form-control-label">Nhập lại mật khẩu mới <span class="tx-danger">*</span></label>
                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                            <input type="password" class="form-control" name="renewPassword" id="renewPassword" value=""
                                   maxlength="20" required data-parsley-required-message="Renew password is required."
                                   data-parsley-minlength="6" data-parsley-minlength-message="Password at least 6 characters."
                                   data-parsley-equalto="#newPassword" data-parsley-equalto-message="Renew password incorrect.">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left"
                        data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="ChangePasswordBtn"
                        data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
                    <i class="fa fa-plus"></i> Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Edit modal employee  -->

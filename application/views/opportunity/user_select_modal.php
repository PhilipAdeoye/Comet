<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="modal fade" id="userSelectModal" data-all-users='<?php echo $all_users ?>'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select Volunteer</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" id="allUsersTypeahead" placeholder="Search for anyone..." />
                        </div>
                        <div class="col-sm-3 col-xs-12">
                            <button class="btn btn-primary btn-block" id="autocompleteCommitBtn" disabled="disabled" 
                                    data-user-id=""
                                    data-opportunity-id=""
                                    data-url="<?php echo base_url('opportunity/schedule_user') ?>">Commit!</button>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="col-xs-offset-1 col-xs-4"><hr></div>
                        <div class="col-xs-2"><h4 class="text-center">OR</h4></div>
                        <div class="col-xs-4"><hr></div>
                    </div>
                </div>
                <div class="form-content">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>



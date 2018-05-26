<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="modal fade" id="partnerEditModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Partner</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('partner/post_edit', array('class'=>'form-horizontal', 'id'=>'editPartnerForm')) ?>
                    <div class="form-content">
                        
                    </div>
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-form-id="editPartnerForm" disabled>Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>



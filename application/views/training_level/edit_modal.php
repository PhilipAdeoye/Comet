<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="modal fade" id="trainingLevelEditModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Training Level</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('training_level/post_edit', array('class'=>'form-horizontal', 'id'=>'editTrainingLevelForm')) ?>
                    <div class="form-content">
                        
                    </div>
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-form-id="editTrainingLevelForm" disabled>Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>



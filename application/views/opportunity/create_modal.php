<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="modal fade" id="opportunityCreateModal" data-backdrop="static" 
     data-is-mobile="<?php echo $this->agent->is_mobile() ? 'yes' : 'no' ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Create Opportunities</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('opportunity/create_many', array('class'=>'form-horizontal', 'id'=>'createOpportunityForm')) ?>
                    <div class="form-content">
                        
                    </div>
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success pull-left add-more-btn">
                    <span class="glyphicon glyphicon-plus right-gutter"></span> Add More
                </button>
                <button type="button" class="btn btn-primary" data-form-id="createOpportunityForm" disabled>Create</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="modal fade" id="cancelCommitmentModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cancel Commitment</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this commitment?</p>
                <p class="text-warning">Please remember to cancel commitments more than 
                    <?php echo $this->config->item('days_before_opportunity_can_be_cancelled') ?> 
                    days before they are scheduled</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" disabled
                        data-id="" 
                        data-url="<?php echo base_url('opportunity/cancel_commitment') ?>">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>



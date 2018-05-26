<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $validation_errors = validation_errors(); ?>
<?php if (strlen($validation_errors) > 0): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Please correct the following errors</strong>
        <ul><?php echo $validation_errors ?></ul>
    </div>
<?php elseif (count($records) === 0): ?>
    <div class="alert alert-info" role="alert">
        There are no attendance records that match your filter criteria
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-sm-12 visible-xs">
            <button type="button" class="btn btn-default btn-block mark-all-btn">
                <span class="glyphicon glyphicon-ok right-gutter"></span> Mark All Present
            </button>
            <button type="button" class="btn btn-default btn-block unmark-all-btn">
                <span class="glyphicon glyphicon-question-sign right-gutter"></span> Unmark All
            </button>
            <a href="<?php echo base_url('attendance/get_sign_in_sheet?date=' . $date . '&location_id=' . $location_id . '&partner_id=' . $partner_id) ?>"
               class="btn btn-default btn-block"  target="_blank">
                <span class="glyphicon glyphicon-print right-gutter"></span> Print Sign-in Sheet
            </a>
            <button type="button" class="btn btn-primary btn-block save-btn">Save</button>
        </div>
        <div class="col-sm-12 hidden-xs">
            <button type="button" class="btn btn-default mark-all-btn">
                <span class="glyphicon glyphicon-ok right-gutter"></span> Mark All Present
            </button>
            <button type="button" class="btn btn-default unmark-all-btn">
                <span class="glyphicon glyphicon-question-sign right-gutter"></span> Unmark All
            </button>
            <a href="<?php echo base_url('attendance/get_sign_in_sheet?date=' . $date . '&location_id=' . $location_id . '&partner_id=' . $partner_id) ?>"
               class="btn btn-default" target="_blank">
                <span class="glyphicon glyphicon-print right-gutter"></span> Print Sign-in Sheet
            </a>
            <button type="button" class="btn btn-primary pull-right save-btn">Save</button>
        </div>
    </div>

    <hr style="margin-bottom: 10px;">
    <?php echo form_open('attendance/capture', array('id' => 'captureForm')) ?>

    <?php $present_partner_name = '' ?>

    <?php for ($i = 0, $count = count($records); $i < $count; $i++): ?>
    
        <?php if ($present_partner_name !== $records[$i]->partner_name): ?>
            <h4 style="margin-top: 23px"><?php echo $records[$i]->partner_name ?></h4>
            <hr>
        <?php endif; ?>
        <?php $present_partner_name = $records[$i]->partner_name ?>
        
        <?php
        $this->load->view('attendance/record', array(
            'record' => $records[$i],
            'idx' => $i
        ))
        ?>
        
        <?php if ($i < $count - 1): ?>
            <hr class="visible-xs">
            <hr class="hidden-xs" style="margin-top: 10px; margin-bottom: 10px;">
        <?php endif; ?>
            
    <?php endfor; ?>
    <?php echo form_close() ?>
    <hr>

    <div class="row">
        <div class="col-sm-12 visible-xs">
            <button type="button" class="btn btn-primary btn-block save-btn">Save</button>
        </div>
        <div class="col-sm-12 hidden-xs">
            <button type="button" class="btn btn-primary save-btn">Save</button>
        </div>
    </div>
<?php endif; ?>


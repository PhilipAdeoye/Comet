<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $validation_errors = validation_errors(); ?>
<?php if (strlen($validation_errors) > 0): ?>
    <div class="alert alert-danger validation-errors-alert" role="alert">
        <strong>Error!</strong>
        <ul><?php echo $validation_errors ?></ul>
    </div>
<?php endif; ?>
<div class="form-group required">
    <?php echo form_label('Location', 'location', array('class' => 'col-sm-4 control-label')) ?>        
    <?php
    $places = array('0' => 'Select Location');
    foreach ($locations as $row) {
        $places[$row->id] = $row->name;
    }
    ?>
    <div class="col-sm-7">
        <?php echo form_dropdown('location_id', $places, set_value('location_id'), array('class' => 'form-control')) ?>
        <?php echo form_error('location_id') ?>
    </div>
</div> 
<div class="form-group required">
    <?php echo form_label('Scheduled On', 'currentDate', array('class' => 'col-sm-4 control-label')) ?>
    <div class="col-sm-7">
        <?php if ($this->agent->is_mobile()): ?>
            <input type="date" name="current_date" value="<?php echo set_value('current_date') ?>" class="form-control" id="currentDate">
        <?php else: ?>
            <input type="text" name="current_date" value="<?php echo set_value('current_date') ?>" class="form-control" id="currentDate">
        <?php endif; ?>
        <?php echo form_error('current_date') ?>
    </div>
</div> 
<div class="form-group required">
    <?php echo form_label('Reschedule To', 'newDate', array('class' => 'col-sm-4 control-label')) ?>
    <div class="col-sm-7">
        <?php if ($this->agent->is_mobile()): ?>
            <input type="date" name="new_date" value="<?php echo set_value('new_date') ?>" class="form-control" id="newDate">
        <?php else: ?>
            <input type="text" name="new_date" value="<?php echo set_value('new_date') ?>" class="form-control" id="newDate">
        <?php endif; ?>
        <?php echo form_error('new_date') ?>
    </div>
</div>
<?php if ($this->agent->is_mobile()): ?>
<?php else: ?>
    <script>
        $(document).ready(function () {
            var currentDatePicker = $("#currentDate");
            var newDatePicker = $("#newDate");
            currentDatePicker.datetimepicker({
                format: "MM/DD/YYYY"
            }).on("dp.show", letModalBodyOverflowVisible);

            newDatePicker.datetimepicker({
                format: "MM/DD/YYYY"
            }).on("dp.show", letModalBodyOverflowVisible);

            function letModalBodyOverflowVisible() {
                $("#eventRescheduleModal .modal-body").css("overflow-y", "visible");
            }
        });
    </script>
<?php endif; ?>
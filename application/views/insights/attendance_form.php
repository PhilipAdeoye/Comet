<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php echo form_open('insights/attendance_summary', array('class' => 'form-horizontal')) ?>
    <input type="hidden" id="hiddenUserAgent" data-is-mobile="<?php echo $this->agent->is_mobile() ? 'yes' : 'no' ?>">
    <div class="form-group required">
        <?php echo form_label('Location', 'locationId', array('class' => 'col-sm-12')) ?>        
        <?php
        $places = array('0' => 'Select a Location');
        foreach ($locations as $row) {
            $places[$row->id] = $row->name;
        }
        ?>
        <div class="col-sm-12">
            <?php echo form_dropdown('location_id', $places, set_value('location_id', $location_id), 
                array('class' => 'form-control', 'id' => 'locationId')) ?>
        </div>
    </div>
    <br>
    <div class="form-group">
        <?php echo form_label('Partner', 'partnerId', array('class' => 'col-sm-12')) ?>        
        <?php        
        $partners_dd_list = array('0' => 'All Partners');        
        foreach ($partners as $row) {
            $partners_dd_list[$row->id] = $row->name;
        }
        ?>
        <div class="col-sm-12">
            <?php echo form_dropdown('partner_id', $partners_dd_list, set_value('partner_id', $partner_id), 
                array('class' => 'form-control', 'id' => 'partnerId')) ?>
        </div>
    </div>
    <br>
    <div class="form-group">        
        <?php if ($this->agent->is_mobile()): ?>
            <?php echo form_label('Start From', 'attendanceStartDate', array('class' => 'col-sm-12')) ?>
            <div class="col-sm-12">
                <input type="date" name="attendance_start_date" value="<?php echo set_value('attendance_start_date') ?>" 
                       class="form-control" id="attendanceStartDate">
            </div>
        <?php else: ?>
            <?php echo form_label('Start From', 'attendanceStartDate', array('class' => 'col-sm-12')) ?>
            <div class="col-sm-12">
                <input type="text" name="attendance_start_date" value="<?php echo set_value('attendance_start_date') ?>" 
                       class="form-control" id="attendanceStartDate">
            </div>
        <?php endif; ?>
    </div>
    <div class="form-group">        
        <?php if ($this->agent->is_mobile()): ?>
            <?php echo form_label('End At', 'attendanceEndDate', array('class' => 'col-sm-12')) ?>
            <div class="col-sm-12">
                <input type="date" name="attendance_end_date" value="<?php echo set_value('attendance_end_date') ?>" 
                       class="form-control" id="attendanceEndDate">
            </div>
        <?php else: ?>
            <?php echo form_label('End At', 'attendanceEndDate', array('class' => 'col-sm-12')) ?>
            <div class="col-sm-12">
                <input type="text" name="attendance_end_date" value="<?php echo set_value('attendance_end_date') ?>" 
                       class="form-control" id="attendanceEndDate">
            </div>
        <?php endif; ?>
    </div>
    <div class="form-group">
            <div class="col-xs-12">
                <div class="checkbox">
                    <label>
                        <?php echo form_checkbox('use_attendance_data', 'done', set_checkbox('use_attendance_data', 'done', $use_attendance_data)) ?> 
                        Use attendance data                        
                    </label>
                </div>
            </div>
        </div>
    <div class="form-group">    
        <div class="col-sm-12">
            <button type='button' class='btn btn-primary btn-block' id="applyAttendanceFilterBtn">View Results</button>
        </div>
    </div>
<?php form_close() ?>
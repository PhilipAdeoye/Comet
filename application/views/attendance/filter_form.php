<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php echo form_open('attendance/filter', array('class' => 'form-horizontal')) ?>
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
    <div class="form-group" id="dateFormGroup" data-is-mobile="<?php echo $this->agent->is_mobile() ? 'yes' : 'no' ?>">
        
        <?php if ($this->agent->is_mobile()): ?>
            <?php echo form_label('Date', 'oppoDate', array('class' => 'col-sm-12')) ?>
            <div class="col-sm-12">
                <input type="date" name="date" value="<?php echo set_value('date') ?>" class="form-control" id="oppoDate">
            </div>
        <?php else: ?>
            <div class="col-sm-12">
                <input type="text" name="date" value="<?php echo set_value('date') ?>" class="form-control" id="oppoDate"
                       style="display:none">
            </div>
        <?php endif; ?>
    </div>
    <div class="form-group">    
        <div class="col-sm-12">
            <button type='button' class='btn btn-primary btn-block' id="applyFiltersBtn">Apply Filters</button>
        </div>
    </div>
<?php form_close() ?>
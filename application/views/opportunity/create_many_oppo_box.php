<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div class="well well-sm oppo-box">
    <div class="form-group required">
        <?php echo form_label('Role', 'role', array('class' => 'col-sm-3 control-label')) ?>
        <button type="button" class="btn btn-default btn-sm visible-xs-inline pull-right delete-btn"><span class="glyphicon glyphicon-remove"></span></button>
        <div class="col-sm-8">
            <?php echo form_dropdown("oppos[$idx][role_id]", $offices, set_value("oppos[$idx][role_id]"), array('class' => 'form-control')) ?>
            <?php echo form_error("oppos[$idx][role_id]") ?>
        </div>
        <button type="button" class="btn btn-default btn-sm hidden-xs delete-btn"><span class="glyphicon glyphicon-remove"></span></button>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-xs-3 col-sm-2" style="padding-left: 10px; padding-right: 5px;">
            <?php if ($this->agent->is_mobile()): ?>
                <input type="time" name='<?php echo "oppos[$idx][start_time]" ?>' value="<?php echo set_value("oppos[$idx][start_time]", '09:00') ?>" 
                       class="form-control start-time" max="<?php echo set_value("oppos[$idx][end_time]", '14:00') ?>">
                   <?php else: ?>
                <input type="text" name='<?php echo "oppos[$idx][start_time]" ?>' value="<?php echo set_value("oppos[$idx][start_time]") ?>" 
                       class="form-control start-time">
                   <?php endif; ?>
        </div>
        <div class="col-xs-3 col-sm-2" style="padding-left: 10px; padding-right: 5px;">
            <?php if ($this->agent->is_mobile()): ?>
                <input type="time" name='<?php echo "oppos[$idx][end_time]" ?>' value="<?php echo set_value("oppos[$idx][end_time]", '14:00') ?>" 
                       class="form-control end-time" min="<?php echo set_value("oppos[$idx][start_time]", '09:00') ?>">
                   <?php else: ?>
                <input type="text" name='<?php echo "oppos[$idx][end_time]" ?>' value="<?php echo set_value("oppos[$idx][end_time]") ?>" 
                       class="form-control end-time">
                   <?php endif; ?>
        </div>
        <?php echo form_label('Spots', 'spots', array('class' => 'col-xs-2 control-label')) ?>
        <div class="col-xs-4 col-sm-2">
            <input type="number" name='<?php echo "oppos[$idx][num_spots]" ?>' value="<?php echo set_value("oppos[$idx][num_spots]", 1) ?>" class="form-control num-spots" min="1">
        </div>
        <?php if (strlen(form_error("oppos[$idx][start_time]")) > 0 || strlen(form_error("oppos[$idx][end_time]")) > 0 || strlen(form_error("oppos[$idx][num_spots]")) > 0): ?>
            <div class="col-xs-12 col-sm-offset-3 col-sm-8">
                <?php echo form_error("oppos[$idx][start_time]"), '<br>' ?>
                <?php echo form_error("oppos[$idx][end_time]"), '<br>' ?>
                <?php echo form_error("oppos[$idx][num_spots]") ?>        
            </div>
        <?php endif; ?>
    </div>
    <input type="hidden" class="name-index" value="<?php echo $idx ?>">
</div>
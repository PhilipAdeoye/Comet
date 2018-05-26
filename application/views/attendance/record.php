<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $someone_signed_up_for_this_opportunity = $record->can_take_attendance; ?>
<div class="row">
    <div class="col-xs-2">
        <?php if ($someone_signed_up_for_this_opportunity): ?>

            <input type="hidden"
                   name='<?php echo "records[$idx][opportunity_id]" ?>' 
                   value='<?php echo $record->opportunity_id ?>'>
            <input type="hidden" class="hidden-status" 
                   name='<?php echo "records[$idx][status]" ?>' 
                   value='<?php echo $record->status ?>'>

            <?php if ($record->status === '1'): ?>
                <button type="button" class="btn btn-default btn-sm capture-btn">
                    <span class="glyphicon glyphicon-ok"></span>
                </button>
            <?php elseif ($record->status === '0'): ?>
                <button type="button" class="btn btn-default btn-sm capture-btn">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-default btn-sm capture-btn">
                    <span class="glyphicon glyphicon-question-sign"></span>
                </button>
            <?php endif; ?>

        <?php endif; ?>
    </div>
    <div class="col-xs-10">
        <div class="row">
            <div class="col-sm-6">
                <?php if ($someone_signed_up_for_this_opportunity): ?>
                    <?php echo $record->first_name . ' ' . $record->last_name ?>
                <?php else: ?>
                    <i class="text-muted">No one signed up</i>
                <?php endif; ?>
            </div>
            <div class="col-sm-6">
                <?php echo $record->role_description; ?>
            </div>
        </div>
    </div>
</div>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $validation_errors = validation_errors(); ?>
<?php if (strlen($validation_errors) > 0): ?>
    <div class="alert alert-danger validation-errors-alert" role="alert">
        <strong>Error!</strong>
        <ul><?php echo $validation_errors ?></ul>
    </div>
<?php endif; ?>  
<?php echo form_hidden('id', set_value('id', $id)) ?>
<div class="form-group required">
    <?php echo form_label('Subject', 'subject', array('class' => 'col-sm-2 control-label')) ?>
    <div class="col-sm-9">
        <input type="text" name="subject" value="<?php echo set_value('subject', $subject) ?>" class="form-control" id="subject">
        <?php echo form_error('subject') ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Placeholders in use', 'placeholdersInUse', array('class' => 'col-sm-2 control-label')) ?>
    <div class="col-sm-9">
        <p class="form-control-static" id="placeholdersInUse"></p>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Not in use', 'placeholdersNotInUse', array('class' => 'col-sm-2 control-label')) ?>
    <div class="col-sm-9">
        <p class="form-control-static" id="placeholdersNotInUse"></p>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Body Template', 'message', array('class' => 'col-sm-2 control-label')) ?>
    <div class="col-sm-9">
        <?php echo form_textarea(array('name'=>'message', 'rows'=>'6', 'class'=>'form-control', 'id'=>'message'), 
                set_value('message', $message, FALSE)) ?>
        <?php echo form_error('message') ?>
    </div>
</div>
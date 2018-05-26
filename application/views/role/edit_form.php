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
    <?php echo form_label('Name', 'description', array('class' => 'col-sm-2 control-label')) ?>
    <div class="col-sm-9">
        <input type="text" name="description" value="<?php echo set_value('description', $description) ?>" class="form-control" id="description">
        <?php echo form_error('description') ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Work Description', 'helpText', array('class' => 'col-sm-2 control-label')) ?>
    <div class="col-sm-9">
        <?php echo form_textarea(array('name'=>'help_text', 'rows'=>'6', 'class'=>'form-control', 'id'=>'helpText'), 
                set_value('help_text', $help_text, FALSE)) ?>
        <?php echo form_error('help_text') ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Email Text', 'emailText', array('class' => 'col-sm-2 control-label')) ?>
    <div class="col-sm-9">
        <?php echo form_textarea(array('name'=>'email_text', 'rows'=>'6', 'class'=>'form-control', 'id'=>'emailText'), 
                set_value('email_text', $email_text, FALSE)) ?>
        <?php echo form_error('email_text') ?>
    </div>
</div>

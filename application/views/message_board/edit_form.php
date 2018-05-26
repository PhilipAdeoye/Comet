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
    <?php echo form_label('Title', 'title', array('class' => 'col-sm-3 control-label')) ?>
    <div class="col-sm-8">
        <input type="text" name="title" value="<?php echo set_value('title', $title) ?>" class="form-control" id="title">
        <?php echo form_error('title') ?>
    </div>
</div>
<div class="form-group required">
    <?php echo form_label('Message', 'message', array('class' => 'col-sm-3 control-label')) ?>
    <div class="col-sm-8">
        <?php echo form_textarea(array('name'=>'message', 'rows'=>'8', 'class'=>'form-control'), 
                set_value('message', $message, FALSE)) ?>
        <?php echo form_error('message') ?>
    </div>
</div>

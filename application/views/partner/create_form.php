<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $validation_errors = validation_errors(); ?>
<?php if (strlen($validation_errors) > 0): ?>
    <div class="alert alert-danger validation-errors-alert" role="alert">
        <strong>Error!</strong>
        <ul><?php echo $validation_errors ?></ul>
    </div>
<?php endif; ?>  
<div class="form-group required">
    <?php echo form_label('Name', 'name', array('class' => 'col-sm-3 control-label')) ?>
    <div class="col-sm-8">
        <input type="text" name="name" value="<?php echo set_value('name') ?>" class="form-control" id="name">
        <?php echo form_error('name') ?>
    </div>
</div>

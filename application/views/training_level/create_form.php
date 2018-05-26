<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $validation_errors = validation_errors(); ?>
<?php if (strlen($validation_errors) > 0): ?>
    <div class="alert alert-danger validation-errors-alert" role="alert">
        <strong>Error!</strong>
        <ul><?php echo $validation_errors ?></ul>
    </div>
<?php endif; ?>  

<div class="form-group required">
    <?php echo form_label('Name', 'name', array('class' => 'col-sm-2 control-label')) ?>
    <div class="col-sm-9">
        <input type="text" name="name" value="<?php echo set_value('name') ?>" class="form-control" id="name">
        <?php echo form_error('name') ?>
    </div>
</div>
<div class="form-group required">
    <?php echo form_label('Partner', 'partnerId', array('class' => 'col-sm-2 control-label')) ?>        
    <?php
    $partners_dd_list = array('0' => 'Select Partner');
    foreach ($partners as $row) {
        $partners_dd_list[$row->id] = $row->name;
    }
    ?>
    <div class="col-sm-9">
        <?php echo form_dropdown('partner_id', $partners_dd_list, set_value('partner_id', $partner_id), array('class' => 'form-control', 'id' => 'partnerId'))
        ?>
        <?php echo form_error('partner_id') ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Email Text', 'emailText', array('class' => 'col-sm-2 control-label')) ?>
    <div class="col-sm-9">
        <?php echo form_textarea(array('name' => 'email_text', 'rows' => '12', 'class' => 'form-control', 'id' => 'emailText'), set_value('email_text', '', FALSE))
        ?>
        <?php echo form_error('email_text') ?>
    </div>
</div>

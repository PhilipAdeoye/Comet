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
    <?php echo form_label('Name', 'name', array('class' => 'col-sm-2 control-label')) ?>
    <div class="col-sm-9">
        <input type="text" name="name" value="<?php echo set_value('name', $name) ?>" class="form-control" id="name">
        <?php echo form_error('name') ?>
    </div>
</div>
<div class="form-group required">
    <?php echo form_label('Address', 'address', array('class' => 'col-sm-2 control-label')) ?>
    <div class="col-sm-9">
        <input type="text" name="address" value="<?php echo set_value('address', $address) ?>" class="form-control" id="address">
        <?php echo form_error('address') ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label('Email Text', 'emailText', array('class' => 'col-sm-2 control-label')) ?>
    <div class="col-sm-9">
        <?php echo form_textarea(array('name' => 'email_text', 'rows' => '6', 'class' => 'form-control', 'id' => 'emailText'), set_value('email_text', $email_text, FALSE))
        ?>
        <?php echo form_error('email_text') ?>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-9">
        <div class="checkbox">
            <label>
                <?php echo form_checkbox('uses_media_release_form', 'whatever', 
                    set_checkbox('uses_media_release_form', 'whatever', $uses_media_release_form)) ?> 
                Use Media Release Form
            </label>
        </div>
    </div>
</div>
<div class="form-group">
     <?php echo form_label('Release Form Text', 'mediaReleaseForm', array('class' => 'col-sm-2 control-label')) ?>
     <div class="col-sm-9">
         <?php echo form_textarea(array('name'=>'media_release_form', 'rows'=>'6', 'class'=>'form-control', 'id'=>'mediaReleaseForm'), 
                 set_value('media_release_form', $media_release_form, FALSE)) ?>
         <?php echo form_error('media_release_form') ?>
     </div>
 </div>
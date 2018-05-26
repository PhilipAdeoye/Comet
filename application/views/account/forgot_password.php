<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h4>Hmmn... So you forgot your password</h4>
<br>
<p>First, we'll need the email you registered with...</p>
<br>
<?php echo form_open('account/forgot_password', array('class' => 'form-horizontal')) ?>

<?php $validation_errors = validation_errors(); ?>
<?php if (strlen($validation_errors) > 0): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Looks like we got a problem!</strong>
        <ul><?php echo $validation_errors ?></ul>
    </div>
<?php endif; ?>  

<div class="form-group required">
    <?php echo form_label('Email', 'email', array('class' => 'col-sm-3 control-label')) ?>        
    <div class="col-sm-5">
        <input type="email" name="email" value="<?php echo set_value('email') ?>"  class="form-control" id="email" 
               autocomplete="off">
               <?php echo form_error('email') ?>
    </div>
    <div class="col-sm-4">
        <button type='submit' class='btn btn-primary'>Let's do this!</button>
    </div>
</div>

<?php echo form_close() ?>
<script>
    $(document).ready(function() {
       $("form").on("submit", function() {
           $(this).find("button[type='submit']").prop("disabled", true);
       });
    });
</script>
    

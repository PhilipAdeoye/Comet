<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h4>Hello <?php echo $first_name ?>!</h4>
<br>
<h5>Please choose a new password</h5>

<p class="text-info">Pick something <strong>long</strong> and fun like <strong>Velociraptors?What.Velociraptors?</strong>,
    or tense and anticipatory like <strong>Butterflies.In.My.Belly</strong>, or passive-aggressive like 
    <strong>Long.Staying.Visitors.Smell.Like.Fish</strong>. Something long and <strong>memorable</strong> that's not a 
    four-digit PIN.
</p>
<br>

<?php echo form_open('account/choose_new_password', array('class' => 'form-horizontal')) ?>

<?php $validation_errors = validation_errors(); ?>
<?php if (strlen($validation_errors) > 0): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Please correct the following errors!</strong>
        <ul><?php echo $validation_errors ?></ul>
    </div>
<?php endif; ?>  

<div class="form-group required">
    <?php echo form_label('A Memorable Long Password', 'password', array('class' => 'col-sm-3 control-label')) ?>        
    <div class="col-sm-7">
        <input type="password" name="password" value="<?php echo set_value('password') ?>" class="form-control" id="password" 
               autocomplete="off">
        <?php echo form_error('password') ?>
    </div>
</div>
<div class="form-group required">
    <?php echo form_label('Confirm Password', 'confirmPassword', array('class' => 'col-sm-3 control-label')) ?>        
    <div class="col-sm-7">
        <input type="password" name="confirm_password" value="<?php echo set_value('confirm_password') ?>" class="form-control" 
               id="confirmPassword" autocomplete="off">
        <?php echo form_error('confirm_password') ?>
    </div>
</div>

<div class="form-group">    
    <div class="col-sm-offset-3 col-sm-7">
        <button type='submit' class='btn btn-primary'>Submit</button>
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
    

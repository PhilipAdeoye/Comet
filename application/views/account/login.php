<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="well well-small col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">

    <?php $validation_errors = validation_errors(); ?>
    <?php if (strlen($validation_errors) > 0): ?>
        <div class="alert alert-danger" role="alert">
            <strong>Error!</strong>
            <ul><?php echo $validation_errors ?></ul>
        </div>
    <?php endif; ?>  

    <?php echo form_open('account/login') ?>

    <h4 class="text-center">Sign In</h4>

    <div class="form-group">            
        <label for="email" class="sr-only"></label>            
        <div>
            <input type="email" class="form-control" name="email" value="<?php echo set_value('email') ?>" placeholder="Email Address"/>                
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="sr-only"></label>            
        <div>
            <input type="password" name="password" value="<?php echo set_value('password') ?>" class="form-control" placeholder="Password"/>                
        </div>
    </div>

    <div class="form-group">
        <div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
        </div>
    </div>
    <?php echo form_close() ?>
</div>

<div class="well well-small col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">    
    <p>New here? <?php echo anchor('account/register', 'Register'); ?></p>
</div>
<div class="well well-small col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">    
    <p>Forgot your password? <?php echo anchor('account/forgot_password', 'Not a problem'); ?></p>
</div>

<script>
    $(document).ready(function() {
       $("form").on("submit", function() {
           $(this).find("button[type='submit']").prop("disabled", true);
       });
    });
</script>
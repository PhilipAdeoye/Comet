<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-offset-2 col-sm-8">

        <?php echo form_open('account/media_release_form', array('class' => 'form-horizontal')) ?>

        <div class="form-group">
            <div class="col-sm-12">
                <h3><?php echo $this->config->item('clinic_name_abbr') ?> Media Release Form</h3>
                <hr>

                <?php $validation_errors = validation_errors(); ?>
                <?php if (strlen($validation_errors) > 0): ?>
                    <div class="alert alert-danger validation-errors-alert" role="alert">
                        <strong>Error!</strong>
                        <ul><?php echo $validation_errors ?></ul>
                    </div>
                <?php endif; ?> 

                <?php echo $media_release_form_text ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <input type="text" name="name" value="<?php echo set_value('name', $name) ?>" class="form-control" id="name">
                <?php echo form_error('name') ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <div class="radio">
                    <label>
                        <?php echo form_radio(array('id' => 'yes', 'name' => 'accept'), 'Yes', TRUE) ?>
                        Yes, I agree
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <div class="radio">
                    <label>
                        <?php echo form_radio(array('id' => 'no', 'name' => 'accept'), 'No') ?>
                        No
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </div>    
</div>

<?php echo form_close() ?>
<script>
    $(document).ready(function () {

        $("form").on("submit", function () {
            $(this).find("button[type='submit']").prop("disabled", true);
        });
    });
</script>
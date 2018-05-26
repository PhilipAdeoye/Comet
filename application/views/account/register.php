<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h4>Create an Account<br></h4>

<br>
<p class="text-info"><strong>A Quick Word About Passwords:</strong></p>
<p class="text-info">Pick something <strong>long</strong> and fun like <strong>Velociraptors?What.Velociraptors?</strong>,
    or tense and anticipatory like <strong>Butterflies.In.My.Belly</strong>, or passive-aggressive like 
    <strong>Long.Staying.Visitors.Smell.Like.Fish</strong>. Something long and <strong>memorable</strong>.
</p>
<hr>
<?php echo form_open('account/register', array('class' => 'form-horizontal')) ?>

<?php $validation_errors = validation_errors(); ?>
<?php if (strlen($validation_errors) > 0): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Error!</strong>
        <ul><?php echo $validation_errors ?></ul>
    </div>
<?php endif; ?>  

<div class="row">

    <!--Left Column-->
    <div class="col-md-6">
        <div class="form-group required">
            <?php echo form_label('Email', 'email', array('class' => 'col-sm-4 control-label')) ?>        
            <div class="col-sm-7 col-md-8">
                <input type="email" name="email" value="<?php echo set_value('email') ?>"  class="form-control" id="email" 
                       autocomplete="off">
                       <?php echo form_error('email') ?>
            </div>
        </div>
        <div class="form-group required">
            <?php echo form_label('Password', 'password', array('class' => 'col-sm-4 control-label')) ?>        
            <div class="col-sm-7 col-md-8">
                <input type="password" name="password" value="<?php echo set_value('password') ?>" class="form-control" id="password" 
                       autocomplete="off">
                       <?php echo form_error('password') ?>
            </div>
        </div>
        <div class="form-group required">
            <?php echo form_label('Confirm Password', 'confirmPassword', array('class' => 'col-sm-4 control-label')) ?>        
            <div class="col-sm-7 col-md-8">
                <input type="password" name="confirm_password" value="<?php echo set_value('confirm_password') ?>" class="form-control" 
                       id="confirmPassword" autocomplete="off">
                       <?php echo form_error('confirm_password') ?>
            </div>
        </div>
        <div class="form-group required">
            <?php echo form_label('First Name', 'firstName', array('class' => 'col-sm-4 control-label')) ?>        
            <div class="col-sm-7 col-md-8">
                <input type="text" name="first_name" value="<?php echo set_value('first_name') ?>" class="form-control" id="firstName">
                <?php echo form_error('first_name') ?>
            </div>
        </div>
        <div class="form-group required">
            <?php echo form_label('Last Name', 'lastName', array('class' => 'col-sm-4 control-label')) ?>        
            <div class="col-sm-7 col-md-8">
                <input type="text" name="last_name" value="<?php echo set_value('last_name') ?>" class="form-control" id="lastName">
                <?php echo form_error('last_name') ?>
            </div>
        </div>
        <div class="form-group required">
            <?php echo form_label('Affiliation', 'partnerId', array('class' => 'col-sm-4 control-label')) ?>        
            <?php
            $partners_dd_list = array('0' => 'Select Affiliation');
            foreach ($partners as $row) {
                $partners_dd_list[$row->id] = $row->name;
            }
            ?>
            <div class="col-sm-7 col-md-8">
                <?php echo form_dropdown('partner_id', $partners_dd_list, set_value('partner_id'), array('class' => 'form-control', 'id' => 'partnerId'))
                ?>
                <?php echo form_error('partner_id') ?>
            </div>
        </div>
    </div>

    <!--Right Column-->
    <div class="col-md-6">
        <div class="form-group required">
            <?php echo form_label('Training Level', 'trainingLevel', array('class' => 'col-sm-4 control-label')) ?>        
            <?php
            $levels = array('0' => 'Select Level');
            foreach ($training_levels as $row) {
                $levels[$row->id] = $row->name;
            }
            ?>
            <div class="col-sm-7" id="trainingLevelSelectContainer"
                 data-url="<?php echo base_url('account/get_training_levels_for_partner') ?>">
                     <?php echo form_dropdown('training_level_id', $levels, set_value('training_level_id'), array('class' => 'form-control', 'id' => 'trainingLevel'))
                     ?>
                     <?php echo form_error('training_level_id') ?>
            </div>
        </div>

        <div class="form-group required">
            <?php echo form_label('Preferred Location', 'preferredLocationId', array('class' => 'col-sm-4 control-label')) ?>        
            <?php
            $places = array('0' => 'Select a Location');
            foreach ($locations as $row) {
                $places[$row->id] = $row->name;
            }
            ?>
            <div class="col-sm-7">
                <?php echo form_dropdown('preferred_location_id', $places, set_value('preferred_location_id'), array('class' => 'form-control', 'id' => 'preferredLocationId'))
                ?>
                <?php echo form_error('preferred_location_id') ?>
            </div>
        </div>
        <div class="form-group required">
            <?php echo form_label('Phone Number', 'phoneNumber', array('class' => 'col-sm-4 control-label')) ?>        
            <div class="col-sm-7">
                <input type="tel" name="phone_number" value="<?php echo set_value('phone_number') ?>" 
                       class="form-control" id="phoneNumber" data-inputmask="'mask': '(999) 999-9999'">
                       <?php echo form_error('phone_number') ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Pager Number', 'pagerNumber', array('class' => 'col-sm-4 control-label')) ?>        
            <div class="col-sm-7">
                <input type="tel" name="pager_number" value="<?php echo set_value('pager_number') ?>" class="form-control" 
                       id="pagerNumber" data-inputmask="'mask': '(999) 999-9999'">
                       <?php echo form_error('pager_number') ?>
            </div>
        </div>
        <div class="form-group required">
            <?php echo form_label('Estimated Graduation Year', 'estimatedGraduationYear', array('class' => 'col-sm-4 control-label')) ?>        
            <div class="col-sm-7">
                <input type="number" name="estimated_graduation_year" value="<?php echo set_value('estimated_graduation_year') ?>" 
                       class="form-control" id="estimatedGraduationYear">
                       <?php echo form_error('estimated_graduation_year') ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-7">
                <div class="checkbox">
                    <label>
                        <?php echo form_checkbox('available_to_serve', 'done', set_checkbox('available_to_serve', 'done', TRUE)) ?> 
                        Available to serve?
                    </label>
                    <a tabindex="0" class="left-gutter" role="button" data-toggle="popover" data-trigger="focus" 
                       data-placement="bottom"
                       data-content="Check this box to let us know that you are available to volunteer for available
                       opportunities. If the time comes when you're no longer available to serve, for example when
                       you graduate please remember to uncheck it">
                        <span class="glyphicon glyphicon-question-sign" style="color:#9c27b0;font-size:medium"></span>
                    </a>                        
                </div>
            </div>
        </div>       
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-7">
                <div class="checkbox">
                    <label>
                        <?php echo form_checkbox('interpreter', 'spanish', set_checkbox('interpreter', 'spanish')) ?> 
                        Interpreter? (Spanish)
                    </label>
                </div>
            </div>
        </div> 
    </div>
</div>
<br>
<h5 class="text-center">Demographics</h5>
<br>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <?php echo form_label('Category', 'ethnicity', array('class' => 'col-sm-4 control-label')) ?>        
            <div class="col-sm-7 col-md-8">
                <input type="text" name="ethnicity" value="<?php echo set_value('ethnicity') ?>" 
                       class="form-control" id="ethnicity" placeholder="Type or select from suggestions">
                       <?php if (count($ethnicities) > 0): ?>
                    <p style="margin-top:7px">Suggestions:
                        <?php foreach ($ethnicities as $name => $desc): ?>
                            <span class="solid-grey-outline cursor-pointer suggestion" 
                                  data-suggestion-for-id="ethnicity"
                                  title="<?php echo $desc ?>"><?php echo $name ?></span>
                              <?php endforeach; ?>
                    </p>
                <?php endif; ?>
                    <p class="help-block">Leave blank if you'd rather not share</p>

            </div>
        </div>

    </div>
    <div class="col-md-6">
        
        <div class="form-group required">
            <?php echo form_label('Birth Month', 'birthMonth', array('class' => 'col-sm-4 control-label')) ?>        
            <div class="col-sm-3">
                <?php $months_of_the_year = array('' => 'Select') + $months_of_the_year; ?>
                <?php echo form_dropdown('birth_month', $months_of_the_year, set_value('birth_month'), array('class' => 'form-control', 'id' => 'birthMonth'))
                ?>
            </div>
            <?php echo form_label('Year', 'birthYear', array('class' => 'col-sm-1 control-label')) ?>        
            <div class="col-sm-3">
                <input type="number" name="birth_year" value="<?php echo set_value('birth_year') ?>" 
                       class="form-control" id="birthYear" placeholder="e.g.1989">
            </div>
            <div class="col-sm-offset-4 col-sm-8 col-md-offset-1 col-md-11">
                <?php echo form_error('birth_month') ?>
                <?php echo form_error('birth_year') ?>                
            </div>
        </div>
        <div class="form-group">
            <?php echo form_label('Sex', 'gender', array('class' => 'col-sm-4 control-label')) ?>        
            <div class="col-sm-7">
                <input type="text" name="gender" value="<?php echo set_value('gender') ?>" 
                       class="form-control" id="gender" placeholder="Type or select from suggestions">
                       <?php if (count($genders) > 0): ?>
                    <p style="margin-top:7px">Suggestions:
                        <?php foreach ($genders as $gender): ?>
                            <span class="solid-grey-outline cursor-pointer suggestion" data-suggestion-for-id="gender"><?php echo $gender ?></span>
                        <?php endforeach; ?>
                    </p>
                <?php endif; ?>
                    <p class="help-block">Leave blank if you'd rather not share</p>
            </div>
        </div>

    </div>
</div>
<br>
<br>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">    
            <div class="col-sm-offset-4 col-sm-8">
                <button type='submit' class='btn btn-primary'>Sign Up!</button>
            </div>
        </div>
    </div>    
</div>

<?php echo form_close() ?>
<script>
    $(document).ready(function () {
        $("#phoneNumber, #pagerNumber").inputmask();
        $('[data-toggle="popover"]').popover();
        
        $(".form-group span.suggestion").on("click", function () {
            var el = $(this);
            $("#" + el.data("suggestionForId")).val(el.html());
        });

        $("form").on("submit", function () {
            $(this).find("button[type='submit']").prop("disabled", true);
        });

        $("#partnerId").on("change", function () {
            var trainingLevelSelectContainer = $("#trainingLevelSelectContainer");
            var url = trainingLevelSelectContainer.data("url");
            var partnerId = $(this).val();

            if (trainingLevelSelectContainer.length && url && partnerId) {
                $.get(url, {
                    "partner_id": partnerId
                }).done(function (data) {
                    trainingLevelSelectContainer.html(data);
                });
            }
        });
    });
</script>


<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $this->load->view('opportunity/confirm_sign_up_modal'); ?>
<?php $this->load->view('opportunity/cancel_commitment_modal') ?>
<?php $this->load->view('opportunity/role_help_text_modal') ?>

<?php
if ($_SESSION['view_as_admin']) {    
    $this->load->view('opportunity/unschedule_user_modal');
    $this->load->view('opportunity/delete_modal');
    $this->load->view('opportunity/create_modal');
    $this->load->view('opportunity/reschedule_modal');    
    $this->load->view('opportunity/user_select_modal', array('all_users' => $all_users));
    
    $this->load->view('email/message_selected_people_modal');
}
?>
<style>
    /* Highlight the active dates unless it has been selected. When selected, 
    it gets the active class and is highlighted a different color, which we don't want to override */
    #calendarContainer .bootstrap-datetimepicker-widget table td.day:not(.disabled):not(.active){
        background-color: wheat;
    }
</style>
<div class="row" id="dateDetailsContainer" data-date="" data-url="">
    <div class="col-sm-12">
        <div class="panel-group" id="accordion_date_details" role="tablist"  style="display:none">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading_date_details">
                    <a data-toggle="collapse" data-parent="#accordion_date_details" href="#collapse_date_details">
                        <h4 class="panel-title">
                            <span class="detail-text"></span>
                            <span class="collapse-indicator glyphicon glyphicon-chevron-down pull-right"></span>
                        </h4>
                    </a>
                </div>
                <div id="collapse_date_details" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($_SESSION['view_as_admin']): ?>
    <div class="row" id="availableOpportunitiesContainer">
        <div class="col-sm-12">
            <div class="panel-group" id="accordion_non_committed" role="tablist" style="display:none">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading_non_committed">
                        <a data-toggle="collapse" data-parent="#accordion_non_committed" href="#collapse_non_committed">
                            <h4 class="panel-title">
                                Unfilled Volunteer Opportunities
                                <span class="collapse-indicator glyphicon glyphicon-chevron-down pull-right"></span>
                            </h4>
                        </a>
                    </div>
                    <div id="collapse_non_committed" class="panel-collapse collapse in" role="tabpanel">
                        <div class="panel-body">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-sm-4 col-md-3">
        <?php if ($_SESSION['view_as_admin']): ?>
            <button class="btn btn-block btn-primary" id="addNewOpportunityBtn" 
                    data-url="<?php echo base_url('opportunity/create_many') ?> ">
                Create Opportunity
            </button>
            <?php /* 
            * <button class="btn btn-block btn-warning" id="rescheduleEventBtn" 
            *        data-url="<?php echo base_url('opportunity/reschedule_event') ?> ">
            *    Reschedule Event
            * </button> 
             */ ?>
            <br>
        <?php endif; ?>
        <div id="calendarContainer" data-url="<?php echo base_url('opportunity/opportunity_dates') ?>">
            <div class="panel panel-default">
                <div class="panel-body">                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8 col-md-9">
        <div id="commitmentsContainer" data-url="<?php echo base_url('opportunity/users_commitments') ?>">
            <div class="panel-group" id="accordion_commitments" role="tablist">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading_commitments">
                        <a data-toggle="collapse" data-parent="#accordion_commitments" href="#collapse_commitments">
                            <h4 class="panel-title">
                                My Commitments
                                <span class="collapse-indicator glyphicon glyphicon-chevron-down pull-right"></span>
                            </h4>
                        </a>
                    </div>
                    <div id="collapse_commitments" class="panel-collapse collapse in" role="tabpanel">
                        <div class="panel-body">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if($_SESSION['view_as_admin']): ?>
        <div id="rolesContainer" data-url="<?php echo base_url('opportunity/upcoming_by_role') ?>">
            <div class="panel-group" id="accordion_role" role="tablist">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading_role">
                        <a data-toggle="collapse" data-parent="#accordion_role" href="#collapse_role">
                            <h4 class="panel-title">
                                Unfilled Opportunities By Role
                                <span class="collapse-indicator glyphicon glyphicon-chevron-down pull-right"></span>
                            </h4>
                        </a>
                    </div>
                    <div id="collapse_role" class="panel-collapse collapse in" role="tabpanel">
                        <div class="panel-body">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>        
        <div id="quickSignUpContainer" data-url="<?php echo base_url('opportunity/opportunities_for_quick_sign_up') ?>">
            <div class="panel-group" id="accordion_quick_sign_up" role="tablist">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading_quick_sign_up">
                        <a data-toggle="collapse" data-parent="#accordion_quick_sign_up" href="#collapse_quick_sign_up">
                            <h4 class="panel-title">
                                Opportunities You Can Sign Up For
                                <span class="collapse-indicator glyphicon glyphicon-chevron-down pull-right"></span>
                            </h4>
                        </a>
                    </div>
                    <div id="collapse_quick_sign_up" class="panel-collapse collapse in" role="tabpanel">
                        <div class="panel-body">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>        
</div>
<script src="<?php echo $this->config->item('base_uri') . 'js/opportunity.js?v=5' ?>"></script>
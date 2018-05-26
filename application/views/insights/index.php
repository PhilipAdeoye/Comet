<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-4" id="attendancefiltersContainer">
        <div class="panel-group" id="accordion_attendance_filters" role="tablist">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading_attendance_filters">
                    <a data-toggle="collapse" data-parent="#accordion_attendance_filters" href="#collapse_attendance_filters">
                        <h4 class="panel-title">
                            Attendance Summary
                            <span class="collapse-indicator glyphicon glyphicon-chevron-down pull-right"></span>
                        </h4>
                    </a>
                </div>
                <div id="collapse_attendance_filters" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <?php echo $attendance_filters ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8" id="attendanceSummaryContainer">
        <div class="panel-group" id="accordion_attendanceSummary" role="tablist">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading_attendanceSummary">
                    <a data-toggle="collapse" data-parent="#accordion_attendanceSummary" href="#collapse_attendanceSummary">
                        <h4 class="panel-title">
                            Attendance Results
                            <span class="collapse-indicator glyphicon glyphicon-chevron-down pull-right"></span>
                        </h4>
                    </a>
                </div>
                <div id="collapse_attendanceSummary" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo $this->config->item('base_uri') . 'js/insights.js' ?>"></script>
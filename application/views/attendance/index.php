<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-4" id="filtersContainer">
        <div class="panel-group" id="accordion_filters" role="tablist">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading_filters">
                    <a data-toggle="collapse" data-parent="#accordion_filters" href="#collapse_filters">
                        <h4 class="panel-title">
                            Filter Attendance
                            <span class="collapse-indicator glyphicon glyphicon-chevron-down pull-right"></span>
                        </h4>
                    </a>
                </div>
                <div id="collapse_filters" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <?php echo $filters ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8" id="rollCallContainer">
        <div class="panel-group" id="accordion_rollCall" role="tablist">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading_rollCall">
                    <a data-toggle="collapse" data-parent="#accordion_rollCall" href="#collapse_rollCall">
                        <h4 class="panel-title">
                            Roll Call
                            <span class="collapse-indicator glyphicon glyphicon-chevron-down pull-right"></span>
                        </h4>
                    </a>
                </div>
                <div id="collapse_rollCall" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body">
                        <div class="alert alert-info" role="alert">
                            Attendance Records matching your specified criteria appear here
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo $this->config->item('base_uri') . 'js/attendance.js?v=1' ?>"></script>
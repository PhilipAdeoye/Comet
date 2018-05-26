<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$this->load->view('message_board/create_modal');
$this->load->view('message_board/edit_modal');
?>
<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <a href="<?php echo base_url('opportunity') ?>" class="btn btn-success btn-block btn-lg">Sign Up!</a>
        <hr>
        <h4 class="text-center">Message Board</h4>
        <hr>
        <?php if ((int) $_SESSION['view_as_admin'] === 1): ?>
            <button class="btn btn-primary" id="addNewMessageBtn" data-url="<?php echo base_url('message_board/create') ?>">
                <span class="glyphicon glyphicon-plus"></span> Add New Message
            </button>
            <hr>
        <?php endif; ?>

        <?php if (count($years) === 0): ?>
            <h5 class="text-center">Looks like there's nothing to show here!</h5>
        <?php endif; ?>

        <div id="messageBoardContainer">
            <?php for ($i = 0, $count = count($years); $i < $count; $i++): ?>
                <?php $year = $years[$i]->year; ?>
                <?php if ($i === 0): ?>
                    <div class="panel-group" id="accordion_<?php echo $year; ?>" role="tablist">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading_<?php echo $year; ?>">
                                <a data-toggle="collapse" data-parent="#accordion_<?php echo $year; ?>" href="#collapse_<?php echo $year; ?>">
                                    <h4 class="panel-title">
                                        <?php echo $year; ?>
                                        <span class="collapse-indicator glyphicon glyphicon-chevron-down pull-right"></span>
                                    </h4>
                                </a>
                            </div>
                            <div id="collapse_<?php echo $year; ?>" class="panel-collapse collapse in" role="tabpanel" data-year="<?php echo $year; ?>">
                                <div class="panel-body message-panel-content">
                                    <?php echo $most_recent_messages; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="panel-group" id="accordion_<?php echo $year; ?>" role="tablist">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading_<?php echo $year; ?>">
                                <a data-toggle="collapse" data-parent="#accordion_<?php echo $year; ?>" href="#collapse_<?php echo $year; ?>">
                                    <h4 class="panel-title">
                                        <?php echo $year; ?>
                                        <span class="collapse-indicator glyphicon glyphicon-chevron-left pull-right"></span>
                                    </h4>
                                </a>
                            </div>
                            <div id="collapse_<?php echo $year; ?>"  class="panel-collapse collapse" role="tabpanel" 
                                 data-year="<?php echo $year; ?>" data-url="<?php echo base_url('message_board/get_messages_for_year') ?>">
                                <div class="panel-body message-panel-content">

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endfor; ?>
        </div>
    </div>
</div>
<script src="<?php echo $this->config->item('base_uri') . 'js/message_board.js?v=1'; ?>"></script>

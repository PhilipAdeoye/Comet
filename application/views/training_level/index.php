<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$this->load->view('training_level/create_modal');
$this->load->view('training_level/edit_modal');
$this->load->view('training_level/edit_abilities_modal');
?>

<div class="row">
    <div class="col-md-offset-1 col-md-10">
        <h4>Training Levels</h4>
        <hr>
        <button class="btn btn-primary" id="addNewTrainingLevelBtn" data-url="<?php echo base_url('training_level/create') ?>">
            <span class="glyphicon glyphicon-plus"></span> Add New Training Level
        </button>
        <hr>
        <div id="trainingLevelsContainer" data-url="<?php echo base_url('training_level/training_levels') ?>">

        </div>
    </div>
</div>
<script src="<?php echo $this->config->item("base_uri") . 'js/training_level.js?v=1' ?>"></script>





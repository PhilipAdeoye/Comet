<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$this->load->view('location/create_modal');
$this->load->view('location/edit_modal');
?>

<div class="row">
    <div class="col-md-offset-1 col-md-10">
        <h4>Locations</h4>
        <hr>
        <button class="btn btn-primary" id="addNewLocationBtn" data-url="<?php echo base_url('location/create') ?>">
            <span class="glyphicon glyphicon-plus"></span> Add New Location
        </button>
        <hr>
        <div id="locationsContainer" data-url="<?php echo base_url('location/locations') ?>">

        </div>
    </div>
</div>
<script src="<?php echo $this->config->item("base_uri") . 'js/location.js?v=1' ?>"></script>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$this->load->view('role/create_modal');
$this->load->view('role/edit_modal');
$this->load->view('role/edit_abilities_modal');
?>

<div class="row">
    <div class="col-md-offset-1 col-md-10">
        <h4>Roles</h4>
        <hr>
        <button class="btn btn-primary" id="addNewRoleBtn" data-url="<?php echo base_url('role/create') ?>">
            <span class="glyphicon glyphicon-plus"></span> Add New Role
        </button>
        <hr>
        <div id="rolesContainer" data-url="<?php echo base_url('role/roles') ?>">

        </div>
    </div>
</div>
<script src="<?php echo $this->config->item("base_uri") . 'js/role.js?v=2' ?>"></script>





<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$this->load->view('partner/create_modal');
$this->load->view('partner/edit_modal');
?>

<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <h4>Partners</h4>
        <hr>
        <button class="btn btn-primary" id="addNewPartnerBtn" data-url="<?php echo base_url('partner/create') ?>">
            <span class="glyphicon glyphicon-plus"></span> Add New Partner
        </button>
        <hr>
        <div id="partnersContainer" data-url="<?php echo base_url('partner/partners') ?>">

        </div>
    </div>
</div>
<script src="<?php echo $this->config->item("base_uri") . 'js/partner.js?v=1' ?>"></script>





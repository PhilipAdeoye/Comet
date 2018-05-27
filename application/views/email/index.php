<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php

$this->load->view('email/edit_modal');
?>

<div class="row">
    <div class="col-md-offset-1 col-md-10">
        <h4>Automated Email Manager</h4>
        <hr>
        <div id="emailsContainer" data-url="<?php echo base_url('email/emails') ?>">

        </div>
    </div>
</div>
<script src="<?php echo $this->config->item("base_uri") . 'js/email.js?v=4' ?>"></script>
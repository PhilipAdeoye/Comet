<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $this->load->view('opportunity/user_volunteer_record_modal') ?>
<?php $this->load->view('email/message_selected_people_modal') ?>

<?php if ($type === 'admins'): ?>
    <h4>Administrators</h4>
<?php else: ?>
    <h4>Users</h4>    
<?php endif; ?>
<hr>
<div id="<?php echo $type; ?>Container" data-url="<?php echo base_url('user/' . $type) ?>">
    <div class="jumbotron text-center">
        <h3>Loading...</h3>
        <p>This could take a minute</p>
    </div>
</div>






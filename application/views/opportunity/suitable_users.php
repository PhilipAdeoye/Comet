<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h5 class="text-center"><?php echo count($suitable_users) ?> Qualified People based on Training Level</h5>
<table class="table table-hover">
    <thead>
        <tr>            
            <th>Name</th>
            <th colspan="2">Training Level</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($suitable_users as $user): ?>
            <tr>
                <td><?php echo mailto($user->email, $user->last_name . ', ' . $user->first_name) ?>
                    <?php if ((int) $user->interpreter === 1): ?>
                    <img src="<?php echo $this->config->item('base_uri') . 'images/interpreter.png' ?>" alt="is an interpreter" class="left-gutter">
                    <?php endif; ?>
                </td>
                <td><?php echo $user->training_level ?></td>
                <td>
                    <button class="btn btn-default btn-sm commit-volunteer-btn pull-right"
                            title="Commit Volunteer"
                            data-opportunity-id="<?php echo $user->opportunity_id ?>"
                            data-user-id="<?php echo $user->user_id ?>"
                            data-url="<?php echo base_url('opportunity/schedule_user') ?>">
                        <span class="glyphicon glyphicon-plus" style="color:#2196f3"></span>                    
                    </button>

                </td>
            </tr>   
        <?php endforeach; ?>
    </tbody>
</table>
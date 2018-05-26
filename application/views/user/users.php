<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="alert alert-info">
    Presently showing users at your preferred location (<?php echo $location_name ?>). To see people at other locations,
    change your preferred location
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th class="no-search no-sort">Admin?</th>
            <th>Email</th>
            <th>Partner</th>
            <th>Training Level</th>
            <th>Graduation Year</th>
            <th>Available to Serve?</th>
            <th>Interpreter?</th>
            <th>Times Volunteered</th>
            <th class="no-search no-sort" style="width:50px"></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Name</th>
            <th class="no-search no-sort"></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th class="no-search no-sort"></th>
        </tr>
    </tfoot>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user->name ?></td>
                <td>
                    <?php if ((int) $user->admin === 1): ?>
                        <img src="<?php echo $this->config->item('base_uri') . 'images/admin_icon.png' ?>">
                    <?php endif; ?>
                </td>               
                <td><?php echo $user->email ?></td>            
                <td><?php echo $user->partner_name ?></td>
                <td><?php echo $user->training_level ?></td>
                <td><?php echo $user->estimated_graduation_year ?></td>
                <td><?php echo $user->available_to_serve ?></td>
                <td><?php echo $user->is_an_interpreter ?></td>
                <td><?php echo $user->times_volunteered ?></td>
                <td class="text-right"><div class="btn-group btn-group-sm">
                        <button class="btn btn-default edit-btn" title="Edit"  
                                data-id="<?php echo $user->id ?>"  
                                data-url="<?php echo base_url('user/edit') ?>">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button class="btn btn-default volunteer-record-btn" title="View Volunteer Record"  
                                data-id="<?php echo $user->id ?>"  
                                data-url="<?php echo base_url('opportunity/user_volunteer_record') ?>">
                            <span class="glyphicon glyphicon-list"></span>
                        </button>                    
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


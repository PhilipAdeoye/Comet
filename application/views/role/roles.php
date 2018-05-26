<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="visible-xs-block visible-sm-block">
    <?php for ($i = 0, $count = count($roles); $i < $count; $i++): ?>
        <?php $role = $roles[$i]; ?>
        <div class="row">
            <div class="col-xs-10 col-sm-11">
                <p class="listing"><b>Name</b>: <?php echo $role->description ?></p>
                <p class="listing"><b>Work Description</b>: <?php echo character_limiter($role->help_text, 50) ?></p>
                <p class="listing"><b>Email Text</b>: <?php echo character_limiter($role->email_text, 50) ?></p>
            </div>
            <div class="col-xs-2 col-sm-1">
                <div class="list-group vertical-btn-list pull-right">
                    <button class="list-group-item list-group-btn edit-btn" title="Edit"  
                            data-id="<?php echo $role->id ?>"  
                            data-url="<?php echo base_url('role/edit') ?>">
                        <span class="glyphicon glyphicon-pencil"></span>                            
                    </button>
                    <button class="list-group-item list-group-btn edit-abilities-btn" title="Change Abilities"  
                            data-id="<?php echo $role->id ?>"  
                            data-url="<?php echo base_url('role/abilities') ?>">
                        <span class="glyphicon glyphicon-list"></span>                            
                    </button>
                </div>
            </div>
        </div>
        <?php if ($i < $count - 1): ?>
            <hr>
        <?php endif; ?>
    <?php endfor; ?>
</div>
<div class="visible-md-block visible-lg-block">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Work Description</th>
                <th>Email Text</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($roles as $role): ?>
                <tr>
                    <td><?php echo $role->description ?></td>
                    <td><?php echo character_limiter($role->help_text, 50) ?></td>
                    <td><?php echo character_limiter($role->email_text, 50) ?></td>
                    <td class="text-right">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-default btn-sm edit-btn" title="Edit"  
                                    data-id="<?php echo $role->id ?>"  
                                    data-url="<?php echo base_url('role/edit') ?>">
                                <span class="glyphicon glyphicon-pencil" ></span>    
                            </button>
                            <button class="btn btn-default edit-abilities-btn" title="Change Abilities"  
                                    data-id="<?php echo $role->id ?>"  
                                    data-url="<?php echo base_url('role/abilities') ?>">
                                <span class="glyphicon glyphicon-list"></span>
                            </button> 
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>


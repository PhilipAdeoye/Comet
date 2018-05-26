<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="visible-xs-block visible-sm-block">
    <?php for ($i = 0, $count = count($training_levels); $i < $count; $i++): ?>
        <?php $level = $training_levels[$i]; ?>
        <div class="row">
            <div class="col-xs-10 col-sm-11">
                <p class="listing"><b>Name</b>: <?php echo $level->name ?></p>
                <p class="listing"><b>Partner</b>: <?php echo $level->partner_name ?></p>
                <p class="listing"><b>Email Text</b>: <?php echo character_limiter($level->email_text, 80) ?></p>
            </div>
            <div class="col-xs-2 col-sm-1">
                <div class="list-group vertical-btn-list pull-right">
                    <button class="list-group-item list-group-btn edit-btn" title="Edit"  
                            data-id="<?php echo $level->id ?>"  
                            data-url="<?php echo base_url('training_level/edit') ?>">
                        <span class="glyphicon glyphicon-pencil"></span>                            
                    </button>
                    <button class="list-group-item list-group-btn edit-abilities-btn" title="Change Abilities"  
                            data-id="<?php echo $level->id ?>"  
                            data-url="<?php echo base_url('training_level/abilities') ?>">
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
            <th>Partner</th>
            <th>Email Text</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($training_levels as $level): ?>
            <tr>
                <td><?php echo $level->name ?></td>
                <td><?php echo $level->partner_name ?></td>
                <td><?php echo character_limiter($level->email_text, 60) ?></td>
                <td class="text-right">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-default edit-btn" title="Edit"  
                                data-id="<?php echo $level->id ?>"  
                                data-url="<?php echo base_url('training_level/edit') ?>">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button class="btn btn-default edit-abilities-btn" title="Change Abilities"  
                                data-id="<?php echo $level->id ?>"  
                                data-url="<?php echo base_url('training_level/abilities') ?>">
                            <span class="glyphicon glyphicon-list"></span>
                        </button>                    
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

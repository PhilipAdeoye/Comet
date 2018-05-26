<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $user_is_admin = (int) $_SESSION['view_as_admin'] === 1 ?>

<?php if($user_is_admin): ?>
    <div class="row">
        <div class="col-sm-12 visible-xs">
            <button type="button" class="btn btn-default btn-block check-all-btn">Check All</button>
            <button type="button" class="btn btn-default btn-block uncheck-all-btn"> Uncheck All</button>
            <button type="button" class="btn btn-primary btn-block message-volunteers-btn">Message Selected Volunteers</button>
        </div>
        <div class="col-sm-12 hidden-xs">
            <button type="button" class="btn btn-default check-all-btn">Check All</button>
            <button type="button" class="btn btn-default uncheck-all-btn"> Uncheck All</button>
            <button type="button" class="btn btn-primary message-volunteers-btn">Message Selected Volunteers</button>
        </div>
    </div>
    <hr>
<?php endif; ?>

<div class="visible-xs-block visible-sm-block">
    <?php for ($i = 0, $count = count($opportunities); $i < $count; $i++): ?>
        <?php $opportunity = $opportunities[$i]; ?>
        <div class="row">
            <?php if ($user_is_admin): ?>
                <div class="col-xs-1 oppo-checkbox-container">
                    <input type="checkbox" class="oppo-checkbox"
                           <?php echo is_null($opportunity->user_id) ? 'disabled' : '' ?>
                           data-user-id="<?php echo $opportunity->user_id ?>"
                           data-email="<?php echo $opportunity->email ?>">
                </div>
            <?php endif; ?>
            <div class="<?php echo $user_is_admin ? 'col-xs-9 col-sm-10' : 'col-xs-10 col-sm-11' ?>">
                <p class="listing"><b>Location</b>: <?php echo $opportunity->location_name ?></p>
                <p class="listing">
                    <b>Role</b>: 
                    <span class="role-description-highlight cursor-pointer" 
                          data-role-id="<?php echo $opportunity->role_id ?>"                          
                          data-url="<?php echo base_url('role/get_by_id_as_JSON') ?>">
                              <?php echo $opportunity->role_description ?>
                    </span>
                </p>
                <p class="listing"><b>Time</b>: <?php echo $opportunity->start_time ?> &#8594; <?php echo $opportunity->end_time ?></p>
                <p class="listing"><b>Volunteer</b>: 
                    <?php if (!is_null($opportunity->user_id)): ?> 
                        <?php
                        echo mailto($opportunity->email, $opportunity->last_name . ', ' . $opportunity->first_name);
                        echo ' - ' . $opportunity->training_level;
                        ?>
                        <?php if ((int) $opportunity->interpreter === 1): ?>
                            <img src="<?php echo $this->config->item('base_uri') . 'images/interpreter.png' ?>" alt="is an interpreter" class="left-gutter">
                        <?php endif; ?>
                        <?php echo ' - ' . $opportunity->phone_number; ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-xs-2 col-sm-1">
                <?php if (is_null($opportunity->user_id)): ?>
                    <?php if ($user_is_admin): ?>
                        <div class="list-group vertical-btn-list pull-right">
                            <button class="list-group-item list-group-btn find-volunteer-btn" 
                                    title="Find Volunteer" 
                                    data-id="<?php echo $opportunity->id ?>"
                                    data-url="<?php echo base_url('opportunity/find_volunteer') ?>">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                            <button class="list-group-item list-group-btn sign-up-btn" 
                                    title="Sign Up!" 
                                    data-id="<?php echo $opportunity->id ?>">
                                <span class="glyphicon glyphicon-plus" style="color:#2196f3"></span>
                            </button>
                            <button class="list-group-item list-group-btn delete-btn" 
                                    title="Delete Opportunity" 
                                    data-id="<?php echo $opportunity->id ?>">
                                <span class="glyphicon glyphicon-remove"></span>                        
                            </button>
                        </div>
                    <?php elseif ((int) $opportunity->training_level_is_qualified === 1) : ?>
                        <div class="list-group vertical-btn-list pull-right">
                            <button class="list-group-item list-group-btn sign-up-btn"
                                    title="Sign Up!" 
                                    data-id="<?php echo $opportunity->id ?>">
                                <span class="glyphicon glyphicon-plus" style="color:#2196f3"></span>                        
                            </button>
                        </div>
                    <?php endif; ?>
                <?php elseif ($user_is_admin) : ?>
                    <div class="list-group vertical-btn-list pull-right">
                        <button class="list-group-item list-group-btn cancel-btn"
                                title="Unschedule Volunteer" 
                                data-id="<?php echo $opportunity->id ?>"
                                data-user-id="<?php echo $opportunity->user_id ?>">
                            <span class="glyphicon glyphicon-ban-circle" style="color:#e51c23"></span>
                        </button>
                    </div>
                <?php endif; ?>
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
                <?php if($user_is_admin): ?>
                <th></th>
                <?php endif; ?>
                <th>Location</th>
                <th>Role</th>
                <th>Time</th>
                <th>Volunteer</th>            
                <th></th>            
            </tr>
        </thead>
        <tbody>            
            <?php foreach ($opportunities as $opportunity) : ?>
                <tr>
                    <?php if($user_is_admin): ?>
                    <td>
                        <?php if(!is_null($opportunity->user_id)): ?>
                            <input type="checkbox" class="oppo-checkbox"                           
                               data-user-id="<?php echo $opportunity->user_id ?>"
                               data-email="<?php echo $opportunity->email ?>">
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                    <td><?php echo $opportunity->location_name ?></td>
                    <td>
                        <span class="role-description-highlight cursor-pointer" 
                              data-role-id="<?php echo $opportunity->role_id ?>"
                              data-url="<?php echo base_url('role/get_by_id_as_JSON') ?>">
                                  <?php echo $opportunity->role_description ?>
                        </span>
                    </td>
                    <td><?php echo $opportunity->start_time ?> &#8594; <?php echo $opportunity->end_time ?></td>
                    <td>
                        <?php if (!is_null($opportunity->user_id)): ?> 
                            <?php echo mailto($opportunity->email, $opportunity->last_name . ', ' . $opportunity->first_name) . ' - '; ?>
                            <span title="<?php echo $opportunity->training_level ?>">
                                <?php echo $user_is_admin ? ellipsize($opportunity->training_level, 15) : $opportunity->training_level; ?>
                            </span>
                            <?php if ((int) $opportunity->interpreter === 1): ?>
                                <img src="<?php echo $this->config->item('base_uri') . 'images/interpreter.png' ?>" alt="is an interpreter" class="left-gutter">
                            <?php endif; ?>
                            <?php echo ' - ' . $opportunity->phone_number; ?>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <?php if (is_null($opportunity->user_id)): ?>
                            <?php if ($user_is_admin): ?>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-default find-volunteer-btn" 
                                            title="Find Volunteer" 
                                            data-id="<?php echo $opportunity->id ?>"
                                            data-url="<?php echo base_url('opportunity/find_volunteer') ?>">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                    <button class="btn btn-default sign-up-btn" 
                                            title="Sign Up!" 
                                            data-id="<?php echo $opportunity->id ?>">
                                        <span class="glyphicon glyphicon-plus" style="color:#2196f3"></span>
                                    </button>
                                    <button class="btn btn-default delete-btn" 
                                            title="Delete Opportunity" 
                                            data-id="<?php echo $opportunity->id ?>">
                                        <span class="glyphicon glyphicon-remove"></span>                        
                                    </button>
                                </div>
                            <?php elseif ((int) $opportunity->training_level_is_qualified === 1) : ?>
                                <button class="btn btn-default btn-sm sign-up-btn"
                                        title="Sign Up!" 
                                        data-id="<?php echo $opportunity->id ?>">
                                    <span class="glyphicon glyphicon-plus" style="color:#2196f3"></span>                        
                                </button>
                            <?php endif; ?>
                        <?php elseif ($user_is_admin) : ?>
                            <button class="btn btn-default btn-sm cancel-btn"
                                    title="Unschedule Volunteer" 
                                    data-id="<?php echo $opportunity->id ?>"
                                    data-user-id="<?php echo $opportunity->user_id ?>">
                                <span class="glyphicon glyphicon-ban-circle" style="color:#e51c23"></span>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
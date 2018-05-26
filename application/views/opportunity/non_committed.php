<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (count($opportunities) > 0): ?>

<div class="visible-xs-block visible-sm-block">
    <?php for ($i = 0, $count = count($opportunities); $i < $count; $i++): ?>
        <?php $opportunity = $opportunities[$i]; ?>
        <div class="row">
            <div class="col-xs-10 col-sm-11">
                <p class="listing"><b>Date</b>: <?php echo $opportunity->date ?></p>
                <p class="listing">
                    <b>Role</b>: 
                    <span class="role-description-highlight" 
                          data-role-id="<?php echo $opportunity->role_id ?>"
                          data-url="<?php echo base_url('role/get_by_id_as_JSON') ?>">
                        <?php echo $opportunity->role_description ?>
                    </span>
                </p>
                <p class="listing"><b>Location</b>: <?php echo $opportunity->location_name ?></p>
                <p class="listing"><b>Time</b>: <?php echo $opportunity->start_time ?> &#8594; <?php echo $opportunity->end_time ?></p>

            </div>
            <div class="col-xs-2 col-sm-1">
                <div class="list-group vertical-btn-list pull-right">
                    <?php if ((int) $_SESSION['view_as_admin'] === 1): ?>
                        <button class="list-group-item list-group-btn find-volunteer-btn" 
                                title="Find Volunteer" 
                                data-id="<?php echo $opportunity->id ?>"
                                data-url="<?php echo base_url('opportunity/find_volunteer') ?>">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    <?php endif; ?>
                    <button class="list-group-item list-group-btn sign-up-btn" 
                            title="Sign Up!" 
                            data-id="<?php echo $opportunity->id ?>">
                        <span class="glyphicon glyphicon-plus" style="color:#2196f3"></span>
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
                <th>Date</th>
                <th>Role</th>
                <th>Location</th>
                <th>Time</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($opportunities as $opportunity) : ?>
                <tr>
                    <td><?php echo $opportunity->date ?></td>
                    <td>
                        <span class="role-description-highlight cursor-pointer" 
                            data-role-id="<?php echo $opportunity->role_id ?>"                              
                            data-url="<?php echo base_url('role/get_by_id_as_JSON') ?>">
                            <?php echo $opportunity->role_description ?>
                        </span>
                    </td>
                    <td><?php echo $opportunity->location_name ?></td>
                    <td><?php echo $opportunity->start_time ?> &#8594; <?php echo $opportunity->end_time ?></td>
                    <td class="text-right">
                        <div class="btn-group btn-group-sm">
                            <?php if ((int) $_SESSION['view_as_admin'] === 1): ?>
                                <button class="btn btn-default find-volunteer-btn"
                                        title="Find Volunteer" 
                                        data-id="<?php echo $opportunity->id ?>"
                                        data-url="<?php echo base_url('opportunity/find_volunteer') ?>">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            <?php endif; ?>   
                            <button class="btn btn-default sign-up-btn"
                                    title="Sign Up!" 
                                    data-id="<?php echo $opportunity->id ?>">
                                <span class="glyphicon glyphicon-plus" style="color:#2196f3"></span>                        
                            </button>     
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<?php else: ?>
    <p class="text-center">There are no opportunities to display</p>
<?php endif; ?>

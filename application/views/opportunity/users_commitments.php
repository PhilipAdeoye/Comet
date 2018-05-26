<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (count($commitments) === 0): ?>
    <p class="text-center">None. We would love you to sign up!</p>
<?php else: ?>
    <div class="visible-xs-block visible-sm-block">
        <?php for ($i = 0, $count = count($commitments); $i < $count; $i++): ?>
            <?php $commitment = $commitments[$i]; ?>
            <div class="row">
                <div class="col-xs-10 col-sm-11">
                    <p class="listing"><b>Date</b>: <?php echo $commitment->date ?></p>
                    <p class="listing">
                        <b>Role</b>:
                        <span class="role-description-highlight" 
                              data-role-id="<?php echo $commitment->role_id ?>"
                              data-url="<?php echo base_url('role/get_by_id_as_JSON') ?>">
                            <?php echo $commitment->role_description ?>
                        </span>
                    </p>
                    <p class="listing"><b>Location</b>: <?php echo $commitment->location_name ?></p>
                    <p class="listing"><b>Time</b>: <?php echo $commitment->start_time ?> &#8594; <?php echo $commitment->end_time ?></p>

                </div>
                <div class="col-xs-2 col-sm-1">
                    <?php if ((int) $commitment->can_be_cancelled === 1): ?>
                        <div class="list-group vertical-btn-list pull-right">
                            <button class="list-group-item list-group-btn cancel-btn"
                                    title="Cancel Commitment" 
                                    data-id="<?php echo $commitment->id ?>">
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
                    <th>Date</th>
                    <th>Role</th>
                    <th>Location</th>
                    <th>Time</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($commitments as $commitment) : ?>
                    <tr>
                        <td><?php echo $commitment->date ?></td>
                        <td>
                            <span class="role-description-highlight cursor-pointer" 
                                  data-role-id="<?php echo $commitment->role_id ?>"
                                  data-url="<?php echo base_url('role/get_by_id_as_JSON') ?>">
                                <?php echo $commitment->role_description ?>
                            </span>
                        </td>
                        <td><?php echo $commitment->location_name ?></td>
                        <td><?php echo $commitment->start_time ?> &#8594; <?php echo $commitment->end_time ?></td>
                        <td class="text-right">
                            <?php if ((int) $commitment->can_be_cancelled === 1): ?>
                                <button class="btn btn-default btn-sm cancel-btn"
                                        title="Cancel Commitment" 
                                        data-id="<?php echo $commitment->id ?>">
                                    <span class="glyphicon glyphicon-ban-circle" style="color:#e51c23"></span>                            
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
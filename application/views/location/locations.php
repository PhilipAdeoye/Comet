<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="visible-xs-block visible-sm-block">
    <?php for ($i = 0, $count = count($locations); $i < $count; $i++): ?>
        <?php $location = $locations[$i]; ?>
        <div class="row">
            <div class="col-xs-10 col-sm-11">
                <p class="listing"><b>Name</b>: <?php echo $location->name ?></p>
                <p class="listing"><b>Address</b>: <?php echo $location->address ?></p>
                <p class="listing"><b>Email Text</b>: <?php echo character_limiter($location->email_text, 50) ?></p>
                <p class="listing"><b>Uses Release Form?</b>: <?php echo $location->uses_media_release_form ? "Yes" : "No" ?></p>
            </div>
            <div class="col-xs-2 col-sm-1">
                <div class="list-group vertical-btn-list pull-right">
                    <button class="list-group-item list-group-btn edit-btn" title="Edit"  
                            data-id="<?php echo $location->id ?>"  
                            data-url="<?php echo base_url('location/edit') ?>">
                        <span class="glyphicon glyphicon-pencil"></span>                            
                    </button>
                    <a class="list-group-item list-group-btn" title="View Release Form Responses" target="_blank"
                       href="<?php echo base_url('location/media_release_form_responses') . '?id=' . $location->id ?>">
                        <span class="glyphicon glyphicon-eye-open"></span>                            
                    </a>
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
                <th>Address</th>
                <th>Email Text</th>
                <th>Release Form?</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($locations as $location): ?>
                <tr>
                    <td><?php echo $location->name ?></td>
                    <td><?php echo $location->address ?></td>
                    <td><?php echo character_limiter($location->email_text, 50) ?></td>
                    <td><?php echo $location->uses_media_release_form ? "Yes" : "No" ?></td>
                    <td class="text-right">
                        <button class="btn btn-default btn-sm edit-btn" title="Edit"  
                                data-id="<?php echo $location->id ?>"  
                                data-url="<?php echo base_url('location/edit') ?>">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <a class="btn btn-default btn-sm edit-btn" title="View Release Form Responses"  target="_blank" 
                            href="<?php echo base_url('location/media_release_form_responses') . '?id=' . $location->id ?>">
                            <span class="glyphicon glyphicon-eye-open"></span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>


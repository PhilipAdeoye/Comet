<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="visible-xs-block visible-sm-block">
    <?php for ($i = 0, $count = count($emails); $i < $count; $i++): ?>
        <?php $email = $emails[$i]; ?>
        <div class="row">
            <div class="col-xs-10 col-sm-11">
                <p class="listing"><b>Description</b>: <?php echo $email->description ?></p>
                <p class="listing"><b>Subject</b>: <?php echo $email->subject ?></p>
                <p class="listing"><b>Body Template</b>: <?php echo character_limiter($email->message, 50) ?></p>                
            </div>
            <div class="col-xs-2 col-sm-1">
                <div class="list-group vertical-btn-list pull-right">
                    <button class="list-group-item list-group-btn edit-btn" title="Edit"  
                            data-id="<?php echo $email->id ?>"  
                            data-url="<?php echo base_url('email/edit') ?>"
                            data-placeholders="<?php echo $email->placeholders ?>">
                        <span class="glyphicon glyphicon-pencil"></span>                            
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
                <th>Description</th>
                <th>Subject</th>
                <th>Body Template</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($emails as $email): ?>
                <tr>
                    <td><?php echo $email->description ?></td>
                    <td><?php echo $email->subject ?></td>
                    <td><?php echo character_limiter($email->message, 50) ?></td>
                    <td class="text-right">
                        <button class="btn btn-default btn-sm edit-btn" title="Edit"  
                                data-id="<?php echo $email->id ?>"  
                                data-url="<?php echo base_url('email/edit') ?>"
                                data-placeholders="<?php echo $email->placeholders ?>">
                            <span class="glyphicon glyphicon-pencil" ></span>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>


<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($partners as $partner): ?>
            <tr>
                <td><?php echo $partner->name ?></td>
                <td class="text-right">
                    <button class="btn btn-default btn-sm edit-btn" title="Edit"  
                            data-id="<?php echo $partner->id ?>"  
                            data-url="<?php echo base_url('partner/edit') ?>">
                        <span class="glyphicon glyphicon-pencil"></span>    
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


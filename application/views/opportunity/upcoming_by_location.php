<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (count($opportunities) === 0): ?>
    <p class="text-center">There are no opportunities to display</p>
<?php else: ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Location</th>
                <th colspan="2">Spots Available</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($opportunities as $opportunity) : ?>
                <tr>                    
                    <td><?php echo $opportunity->location_name ?></td> 
                    <td><?php echo $opportunity->num_spots ?></td>
                    <td>
                        <button class="btn btn-default btn-sm pull-right select-btn" 
                                title="View"
                                data-id="<?php echo $opportunity->location_id ?>"
                                data-url="<?php echo base_url('opportunity/opportunities_for_location') ?>">
                            <span class="glyphicon glyphicon-eye-open"></span>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
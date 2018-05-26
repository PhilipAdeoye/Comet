<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div style="min-width:992px">
    <h3><?php echo $date ?> - Please Sign In</h3>
    <button type="button" class="btn btn-default pull-right hidden-print" onclick="window.print();">
        <span class="glyphicon glyphicon-print right-gutter"></span> Print
    </button>
    <h4><?php echo ($partner_name === 'All Partners' ? $partner_name . ' - ' : '') . $location_name ?></h4>
    <hr>

    <table class="table">
        <thead>
            <tr>
                <th style="width:5%"></th>
                <th style="width:30%">Name</th>
                <th>Role</th>
                <th style="width:35%">Signature</th>
            </tr>
        </thead>
        <tbody>
            <?php $present_partner_name = '' ?>
            
            <?php for ($i = 0, $count = count($records); $i < $count; $i++): ?>
                <?php $person = $records[$i]; ?>
                
                <?php if ($present_partner_name !== $person->partner_name): ?>
                <tr>
                    <td colspan="4"><h3><?php echo $person->partner_name ?></h3></td>
                </tr>
                <?php endif; ?>
                <?php $present_partner_name = $person->partner_name ?>
                
                <tr>
                    <td style="font-size:1.7em; padding-top: 3px; padding-bottom: 3px;"><?php echo $i + 1 ?></td>
                    <td style="font-size:1.7em; padding-top: 3px; padding-bottom: 3px;">
                        <?php echo $person->first_name ?> <strong><?php echo $person->last_name ?></strong>
                    </td>
                    <td style="vertical-align:middle; padding-top: 3px; padding-bottom: 3px;"><?php echo $person->role_description ?></td>
                    <td style="padding-top: 3px; padding-bottom: 3px;"></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>
<script>
    window.print();
</script>
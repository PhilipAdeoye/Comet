<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h6><strong><?php echo count($records) ?></strong> total opportunities volunteered at</h6>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Date</th>
            <th>Role</th>
            <th>Location</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $record): ?>
            <tr>
                <td><?php echo $record->date ?></td>
                <td><?php echo $record->role ?></td>            
                <td><?php echo $record->location ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (count($records) === 0): ?>
            <tr>
                <td colspan="3">There are no records to display</td>
            </tr>            
        <?php endif; ?>
    </tbody>
</table>



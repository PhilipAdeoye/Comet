<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h3><?php echo $location_name ?> - Media Release Form Responses</h3>

<hr>

<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Response</th>
            <th>Responded On</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($responses as $response): ?>
        <tr>
            <td><?php echo $response->full_name ?></td>
            <td><?php echo $response->response ?></td>
            <td><?php echo $response->responded_on ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
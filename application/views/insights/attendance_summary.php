<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $validation_errors = validation_errors(); ?>
<?php if (strlen($validation_errors) > 0): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Please correct the following errors</strong>
        <ul><?php echo $validation_errors ?></ul>
    </div>
<?php else: ?>
    <table class="table table-hover" id="volunteerCountsTable">
        <thead>
            <tr>                              
                <th>Partner</th>
                <th>Date</th>  
                <th># of Volunteers</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>                
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($volunteer_counts_for_clinic_days as $day): ?>
                <tr>
                    <td><?php echo $day->partner ?></td>
                    <td><?php echo $day->oppo_date ?></td>
                    <td><?php echo $day->volunteer_count ?></td>                
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <table class="table table-hover" id="volunteerHoursTable">
        <thead>
            <tr>
                <th>Partner</th>                                
                <th>Volunteer</th>
                <th>Times Volunteered</th>
                <th>Hours Volunteered</th>                
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($volunteer_hours_summary as $volunteer): ?>
                <tr>
                    <td><?php echo $volunteer->partner ?></td>
                    <td><?php echo $volunteer->full_name ?></td>
                    <td><?php echo $volunteer->times_volunteered ?></td>                
                    <td><?php echo $volunteer->hours_volunteered ?></td>   
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


<?php endif; ?>


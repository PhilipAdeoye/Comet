<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (count($dates) === 0): ?>
    <p class="text-center">Normally, there would be a calendar here, 
        but there are no opportunities to display, so there isn't one</p>
<?php else: ?>
    <?php 
    $all_dates = array();
    foreach ($dates as $date) {
        $all_dates[] = $date->date;
    } ?>
    <input type="text" id="oppoCalendar" style="display:none" 
           data-dates='<?php echo json_encode($all_dates, JSON_HEX_APOS) ?>'
           data-url='<?php echo base_url('opportunity/oppo_by_date') ?>'>
<?php endif; ?>
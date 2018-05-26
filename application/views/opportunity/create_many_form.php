<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="form-group required">
    <?php echo form_label('Date', 'oppoDate', array('class' => 'col-sm-3 control-label')) ?>
    <div class="col-sm-8">
        <?php if ($this->agent->is_mobile()): ?>
            <input type="date" name="date" value="<?php echo set_value('date') ?>" class="form-control" id="oppoDate">
        <?php else: ?>
            <input type="text" name="date" value="<?php echo set_value('date') ?>" class="form-control" id="oppoDate">
        <?php endif; ?>
        <?php echo form_error('date') ?>
    </div>
</div>
<div class="form-group required">
    <?php echo form_label('Location', 'location', array('class' => 'col-sm-3 control-label')) ?>        
    <?php
    $places = array('0' => 'Select Location');
    foreach ($locations as $row) {
        $places[$row->id] = $row->name;
    }
    ?>
    <div class="col-sm-8">
        <?php echo form_dropdown('location_id', $places, set_value('location_id'), array('class' => 'form-control')) ?>
        <?php echo form_error('location_id') ?>
    </div>
</div>
<?php
$offices = array('0' => 'Select Role');
foreach ($roles as $row) {
    $offices[$row->id] = $row->description;
}
?>

<?php
if (null !== ($oppos)) {
    $keys = array_keys($oppos);
    for ($i = 0, $key = $keys[$i], $count = count($keys); $i < $count; $i++, $key = $i < $count ? $keys[$i] : 0) {
        echo $this->load->view('opportunity/create_many_oppo_box', array(
            'offices' => $offices,
            'idx' => $key
            ), TRUE);
    }
} else {
    echo $this->load->view('opportunity/create_many_oppo_box', array(
        'offices' => $offices,
        'idx' => 0
        ), TRUE);
}
?>

<?php if ($this->agent->is_mobile()): ?>
    <script>
        $(document).ready(function () {
            $(".oppo-box").each(function () {
                var startTimePicker = $(this).find(".start-time");
                var endTimePicker = $(this).find(".end-time");

                startTimePicker.on("change", function (e) {
                    endTimePicker.prop("min", $(this).val());
                });
                endTimePicker.on("change", function (e) {
                    startTimePicker.prop("max", $(this).val());
                });
            });

        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function () {
            $("#oppoDate").datetimepicker({
                format: "MM/DD/YYYY"
            });

            $(".oppo-box").each(function () {
                var startTimePicker = $(this).find(".start-time");
                var endTimePicker = $(this).find(".end-time");

                startTimePicker.datetimepicker({
                    format: "H:mm",
                    defaultDate: moment("9 00", "H mm")
                });

                endTimePicker.datetimepicker({
                    format: "H:mm",
                    defaultDate: moment("14 00", "H mm")
                });

                // Link the two time pickers such that the end time always comes after the start time
                startTimePicker.on("dp.change", function (e) {
                    endTimePicker.data("DateTimePicker").minDate(e.date);
                });
                endTimePicker.on("dp.change", function (e) {
                    startTimePicker.data("DateTimePicker").maxDate(e.date);
                });                
            });
            
            $("#opportunityCreateModal .modal-body").css("min-height", 460);
        });
    </script>
<?php endif; ?>
<script>
    $(document).ready(function () {
        // Other non-platform specific javascript
    });
</script>
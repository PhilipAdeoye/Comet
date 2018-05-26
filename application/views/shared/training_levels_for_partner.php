<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$levels = array('0' => 'Select Level');
foreach ($training_levels as $row) {
    $levels[$row->id] = $row->name;
}
?>
<?php echo form_dropdown('training_level_id', $levels, set_value('training_level_id'), array('class' => 'form-control', 'id' => 'trainingLevel')) ?>
  
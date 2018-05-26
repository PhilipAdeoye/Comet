<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row" style="display:flex; flex-wrap: wrap;">
    <?php foreach ($tuples as $tuple): ?>    
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="form-group">
            <div class="col-xs-12">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" <?php echo (int)$tuple->has_ability === 1 ? 'checked' : ''?>
                               value="<?php echo $tuple->training_level_id ?>">
                        <?php echo $tuple->partner_training_level ?>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
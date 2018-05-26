<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php for ($i = 0, $count = count($messages); $i < $count; $i++): ?>
    <?php $message = $messages[$i]; ?>
    <div class="row">
        <div class="<?php echo (int)$_SESSION['view_as_admin'] === 1 ? 'col-xs-10 col-sm-11' : 'col-xs-12' ?>">
            <h5>
                <?php echo $message->title ?>
            </h5>
        </div>
        <?php if ((int) $_SESSION['view_as_admin'] === 1): ?>
        <div class="col-xs-2 col-sm-1">
            <button class="btn btn-default pull-right edit-btn" title="Edit message" 
                    data-id="<?php echo $message->id ?>"
                    data-url="<?php echo base_url('message_board/edit') ?>">
                <span class="glyphicon glyphicon-pencil" ></span>
            </button>
        </div>
        <?php endif; ?>
    </div>

    

    

    <p><?php echo $message->message; ?></p>
    <p>
        <span class="text-muted">Last Updated on</span> <?php echo explode(' ', $message->modified_on)[0]; ?> 
        <span class="text-muted">by</span> <?php echo $message->full_name; ?>
    </p>
    <?php if ($i < $count - 1): ?>
        <hr>
    <?php else: ?>
        <br>
    <?php endif; ?>
<?php endfor; ?>





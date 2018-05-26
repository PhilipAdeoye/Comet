<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<footer class="footer">
    <div class="container">
        <p class="privacy-p">
            <?php if (isset($_SESSION['user']->id)): ?>                 
                <script src="<?php echo $this->config->item('base_uri').'js/user.js?v=2' ?>"></script>
            <?php endif; ?>            
        </p>
    </div>
</footer>
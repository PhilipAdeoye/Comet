<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $page_title ?></title>

        <link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_uri') . 'css/third.party.combined.css?v=2'; ?>" >
        <link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_uri') . 'css/site.css?v=5'; ?>" >

        <script src="<?php echo $this->config->item('base_uri') . 'js/third.party.combined.js?v=2'; ?>"></script>
        <script src="<?php echo $this->config->item('base_uri') . 'js/site.js?v=2'; ?>"></script>  
    </head>
    <body>
        <div id="uiActivityIndicator"></div>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo base_url() ?>">
                        <span class="glyphicon glyphicon-home" style="margin-right: 15px"></span>
                        <?php if (!isset($_SESSION['user']->id)): ?>
                        <span><?php echo $this->config->item('clinic_name_abbr') ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="navbar-collapse"> 
                    <?php $this->load->view('shared/navbar_menu') ?>
                </div>
            </div>
        </nav>
        <div class="<?php echo isset($container_class) ? $container_class : 'container' ?>" >
            <?php
            if (isset($_SESSION['user']->id)) {
                $this->load->view('user/edit_modal');
            }
            // Load the main view
            echo $main;

            // Request Verification token for ajax POST calls that aren't for forms created with the form_open() helper 
            $csrf = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            ?>
            <input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>" />
        </div>
        <?php $this->load->view('shared/footer') ?>

    </body>
</html>

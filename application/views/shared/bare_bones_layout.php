<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $page_title ?> </title>
        
        <link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_uri') . 'css/bootstrap-paper.css'; ?>" >
        <link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('base_uri') . 'css/site.css?v=4'; ?>" >
        <script src="<?php echo $this->config->item('base_uri') . 'js/jquery-2.1.4.min.js' ?>"></script>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?php echo base_url() ?>">
                        <span class="glyphicon glyphicon-home" style="margin-right: 15px"></span>
                        <span><?php echo $this->config->item('clinic_name_abbr') ?></span>
                    </a>
                </div>
            </div>
        </nav>
        <div class="container">
            <?php echo $main ?>
        </div>
    </body>
</html>

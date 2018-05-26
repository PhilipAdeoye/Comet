<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $this->config->item('clinic_name_abbr') ?> | Frontiers </title>
        
        <link rel="stylesheet" type="text/css" href="<?= $this->config->item('base_uri') . 'vendor/css/bootstrap-paper.css'; ?>" >
    </head>
    <body>
        <div class="container">
            <h2>We haven't built this part yet</h2>
            <br>
            <button class='btn btn-default' onclick="window.history.back();">Click me to go back</button>
        </div>
    </body>
</html>

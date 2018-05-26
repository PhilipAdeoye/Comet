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
        <style>
            body { margin: 0px }
        </style>
    </head>
    <body>
        <div class="container">
            <?php echo $main ?>
        </div>
    </body>
</html>

<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (isset($_SESSION['user']->id)): ?>
    <ul class="nav navbar-nav">

        <?php $current_url = current_url();?>

        <?php if ((int) $_SESSION['view_as_admin'] === 1): ?>
            <?php
            $active_menu = '';
            if (strpos($current_url, base_url('user')) === 0) {
                $active_menu = 'People';
            }
            else if (strpos($current_url, base_url('opportunity')) === 0) {
                $active_menu = 'Opportunities';
            }
            else if (strpos($current_url, base_url('role')) === 0
                || strpos($current_url, base_url('location')) === 0
                || strpos($current_url, base_url('training_level')) === 0
                || strpos($current_url, base_url('partner')) === 0
                || strpos($current_url, base_url('message_board')) === 0
                || strpos($current_url, base_url('email')) === 0) {
                $active_menu = 'Manage';
            } else if (strpos($current_url, base_url('attendance')) === 0
                || strpos($current_url, base_url('insights')) === 0) {
                $active_menu = 'More';
            }
            ?>
            <li class="dropdown <?php echo $active_menu === 'People' ? 'active' : ''; ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">People <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="<?php echo base_url('user/index?type=users') ?>">Users</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('user/index?type=admins') ?>">Administrators</a>
                    </li>
                </ul>
            </li>
            
            <li class="<?php echo $active_menu === 'Opportunities' ? 'active' : ''; ?>">                
                <a href="<?php echo base_url('opportunity') ?>">Opportunities</a>
            </li>
            
            <li class="dropdown <?php echo $active_menu === 'Manage' ? 'active' : ''; ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Manage <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">               
                    <li class="<?php echo strpos($current_url, base_url('message_board')) === 0 ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('message_board') ?>">Message Board</a>
                    </li>
                    <li class="<?php echo strpos($current_url, base_url('training_level')) === 0 ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('training_level') ?>">Training Levels</a>
                    </li>                       
                    <li class="<?php echo strpos($current_url, base_url('role')) === 0 ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('role') ?>">Roles</a>
                    </li>                    
                    <li class="<?php echo strpos($current_url, base_url('location')) === 0 ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('location') ?>">Locations</a>
                    </li>
                    <li class="<?php echo strpos($current_url, base_url('partner')) === 0 ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('partner') ?>">Partners</a>
                    </li>                        
                    <li class="<?php echo strpos($current_url, base_url('email')) === 0 ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('email') ?>">Automated Emails</a>
                    </li>
                </ul>
            </li>

            <li class="dropdown <?php echo $active_menu === 'More' ? 'active' : ''; ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">More... <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li class="<?php echo strpos($current_url, base_url('attendance')) === 0 ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('attendance') ?>">Attendance</a>
                    </li>
                    <li class="<?php echo strpos($current_url, base_url('insights')) === 0 ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('insights') ?>">Insights</a>
                    </li>
                </ul>
            </li>
        <?php else: ?>            
            <li class="<?php echo strpos($current_url, base_url('message_board')) === 0 ? 'active' : ''; ?>">
                <a href="<?php echo base_url('message_board') ?>">Message Board</a>
            </li>
        <?php endif; ?>
            
        <?php if ((int) $_SESSION['user']->admin === 1): ?>
            <li>
                <a class="cursor-pointer" id="toggleAdminViewMode" data-url="<?php echo base_url('welcome/toggle_admin_view_mode') ?>">
                    <span class="visible-sm">
                        <?php echo (int) $_SESSION['view_as_admin'] === 1 ? 'User Mode' : 'I want to be an Admin again' ?>
                    </span>
                    <span class="hidden-sm">
                        <?php echo (int) $_SESSION['view_as_admin'] === 1 ? 'Switch to User Mode' : 'I want to be an Admin again' ?>
                    </span>
                    
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <ul class="nav navbar-nav navbar-right">
        <li>   
            <a class="cursor-pointer" id="userEditBtn" title="Account Details" data-id="<?php echo $_SESSION['user']->id; ?>"
               data-url="<?php echo base_url('user/edit') ?>">Hi <?php echo $_SESSION['user']->first_name ?>!&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="glyphicon glyphicon-user"></span>
            </a>
        </li>
        <li>   
            <?php echo anchor('account/logout', 'Logout') ?>
        </li>
    </ul>    
<?php endif; ?>
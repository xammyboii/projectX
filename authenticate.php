<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Script that prevents non-admin users to do CUD tasks.
 */
    require('connect.php');

    // If the current logged-in user is not an admin, then redirect to index.php
    if (!isset($_SESSION['active']) || ($_SESSION['admin_access'] != 1)) {

        header("Location: index.php");
        exit();
    }

?>
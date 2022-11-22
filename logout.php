<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page that logs out the user and returns to index.
 */
    session_start();

    session_destroy();

    header("Location: index.php");
?>
<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Script to connect to the database.
 */
    session_start();

    // Database Connection
    define('DB_DSN','mysql:host=localhost;dbname=serverside;charset=utf8');
    define('DB_USER','serveruser');
    define('DB_PASS','gorgonzola7!');

    try{
    	$db = new PDO(DB_DSN, DB_USER, DB_PASS);
    } catch(PDOException $e){
    	print "Error: " . $e->getMessage();
    	die(); // Forced execution
    }
?>
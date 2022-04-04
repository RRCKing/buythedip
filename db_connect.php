<?php
    define('DB_DSN','mysql:host=localhost;dbname=buythedip;charset=utf8');
    define('DB_USER','serveruser');
    define('DB_PASS','gorgonzola7!');

    try{
        // Try creating new PDO connection to MYSQL.
        $db = new PDO(DB_DSN, DB_USER, DB_PASS);
        //, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    } catch (PDOException $e) {
        print "Error: " . $e->getMessage();
        die(); // Force execution to production you should handle this
        // When deploying to production you should handle this
        // situation more gracefully.
    }
?>
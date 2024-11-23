<?php
// This file will connect to the database 

$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

    $pdo->exec("CREATE DATABASE IF NOT EXISTS purchases_db");
    echo "Database 'purchases_db' created successfully (if it didn't exist already).<br>";

    // Switch to the 'purchases_db' database
    $pdo->exec("USE purchases_db");
    // Add `purchases` table
    $createTableSql = "
    CREATE TABLE IF NOT EXISTS `purchases` (
        `item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `username` varchar(15) NOT NULL,
        `item_name` varchar(256) NOT NULL,
        `item_price` double NOT NULL,
        `item_type` varchar(256) NOT NULL,
        `link` varchar(256) DEFAULT NULL COMMENT 'This category is optional',
        PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";

    $pdo->exec($createTableSql);
    echo "Table 'purchases' created successfully.<br>";

    //add users table
    $createTableSql = "
CREATE TABLE IF NOT EXISTS `users` (
    `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(15) NOT NULL,
    `password` varchar(64) NOT NULL,
    PRIMARY KEY (`user_id`, `username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

    $pdo->exec($createTableSql);
    echo "Table 'users' created successfully.<br>";

    $dbname = $pdo->query('SELECT database()')->fetchColumn();  
    echo "Connected to the database: " . $dbname."<br>"; 

} 
catch (PDOException $e) {
    // Handle any exceptions (e.g., connection failure)
    echo "Error!: " . $e->getMessage() . "<br>";
    die();  // Exit the script if the connection fails
}

<?php
// This file will connect to the database to store information and retrieve data when it is time to create a budget analysis report.
include_once "pdo_connect.php";
/*
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
    $createTableSql ="
    CREATE TABLE `users` (
        `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `username` varchar(15) NOT NULL,
        `password` varchar(64) NOT NULL,
        PRIMARY KEY (`user_id`,`username`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

    $pdo->exec($createTableSql);
    echo "Table 'users' created successfully.<br>";

    $dbname = $pdo->query('SELECT database()')->fetchColumn();  
    echo "Connected to the database: " . $dbname; 

} 
catch (PDOException $e) {
    // Handle any exceptions (e.g., connection failure)
    echo "Error!: " . $e->getMessage() . "<br>";
    die();  // Exit the script if the connection fails
}

*/

function displayItemPrices($pdo) {
    try {
        $sql = "SELECT item_price FROM purchase";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) > 0) {
            echo "Item Prices:<br>";
            foreach ($rows as $item) {
                // Display the item price for each row
                echo "Price: " . $item['item_price'] . "<br>";
            }
        } 
        else {
            echo "No data found in the 'purchase' table.<br>";
        }
    } 
    catch (PDOException $e) {
        echo "Error fetching item prices: " . $e->getMessage() . "<br>";
    }
}


// insertNewPurchase() function that includes link
function insertNewPurchase_Link($pdo, $name, $price, $type, $link, $username) {
    try {
        $insertItemSql = "
        INSERT INTO purchase (item_name, item_price, item_type, link, username)
        VALUES (:name, :price, :type, :link, :username)";
        
        $stmt = $pdo->prepare($insertItemSql);

        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':link', $link, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        echo "New purchase added successfully.<br>";

    } 
    catch (PDOException $e) {
        echo "Error inserting new purchase: " . $e->getMessage() . "<br>";
    }
}


// insertNewPurchase() function without link
function insertNewPurchase_NoLink($pdo, $name, $price, $type, $username) {
    try {
        // The 'link' field will default to NULL when not included
        $insertItemSql = "
        INSERT INTO purchase (item_name, item_price, item_type, username) 
        VALUES (:name, :price, :type, :username)";
        
        $stmt = $pdo->prepare($insertItemSql);

        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        
        $stmt->execute();

        echo "New purchase added successfully.<br>";

    } 
    catch (PDOException $e) {
        echo "Error inserting new purchase: " . $e->getMessage() . "<br>";
    }
}


// Need a way to calculate the value of all item_price and check against budget threshold
// before giving a good or bad rating depeending on severity of over budgeting /
// remainder of total funds available.
function analyzeBudget($pdo, $budget) {
    try {
        // Calculate the total value of all item prices
        $sql = "SELECT SUM(item_price) AS total_price FROM purchases";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $totalPrice = $result['total_price'] ?? 0; // Default to 0 if no items are found

        echo "Total Spent: $" . $totalPrice . "<br>";
        echo "Budget Threshold: $" . $budget . "<br>";

        // Compare total price with budget
        if ($totalPrice > $budget) {
            $overBudget = $totalPrice - $budget;
            echo "You are over budget by $" . $overBudget . ". Consider reducing expenses.<br>";
            if ($overBudget > $budget) { 
                echo "Warning: Overspending detected.<br>";
            }
        } 
        elseif ($totalPrice == $budget) {
            echo "You are exactly on budget. Good job managing your expenses!<br>";
        } 
        else {
            $remainingBudget = $budget - $totalPrice;
            echo "You are under budget by $" . $remainingBudget . ". Keep up the good work!<br>";
        }
    } catch (PDOException $e) {
        echo "Error analyzing budget: " . $e->getMessage() . "<br>";
    }
}
/**
 * basically, it inserts a new user using username and password. user_id is auto increment so we dont have to fill that field out (currently debating if we even need
 * user_id. could instead make username the sole primary key and make usernames unique among users)
 */
function insertNewUser($pdo, $username, $password){
    try{
        $sql = "INSERT INTO `users` (username, password) VALUES (:username, :password)";
        $parameters = [":username" => $username, ":password" => md5($password)];

        $statement= $pdo->prepare($sql);
        $statement->execute($parameters);
    }
    catch (PDOException $e) {
        echo "Error inserting new user: " . $e->getMessage() . "<br>";
    }
       
}
/**
 * basically, this takes a username and password and checks if a matching combination of username + password exists in the db. current implementation might not work
 */
function validateCredentials($pdo, $username, $password){
    try{
        $sql = "SELECT password FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $dbpw = ($stmt->execute()); //i have no idea if this will even work

        if(md5($password)==$dbpw){
            return true;
        }
        return false;
    }catch (PDOException $e) {
        echo "Error logging in: " . $e->getMessage() . "<br>";
        return false;
    }


}

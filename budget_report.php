<?php 
// This file will connect to the database to store information and retrieve data when it is time to create a budget analysis report.


include_once "pdo_connect.php";

function displayItemPrices($pdo) {
    try {
        $sql = "SELECT item_name, item_price FROM purchases";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) > 0) {
            echo "Item Prices:<br>";
            foreach ($rows as $item) {
                // Display the item price for each row
                
                echo "Item: ".$item['item_name']."  "."Price: " . $item['item_price'] . "<br>";
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
        INSERT INTO purchases (item_name, item_price, item_type, link, username)
        VALUES (:name, :price, :type, :link, :username)";
        
        $stmt = $pdo->prepare($insertItemSql);

        $stmt->bindParam(':item_name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':item_price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':item_type', $type, PDO::PARAM_STR);
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
        INSERT INTO purchases (item_name, item_price, item_type, username) 
        VALUES (:item_name, :item_price, :item_type, :username)";
        
        $stmt = $pdo->prepare($insertItemSql);

        $stmt->bindParam(':item_name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':item_price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':item_type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        
        $stmt->execute();

        echo "New purchase added successfully.<br>";

    } 
    catch (PDOException $e) {
        echo "Error inserting new purchase: " . $e->getMessage() . "<br>";
    }
}


function clearPurchasesTable($pdo) {
    try {
        $sql = "DELETE FROM purchases";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        echo "All items have been removed from the purchases table.<br>";
        if (isset($_SESSION['finalized_purchases'])) {
            $_SESSION['finalized_purchases'] = [];
        } 
    } catch (PDOException $e) {
        echo "Error clearing purchases table: " . $e->getMessage() . "<br>";
    }
}

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
        $pdo = new PDO("mysql:host=localhost", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec("USE purchases_db");

        $sql = "INSERT INTO `users` (username, password) VALUES (:username, :password)";
        $parameters = [":username" => $username, ":password" => md5($password)];

        $statement= $pdo->prepare($sql);
        $statement->execute($parameters);

        echo("<br>Successfully added new user.");
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

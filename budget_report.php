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
        echo("<br>" . $link);
        echo("<br>" . $name);
        echo("<br>" . $price);
        echo("<br>" . $type);
        echo("<br>" . $username);
        $insertItemSql = "
        INSERT INTO purchases (item_name, item_price, item_type, link, username)
        VALUES (:item_name, :item_price, :item_type, :link, :username)";
        
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

function analyzeBudget($pdo, $totalFunds, $allocatedBudget) {  //changed the names so things were clearer
    try {
        // Calculate the spending limit
        $spendingLimit = $totalFunds - $allocatedBudget;
        
        // Calculate the total value of all item prices
        $sql = "SELECT SUM(item_price) AS total_price FROM purchases";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Default to 0 if no items are found
        $totalPrice = $result['total_price'] ?? 0; 

      
        echo "Total Spent: $" . number_format($totalPrice, 2) . "<br>";
        echo "Budget Threshold: $" . number_format($spendingLimit, 2) . "<br>";

        // Compare total price with budget
        if ($totalPrice > $spendingLimit) {
            $overBudget = $totalPrice - $allocatedBudget;
            echo "You are over budget by $" . number_format($overBudget, 2) . ". Consider reducing expenses.<br>";
            if ($overBudget > $allocatedBudget) { 
                echo "Warning: Overspending detected.<br>";
            }
        } 
        elseif ($totalPrice == $spendingLimit) {
            echo "You are exactly on budget. Good job managing your expenses!<br>";
        } 
        else {
            $remainingBudget = $spendingLimit - $totalPrice;
            echo "You are under budget by $" . number_format($remainingBudget, 2) . ". Keep up the good work!<br>";
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
        $sql = "SELECT username FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        ($stmt->execute()); //i have no idea if this will even work

        $db_username = $stmt->fetchColumn();


        if($db_username===$username){
            echo("Error! User with that username already exists!");
        }
        else{
            $sql = "INSERT INTO `users` (username, password) VALUES (:username, :password)";
            $parameters = [":username" => $username, ":password" => md5($password)];

            $statement= $pdo->prepare($sql);
            $statement->execute($parameters);

            echo("<br>Successfully added new user.");
        }
        
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
        ($stmt->execute()); //i have no idea if this will even work

        $dbpw = $stmt->fetchColumn();

        if(md5($password)===$dbpw){
            return true;
        }
        return false;
    }catch (PDOException $e) {
        echo "Error logging in: " . $e->getMessage() . "<br>";
        return false;
    }


}

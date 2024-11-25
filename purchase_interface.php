<?php
    /**
     * @param
     */
    
    session_start(); //I know in the todo list it says that this file will send stuff to database, but for now (since we havent covered) database I'll be using sessions
    // maybe, instead of saving straight to the database, we'll use a session variable and allow user to save to database using a username and password?
    //idk just riffin ya know?
    require 'budget_report.php';
    require 'budget_calc.php';

    if (!isset($_SESSION['purchases'])) {
        $_SESSION['purchases'] = [];
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_name'])) {
        $itemName = trim($_POST['item_name']);
        $itemPrice = intval($_POST['item_price']);
        $itemType = trim($_POST['item_type']);
        $itemLink = !empty($_POST['item_link']) ? trim($_POST['item_link']) : null;
    
        // Validate inputs
        if (empty($itemName) || $itemPrice <= 0 || empty($itemType)) {
            echo "All required fields must be filled correctly.<br>";
        } else {
            $_SESSION['purchases'][] = [
                "item_name" => $itemName,
                "item_price" => $itemPrice,
                "item_type" => $itemType,
                "item_link" => $itemLink,
            ];
    
            // Save to database
            try {
                if ($itemLink) {
                    insertNewPurchase_Link($pdo, $itemName, $itemPrice, $itemType, $itemLink, $username);
                }
                
                else {    
                    insertNewPurchase_NoLink($pdo, $itemName, $itemPrice, $itemType, $username);
                }
                echo "Purchase successfully added to the database.<br>";
            } 
        
            catch (Exception $e) {
                echo "Error adding purchase to the database: " . $e->getMessage() . "<br>";
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Budget Calculator</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="./ui_styles.css">
    <script>
        // JavaScript function to validate file input
        function validateFileInput(event) {
            const fileInput = document.getElementById('textfile');
            if (fileInput.value === '') {
                alert('Please choose a file before submitting.');
                event.preventDefault(); // Prevent the form from submitting
            }
        }
    </script>
</head>
<body>
    <h1>
        Add purchase:
</h1>
<form action="./purchase_interface.php" method="post"> <!--Form action shoud be directed to budget_index-->
    <label for="item_name">Item name: </label><br>
    <input type="text" id="item_name" name="item_name" required><br>

    <label for="price">Item price: </label><br>
    $<input type="number" id="item_price" name="item_price" required><br>

    <label for="item_type">Item type:</label>
    <select select name="item_type" id="item_type">
        <option value="Housing">Housing</option>
        <option value="Utilities">Utilities</option>
        <option value="Groceries">Groceries</option>
        <option value="Other">Other</option>
        <option value="Wants">Wants</option>
  </select><br>
    
    <label for="link">Link to product (optional): </label><br>
    <input type="text" id="link" name="item_link"><br>

    <button type="submit">Add purchase</button>
</form>
<br>
<form action="./read_file.php" method="post" enctype="multipart/form-data" onsubmit="validateFileInput(event)"> <!-- -->
    <label for="textfile">Choose a product file: </label>
    <br>
    <input type="file" id="textfile" name="textfile" accept=".txt, .csv, .docx" /> <!--accept attribute controls what files are allowed to be put in-->
    <br>
    <input type="submit" name="submitFile" value="Submit file">
</form>

<?php 
    $your_purchases = displayPurchases();
    echo $your_purchases;   
?>

<a href="budget_index.php">Back to budget index</a> 
</body>

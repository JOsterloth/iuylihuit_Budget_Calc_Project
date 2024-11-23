<?php


/*
session_start();
//include_once "budget_calc.php";
include_once "budget_report.php";
//include_once "budget_index.php";
// This file is where all the inputs from starting_interface and purchase_interface are displayed after they are used to calculate remaining budget.

// doesn't work
//echo displayRemainingBudget();
//echo displayPurchases();

// currently works
$budget_amount = $_SESSION['budget_amount'];
echo analyzeBudget($pdo, $budget_amount);

echo displayItemPrices($pdo);

$budget_amount = $_SESSION['budget_amount'];
echo analyzeBudget($pdo, $budget_amount);

if (isset($_SESSION['your_purchases'])) {
    $your_purchases = $_SESSION['your_purchases'];
    echo $your_purchases;
} else {
    echo "No purchases available.";
}

session_abort();
// add button that allows user to remove/confirm purchases
// validatePurchase() ?



clearPurchasesTable($pdo);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_purchases'])) {
    // Call the function to clear the purchases table
    clearPurchasesTable($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget View</title>
</head>
<body>
    <h1>Budget View</h1>

    <!-- Button to clear purchases -->
    <form method="post">
        <button type="submit" name="clear_purchases">Clear All Purchases</button>
    </form>

    <!-- Display budget analysis or other content here -->
    <?php
    $budget_amount = $_SESSION['budget_amount'] ?? 0;
    echo analyzeBudget($pdo, $budget_amount);
    ?>
</body>
</html>


// Add a button that directs user to budget_report.php so they can see how well they are following their budget goals
*/ 

session_start();
require "budget_calc.php";
require "budget_report.php";
//include_once "budget_index.php";
// This file is where all the inputs from starting_interface and purchase_interface are displayed after they are used to calculate remaining budget.

// doesn't work
//echo displayRemainingBudget();
//echo displayPurchases();

// currently works
//$budget_amount = $_SESSION['budget_amount'];
//echo analyzeBudget($pdo, $budget_amount);

//echo displayItemPrices($pdo);

//session_abort();
// add button that allows user to remove/confirm purchases
// validatePurchase() ?

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_purchases'])) {
    // Call the function to clear the purchases table
    clearPurchasesTable($pdo);
    // Clear the session data related to purchases
    unset($_SESSION['purchases']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget View</title>
</head>
<body>
    <h1>Budget View</h1>
    <?php

    echo displayPurchases();

    if (isset($_SESSION['your_purchases'])) {
        $your_purchases = $_SESSION['your_purchases'];
        echo $your_purchases;
    } else {
        echo "No purchases available.";
    }

    echo "<br>";
    $budget_amount = $_SESSION['budget_amount'] ?? 0;
    echo analyzeBudget($pdo, $budget_amount);
    ?>
    <form method="post">
        <button type="submit" name="clear_purchases">Clear All Purchases</button>
    </form>
    <div>
        <a href="budget_index.php">Return To Budget Index</a>
    </div>
</body>
</html>

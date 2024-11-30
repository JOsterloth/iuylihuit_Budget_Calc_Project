<?php
session_start();
require "budget_calc.php";
require "budget_report.php";
//include_once "budget_index.php";
// This file is where all the inputs from starting_interface and purchase_interface are displayed after they are used to calculate remaining budget


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
    echo "<br>";
    echo displayRemainingBudget();
    echo "<br>";
    $budget_amount = $_SESSION['budget_amount'] ?? 0;
    $funds = $_SESSION['totalfunds'] ?? 0;
    echo analyzeBudget($pdo, $budget_amount, $funds);
    ?>
    <form method="post">
        <button type="submit" name="clear_purchases">Clear All Purchases</button>
    </form>
    <div>
        <a href="budget_index.php">Return To Budget Index</a>
    </div>
</body>
</html>

<?php
session_start();
// Required files and database connection
require_once "budget_calc.php";
require_once "budget_report.php";
require_once "pdo_connect.php"; 

// Functionality to clear purchases
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_purchases'])) {
    // Clear the purchases from the database
    clearPurchasesTable($pdo);
    // Clear the session data related to purchases
    unset($_SESSION['purchases']);
}

// Fetch and display budget data
$budget_amount = $_SESSION['budget_amount'] ?? 0; 
$your_purchases = $_SESSION['your_purchases'] ?? "No purchases available.";
// $allocatedBudget = $_SESSION['allocatedBudget'] ?? 0; //should only need $totalfunds, $budget_amount, and sum cost of purchases
// to analyze budget
$totalFunds = $_SESSION['totalfunds'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget View</title>
    <link rel="stylesheet" href="ui_styles.css">
</head>
<body>
    <h1>Budget View</h1>

    <div class="purchases">
        <h2>Your Purchases:</h2>
        <?php
        echo displayPurchases();
        echo "<br>";
        echo $your_purchases;
        ?>
    </div>

    <div class="budget-analysis">
        <h2>Budget Analysis:</h2>
        <?php
        echo analyzeBudget($pdo, $totalFunds,$budget_amount);
        ?>
    </div>

    <form method="post">
        <button type="submit" name="clear_purchases">Clear All Purchases</button>
    </form>
    <div>
        <a href="budget_index.php"><button>Return To Budget Index</button></a>
    </div>
</body>
</html>

<?php
// Start the session at the beginning
session_start();

// Required files and database connection
require_once "budget_calc.php";
require_once "budget_report.php";
// Make sure $pdo is initialized before it's used
require_once "pdo_connect.php"; 

// Functionality to clear purchases
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_purchases'])) {
    // Clear the purchases from the database
    clearPurchasesTable($pdo);
    // Clear the session data related to purchases
    unset($_SESSION['purchases']);
    // Optionally redirect to the same page to refresh the content
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch and display budget data
$budget_amount = $_SESSION['budget_amount'] ?? 0;
$your_purchases = $_SESSION['your_purchases'] ?? "No purchases available.";
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

    <!-- Display Purchases -->
    <div class="purchases">
        <h2>Your Purchases:</h2>
        <?php
        echo displayPurchases();
        echo "<br>";
        echo $your_purchases;
        ?>
    </div>

    <!-- Display Budget Analysis -->
    <div class="budget-analysis">
        <h2>Budget Analysis:</h2>
        <?php
        echo analyzeBudget($pdo, $budget_amount);
        ?>
    </div>

    <!-- Button to clear purchases -->
    <form method="post">
        <button type="submit" name="clear_purchases">Clear All Purchases</button>
    </form>

    <!-- Link to return to the budget index -->
    <div>
        <a href="budget_index.php">Return To Budget Index</a>
    </div>
</body>
</html>

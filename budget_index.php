<?php // validate inputs from start_interface.html

session_start();
require 'budget_calc.php';
require 'budget_report.php';

$username = null;
$totalfunds = null;
$budget_percentage = null;
$budget_amount = null;

$custom_budget_percentage = null;
$custom_budget_amount = null;


function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}
else{
    $username = "Anonymous"; //idk, just something to denote that the current user is not logged in
}

if (isset($_POST['totalfunds'])) {
    $totalfunds = sanitize_input($_POST['totalfunds']);
    if (is_numeric($totalfunds) && $totalfunds >= 0) {
        $_SESSION['totalfunds'] = floatval($totalfunds); 
    } else {
        echo "Total funds must be a non-negative number.<br>";
    }
}

// New: Add funds to totalfunds
if (isset($_POST['add_funds'])) {
    $additional_funds = sanitize_input($_POST['additional_funds']);
    if (is_numeric($additional_funds) && $additional_funds > 0) {
        $_SESSION['totalfunds'] += floatval($additional_funds);  // Increase total funds
        echo "Funds successfully added!<br>";
    } else {
        echo "Please enter a valid amount to add.<br>";
    }
}
// Process budget input
if (isset($_POST['budget_percentage']) && isset($_SESSION['totalfunds'])) {
    $totalfunds = $_SESSION['totalfunds'];

    if ($_POST['budget_percentage'] === "Custom") {
        // Custom percentage
        if (!empty($_POST['custom_budget_percentage'])) {
            $custom_budget_percentage = sanitize_input($_POST['custom_budget_percentage']);
            if (is_numeric($custom_budget_percentage) && $custom_budget_percentage >= 0 && $custom_budget_percentage <= 100) {
                $_SESSION['budget_percentage'] = floatval($custom_budget_percentage);
                $_SESSION['budget_amount'] = $totalfunds * ($_SESSION['budget_percentage'] / 100);
            } else {
                echo "Custom budget percentage must be between 0 and 100.<br>";
            }
        }

        // Custom amount
        if (!empty($_POST['custom_budget_amount'])) {
            $custom_budget_amount = sanitize_input($_POST['custom_budget_amount']);
            if (is_numeric($custom_budget_amount) && $custom_budget_amount >= 0 && $custom_budget_amount <= $totalfunds) {
                $_SESSION['budget_percentage'] = null; // Clear percentage since amount is custom
                $_SESSION['budget_amount'] = floatval($custom_budget_amount);
            } else {
                echo "Custom budget amount must be between 0 and total funds.<br>";
            }
        }
    } else {
        // Standard percentage
        $budget_percentage = sanitize_input($_POST['budget_percentage']);
        if (is_numeric($budget_percentage) && $budget_percentage >= 0 && $budget_percentage <= 100) {
            $_SESSION['budget_percentage'] = floatval($budget_percentage);
            $_SESSION['budget_amount'] = $totalfunds * ($_SESSION['budget_percentage'] / 100);
        } else {
            echo "Budget percentage must be between 0 and 100.<br>";
        }
    }
}

// $username = $_SESSION['username'] ?? null; this is instead handled by login page and a prior if statement. keeping it commented for now just in case
$totalfunds = $_SESSION['totalfunds'] ?? 0;
$budget_percentage = $_SESSION['budget_percentage'] ?? null;
$budget_amount = $_SESSION['budget_amount'] ?? 0;
$remaining_money = $totalfunds - $budget_amount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Calculator</title>
    <link rel="stylesheet" href="./ui_styles.css">
</head>
<body>
    <h1 id="project_name"> 
        Budget Calculator <img class="logo" alt="money_logo" src="money_logo.jpg"><br>
        <p>~ We Judge You For Your Purchases ~</p>
        <hr>
    </h1>
    <p>
        <?php 
        echo "<h1>Hello, " . htmlspecialchars($username) . "</h1>"; 
        echo "Total funds: $" . number_format($totalfunds, 2) . "<br>";

        if (isset($_POST['budget_percentage']) && $_POST['budget_percentage'] === "Custom") {
            if (!empty($custom_budget_percentage)) {
                echo "You have chosen a custom budget percentage of " . number_format($custom_budget_percentage, 2) . "%.<br>";
            }
            if (!empty($custom_budget_amount)) {
                echo "You have chosen a custom budget amount of $" . number_format($custom_budget_amount, 2) . ".<br>";
            }
        } elseif ($budget_percentage !== null) {
            echo "You are budgeting " . number_format($budget_percentage, 2) . "% of your total funds.<br>";
        }

        echo "Budget amount: $" . number_format($budget_amount, 2) . "<br>";
        echo "Remaining money: $" . number_format($remaining_money, 2) . "<br>";
        ?>
    </p>
    
    <form method="POST" action="">
        <label for="additional_funds">Add Funds: </label>
        <input type="number" id="additional_funds" name="additional_funds" required>
        <input type="submit" name="add_funds" value="Add Funds">
    </form>

<div>
<a href="purchase_interface.php"><button>Add Purchase</button></a>
</div>
<br>
<div>
<a href="budget_view.php"><button>See Budget Report</button></a>
</div>
</body>
</html>







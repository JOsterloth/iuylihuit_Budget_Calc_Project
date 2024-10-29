<?php // validate inputs from start_interface.html

session_start();

$username = null;
$totalfunds = null;
$budget_percentage = null;
$budget_amount = null;

$custom_budget_percentage = null;
$custom_budget_amount = null;


function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if (isset($_POST['username'])) {
    $username = sanitize_input($_POST['username']);
    if (!empty($username)) {
        $_SESSION['username'] = $username;
    } else {
        echo "Username is required.<br>";
    }
}

if (isset($_POST['totalfunds'])) {
    $totalfunds = sanitize_input($_POST['totalfunds']);
    if (is_numeric($totalfunds) && $totalfunds >= 0) {
        $_SESSION['totalfunds'] = floatval($totalfunds); 
    } else {
        echo "Total funds must be a non-negative number.<br>";
    }
}
/*
if(isset($_POST['budget_percentage'])){
    if ($_POST['budget_percentage'] == "Custom"){ //This entire if/else lacks input validation
      $_SESSION['budget_percentage'] = $_POST['custom_budget_percentage'];  //We don't check if this is set before we assign yet
      $_SESSION['budget_amount'] = $POST['custom_budget_amount'];
    }
    else{
        $_SESSION['budget_percentage'] = $_POST['budget_percentage'];
        $_SESSION['budget_amount'] = $_SESSION['totalfunds']*($_SESSION['budget_percentage']/100); //nor do we check if $totalfunds was properly assigned.
        
    }    //TODO add more validation here
}
*/
//new budget validation
if (isset($_POST['budget_percentage']) && is_numeric($totalfunds) && isset($totalfunds)){
    if ($_POST['budget_percentage'] == "Custom") {
        // Validate custom budget percentage
        if (isset($_POST['custom_budget_percentage'])) {
            $budget_percentage = sanitize_input($_POST['custom_budget_percentage']);
            if (is_numeric($budget_percentage) && $budget_percentage >= 0 && $budget_percentage <= 100) {
                $_SESSION['budget_percentage'] = floatval($budget_percentage);
                $_SESSION['budget_amount'] = $_SESSION['totalfunds'] * ($_SESSION['budget_percentage'] / 100);
            }
            else {
                echo "Custom budget percentage must a number between 0 and 100.";
            }
        }
        // Validate custom budget amount
        $temp_var = null;
        if(isset($_POST['custom_budget_amount'])){        
            $custom_budget_amount = sanitize_input($_POST['custom_budget_amount']);
            if (is_numeric($custom_budget_amount) && $custom_budget_amount >= 0 && $custom_budget_amount < $totalfunds ) { 
                $_SESSION['temp_var'] = floatval($custom_budget_amount);
                $_SESSION['budget_amount'] = $_SESSION['totalfunds'] - ($_SESSION['temp_var']);
            }
            else {
                echo "Custom budget amount cannot be a number more than total funds.";
            }
        }
    } 
    else {
        // Validate standard budget percentage
        $budget_percentage = sanitize_input($_POST['budget_percentage']);
        if (is_numeric($budget_percentage) && $budget_percentage >= 0 && $budget_percentage <= 100) {
            $_SESSION['budget_percentage'] = floatval($budget_percentage);
            $budget_amount = $_SESSION['totalfunds'] * ($_SESSION['budget_percentage'] / 100);
            $_SESSION['budget_amount'] = $budget_amount;
        } 
        else {
            echo "Budget percentage must be a number between 0 and 100.<br>";
        }
    }
        

    
}

// include budget_calc.php to access methods


// send start_interface.html inputs into methods  
// methods should display inital parameters of budget
// ex: Total cash, budget percentage, etc..

// open purchase_interface.php, get inputs and calculate current budget with methods.
// direct user to budget_view.php to let them see how purchases affect remaining cash. 


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
    <h1 id="project_name"> <!--Shamelessly stole this from start_interface.html until I know what this page will look more like-->
        Budget Calculator <img class="logo" alt="money_logo" src="money_logo.jpg"><br>
        <p>~ We Judge You For Your Purchases ~</p>
        <hr>
    </h1>
    <p>
        <?php 
        echo ("<h1> Hello, " . $username . "</h1>"); 
        echo ("Total funds: $" . $totalfunds . "<br>");
        echo ("You are attempting to budget " . $budget_percentage . "% of your total funds<br>");
        echo ("Therefore, you are setting aside $" . $budget_amount . " and have $" . ($totalfunds-($budget_amount) . " to spend."));
        ?>
</p>
<div>
<a href="purchase_interface.php">Add purchase</a>
</div>
<table>
    <th>Name</th>
    <th>Price</th>
    <th>Link</th>
<?php
if(isset($_SESSION['purchases'])){ //this block of code is ripped straight from purchase interface to prove to myself that the purchases are tracked between pages.
    $purchases = $_SESSION['purchases']; //this isnt part of the final version
    foreach ($purchases as $p){
        $tr= "<tr>";
        $tr .= ("<th>" . $p['item_name'] . "</th>"); 
        $tr .= ("<th>" . $p['price'] . "</th>"); 
        if($p['link']!=""){ 
            $tr .= ("<th>" . $p['link'] . "</th>"); 
        }
        else{
            $tr .= ("<th> N/A </th>");
        }
        $tr .= "</tr>";
        echo $tr;
    }
}
?>
</table>
</body>
</html>







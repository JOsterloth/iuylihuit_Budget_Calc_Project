<?php // validate inputs from start_interface.html

session_start();

$username = null;
$totalfunds = null;
$budget_percentage = null;
$budget_amount = null;

if(isset($_POST['username'])){ //checking to see if the variables have been set before we assign them their sent values.
    $_SESSION['username'] = $_POST['username'];
}
if(isset($_POST['totalfunds'])){ 
    $_SESSION['totalfunds'] = $_POST['totalfunds'];
}
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
if(isset($_SESSION['username'])){ //lazy for now, since username is required then (technically?) these should be set as well
    $username = $_SESSION['username'];
    $totalfunds = $_SESSION['totalfunds'];
    $budget_percentage = $_SESSION['budget_percentage'];
    $budget_amount = $_SESSION['budget_amount'];
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
















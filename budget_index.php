

<?php // validate inputs from start_interface.html
$username = null; //define default values for variables if they are not set. there's probably a better default value but for now this will suffice. 
$totalfunds = null;
$budget_percentage = null;
$budget_amount = null;
if(isset($_POST['username'])){ //checking to see if the variables have been set before we assign them their sent values.
    $username = $_POST['username'];
}
if(isset($_POST['totalfunds'])){ 
    $totalfunds = $_POST['totalfunds'];
}
if(isset($_POST['budget_percentage'])){
    $budget_percentage = $_POST['budget_percentage'];
    if ($budget_percentage == "Custom"){ //This entire if/else lacks input validation
      $budget_percentage = $_POST['custom_budget_percentage'];  //We don't check if this is set before we assign
      $budget_amount = $POST['custom_budget_amount'];
    }
    else{
        $budget_amount = $totalfunds*($budget_percentage/100); //nor do we check if $totalfunds was properly assigned.
    }    //TODO add more validation here
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
        echo ("Therefore, you are setting aside $" . $budget_amount);
        
        
        ?>
</p>

</body>
</html>
















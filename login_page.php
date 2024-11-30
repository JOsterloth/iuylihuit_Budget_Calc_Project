<?php
    function sanitize_input($data) { //should move this to budget calc sometime
        return htmlspecialchars(strip_tags(trim($data)));
    }
    session_start();
    require 'budget_report.php';
    
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = sanitize_input($_POST['username']);
        $password = sanitize_input($_POST['password']);
        if(validateCredentials($pdo, $username, $password)){ 
            $_SESSION['username'] = $username;
            // I dont think we need to keep password information for the session
        }
        else{
            echo("Invalid username or password!");
        }
    }
    else if(isset($_POST['new_username']) && isset($_POST['new_password'])){
        insertNewUser($pdo,$_POST['new_username'], $_POST['new_password']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-in to budget calculator</title>
</head>
<body>
    <?php
        if(isset($_SESSION['username'])){
            echo "<p>Logged in. Hello, " . $_SESSION['username'] . "</p>";
            echo('<a href="start_interface.html">Continue to budget calculator</a>');
        }
        else{
            include 'login_form.html';
            echo('<a href="start_interface.html">Continue without login</a>');
        }
        
    ?>

    
</body>
</html>
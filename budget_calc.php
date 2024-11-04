<?php
// This file will contain the various methods used to calculate and display the budget.


// displayTotalCash()

/**
 * basically returns a message showing how much each purchase category is affecting the budget
 */
function displayRemainingBudget(){
    $housing = 0;
    $utilities = 0;
    $groceries = 0;
    $other = 0;
    $wants = 0;
    $display = "";
    if(isset($_SESSION['purchases'])){
        foreach($_SESSION['purchases'] as $p){
            switch ($p['item_type']) {
                case 'Housing':
                    $housing += $p['item_price'];
                    break;
                case 'Utilities':
                    $utilities += $p['item_price'];
                    break;
                case 'Groceries':
                    $groceries += $p['item_price'];
                    break;
                case 'Wants':
                    $wants += $p['item_price'];
                    break;
                default: //currently just adding any non-matches to the 'Other' category. 
                    $other += $p['item_price'];
                    break;
                    
            }
        }
        $remaining = $_SESSION['totalfunds'] - $_SESSION['budget_amount']; // accessing potentially null value jumpscare
        $remaining = $remaining - ($housing + $utilities + $groceries + $other + $wants);
        $display = "<p>You are spending: <br> 
                    $$housing on housing, <br>
                    $$utilities on utilities, <br>
                    $$groceries on groceries, <br>
                    $$other on other, <br>
                    and $$wants on wants. <br>
                    You currently have $$remaining remaining. </p>";
    }
    else{
        $display = "<p>You haven't added any purchases</p>";
    }
    return $display;
}
/**
 * basically just returns a table using the session. if there are no purchases then it returns a message saying that instead. probably wont work if
 * the file this is being used in hasnt started a session 
 */
function displayPurchases(){
    $t = "";
    if(isset($_SESSION['purchases'])){ // This block of code pretty much just creates a table out of the purchases array
        $t = "<table> <th>Name</th> <th>Price</th> <th>Type</th> <th>Link</th>";
        $purchases = $_SESSION['purchases'];
        foreach ($purchases as $p){
            $tr= "<tr>";
            $tr .= ("<td>" . $p['item_name'] . "</td>"); 
            $tr .= ("<td>" . $p['item_price'] . "</td>"); 
            $tr .= ("<td>" . $p['item_type'] . "</td>"); 
            if($p['item_link']!=""){ // idk why, but the form sends link out as an empty string if the user doesnt put anything in. I assume this is a blunder on my part, so for now this if statement is like this
                $tr .= ("<td>" . $p['item_link'] . "</td>"); 
            }
            else{
                $tr .= ("<td> N/A </td>");
            }
            $tr .= "</tr>";
            $t.= $tr;
        }
        $t .="</table>";
    }
    else{
        $t = "<p>No purchases added yet</p>";
    }
    
    return $t;
}

// add button that allows user to remove/confirm purchases
// validatePurchase() 
// uses sql to add or remove purchases from purchases table














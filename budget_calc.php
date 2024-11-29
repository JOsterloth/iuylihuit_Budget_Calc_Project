<?php

function displayRemainingBudget() {
    $housing = 0;
    $utilities = 0;
    $groceries = 0;
    $other = 0;
    $wants = 0;

    if (isset($_SESSION['purchases'])) {
        foreach ($_SESSION['purchases'] as $p) {
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
                default:
                    $other += $p['item_price'];
                    break;
            }
        }
    }
    //finalized purchases
    if (isset($_SESSION['finalized_purchases'])) {
        foreach ($_SESSION['finalized_purchases'] as $fp) {
            switch ($fp['item_type']) {
                case 'Housing':
                    $housing += $fp['item_price'];
                    break;
                case 'Utilities':
                    $utilities += $fp['item_price'];
                    break;
                case 'Groceries':
                    $groceries += $fp['item_price'];
                    break;
                case 'Wants':
                    $wants += $fp['item_price'];
                    break;
                default:
                    $other += $fp['item_price'];
                    break;
            }
        }
    }

    $budget_remaining = $_SESSION['budget_amount'] - ($housing + $utilities + $groceries + $other + $wants);
    $total_remaining = $_SESSION['totalfunds'] - ($housing + $utilities + $groceries + $other + $wants);

    $display = "<p>You have spent: <br> 
    $$housing on housing. <br>
    $$utilities on utilities. <br>
    $$groceries on groceries. <br>
    $$other on other. <br>
    $$wants on wants. <br>
    You have $$budget_remaining in your budget. <br>
    You currently have $$total_remaining remaining. </p>";


    return $display;
}


/**
 * basically just returns a table using the session. if there are no purchases then it returns a message saying that instead. probably wont work if
 * the file this is being used in hasnt started a session 
 */
function displayPurchases() {
    $t = "<table> 
            <th>Name</th> 
            <th>Price</th> 
            <th>Type</th> 
            <th>Link</th> 
            <th>Status</th>";

    if (isset($_SESSION['purchases'])) {
        $purchases = $_SESSION['purchases'];
        for ($i = 0; $i < count($purchases); $i++) {
            $p = $purchases[$i];
            $tr = "<tr>";
            $tr .= "<td>" . htmlspecialchars($p['item_name']) . "</td>";
            $tr .= "<td>$" . htmlspecialchars($p['item_price']) . "</td>";
            $tr .= "<td>" . htmlspecialchars($p['item_type']) . "</td>";
            $tr .= "<td>" . (!empty($p['item_link']) ? htmlspecialchars($p['item_link']) : "N/A") . "</td>";
            $tr .= "<td>Pending</td>"; 

            $tr .= "<td><form method='post' action='purchase_interface.php' class='inline'>
                        <input type='hidden' name='element' value='$i'>
                        <button type='submit' name='submit_param' value='submit_value' class='link-button'>
                        Remove item
                        </button>
                    </form>";
            if (isset($_SESSION['username'])) { // only users can finalize
                $tr .= "<td><form method='post' action='purchase_interface.php' class='inline'>
                            <input type='hidden' name='addtodb' value='$i'>
                            <button type='submit' name='submit_param' value='submit_value' class='link-button'>
                            Finalize
                            </button>
                        </form>";
            }
            $tr .= "</td>";
            $tr .= "</tr>";
            $t .= $tr;
        }
    }

    if (isset($_SESSION['finalized_purchases'])) {
        $finalizedPurchases = $_SESSION['finalized_purchases'];
        foreach ($finalizedPurchases as $fp) {
            $tr = "<tr>";
            $tr .= "<td>" . htmlspecialchars($fp['item_name']) . "</td>";
            $tr .= "<td>$" . htmlspecialchars($fp['item_price']) . "</td>";
            $tr .= "<td>" . htmlspecialchars($fp['item_type']) . "</td>";
            $tr .= "<td>" . (!empty($fp['item_link']) ? htmlspecialchars($fp['item_link']) : "N/A") . "</td>";
            $tr .= "<td>Finalized</td>"; 
            $tr .= "</tr>";
            $t .= $tr;
        }
    }
    if (empty($_SESSION['purchases']) && empty($_SESSION['finalized_purchases'])) {
        $t = "<p>No purchases added yet</p>";
    } else {
        $t .= "</table>";
    }

    return $t;
}










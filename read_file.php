<?php
/**
 * reads a file and spits back an array of purchases for each line
 * 
 * TODO this function needs some work in case it receives bad input from a file
 * 
 * @param $file_superglobal. like, this: $_FILES['fileNameOrWhatever']
 * @return array of arrays where each element is a purchase
 */
function readFileToArray($file_superglobal){
        $potentialPurchases = [];
        if(file_exists($file_superglobal['tmp_name'])){
            $openedFile = fopen($file_superglobal['tmp_name'], "r");
            while(! feof($openedFile)){ 
                $line = fgetcsv($openedFile);
                if(isset($line[3])){
                    array_push($potentialPurchases, array("item_name" => $line[0],
                            "item_price" => $line[1], 
                            "item_type" => $line[2],
                            "item_link" => $line[3])); 
                }
                else{ //if there is no provided link we just mark it as "N/A"
                    array_push($potentialPurchases, array("item_name" => $line[0],
                            "item_price" => $line[1], 
                            "item_type" => $line[2],
                            "item_link" => "N/A")); 
            }
        }
        }
        else{
            echo("Error! File not found!");
        }
    return $potentialPurchases;
}
/**
 * creates a table and returns it as a string
 * 
 * @param string array - $tableHeads: array of strings that will be used as the tables heads
 * @param two-dimensional array - $purchases: 2d array of purchases
 * @return string that can be echoed 
 */
function createTableFromArray($tableHeads, $purchases){
    $table = "<table> <tr>";
    foreach($tableHeads as $th){
        $table.="<th>$th</th>";
    }
    $table.="</tr>";
    foreach($purchases as $p){
        $table.="<tr>";
        foreach($p as $p2){
            $table.= "<td>$p2</td>";
        }
        $table.="</tr>";
    }
    $table.="</table>";
    return $table;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>The following will be added to your purchases:</h1>
    <?php
        $purchases=[];
        if(isset($_FILES['textfile'])){
            $purchases = readFileToArray($_FILES['textfile']);
            echo(createTableFromArray(array("Name", "Price","Type","Link"), $purchases));
            session_start();
            if(!isset($_SESSION['purchases'])){ //if the purchases array hasnt been set yet, we initialize as empty array
                $_SESSION['purchases'] = [];
            }
            foreach($purchases as $p){
                array_push($_SESSION['purchases'], array("item_name" => $p['item_name'],
                        "item_price" => $p['item_price'],
                        "item_type" => $p['item_type'], 
                        "item_link" => $p['item_link'])); 
            }
        }
    ?>
    <a href="budget_index.php">Back to budget index</a> 
</form>
</body>
</html>
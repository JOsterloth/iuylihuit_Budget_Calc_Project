<?php

function readFileToArray($file_superglobal){
        $potentialPurchases = [];
        $openedFile = fopen($file_superglobal['tmp_name'], "r");
        while(! feof($openedFile)){
            $line = fgetcsv($openedFile);
            if(isset($line[2])){
                array_push($potentialPurchases, array("item_name" => $line[0],
                        "item_price" => $line[1], 
                        "item_link" => $line[2])); 
            }
            else{
                array_push($potentialPurchases, array("item_name" => $line[0],
                        "item_price" => $line[1], 
                        "item_link" => "N/A")); 
            }
        }
    return $potentialPurchases;
}

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
if(isset($_FILES['textfiles'])){
    $purchases = readFileToArray($_FILES['textfile']);
    echo(createTableFromArray(array("Name", "Price", "Link"), $purchases));
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
    
    
</body>
</html>
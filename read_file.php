<?php
session_start();

/**
 * reads a file and spits back an array of purchases for each line
 * 
 * TODO this function needs some work in case it receives bad input from a file
 * 
 * @param $file_superglobal. like, this: $_FILES['fileNameOrWhatever']
 * @return array of arrays where each element is a purchase
 */
function readFileToArray($file_superglobal) {
    $potentialPurchases = [];

    if (file_exists($file_superglobal['tmp_name'])) {
        $openedFile = fopen($file_superglobal['tmp_name'], "r");
        
        while (!feof($openedFile)) {
            $line = fgetcsv($openedFile);

            // If the line is not false and has at least 3 elements
            if ($line && count($line) >= 3) {
                $potentialPurchases[] = [
                    "item_name" => $line[0],
                    "item_price" => $line[1],
                    "item_type" => $line[2],
                    "item_link" => $line[3] ?? "N/A"
                ];
            } elseif ($line) {
                // If the line exists but has fewer than 3 elements
                echo "<script>alert('File is in incorrect format. Please ensure each line has at least three elements.');</script>";
                fclose($openedFile);  // Close the file before returning
                return [];  // Return an empty array indicating an error in format
            }
        }

        fclose($openedFile);
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
    $table = "<table border ='1'> <tr>";
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

// Form Submission Logic
$fileSelected = true;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was selected
    if (empty($_FILES['textfile']['name'])) {
        echo "<script>alert('Please choose a file before submitting.');</script>";
        $fileSelected = false; 
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="ui_styles.css">
</head>
<body>
    <h1>The following will be added to your purchases:</h1>
    <?php
//prevents error when submiting with no file 
if ($fileSelected && isset($_FILES['textfile']) && $_FILES['textfile']['error'] === UPLOAD_ERR_OK) {
    $purchases = readFileToArray($_FILES['textfile']);
    echo(createTableFromArray(array("Name", "Price", "Type", "Link"), $purchases));

    // Save the purchases to the session
    if (!isset($_SESSION['purchases'])) {
        $_SESSION['purchases'] = [];
    }
    foreach ($purchases as $p) {
        $_SESSION['purchases'][] = [
            "item_name" => $p['item_name'],
            "item_price" => $p['item_price'],
            "item_type" => $p['item_type']
        ];
    }

}
    ?>
   <a href="purchase_interface.php"><button>Return to Purchase Page</button> </a>
     <br> 
     <br>
    
    <a href="budget_index.php"><button>Back to budget index</button></a>

    

</form>
</body>
</html>

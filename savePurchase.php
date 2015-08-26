<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            require 'php/databaseFunctions.php';
            $conn = dbConnect();

            $i = 1;
            while($i<=(int)$_POST['numItems']){
                $table;
                $data = $_POST["location" . $i] . "', '" .
                        
                        $_POST["amount" . $i] . "', '" .
                        $_POST["date" . $i] . "', '" .
                        $_POST["account" . $i] ; 
                        
                switch($_POST['type']){
                    case "purchase":
                        $table = "INSERT INTO purchases (location, amount, purchaseDate, account, personName, budget) VALUES ('";
                        $data = $data . "', '" . $_POST["personName" . $i] . "', '" .$_POST["budget" . $i] . "');";
                        break;
                    case "income":
                        $table = "INSERT INTO income (name, amount, incomeDate, account, personName) VALUES ('";
                        $data = $data . "', '" . $_POST["personName" . $i] . "');";
                        break;
                }
                $sql = $table . $data;
            
                //write data to request table
                if($conn->query($sql) == TRUE) {
                    echo "<p> New Purcase " . $_POST["location" . $i]. " Created Successfully </p>";
                }else {
                    echo "Error: " .$sql ."<br />" . $conn->error;
                }
                
                //update account balance
                $sql = "SELECT balance, type FROM accounts WHERE name='". $_POST["account" . $i] . "';";
                $purchases = $conn->query($sql);
                $row = $purchases->fetch_assoc();
                $amount = $_POST["amount" . $i];
                if($_POST['type'] == "income"){
                    $amount = -1 * $amount;
                }
                if ($row['type'] == "Credit"){
                    $newBal = $row['balance'] + $amount;
                }
                else{
                    $newBal = $row['balance'] - $amount;
                }
                
                $sql = "UPDATE accounts SET balance='" . $newBal . "' WHERE name='" . $_POST['account' . $i] . "';";
                $conn->query($sql);
                
                $i+=1;
            }
            
            $conn->close();
        ?>
        
        <p> Returning to the home page in 5s </p> <?php header ("refresh:5; url=index.php"); ?>
    </body>
</html>

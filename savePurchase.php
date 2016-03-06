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
                        $_POST["date" . $i] . "', '" ;
                
                switch($_POST['type']){
                    case "purchase":
                        $table = "INSERT INTO receipts (location, amount, date, personName, memo, type) VALUES ('";
                        $data = $data . $_POST["personName" . $i] . "', '" . $_POST["memo" . $i] . "', '0');";
                        break;
                    case "income":
                        $table = "INSERT INTO income (name, amount, incomeDate, account, personName) VALUES ('";
                        $data = $data . "', '" . $_POST["personName" . $i] . "');";
                        break;
                }
                $sql = $table . $data;
            
                //write data to receipt table
                if($conn->query($sql) == TRUE) {
                    echo "<p> New Receipt " . $_POST["location" . $i]. " Created Successfully </p>";
                    //save receipt id
                    $recID = $conn->insert_id;
                    if($_POST["bfa" .$i] == "SPLIT"){
                        //split receipt into multiple budgets
                        $numSplits = $_POST["splitCounter" . $i];
                        for($j=1;$j<=$numSplits; $j++){
                            if(savePurchase($conn, $_POST["splitAmount" . $i ."_" . $j], $recID, $_POST["splitBudget" .$i . "_" . $j]) == TRUE) {
                                echo "<p> New Purchase " . $_POST["location" . $i]. " Split Budget: ". $j ." Created Successfully </p>";
                                updateAccount($conn,getAssocAccount($conn, $_POST["splitBudget" .$i . "_" . $j]), $_POST["splitAmount" . $i ."_" . $j]);
                            }
                            else {
                                echo "Error: " .$conn->info ."<br />" . $conn->error;
                            }   
                        }
                    }
                    else {
                        //single transaction
                        if(savePurchase($conn, $_POST["amount" . $i], $recID, $_POST["bfa" .$i]) == TRUE) {
                            echo "<p> New Purchase " . $_POST["location" . $i]. " Created Successfully </p>";
                            $accountName = getAssocAccount($conn, $_POST["bfa" . $i]);
                            updateAccount($conn,$accountName , $_POST["amount" . $i]);
                        }
                        else {
                            echo "Error: " .$conn->info ."<br />" . $conn->error;
                        }
                    }
                }else {
                    echo "Error: " .$sql ."<br />" . $conn->error;
                }
                
                $i+=1;
            }
            
            $conn->close();
            
            function savePurchase($conn, $amount, $recID, $bfa){
                $sql = "INSERT INTO purchases (amount, account, recID) 
                    VALUES ('" . $amount . "', '" . $bfa . "', '" . $recID . "');";
                return $conn->query($sql);             
            }
            function updateAccount($conn, $accountName, $amount){
                $sql = "SELECT balance, type FROM accounts WHERE name='". $accountName . "';";
                $purchases = $conn->query($sql);
                $row = $purchases->fetch_assoc();
                //$amount = $_POST["amount" . $i];
                if($_POST['type'] == "income"){
                    $amount = -1 * $amount;
                }
                if ($row['type'] == "Credit"){
                    $newBal = $row['balance'] + $amount;
                }
                else{
                    $newBal = $row['balance'] - $amount;
                }
                
                $sql = "UPDATE accounts SET balance='" . $newBal . "' WHERE name='" . $accountName . "';";
                if ($conn->query($sql) ==TRUE){
                    echo "Account: " . $accountName . " updated successfully!";
                }
                else {
                    echo "Error: " .$conn->info ."<br />" . $conn->error;
                }
            }
            function getAssocAccount ($conn, $bfa){
                $result = $conn->query("SELECT name FROM account WHERE name = '" . $bfa . "'");
                if($result->num_rows == 0) {
                    $result = $conn->query("SELECT name, assocAccountID FROM budget_items WHERE name = '" . $bfa . "'");
                    if($result->num_rows == 0) {
                        //it's a fund
                        $result = $conn->query("SELECT name, assocAccountID FROM funds WHERE name = '" . $bfa . "'");
                        
                    }
                    //get the associated account id then look up the account
                    $row = $result->fetch_assoc();
                    $result = $conn->query("SELECT account_ID, name FROM accounts WHERE account_ID = '" . $row['assocAccountID'] . "'");
                    
                }
                $row = $result->fetch_assoc();
                return $row['name'];
            }
        ?>
        
        <p> Returning to the home page in 5s </p> <?php header ("refresh:5; url=index.php"); ?>
    </body>
</html>

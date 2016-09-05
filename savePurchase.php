<?php
    require 'php/navbar.php';
    require 'php/databaseFunctions.php';
    
        
            //require 'php/databaseFunctions.php';
            $conn = dbConnect();

            $i = 1;
            $purCount = (int)$_POST['numItems'];
            while($i<=$purCount){
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
                        $numSplits = intval($_POST["splitCounter" . $i]);
                        for($j=1;$j<=$numSplits; $j++){
                            if(savePurchase($conn, $_POST["splitAmount" . $i ."_" . $j], $recID, $_POST["splitBudget" .$i . "_" . $j]) == TRUE) {
                                echo "<p> New Purchase " . $_POST["location" . $i]. " Split Budget: ". $j ." Created Successfully </p>";
                                $accountID = getAssocAccount($conn, $_POST["splitBudget" .$i . "_" . $j]);
                                updateAccounts($conn,$accountID, $_POST["splitAmount" . $i ."_" . $j],TRUE);
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
                            $accountID = getAssocAccount($conn, $_POST["bfa" . $i]);
                            $spendFlag = TRUE;
                            updateAccounts($conn,$accountID , $_POST["amount" . $i], $spendFlag);
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
            function updateAccounts($conn,$accountID,$amount, $spendFlag){
                $sql = "SELECT balance, type FROM accounts WHERE account_ID='". $accountID . "';";
                $purchases = $conn->query($sql);
                $row = $purchases->fetch_assoc();
                //$amount = $_POST["amount" . $i];
                if(!$spendFlag) { //Income/deposit
                    $amount = $amount * -1;
                }
                if ($row['type'] == "Credit"){
                $newBal = $row['balance'] + $amount;
                }
                else{
                    $newBal = $row['balance'] - $amount;
                }

                $sql = "UPDATE accounts SET balance='" . $newBal . "' WHERE account_ID='" . $accountID . "';";
                if ($conn->query($sql) ==TRUE){
                    echo "Account: " . $accountID . " updated successfully!";
                }
                else {
                    echo "Error: " .$conn->info ."<br />" . $conn->error;
                }
            }
            function getAssocAccount($conn, $budName ){
                //this function gets the associated bank account ID for funds or budgets. Return the bank account ID otherwis
                $sql = "SELECT account_ID, name FROM accounts WHERE name='". $budName . "';";
                $results = $conn->query($sql);
                //check if it's an account
                if ($results->num_rows >=1){
                    $row = $results->fetch_assoc();
                    return $row['account_ID'];
                }
                //check if it's a budget
                $sql = "SELECT assocAccountID, name FROM budget_items WHERE name='". $budName . "';";
                $results = $conn->query($sql);
                if ($results->num_rows >=1){
                    $row = $results->fetch_assoc();

                    return $row['assocAccountID'];
                }
                //check if it's a fund
                $sql = "SELECT name, assocAccountID FROM funds WHERE name='". $budName . "';";
                $results = $conn->query($sql);
                if ($results->num_rows >=1){
                    $row = $results->fetch_assoc();

                    return $row['assocAccountID'];
                }
                return -1;//return an error for not being found
            }
        ?>
        
        <p> Returning to the home page in 5s </p> <?php header ("refresh:5; url=index.php"); ?>
    <?php require 'php/footer.php'; ?>

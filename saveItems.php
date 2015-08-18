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
            dbConnect();

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $i = 1;
            while($i<=(int)$_POST['numPurchases']){
                $sql = "INSERT INTO purchases (location, personName, amount, purchaseDate, account, budget)
                VALUES ('" . $_POST["location" . $i] . "', '" .
                $_POST["personName" . $i] . "', '" .
                $_POST["amount" . $i] . "', '" .
                $_POST["date" . $i] . "', '" .
                $_POST["account" . $i] . "', '" .
                $_POST["budget" . $i] . "');";
                
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
                if($row['type'] == "Checking/Savings"){
                    $newBal = $row['balance'] - $_POST["amount" . $i];
                }
                else{
                    $newBal = $row['balance'] + $_POST["amount" . $i];
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

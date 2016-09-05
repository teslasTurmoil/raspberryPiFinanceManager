<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists(money_format)){
    require 'money_format.php';
}
    function dbConnect(){
        $servername = "127.0.0.1";
        $username = "web";
        $password = "thaler1212";
        $dbname = "financesNew";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            return die("Connection failed: " . $conn->connect_error);
        }
        else {
            return $conn;
        }
    }
    function dbQuery($conn, $query){
        if(!empty($conn)){
            return $conn->query($query);
        }
        else{
            return false;
        }
    }
    
    function dbClose($conn){
        if(!empty($conn)){
            return $conn->close();
        }
        else{
            return false;
        }
    }
    /*
    function updateAccounts($amount, $accountName, $conn, $spendFlag){
        $sql = "SELECT balance, type FROM accounts WHERE name='". $accountName . "';";
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
        
        

        $sql = "UPDATE accounts SET balance='" . $newBal . "' WHERE name='" . $accountName . "';";
        if ($conn->query($sql) ==TRUE){
            echo "Account: " . $accountName . " updated successfully!";
        }
        else {
            echo "Error: " .$conn->info ."<br />" . $conn->error;
        }
    }
    function getAssocAccount($budName, $conn){
        //this function gets the associated bank account ID for funds or budgets. Return the bank account ID otherwis
        $sql = "SELECT balance, type FROM accounts WHERE name='". $budName . "';";
        $results = $conn->query($sql);
        //check if it's an account
        if ($results->num_rows() >=1){
            $row = $results->fetch_assoc();
            return $row['account_ID'];
        }
        //check if it's a budget
        $sql = "SELECT balance, type FROM budget_items WHERE name='". $budName . "';";
        $results = $conn->query($sql);
        if ($results->num_rows() >=1){
            $row = $results->fetch_assoc();
            
            return $row['assocAccountID'];
        }
        //check if it's a fund
        $sql = "SELECT name, account FROM funds WHERE name='". $budName . "';";
        $results = $conn->query($sql);
        if ($results->num_rows() >=1){
            $row = $results->fetch_assoc();
            
            return $row['assocAccountID'];
        }
        return -1;//return an error for not being found
    }
     
     */
?>
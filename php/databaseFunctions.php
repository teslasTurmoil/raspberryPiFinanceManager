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
?>
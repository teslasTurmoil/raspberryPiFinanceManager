<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    function dbConnect(){
        $servername = "192.168.56.101";
        $username = "testuser";
        $password = "abcd1234";
        $dbname = "finances";

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
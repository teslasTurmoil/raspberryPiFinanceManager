<?php 
    require 'php/navbar.php';
    require 'php/databaseFunctions.php'
?>
            <form action="monthlySummary.php" method="post">
                <p>
                    Select date Range: <br />
                    <input type="date" name="start" id = "start" required="required"> to <input type="date" name="end" id = "end" required = "required"> <input type="Submit" value="Submit">
                </p>
            </form>
            <table>
                <caption> Budget Balances</caption>
                <thead>
                    <tr>
                        <th>Budget</th>
                        <th>Amount Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        setlocale(LC_MONETARY, 'en_US');
                        $conn = dbConnect(); 
                        
                        //get the different budget buckets
                        $sql = "SELECT name, amount, account FROM budget_items ORDER BY name ASC;";
                        $budgetResults = dbQuery($conn, $sql);
                        
                        if($budgetResults->num_rows >0){
                            while($budgetBucket = $budgetResults->fetch_assoc()) {
                                //get the purchases against the budget buckets
                                
                                if($_POST['start'] <> null){ //user specified date range
                                    $sql = "SELECT budget, SUM(amount), account FROM purchases" . 
                                            " WHERE budget='" .$budgetBucket['name'] . "' AND account='" .$budgetBucket['account'] . "' AND purchaseDate BETWEEN '" . $_POST['start'] . "' and '" . $_POST['end'] .
                                            "' GROUP BY budget ORDER BY budget ASC;";
                                }
                                else {
                                    $date = getDate();
                                    $sql = "SELECT budget, SUM(amount), account FROM purchases "
                                            . "WHERE budget='" .$budgetBucket['name'] . "' AND account='" .$budgetBucket['account'] . "' AND purchaseDate > '" . $date['year']."-" . $date['mon'] ."-". "01" . 
                                            "' GROUP BY budget ORDER BY budget ASC". ";";
                                }
                                //figure out the remaining total
                                $purchaseItems = dbQuery($conn, $sql);
                                if ($purchaseItems->num_rows > 0 ){
                                    $budgetSpent = $purchaseItems ->fetch_assoc();

                                    //figure out the remaining total 
                                    $remaining = $budgetBucket['amount'] - $budgetSpent['SUM(amount)'];
                                }
                                else {
                                    $remaining = $budgetBucket['amount'];
                                }
                                echo "<tr><td>".$budgetBucket["name"]."</td><td>".money_format("%+.2n", $remaining)."</td></tr>";
                            }
                        }
                        else {
                            echo "<tr><td colspan=\"6\">0 results</td></tr>";
                        }
                        
                        /*        /////////////////////////////////////////////////////////////////
                            if($_POST['start'] <> null){
                                $sql = "SELECT budget, SUM(amount), account FROM purchases" . 
                                        " WHERE purchaseDate BETWEEN '" . $_POST['start'] . "' and '" . $_POST['end'] .
                                        "' GROUP BY budget ORDER BY budget ASC;";
                            }
                            else {
                                $date = getDate();
                                $sql = "SELECT budget, SUM(amount), account FROM purchases "
                                        . "WHERE purchaseDate > '" . $date['year']."-" . $date['mon'] ."-". "01" . 
                                        "' GROUP BY budget ORDER BY budget ASC". ";";
                            }
                            $budgetSpent = dbQuery($conn, $sql);
                        
                        //get budget totals
                        //$sql = "SELECT name, amount FROM budget_items ORDER BY name ASC;";
                        //$budgetTotals = $conn->query($sql);
                            if ($budgetSpent->num_rows > 0) {

                                // output data of each row
                                while($row = $budgetSpent->fetch_assoc()) {
                                    $sql = "SELECT name, amount, account FROM budget_items WHERE name='".$row["budget"] ."' ORDER BY name ASC;";
                                    $budgetResult = dbQuery($conn, $sql);
                                        if ($budgetSpent->num_rows > 0){ 
                                            $bucketSize = $budgetResult->fetch_assoc();
                                            $remaining = $bucketSize['amount'] - $row['SUM(amount)'];
                                        }
                                        else{
                                            $remaining = 0-$row['amount'];
                                        }
                                    //$budgetTotals = $conn->query($sql);
                                    echo "<tr><td>".$row["budget"]."</td><td>".money_format("%+.2n", $remaining)."</td></tr>";
                                }
                                
                            } 
                            else {
                                echo "<tr><td colspan=\"6\">0 results</td></tr>";
                            }
                      */  
                    ?>
            </table>
            <table>
                <caption> Account Balances</caption>
                <thead>
                    <tr>
                        <th>Account</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT name, balance FROM accounts;";
                        $results = dbQuery($conn, $sql);
                        if ($results->num_rows > 0) {
                            while($row = $results->fetch_assoc()) {
                                echo "<tr><td>".$row["name"]."</td><td>".money_format("%+.2n", $row["balance"])."</td></tr>";
                            }
                        }
                        else {
                            echo "<tr><td colspan=\"2\">0 results</td></tr>";
                        }
                        dbClose($conn);
                    ?>
            </table>
        <?php require 'php/footer.php';?>

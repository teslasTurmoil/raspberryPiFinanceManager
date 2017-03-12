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
                        $sql = "SELECT name, amount, account, assocAccountID FROM budget_items ORDER BY name ASC;";
                        $budgetResults = $conn->query($sql);
                        
                        if($budgetResults->num_rows >0){
                            while($budgetBucket = $budgetResults->fetch_assoc()) {
                                //get the purchases against the budget buckets
                                
                                if($_POST['start'] <> null){ //user specified date range
                                    $sql = "SELECT SUM(purchases.amount), purchases.account FROM purchases, receipts " . 
                                            " WHERE purchases.account='" .$budgetBucket['name'] . "' AND receipts.date BETWEEN '" . $_POST['start'] . "' and '" . $_POST['end'] ."' AND purchases.recID = receipts.recID;";
                                }
                                else {
                                    $date = getDate();
                                    $sql = "SELECT SUM(purchases.amount), purchases.account FROM purchases, receipts " .
                                            "WHERE purchases.account='" .$budgetBucket['name'] . "' AND purchases.recID = receipts.recID AND receipts.date >= '" . $date['year']."-" . $date['mon'] ."-". "01';"; 
                                }
                                //figure out the remaining total
                                $purchaseItems = dbQuery($conn, $sql);
                                if ($purchaseItems->num_rows > 0 ){
                                    $budgetSpent = $purchaseItems ->fetch_assoc();

                                    //figure out the remaining total 
                                    $remaining = $budgetBucket['amount'] - $budgetSpent['SUM(purchases.amount)'];
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
                        $results = $conn->query($sql);
                        if ($results->num_rows > 0) {
                            while($row = $results->fetch_assoc()) {
                                echo "<tr><td>".$row["name"]."</td><td>".money_format("%+.2n", $row["balance"])."</td></tr>";
                            }
                        }
                        else {
                            echo "<tr><td colspan=\"2\">0 results</td></tr>";
                        }
                    ?>
            </table>
            <table>
                <caption>Fund Balances</caption>
                <thead>
                    <tr>
                        <th>Fund</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = "SELECT name, balance FROM funds;";
                        $results = $conn->query($query);
                        if ($results->num_rows > 0) {
                            while($row = $results->fetch_assoc()) {
                                echo "<tr><td>".$row["name"]."</td><td>".money_format("%+.2n", $row["balance"])."</td></tr>";
                            }
                        }
                        else {
                            echo "<tr><td colspan=\"2\">0 results</td></tr>";
                        }
                        $conn->close();
                    ?>
            </table>
        <?php require 'php/footer.php';?>

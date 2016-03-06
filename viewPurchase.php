<?php require 'php/navbar.php';
      require 'php/databaseFunctions.php'; ?>
            <form action="viewPurchase.php" method="post">
                <p>
                    Select date Range: <br />
                    <input type="date" name="start" id = "start" required="required"> to <input type="date" name="end" id = "end" required = "required"> <input type="Submit" value="Submit">
                </p>
            </form>
            <table>
                <caption> This Month's Purchases</caption>
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Who</th>
                        <th>Budget</th>
                        <th>Account</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        setlocale(LC_MONETARY, 'en_US');
                        $conn = dbConnect();
                        if($_POST['start'] <> null){
                            $sql = "SELECT * FROM purchases WHERE purchaseDate BETWEEN '" . $_POST['start'] . "' and '" . $_POST['end'] ."';";
                        }
                        else {
                            $date = getDate();
                            $sql = "SELECT * FROM purchases WHERE purchaseDate > '" . $date['year']."-" . $date['mon'] ."-". "01" . "';";
                        }
                        $purchases = $conn->query($sql);

                        if ($purchases->num_rows > 0) {

                            // output data of each row
                            while($row = $purchases->fetch_assoc()) {
                                echo "<tr><td>".$row["location"]."</td><td>".money_format("%+.2n", $row["amount"])."</td><td> ".date("F j, Y",strtotime($row["purchaseDate"])). "</td><td> ".$row["personName"]. "</td><td> ".$row["budget"]."</td><td> ".$row["account"]."</td></tr>";
                            }

                        } 
                        else {
                            echo "<tr><td colspan=\"6\">0 results</td></tr>";
                        }
                            
                         //get income
                        if($_POST['start'] <> null){
                            $sql = "SELECT * FROM income WHERE incomeDate BETWEEN '" . $_POST['start'] . "' and '" . $_POST['end'] ."';";
                        }
                        else {
                            $date = getDate();
                            $sql = "SELECT * FROM income WHERE incomeDate > '" . $date['year']."-" . $date['mon'] ."-". "01" . "';";
                        }
                        $income = $conn->query($sql);
                        
                        //get monthly bills
                        if($_POST['start'] <> null){
                            $sql = "SELECT * FROM monthlyBills WHERE billDate BETWEEN '" . $_POST['start'] . "' and '" . $_POST['end'] ."';";
                        }
                        else {
                            $date = getDate();
                            $sql = "SELECT * FROM monthlyBills WHERE billDate > '" . $date['year']."-" . $date['mon'] ."-". "01" . "';";
                        }
                        $monthlyBills = $conn->query($sql);
                        
                        $conn->close();
                    ?>
                </tbody>
            </table>

            <table>
                <caption> This Month's Income</caption>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Who</th>
                        <th>Account</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($income->num_rows > 0) {

                            // output data of each row
                            while($row = $income->fetch_assoc()) {
                                echo "<tr><td>".$row["name"]."</td><td>".money_format("%+.2n", $row["amount"])."</td><td> ".date("F j, Y",strtotime($row["incomeDate"])). "</td><td> ".$row["personName"]. "</td><td> ".$row["account"]."</td></tr>";
                            }

                        } 
                        else {
                            echo "<tr><td colspan=\"5\">0 results</td></tr>";
                        }
                        
                    ?>
                </tbody>
            </table>
            <table>
                <caption> This Month's Bills</caption>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Account</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($monthlyBills->num_rows > 0) {

                            // output data of each row
                            while($row = $monthlyBills->fetch_assoc()) {
                                echo "<tr><td>".$row["name"]."</td><td>".money_format("%+.2n", $row["amount"])."</td><td> ".date("F j, Y",strtotime($row["billDate"])). "</td><td> ".$row["account"]."</td></tr>";
                            }

                        } 
                        else {
                            echo "<tr><td colspan=\"4\">0 results</td></tr>";
                        }
                        
                    ?>
                </tbody>
            </table>
        <?php require 'php/footer.php';?>
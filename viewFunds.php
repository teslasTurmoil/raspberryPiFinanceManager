<?php 
    $pageTitle = "View Funds";
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
                <caption> Fund Balances</caption>
                <thead>
                    <tr>
                        <th>Fund</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        setlocale(LC_MONETARY, 'en_US');
                        $conn = dbConnect();
                        $sql = "SELECT * FROM funds ORDER BY name ASC;";
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
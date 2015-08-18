<?php
    require 'php/navbar.php';
    require 'php/databaseFunctions.php';
    setlocale(LC_MONETARY, 'en_US');
    
    $conn = dbConnect(); 

    //monthly bills
    //first paycheck
    $sql = "SELECT name, amount, entryType FROM repeatingEntries WHERE entryType <> 'Income' AND timeOfMonth = '1st Paycheck'";
    $firstPayBills = $conn->query($sql);

    $sql = "SELECT name, amount, entryType FROM repeatingEntries WHERE entryType <> 'Income' AND timeOfMonth = '2nd Paycheck'";
    $secondPayBills = $conn->query($sql);

    //monthly income
    $sql = "SELECT name, amount, entryType FROM repeatingEntries WHERE entryType = 'Income'";
    $income = $conn->query($sql);

    //monthly budgets
    $sql = "SELECT name, amount FROM budget_items";
    $budgets = $conn->query($sql);
    $conn->close();

    $firstBillsTot = displayResults("First Half of Month", $firstPayBills);
    $secBillsTot = displayResults("Second Half of Month Bills", $secondPayBills);
    $incomeTot = displayResults("Income", $income);
    $budgetTot = displayResults("Budgeted Items", $budgets);

    $firstSum = round($incomeTot/2 - ($firstBillsTot + $budgetTot/2), 2, PHP_ROUND_HALF_EVEN);
    $secSum = round($incomeTot/2 - ($secBillsTot + $budgetTot/2), 2, PHP_ROUND_HALF_EVEN);
    $monthSum = round($incomeTot - ($firstBillsTot+ $secBillsTot + $budgetTot), 2, PHP_ROUND_HALF_EVEN);

    echo "<table>\n<caption>Budget Totals</caption>\n<thead>\n<tr>\n<th>Name</th>\n<th>Amount</th></tr>\n</thead>\n<tbody>\n";
    echo "<tr>\n<td class=\"desc\">1st Pay Remaining</td>\n<td>" . money_format("%+.2n", $firstSum) ."</td>\n</tr>\n";
    echo "<tr>\n<td class=\"desc\">2nd Pay Remaining</td>\n<td>" . money_format("%+.2n", $secSum)."</td>\n</tr>\n";
    echo "<tr>\n<td class=\"desc\">Overall Remaining</td>\n<td>" . money_format("%+.2n", $monthSum)."</td>\n</tr>\n";
    echo "</tbody>\n</table>\n";

    function displayResults($tableName, $results){
        //returns the sum of the table entries
        $sum = 0;
        echo "<table><caption>" . $tableName . "</caption>\n";
        if ($results->num_rows > 0) {
            echo "<thead><tr><th>Name</th><th>Amount</th></tr></thead><tbody>\n";
            // output data of each row
            while($row = $results->fetch_assoc()) {
                echo "<tr>\n<td class=\"desc\">".$row["name"]."</td><td>".money_format("%+.2n", $row["amount"])."</td>\n</tr>\n";
                $sum+=$row["amount"];
            }
            echo "<tr><td class=\"desc\">Total</td><td>" . money_format("%+.2n", $sum) ."</td></tr>\n</tbody>";
            echo "</table>";
        } 
        else {
            echo "0 results";
        }
        return $sum;
    }
    require 'php/footer.php';
?>
        
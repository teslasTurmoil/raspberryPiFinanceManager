<?php 
    require 'php/navbar.php';
    require 'php/databaseFunctions.php'; ?>
<script src="js/Chart.js"></script>
    <?php
    $conn = dbConnect();

    $date = (int)Date("Y");
    $colors = array("\"rgba(221, 221, 3, 0.5)\",","\"rgba(11, 129, 218, 0.50)\",", "\"rgba(3, 221, 32, 0.5)\",","\"rgba(255,153,0,0.4)\",","\"rgba(230, 0, 0, 0.5)\",");
    $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $monthvalues = array();
    foreach ($months as $month) {
        $monthvalues[$month] = 0;
    }
    $budgetListQuery = "SELECT name FROM budget_items";
    $budgetResults = $conn->query($budgetListQuery);
    while($row = $budgetResults->fetch_array()){
        $budgets[] = $row[0];
    }
    $count=0;
    foreach($budgets as $budget){
        echo "\t\t\t<div class=\"chart\">\n";
            echo "\t\t\t\t<h2>" . $budget . "</h2>\n";
            echo "\t\t\t\t<div>\n";
                echo "\t\t\t\t\t<canvas id= \"Chart" .$count . "\"></canvas>\n";
            echo "\t\t\t\t</div>\n";
        echo "\t\t\t</div>\n"; 
        $count++;
    } 
    echo "\t\t<script type=\"text/javascript\">\n";
    $count = 0;
    foreach($budgets as $budget){ 
        echo "\t\t\t\tvar ctx = document.getElementById(\"Chart" .$count ."\").getContext('2d');\n";
        echo "\t\t\t\tvar myChart = new Chart(ctx, {\n";
        echo "\t\t\t\t\ttype: 'line',\n";
        echo "\t\t\t\t\t data: {\n";
        echo "\t\t\t\t\t\tlabels: " . json_encode($months) . ",\n";
        echo "\t\t\t\t\t\tdatasets: [{\n";
        for ($i=$date - 4; $i <= $date; $i++){
            foreach ($months as $month) {
                $monthvalues[$month] = 0;
            }
            $yearDataQuery = "SELECT SUM(purchases.amount), MONTH(receipts.date) FROM purchases, receipts WHERE purchases.account='" . $budget . "' AND receipts.recID=purchases.recID AND YEAR(receipts.date) = '" . $i . "' GROUP BY Month(receipts.date) ORDER BY Month(receipts.date) ASC;";
            $result = $conn->query($yearDataQuery);
            while($row = $result->fetch_array()){
                $monthvalues[$months[$row[1] - 1]] = (double)$row[0];
            }
            echo "\t\t\t\t\t\t\tlabel: '" . $i .  "',\n";
            echo "\t\t\t\t\t\t\tbackgroundColor: " . $colors[4 - ($date-$i)] . "\n";
            echo "\t\t\t\t\t\t\tdata: " . json_encode(array_values($monthvalues)) . "";
            if ($i < $date){
                echo "\n\t\t\t\t\t\t} , {\n";
            }
            else{
                echo "\n\t\t\t\t\t\t}]\n\t\t\t\t\t}\n\t\t\t\t});\n";
            }
            
        }
        $count++;
    }
        ?>
        </script>
<?php 
    $conn->close();
'php/footer.php'; ?>


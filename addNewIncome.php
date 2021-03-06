<?php 
    require 'php/navbar.php';
    require 'php/databaseFunctions.php';
    $conn = dbConnect();

    $date = getDate();
    $sql = "SELECT name FROM budget_items;";
    $purchases = $conn->query($sql);

        if ($purchases->num_rows > 0) {

            // output data of each row to an array of options
            $index = 0;
            while($row = $purchases->fetch_assoc()) {
                $budgets[$index] = $row['name'];
                $index++;
            }

        }
    $sql = "SELECT name FROM funds;";
    $results = $conn->query($sql);

        if ($results->num_rows > 0) {

            // output data of each row to an array of options
            $index = 0;
            while($row = $results->fetch_assoc()) {
                $funds[$index] = $row['name'];
                $index++;
            }

        }
    $sql = "SELECT name FROM accounts;";
    $purchases = $conn->query($sql);

        if ($purchases->num_rows > 0) {

            // output data of each row to an array of options
            $index = 0;
            while($row = $purchases->fetch_assoc()) {
                $accounts[$index] = $row['name'];
                $index++;
            }

        } 
    $conn->close();
?>
            <form action="savePurchase.php" method="post">
                <label><input type="number" name="numItems" id="numItems" min="0" value="0"/>
                    How many income items do you want to enter?
                </label>
                <div id="income">
                    
                </div>
                
                
                <input type="Submit" value="submit"/>
                <input type="hidden" name="type" value="income"/>
                <script type="text/javascript">
                    var newCount;
                    var oldCount;
                    $(document).ready(function(){
                        newCount = document.getElementById("numItems").value;
                        oldCount = newCount;
                    });
                    $("#numItems").change(function() {
                        newCount = document.getElementById("numItems").value;
                        if (parseInt(newCount) > parseInt(oldCount)){
                            index = parseInt(oldCount)+1;
                            while(index <=newCount){
                                addFieldset(index);
                                index++;
                            }
                            
                            
                        }
                        else{
                            index = parseInt(oldCount);
                            while (index >parseInt(newCount)){
                                deleteFieldset(index);
                                index--;
                            }

                        }
                        oldCount = newCount;
                    });
                                        
                    function addFieldset(i) {
                        var funds = [];
                        var accounts = [];
                        var optFunds = document.createElement("optgroup");
                        optFunds.setAttribute("label", "Funds");
                        var optAccounts = document.createElement("optgroup");
                        optAccounts.setAttribute("label", "Accounts");
                        <?php
                            for ($i=0; $i<count($accounts); $i++){
                                echo "accounts[{$i}] = document.createElement(\"option\");";
                                echo "accounts[{$i}].text = \"" . $accounts[$i] . "\";\n\t\t\t\t\t\t";
                            }
                            
                            for ($i=0; $i<count($funds); $i++){
                                echo "funds[{$i}] = document.createElement(\"option\");";
                                echo "funds[{$i}].text = \"" . $funds[$i] . "\";\n\t\t\t\t\t\t";
                            }
                        ?>
                                
                        var fieldset = document.createElement ("fieldset");
                        var legend = document.createElement ("legend");
                        legend.innerHTML = "Income "+ i;
                        fieldset.appendChild (legend);
                        fieldset.setAttribute("id", "income" + i);
                        
                        for(var index2 = 0; index2< funds.length; index2++){
                            optFunds.appendChild(funds[index2]);
                        }
                        for(var index2 = 0; index2< accounts.length; index2++){
                            optAccounts.appendChild(accounts[index2]);
                        }
                        
                        
                        var input = [];
                        input[0] = document.createElement("input");
                        input[0].type = "text";
                        input[0].setAttribute("name","location" + i);
                        input[0].setAttribute("size", "20");
                        input[0].setAttribute("maxlength","100");
                        input[0].setAttribute("required","required");
                        
                        input[1] = document.createElement("input");
                        input[1].type = "number";
                        input[1].setAttribute("name","amount" + i);
                        input[1].setAttribute("min", "0.01");
                        input[1].setAttribute("step","0.01");
                        input[1].setAttribute("required","required");
                        
                        input[2] = document.createElement("input");
                        input[2].type = "text";
                        input[2].setAttribute("name","personName" + i);
                        input[2].setAttribute("size", "10");
                        input[2].setAttribute("maxlength","100");
                        input[2].setAttribute("required","required");
                        
                        input[3] = document.createElement("input");
                        input[3].type = "date";
                        input[3].setAttribute("name","date" + i);
                        input[3].setAttribute("required","required");
                        var d = new Date();
                        //var offset = new Date().getTimezoneOffset();
                        //d.setUTCHours(d.getUTCHours() + offset/60);  //need to set a locale
                        d.setUTCHours(d.getUTCHours() - 5);  //setting locale to US Eastern
                        input[3].setAttribute("value", d.toISOString().substring(0, 10));
                        
                        input[4] = document.createElement("select");
                        input[4].type = "text";
                        input[4].setAttribute("name","account" + i);
                        input[4].setAttribute("required","required");
                        input[4].add(optAccounts);
                        input[4].add(optFunds);
                        
                        var table = document.createElement("table");
                        table.setAttribute("class", "dataEntry");
                        var row, cell1, cell2;
                        var desc = ["Description: ", "Amount: ", "Who: ", "Date: ", "Account: "];
                        for(var index2 = 0; index2 <input.length; index2++){
                            row = table.insertRow(index2);
                            cell1 = row.insertCell(0);
                            cell2 = row.insertCell(1);
                        
                            cell1.innerHTML = desc[index2];
                            cell1.setAttribute("class", "formDesc");
                            cell2.appendChild(input[index2]);
                        }   
                        fieldset.appendChild(table); 
                        
                        document.getElementById("income").appendChild(fieldset);
                        
                    }
                    function deleteFieldset(i) {
                        var id = "#purchase" + i;
                        $(id).remove();
                    }
                    
                    $(document).ready(function(){
                        document.getElementById('numItems').value = '1';
                        $("#numItems").change();
                    });
                </script>
            </form>
        <?php require 'footer.php';?>
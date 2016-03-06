<?php 
    require 'php/navbar.php';
    require 'php/databaseFunctions.php';
    $conn = dbConnect();

    $date = getDate();
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
    $results = $conn->query($sql);

        if ($results->num_rows > 0) {

            // output data of each row to an array of options
            $index = 0;
            while($row = $results->fetch_assoc()) {
                $accounts[$index] = $row['name'];
                $index++;
            }

        } 
    $conn->close();
?>
            <form action="fundTransfer.php" method="post">
                <label><input type="number" name="numItems" id="numItems" min="0" value="0">
                    How many fund transfers do you want to enter?
                </label>
                
                <div id="transfers">
                    
                </div>
                
                
                <input type="Submit" value="submit">
                
                <script type="text/javascript">
                    var purCount;
                    var oldCount;
                    $(document).ready(function(){
                        purCount = document.getElementById("numItems").value;
                        oldCount = purCount;
                    });
                    $("#numItems").change(function() {
                        purCount = document.getElementById("numItems").value;
                        if (parseInt(purCount) > parseInt(oldCount)){
                            index = parseInt(oldCount)+1;
                            while(index <=purCount){
                                addFieldset(index);
                                index++;
                            }
                            
                            
                        }
                        else{
                            index = parseInt(oldCount);
                            while (index >parseInt(purCount)){
                                deleteFieldset(index);
                                index--;
                            }

                        }
                        oldCount = purCount;
                    });
                                        
                    function addFieldset(i) {
                        var funds = [];
                        var accounts = [];
                        var optFunds = document.createElement("optgroup");
                        optFunds.setAttribute("label", "Funds");
                        var optAccounts = document.createElement("optgroup");
                        optAccounts.setAttribute("label", "Accounts");
                        <?php
                            
                            for ($i=0; $i<count($funds); $i++){
                                echo "funds[{$i}] = document.createElement(\"option\");";
                                echo "funds[{$i}].text = \"" . $funds[$i] . "\";\n\t\t\t\t\t\t";
                            }
                            
                            for ($i=0; $i<count($accounts); $i++){
                                echo "accounts[{$i}] = document.createElement(\"option\");";
                                echo "accounts[{$i}].text = \"" . $accounts[$i] . "\";\n\t\t\t\t\t\t";
                            }
                            
                        ?>
                        
                        var fieldset = document.createElement ("fieldset");
                        var legend = document.createElement ("legend");
                        legend.innerHTML = "Transfer "+ i;
                        fieldset.appendChild (legend);
                        fieldset.setAttribute("id", "transfer" + i);
                        
                        for(var index2 = 0; index2< funds.length; index2++){
                            optFunds.appendChild(funds[index2]);
                        }
                        for(var index2 = 0; index2< accounts.length; index2++){
                            optAccounts.appendChild(accounts[index2]);
                        }
                      
                        var input = [];
                        
                        input[0] = document.createElement("select");
                        input[0].setAttribute("name","from" + i);
                        input[0].setAttribute("required","required");
                        input[0].add(optAccounts);
                        input[0].add(optFunds);
                        
                        
                        input[1] = document.createElement("select");
                        input[1].setAttribute("name","to" + i);
                        input[1].setAttribute("required","required");
                        input[1].add(optAccounts.cloneNode(true));
                        input[1].add(optFunds.cloneNode(true));
                        
                        
                        input[2] = document.createElement("input");
                        input[2].type = "number";
                        input[2].setAttribute("name","amount" + i);
                        input[2].setAttribute("min", "0.01");
                        input[2].setAttribute("step","0.01");
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
                        
                        
                        
                        var table = document.createElement("table");
                        table.setAttribute("class", "dataEntry");
                        var row, cell1, cell2;
                        var desc = ["From: ", "To: ", "Amount: ", "Date: "];
                        for(var index2 = 0; index2 <input.length; index2++){
                            row = table.insertRow(index2);
                            cell1 = row.insertCell(0);
                            cell2 = row.insertCell(1);
                        
                            cell1.innerHTML = desc[index2];
                            cell1.setAttribute("class", "formDesc");
                            cell2.appendChild(input[index2]);
                        }   
                        fieldset.appendChild(table); 
                        
                        document.getElementById("transfers").appendChild(fieldset);
                        
                    }
                    function deleteFieldset(i) {
                        var id = "#transfer" + i;
                        $(id).remove();
                    }
                    
                    $(document).ready(function(){
                        document.getElementById('numItems').value = '1';
                        $("#numItems").change();
                    });
                </script>
            </form>
        <?php 'php/footer.php'; ?>
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
            <form name="mainForm" action="savePurchase.php" onsubmit="return validateForm()" method="post">
                <label><input type="number" name="numItems" id="numItems" min="0" value="0">
                    How many purchases do you want to enter?
                </label>
                <input type ="hidden" name="type" value="purchase"/>
                <div id="purchases">
                    
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
                        var budgets = [];
                        budgets[0] = document.createElement("option");
                        budgets[0].text = "";
                        budgets[1] = document.createElement("option");
                        budgets[1].text = "SPLIT";
                        var accounts = [];
                        <?php
                            for ($i=0; $i<count($budgets); $i++){
                                echo "budgets[{$i}+2] = document.createElement(\"option\");";
                                echo "budgets[{$i}+2].text = \"" . $budgets[$i] . "\";\n\t\t\t\t\t\t";
                            }
                            
                            for ($i=0; $i<count($accounts); $i++){
                                echo "accounts[{$i}] = document.createElement(\"option\");";
                                echo "accounts[{$i}].text = \"" . $accounts[$i] . "\";\n\t\t\t\t\t\t";
                            }
                        ?>
                        
                        var fieldset = document.createElement ("fieldset");
                        var legend = document.createElement ("legend");
                        legend.innerHTML = "Purchase "+ i;
                        fieldset.appendChild (legend);
                        fieldset.setAttribute("id", "purchase" + i);
                        
                        var input = [];
                        input[0] = document.createElement("input");
                        input[0].type = "text";
                        input[0].setAttribute("name","location" + i);
                        input[0].setAttribute("size", "20");
                        input[0].setAttribute("maxlength","100");
                        input[0].setAttribute("required","required");
                        
                        input[1] = document.createElement("input");
                        input[1].type = "text";
                        input[1].setAttribute("name","memo" + i);
                        input[1].setAttribute("size", "20");
                        input[1].setAttribute("maxlength","200");
                        input[1].setAttribute("required","required");
                        
                        input[2] = document.createElement("input");
                        input[2].type = "number";
                        input[2].setAttribute("name","amount" + i);
                        input[2].setAttribute("min", "0.01");
                        input[2].setAttribute("step","0.01");
                        input[2].setAttribute("required","required");
                        
                        input[3] = document.createElement("input");
                        input[3].type = "text";
                        input[3].setAttribute("name","personName" + i);
                        input[3].setAttribute("size", "10");
                        input[3].setAttribute("maxlength","100");
                        input[3].setAttribute("required","required");
                        
                        input[4] = document.createElement("input");
                        input[4].type = "date";
                        input[4].setAttribute("name","date" + i);
                        input[4].setAttribute("required","required");
                        var d = new Date();
                        //var offset = new Date().getTimezoneOffset();
                        //d.setUTCHours(d.getUTCHours() + offset/60);  //need to set a locale
                        d.setUTCHours(d.getUTCHours() - 5);  //setting locale to US Eastern
                        input[4].setAttribute("value", d.toISOString().substring(0, 10));
                        
                        input[5] = document.createElement("select");
                        input[5].setAttribute("id", "budget" + i);
                        input[5].setAttribute("name","budget" + i);
                        input[5].setAttribute("required","required");
                        for(var index2 = 0; index2< budgets.length; index2++){
                            input[5].add(budgets[index2]);
                        }
                        input[5].onchange = checkBudgetSplit;
                        
                        input[6] = document.createElement("select");
                        //input[5].type = "text";
                        input[6].setAttribute("name","account" + i);
                        input[6].setAttribute("required","required");
                        for(var index2 = 0; index2< accounts.length; index2++){
                            input[6].add(accounts[index2]);
                        }
                        
                        var table = document.createElement("table");
                        table.setAttribute("class", "dataEntry");
                        table.setAttribute("id", "pTable" + i)
                        var row, cell1, cell2;
                        var desc = ["Location: ", "Memo: ", "Amount: ", "Who: ", "Date: " , "Budget: ", "Account: "];
                        for(var index2 = 0; index2 <input.length; index2++){
                            row = table.insertRow(index2);
                            cell1 = row.insertCell(0);
                            cell2 = row.insertCell(1);
                        
                            cell1.innerHTML = desc[index2];
                            cell1.setAttribute("class", "formDesc");
                            cell2.appendChild(input[index2]);
                        }   
                        fieldset.appendChild(table); 
                        
                        //create divider for handling splitting purchases between multiple budgets
                        var div = document.createElement("div");
                        div.setAttribute("id", "purchaseSplit" + i);
                        div.setAttribute("name", "purchaseSplit" + i);
                        div.hidden = true;
                        document.getElementById("purchases").appendChild(fieldset);
                        fieldset.appendChild(div);
                                                
                    }
                    function deleteFieldset(i) {
                        var id = "#purchase" + i;
                        $(id).remove();
                    }
                    
                    function checkBudgetSplit(){
                        var i = this.id.slice(-1);
                        var table = document.getElementById("pTable"+i);
                        if (this.value === "SPLIT"){
                            var splitCounter = document.createElement("input");
                            splitCounter.type = "number";
                            splitCounter.setAttribute("id","splitCounter" + i);
                            splitCounter.setAttribute("name","splitCounter" + i);
                            splitCounter.setAttribute("min", "2");
                            splitCounter.setAttribute("step","1");
                            splitCounter.setAttribute("required","required");
                            splitCounter.onchange = splitInputChange;
                            splitCounter.dataset.lastValue = 0;
                            
                            var row, cell1, cell2;
                            row = table.insertRow(table.rows.length);
                            cell1 = row.insertCell(0);
                            cell2 = row.insertCell(1);
                        
                            cell1.innerHTML = "Number of budgeted items";
                            cell1.setAttribute("class", "formDesc");
                            cell2.appendChild(splitCounter);
                            //document.getElementById("purchaseSplit" + i).appendChild(splitCounter);
                            document.getElementById("purchaseSplit" + i).hidden = false;
                        }
                        else{
                            table.deleteRow(table.rows.length -1);
                            var div = document.getElementById("purchaseSplit" + i);
                            while (div.firstChild) {
                                div.removeChild(div.firstChild);
                            }
                            //document.getElementById("purchaseSplit" + i).innerhtml = "";
                            div.hidden = true;
                        }
                    }
                    
                    function splitInputChange(){
                        var i = this.id.slice(-1);
                        var splitVal = parseInt(document.getElementById(this.id).value);
                        if (splitVal < this.dataset.lastValue){ //remove split
                            index = parseInt(this.dataset.lastValue);
                            while (index >parseInt(splitVal)){
                                deleteSplits(index);
                                index--;
                            }
                        }
                        else{ //add split
                            var index = parseInt(this.dataset.lastValue)+1;
                            while(index <=splitVal){
                                addSplits(i, index);
                                index++;
                            }
                        }
                        this.dataset.lastValue = splitVal;
                    }
                    function addSplits(divID, i) {
                        var budgets = [];
                        <?php
                            for ($i=0; $i<count($budgets); $i++){
                                echo "budgets[{$i}] = document.createElement(\"option\");";
                                echo "budgets[{$i}].text = \"" . $budgets[$i] . "\";\n\t\t\t\t\t\t";
                            }
                        ?>
                        var fieldset = document.createElement ("fieldset");
                        var legend = document.createElement ("legend");
                        legend.innerHTML = "Budget Split "+ i;
                        fieldset.appendChild (legend);
                        fieldset.setAttribute("id", "split" + i);
                        
                        var input = [];
                        input[0] = document.createElement("input");
                        input[0].type = "number";
                        input[0].setAttribute("name","splitAmount"+divID+"_" + i);
                        input[0].setAttribute("min", "0.01");
                        input[0].setAttribute("step","0.01");
                        input[0].setAttribute("required","required");
                        
                        input[1] = document.createElement("select");
                        input[1].setAttribute("name","splitBudget" + divID + "_" + i);
                        input[1].setAttribute("required","required");
                        for(var index2 = 0; index2< budgets.length; index2++){
                            input[1].add(budgets[index2]);
                        }
                        
                        var table = document.createElement("table");
                        table.setAttribute("class", "dataEntry");
                        var row, cell1, cell2;
                        var desc = ["Amount: ", "Budget: "];
                        for(var index2 = 0; index2 <input.length; index2++){
                            row = table.insertRow(index2);
                            cell1 = row.insertCell(0);
                            cell2 = row.insertCell(1);
                        
                            cell1.innerHTML = desc[index2];
                            cell1.setAttribute("class", "formDesc");
                            cell2.appendChild(input[index2]);
                        }   
                        fieldset.appendChild(table); 
                        document.getElementById("purchaseSplit" + divID).appendChild(fieldset);
                    }
                    function deleteSplits(i){
                        var id = "#split" + i;
                        $(id).remove();
                    }
                    
                    function validateForm(){ //neds tested
                        //This function validates the input on the form
                        //returns true when ok to send to the server
                        var purCount = parseInt(document.getElementById("numItems").value);
                        var valid = true;
                        var budget = "";
                        var amount, splitAmount;
                        var splitCount = 0;
                        for(var i = 1; i<=purcount; i++){
                            budget = document.forms["mainForm"]["budget"+i].value;
                            if (budget === "SPLIT"){
                                amount = document.forms["mainForm"]["amount"+i].value;
                                //sum up the split budgets
                                splitAmount = 0;
                                for(int j=1; j<=parseInt(document.getElementById("splitCounter"+i).value); j++){
                                    splitAmount += parseFloat(document.forms["mainForm"]["splitAmount" + i + "_" + j].value);
                                    
                                }
                                if(splitAmount != amount){
                                    valid =false;
                                    break;
                                }
                            } 
                        }
                        return valid;
                    }
                    
                    $(document).ready(function(){
                        document.getElementById('numItems').value = '1';
                        $("#numItems").change();
                    });
                </script>
            </form>
        <?php 'php/footer.php'; ?>
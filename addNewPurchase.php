<?php 
    require 'php/navbar.php';
    require 'php/databaseFunctions.php';
    $conn = dbConnect();

    $date = getDate();
    $sql = "SELECT name FROM budget_items;";
    $results = $conn->query($sql);

        if ($results->num_rows > 0) {

            // output data of each row to an array of options
            $index = 0;
            while($row = $results->fetch_assoc()) {
                $budgets[$index] = $row['name'];
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
                        var option;
                        var budOG = document.createElement("OPTGROUP");
                        budOG.label = "Budgets";
                        var accountOG = document.createElement("OPTGROUP");
                        accountOG.label = "Accounts";
                        var fundOG = document.createElement("OPTGROUP");
                        fundOG.label = "Funds";
                        //budgets[0] = document.createElement("option");
                        //budgets[0].text = "";
                        var splitOpt = document.createElement("option");
                        splitOpt.text = "SPLIT";
                        var blankOpt = document.createElement("option");
                        blankOpt.text = "";
                        <?php
                            for ($i=0; $i<count($budgets); $i++){
                                echo "option = document.createElement(\"option\");\n\t\t\t\t\t\t";
                                echo "option.text = \"" . $budgets[$i] . "\";\n\t\t\t\t\t\t";
                                echo "budOG.appendChild(option);\n\t\t\t\t\t\t";
                            }
                            
                            for ($i=0; $i<count($accounts); $i++){
                                echo "option = document.createElement(\"option\");\n\t\t\t\t\t\t";
                                echo "option.text = \"" . $accounts[$i] . "\";\n\t\t\t\t\t\t";
                                echo "accountOG.appendChild(option);\n\t\t\t\t\t\t";
                            }
                            for ($i=0; $i<count($funds); $i++){
                                echo "option = document.createElement(\"option\");\n\t\t\t\t\t\t";
                                echo "option.text = \"" . $funds[$i] . "\";\n\t\t\t\t\t\t";
                                echo "fundOG.appendChild(option);\n\t\t\t\t\t\t";
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
                        input[5].setAttribute("id", "bfa" + i);
                        input[5].setAttribute("name","bfa" + i);
                        input[5].setAttribute("required","required");
                        //for(var index2 = 0; index2< budgets.length; index2++){
                        //    input[5].add(budgets[index2]);
                        //}
                        input[5].add(blankOpt);
                        input[5].add(splitOpt);
                        input[5].add(budOG);
                        input[5].add(fundOG);
                        input[5].add(accountOG);
                        input[5].onchange = checkBudgetSplit;
                        
                        /*
                        input[6] = document.createElement("select");
                        //input[5].type = "text";
                        input[6].setAttribute("name","account" + i);
                        input[6].setAttribute("required","required");
                        for(var index2 = 0; index2< accounts.length; index2++){
                            input[6].add(accounts[index2]);
                        }
                        */
                       
                        var table = document.createElement("table");
                        table.setAttribute("class", "dataEntry");
                        table.setAttribute("id", "pTable" + i)
                        var row, cell1, cell2;
                        var desc = ["Location: ", "Memo: ", "Amount: ", "Who: ", "Date: " , "From: "];
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
                            splitCounter.value = 2;
                            splitCounter.onchange();
                        }
                        else{
                            if(document.getElementById("splitCounter" + i) !== null){
                                table.deleteRow(table.rows.length -1);
                                var div = document.getElementById("purchaseSplit" + i);
                                while (div.firstChild) {
                                    div.removeChild(div.firstChild);
                                }
                                //document.getElementById("purchaseSplit" + i).innerhtml = "";
                                div.hidden = true;
                            }
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
                        var option;
                        var budOG = document.createElement("OPTGROUP");
                        budOG.label = "Budgets";
                        var accountOG = document.createElement("OPTGROUP");
                        accountOG.label = "Accounts";
                        var fundOG = document.createElement("OPTGROUP");
                        fundOG.label = "Funds";
                        //budgets[0] = document.createElement("option");
                        //budgets[0].text = "";
                        splitOpt = document.createElement("option");
                        splitOpt.text = "SPLIT";
                        <?php
                            for ($i=0; $i<count($budgets); $i++){
                                echo "option = document.createElement(\"option\");\n\t\t\t\t\t\t";
                                echo "option.text = \"" . $budgets[$i] . "\";\n\t\t\t\t\t\t";
                                echo "budOG.appendChild(option);\n\t\t\t\t\t\t";
                            }
                            
                            for ($i=0; $i<count($accounts); $i++){
                                echo "option = document.createElement(\"option\");\n\t\t\t\t\t\t";
                                echo "option.text = \"" . $accounts[$i] . "\";\n\t\t\t\t\t\t";
                                echo "accountOG.appendChild(option);\n\t\t\t\t\t\t";
                            }
                            for ($i=0; $i<count($funds); $i++){
                                echo "option = document.createElement(\"option\");\n\t\t\t\t\t\t";
                                echo "option.text = \"" . $funds[$i] . "\";\n\t\t\t\t\t\t";
                                echo "fundOG.appendChild(option);\n\t\t\t\t\t\t";
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
                        input[1].add(budOG);
                        input[1].add(fundOG);
                        input[1].add(accountOG);
                        
                        var table = document.createElement("table");
                        table.setAttribute("class", "dataEntry");
                        var row, cell1, cell2;
                        var desc = ["Amount: ", "From: "];
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
                    
                    function validateForm(){ 
                        //This function validates the input on the form
                        //returns true when ok to send to the server
                        var purCount = parseInt(document.getElementById("numItems").value);
                        var valid = true;
                        var budget = "";
                        var amount, splitAmount;
                        var budgetsUsed = [];
                        
                        for(var i = 1; i<=purCount; i++){
                            budget = document.forms["mainForm"]["budget"+i].value;
                            if (budget === "SPLIT"){
                                amount = parseFloat(document.forms["mainForm"]["amount"+i].value);
                                //sum up the split budgets
                                splitAmount = 0;
                                //This next loop traverses through the splits
                                for(var j=1; j<=parseInt(document.forms["mainForm"]["splitCounter"+i].value); j++){
                                    splitAmount += parseFloat(document.forms["mainForm"]["splitAmount" + i + "_" + j].value);
                                    //check if assigning to the same budgets
                                    var bud = document.forms["mainForm"]["splitBudget" + i + "_" + j].value;
                                    if(budgetsUsed.indexOf(bud)=== -1){
                                        budgetsUsed.push(bud);
                                    }
                                    else{ //it is already in the array
                                        valid =false;
                                        alert ("The amount being split up must be between different budgets. You have more than one entry for " + bud );
                                        document.forms["mainForm"]["splitBudget" + i + "_" + j].focus();
                                        document.forms["mainForm"]["splitBudget" + i + "_" + j].select();
                                        break;
                                    }
                                }
                                if(splitAmount !== amount){
                                    valid =false;
                                    alert ("The amount being split up must total to " + amount + " but it only equals " + splitAmount + " for Purchase " + i);
                                    document.forms["mainForm"]["splitAmount" + i + "_" + j].focus();
                                    document.forms["mainForm"]["splitAmount" + i + "_" + j].select();
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
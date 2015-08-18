<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Finance Manager</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        
        
        <link rel="stylesheet" href="css/styles.css" type="text/css" />
        <link rel="stylesheet" href="css/navbar.css" type="text/css"/>
        <link rel="shortcut icon" href="images/logo.ico" />
    </head>
    <body>
        <header>
            <div id="logo" class="menuUp">
                <h1>Finance Manager</h1>
                <div id="navToggle"><a href="#">Menu</a></div>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">Budget <span class="toggle">Expand</span><span class="caret"></span></a>
                        <nav>
                            <ul>
                                <li><a href="monthlyOverview.php">Monthly Overview</a></li>
                                <li><a href="viewBudget.php">View Budget</a></li>
                                <li><a href="editBudget.php">Edit Budget</a></li>
                            </ul>
                        </nav>
                    </li>
                    <li><a href="#">Purchases <span class="toggle">Expand</span><span class="caret"></span></a>
                        <nav>
                            <ul>
                                <li><a href="addNewPurchase.php">Add New Purchase</a></li>
                                <li><a href="addNewIncome.php">Add New Income</a></li>
                                <li><a href="addNewBillPayment.php">Add New Bill Payment</a></li>
                                <li><a href="viewPurchase.php">View Purchases, Bills, Income</a></li>
                            </ul>
                        </nav>
                    </li>
                    <li><a href="#">Summary <span class="toggle">Expand</span><span class="caret"></span></a>
                        <nav>
                            <ul>
                                <li><a href="monthlySummary.php">Monthly Summary</a></li>
                                <li><a href="Yearly Summary.php">Yearly Summary</a></li>
                            </ul>
                        </nav>
                    </li>
                </ul>
            </nav>
        </header>
        <div id="content" class="content">
            <p id="welcome"> Welcome to the finance tracker! </p>
        </div>
        
        <script type="text/javascript">
                        
            //This script controls the navbar
            $(document).ready(function(){
                //move content wrapper below nav-bar
                if($(window).width() > "600") {
                    var navHeight = $("header").height() + 5;
                    document.getElementById("content").setAttribute("style", "margin-top:" + navHeight.toString() + "px");
                }
                else {
                    document.getElementById("content").setAttribute("style", "margin-top: 5px");
                }
               //var navHeight = $("header").height();
               //navHeight +=5;
               //$(".content").css("margin-top",  navHeight + "px");
               
               $("#navToggle a").click(function(e){ //disply or hide the tier 1 menu
                  e.preventDefault();
                  //cause the transtion of CSS formatting
                  $("header > nav").slideToggle();
                  $("#logo").toggleClass("menuUp menuDown");
                  
                  //clean up the menu if user leaves tier 2 options expanded
                  if ($("#logo").attr("class")=== "menuUp"){
                      if($("header > nav > ul > li > a").children(".toggle").html() ==='close'){
                            $("header > nav > ul > li > a").children(".toggle").html('expand');
                            $("header > nav > ul > li > a").siblings().hide();
                        }
                  }
               }); 
               
               //Create the tier 2 dropdown menu for mobile devices
               $("header > nav > ul > li > a").click(function(e){
                  if($(window).width() <="600"){
                      if($(this).siblings().size() > 0){
                          e.preventDefault();
                          $(this).siblings().slideToggle("fast");
                          //$(this).children(".toggle").html($(this).children(".toggle").html() === 'close' ? 'expand' : 'close');
                          
                          if ($(this).children(".toggle").html() === 'close'){
                              $(this).children(".toggle").html('expand');
                          }
                          else {
                              $(this).children(".toggle").html('close');
                          }
                      }
                  } 
               });
               //Clean up the menu for resizing
               $(window).resize(function(){
                  if($(window).width() > "600") {
                      $("header > nav").css("display", "block");
                      //$("header > nav").toggleClass("navHide navShow")
                      if($("#logo").attr('class') === "menuDown") {
                          $("#logo").toggleClass("menuUp menuDown");
                          if($("header > nav > ul > li > a").children(".toggle").html() ==='close'){
                              $("header > nav > ul > li > a").children(".toggle").html('expand');
                              $("header > nav > ul > li > a").siblings().hide();
                              
                          }
                      }
                      var navHeight = $("header").height() + 5;
                      document.getElementById("content").setAttribute("style", "margin-top:" + navHeight.toString() + "px");
                  }
                  else {
                      $("header > nav").css("display", "none");
                      //$("header  nav").attr("class", "navHide")
                      document.getElementById("content").setAttribute("style", "margin-top: 5px");
                  }
                  
               });
            });
            
        </script>
    </body>
</html>

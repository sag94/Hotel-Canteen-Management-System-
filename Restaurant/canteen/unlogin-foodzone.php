<?php
//checking connection and connecting to a database
require_once('connection/config.php');
//Connect to mysql server
    $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
    if(!$link) {
        die('Failed to connect to server: ' . mysql_error());
    }
    
    //Select database
    $db = mysql_select_db(DB_DATABASE);
    if(!$db) {
        die("Unable to select database");
    }

//selecting all records from the food_details table. Return an error if there are no records in the table
$result=mysql_query("SELECT * FROM food_details,categories WHERE food_details.food_category=categories.category_id ")
or die("A problem has occured ... \n" . "Our team is working on it at the moment ... \n" . "Please check back after few hours."); 
?>
<?php
    //retrive categories from the categories table
    $categories=mysql_query("SELECT * FROM categories")
    or die("A problem has occured ... \n" . "Our team is working on it at the moment ... \n" . "Please check back after few hours."); 
?>
<?php
    //retrive a currency from the currencies table
    //define a default value for flag_1
    $flag_1 = 1;
    $currencies=mysql_query("SELECT * FROM currencies WHERE flag='$flag_1'")
    or die("A problem has occured ... \n" . "Our team is working on it at the moment ... \n" . "Please check back after few hours."); 
?>
<?php
    if(isset($_POST['Submit'])){
        //Function to sanitize values received from the form. Prevents SQL injection
        function clean($str) {
            $str = @trim($str);
            if(get_magic_quotes_gpc()) {
                $str = stripslashes($str);
            }
            return mysql_real_escape_string($str);
        }
        //get category id
        $id = clean($_POST['category']);
        
        //selecting all records from the food_details and categories tables based on category id. Return an error if there are no records in the table
        $result=mysql_query("SELECT * FROM food_details,categories WHERE food_category='$id' AND food_details.food_category=categories.category_id ")
        or die("A problem has occured ... \n" . "Our team is working on it at the moment ... \n" . "Please check back after few hours."); 
    }
?>    
<?php
    //retrieving all rows from the cart_details table based on flag=0
    $flag_0 = 0;
    @$items=mysql_query("SELECT * FROM cart_details WHERE member_id='$memberId' AND flag='$flag_0'")
    or die("Something is wrong ... \n" . mysql_error()); 
    //get the number of rows
    $num_items = mysql_num_rows($items);
?>
<?php
    //retrieving all rows from the messages table
    $messages=mysql_query("SELECT * FROM messages")
    or die("Something is wrong ... \n" . mysql_error()); 
    //get the number of rows
    $num_messages = mysql_num_rows($messages);
?>
<?php
    //retrive a currency from the currencies table
    //define a default value for flag_1
    $flag_1 = 1;
    $currencies=mysql_query("SELECT * FROM currencies WHERE flag='$flag_1'")
    or die("A problem has occured ... \n" . "Our team is working on it at the moment ... \n" . "Please check back after few hours.");     
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Hotel Grand: Foodzone</title>

    <link href="css/css1.css" rel="stylesheet">

    <link href="css/css2.css" rel="stylesheet">
  </head>

  <body>
  
    <div class="brand">Hotel Grand</div>
    <div class="address-bar"> near bus stand |  Mandi , Himachal Pradesh(H.P) 175001 | 9805343650</div>
    <nav class="navbar navbar-default" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="">Hotel Grand</a>
        </div>

        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="foodzone.php">Food Zone</a></li>
            <li><a href="specialdeals.php">Special Deals</a></li>
            <li><a href="member-index.php">My Account</a></li>
            <li><a href="contactus.php">Contact Us</a></li>
         </ul>
        </div>
      </div>
    </nav>

    <div class="container">

      <div class="row">
        <div class="box">
          <div class="col-lg-12 text-center">
            <div id="carousel-example-generic" class="carousel slide">


<div id="center">
 <h1>CHOOSE YOUR FOOD</h1>
 <hr>
 <h3>Note: limit the food zone by selecting a category below:</h3>
 <form name="categoryForm" id="categoryForm" method="post" action="foodzone.php" onsubmit="return categoriesValidate(this)">
     <table width="444" align="center">
     <tr>
        <td width="72">Category</td>
        <td width="159"><select name="category" id="category">
        <option value="select">- select category -
        <?php 
        //loop through categories table rows
        while ($row=mysql_fetch_array($categories)){
        echo "<option value=$row[category_id]>$row[category_name]"; 
        }
        ?>
        </select></td>
        <td width="104"><input type="submit" name="Submit" value="Show Foods" /></td>
         <td width="89"><a href="cart.php">Show Order</a></td>
     </tr>
     </table>
 </form>
  <div>
      <table width="860" height="auto" style="text-align:center;">
        <tr>
                <th>Food Photo</th>
                <th>Food Name</th>
                <th>Food Description</th>
                <th>Food Category</th>
                <th>Food Price</th>
                <th>Action(s)</th>
        </tr>
        <?php
            $count = mysql_num_rows($result);
            if(isset($_POST['Submit']) && $count < 1){
                echo "<html><script language='JavaScript'>alert('There are no foods under the selected category at the moment. Please check back later.')</script></html>";
            }
            else{
                //loop through all table rows
                //$counter = 3;
                $symbol=mysql_fetch_assoc($currencies); //gets active currency
                while ($row=mysql_fetch_assoc($result)){
                    echo "<tr>";
                    echo '<td><a href=images/'. $row['food_photo']. ' alt="click to view full image" target="_blank"><img src=images/'. $row['food_photo']. ' width="80" height="70"></a></td>';
                    echo "<td>" . $row['food_name']."</td>";
                    echo "<td>" . $row['food_description']."</td>";
                    echo "<td>" . $row['category_name']."</td>";
                    echo "<td>" . $symbol['currency_symbol']. "" . $row['food_price']."</td>";
                    echo '<td><a href="cart-exec.php?id=' . $row['food_id'] . '">Add To Cart</a></td>';
                    echo "</td>";
                    echo "</tr>";
                    }      
                }
            mysql_free_result($result);
            mysql_close($link);
        ?>
      </table>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>

    <footer>
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <p>Copyright &copy;Friends &  Company 2013</p>
          </div>
        </div>
      </div>
    </footer>

    <script src="js/jquery1.js"></script>
    <script src="js/jquery2.js"></script>
    <script>
    $('.carousel').carousel({
    interval: 5000
    })
  </script>
  </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Bookstore.com</title>
        <link rel="stylesheet" href="styles.css" />
    </head>
    
    <!-- -->   
    <?php
    // connect to database and start session if not yet started
    include ('connection.php');
    require 'auth_check.php'; //File contains code checking if users session has the parmeter user_type as seller, if not it displays an Access Denied.
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
    
    ?>
    <body>
    <!--Title bar -->    
    <div class="titlebar">
        <h1>Welcome to Bookstore.com</h1>
    </div>
        
    <!--Navigation Bar -->   
    <div class="topnav">
    <a href="index.php">Home</a>
    <a href="buy.php">Buy</a>
    <a class="active" href="sell.php">Sell</a>
    <a href="register.php">Register</a>
    <?php if (isset($_SESSION['logged_in'])): ?><!--Checks if session is logged in and display a Logout button -->
        <a href="logout.php">Logout</a>
    <?php else: ?><a href="login.php">Login</a> <!--If user isnt logged in, show a login button -->
    <?php endif; ?>
    </div>
    
    <div class="Main">
    
        <div class="SellerDashboard">
        
        <div class="Listbox">
            <h3>Make a Listing</h3>
            <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                <table class="regtable">
                <tr><td>Product Name: </td><td><input type="text" name="l_productname" required></td></tr>
                <tr><td>Cost: </td><td><input type="number" name="l_cost" step="0.01" required></td></tr>
                <tr><td>Quantity: </td><td><input type="number" name="l_quantity" required></td></tr>
                <tr><td><input type="submit" name="listBtn" value="List product"></td></tr>
                </table>
            </form>
            <?php
            if (isset($_POST['listBtn'])){
                
            $itemname = $db->real_escape_string($_POST["l_productname"]);
            $cost = ($_POST["l_cost"]);
            $cost = (float)$cost;
            $quantity = ($_POST["l_quantity"]);
            
            $username = $_SESSION['user_id'];
            $sellerPID =mysqli_query($db,"SELECT SellerID FROM Sellers WHERE username = '$username'");
            if ($sellerPID && mysqli_num_rows($sellerPID) > 0) {
            $row = mysqli_fetch_assoc($sellerPID);
            $sellerPID = $row['SellerID'];}
            
            $sql="INSERT INTO products (itemName,cost,quantity, sellerID) VALUES ('$itemname','$cost','$quantity','$sellerPID')"; 
    
            $result=mysqli_query($db, $sql); 
            
            //check that the insert worked
            if ($result){
            echo "<h3 style='text-align:center'>Product Listed Succesfully!</h3>";
                }
            else {
            echo "<h3 style='text-align:center'>An error occured, Listing has not gone through.</h3>". mysqli_error($db);
                }
            }
            ?>
            
            </div>
        
        <div class="Soldbox">
            <h3>Previous Sales</h3>
            <?php
        //Pulls the SellerID from the username assosciated with the current session
        $username = $_SESSION['user_id'];
        $sellerPID =mysqli_query($db,"SELECT SellerID FROM Sellers WHERE username = '$username'");
        if ($sellerPID && mysqli_num_rows($sellerPID) > 0) {
        $row = mysqli_fetch_assoc($sellerPID);
        $sellerPID = $row['SellerID'];}
        
        // Fetches Information about a sold product by scanning the orders table, and pulling information by comparing 
        // the logged in Seller's SellerID and the SellerID attatched to the Order when a purchase was made 
        $stock = mysqli_query($db, 
        "SELECT 
        orders.orderID,
        products.productID,
        products.itemName,
        products.cost,
        CONCAT(customers.firstName, ' ', customers.lastName) AS customerName,
        1 AS quantity,
        products.cost AS totalPrice
        FROM 
        orders
        JOIN 
        products ON orders.productID = products.productID
        JOIN 
        sellers ON orders.sellerID = sellers.sellerID
        JOIN 
        customers ON orders.customerID = customers.customerID
        WHERE
        sellers.sellerid = '$sellerPID'
        ORDER BY 
        orders.orderID;
                ");
        if($stock){
        //If empty
        if(mysqli_num_rows($stock) == 0){
            echo "<h3>You haven't sold anything yet :(</h3>";
        }
        else{
            echo "<table border='1' >";
            echo "<tr><th>OrderID</th><th>ProductID</th><th>Product</th><th>Cost</th><th>Customer</th><th>Amt. Bought</th><th>Profit</th></tr>";
            
            while($row = mysqli_fetch_assoc($stock)){
                echo "<tr>";
                foreach($row as $value)
                {
                    echo "<td>$value</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
    }
    //An error occured
    else {
        echo "<h3>Error x.x</h3>";
    }
        
    ?>
            </div>
        
        </div>
        




    <p style=font-size:15px>Made by Georgy Bodrov:K00299992</p>
    </div>
    
    
    </body>
</html>


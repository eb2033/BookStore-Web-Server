<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Bookstore.com</title>
        <link rel="stylesheet" href="styles.css" />
    </head>
    
    <!-- -->   
    
    <body>
    <?php
    // connect to database and start session if not yet started
    include ('connection.php');
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
    
    ?>
    
    <!--Title bar -->    
    <div class="titlebar">
        <h1>Welcome to Bookstore.com</h1>
    </div>
        
    <!--Navigation Bar -->   
    <div class="topnav">
    <a class="active" href="index.php">Home</a>
    <a href="buy.php">Buy</a>
    <a href="sell.php">Sell</a>
    <a href="register.php">Register</a>
    <?php if (isset($_SESSION['logged_in'])): ?> <!--Checks if session is logged in and display a Logout button -->
        <a href="logout.php">Logout</a>
    <?php else: ?><a href="login.php">Login</a><!--If user isnt logged in, show a login button -->
    <?php endif; ?>
    </div>
      
    <!--Main Body -->    
    <div class="Main">
    <p>Welcome to BookStore.com, On this web store you can browse and order books aswell as register as a Seller and sell books from home or
        from your store.</p>
    <p>To begin Buying / Selling , Register an account by clicking <a href ="register.php">Register</a> in the top navigation bar.</p>
    <br>
        
        
    <h2>Currently for sale!</h2>
       <!--Displays a table of the products table in bookstoredb --> 
    <?php
    $stock = mysqli_query($db, 'SELECT products.itemName,products.cost,products.quantity,concat(sellers.firstname," ",sellers.lastname) AS sellername FROM sellers,products WHERE sellers.sellerID = products.sellerID');
        if($stock){
        //If empty
        if(mysqli_num_rows($stock) == 0){
            echo "<h3>Currently Nobody is Selling :(</h3>";
        }
        //Else if there is atleast 1 item being sold
        else{
            echo "<table border='1' >";
            echo "<tr><th>Item</th><th>Cost (€)</th><th>Quantity</th><th>Seller</th></tr>";
            
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
    <p style=font-size:15px>Made by Georgy Bodrov:K00299992</p>
    </div>
    </body>
</html>


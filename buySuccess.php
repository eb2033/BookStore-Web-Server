<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Order Successful</title>
        <link rel="stylesheet" href="styles.css" />
    </head>
    
    <!-- -->   
    <?php
    // connect to database
    include ('connection.php');
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
    
    ?>
    <body>
    <!--Title bar -->    
    <div class="titlebar">
        <h1>Thanks for Shopping with us!</h1>
    </div>
        
    <!--Navigation Bar -->   
    <div class="topnav">
    <a href="index.php">Home</a>
    <a class="active" href="buy.php">Buy</a>
    <a href="sell.php">Sell</a>
    <a href="register.php">Register</a>
    <?php if (isset($_SESSION['logged_in'])): ?><!--Checks if session is logged in and display a Logout button -->
        <a href="logout.php">Logout</a>
    <?php else: ?><a href="login.php">Login</a> <!--If user isnt logged in, show a login button -->
    <?php endif; ?>
    </div>
    
    <div class="Main">
        <h1>Thanks for your order!</h1>
        <a href="index.php" class="button">Back to Home</a>




    <p style=font-size:15px>Made by Georgy Bodrov:K00299992</p>
    </div>
    
    
    </body>
</html>


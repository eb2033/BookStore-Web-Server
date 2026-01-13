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
        
    <div class="topnav">
    <a href="index.php">Home</a>
    <a href="buy.php">Buy</a>
    <a href="sell.php">Sell</a>
    <a class="active" href="register.php">Register</a>
    <?php if (isset($_SESSION['logged_in'])): ?> <!--Checks if session is logged in and display a Logout button -->
        <a href="logout.php">Logout</a>
    <?php else: ?><a href="login.php">Login</a><!--If user isnt logged in, show a login button -->
    <?php endif; ?>
    </div>
    
    <div class="Main">
    <h3 style=text-align:center>Register your Account</h3>
        
        <!--Flex container holding the two registration forms, one for each kind of account -->
        <!--Each flex Container also hold the php code so the Success/Error message appears underneath the form -->
        <div class="Registration">
        <div class="CustomerReg">
        <h4>Customer Registration</h4>
            <img src="customer.png" alt="CustomerPic" style="width:250px;height:250px">
            <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                <table class="regtable">
                <tr><td>First Name: </td><td><input type="text" name="c_firstname"></td></tr>
                <tr><td>Last Name: </td><td><input type="text" name="c_lastname"></td></tr>
                <tr><td>Username: </td><td><input type="text" name="c_username"></td></tr>
                <tr><td>Password: </td><td><input type="password" name="c_password"></td></tr>
                <tr><td><input type="submit" name="C_registerBtn" value="REGISTER"></td></tr>
                </table>
            </form>
            
        </div>
            
        <div class="SellerReg">
        <h4>Seller Registration</h4>
            <img src="seller.jpg" alt="SellerPic" style="width:250px;height:250px">
            <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                <table class="regtable">
                <tr><td>First Name: </td><td><input type="text" name="s_firstname"></td></tr>
                <tr><td>Last Name: </td><td><input type="text" name="s_lastname"></td></tr>
                <tr><td>Username: </td><td><input type="text" name="s_username"></td></tr>
                <tr><td>Password: </td><td><input type="password" name="s_password"></td></tr>
                <tr><td><input type="submit" name="S_registerBtn" value="REGISTER"></td></tr>
                </table>
            </form>
        
        </div>

        </div>

    <!--PHP scripts to register the entered information into database -->
    
    <!-----------Customer ------------->
    <?php
      if (isset($_POST['C_registerBtn'])){

    $username = addslashes($_POST["c_username"]);

    //Check if username exists
    $sql= "SELECT * FROM customers WHERE username = '$username'";
    $result=mysqli_query($db, $sql);

    //if there is username overlap
    if($result){
        if(mysqli_num_rows($result) > 0)
        {
            echo "<h3>This User already exists!</h3>";
            exit();
        }
    }

    //If username is not taken

    $firstname = ($_POST["c_firstname"]);
    $lastname = ($_POST["c_lastname"]);
    $password = ($_POST["c_password"]);
    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

    $sql="INSERT INTO customers (firstname,lastname,username, password) VALUES ('$firstname','$lastname','$username','$hashedpassword')"; 
    
    $result=mysqli_query($db, $sql); 

    //check that the insert worked
    if ($result){
        echo "<h3 style='text-align:center'>Succesfully registered! Happy shopping!</h3>";
    }
    else {
        echo "<h3 style='text-align:center'>An error occured, you have not been registered!</h3>";
    }

    //close the connection
    mysqli_close($db);

}?>

    <!----------- Seller -------------->
<?php
      if (isset($_POST['S_registerBtn'])){

    $username = addslashes($_POST["s_username"]);

    //Check if username exists
    $sql= "SELECT * FROM customers WHERE username = '$username'";
    $result=mysqli_query($db, $sql);

    //if there is username overlap
    if($result){
        if(mysqli_num_rows($result) > 0)
        {
            echo "<h3>This User already exists!</h3>";
            exit();
        }
    }

    //If username is not taken

    $firstname = ($_POST["s_firstname"]);
    $lastname = ($_POST["s_lastname"]);
    $password = ($_POST["s_password"]);
    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

    $sql="INSERT INTO sellers (firstname,lastname,username, password) VALUES ('$firstname','$lastname','$username','$hashedpassword')"; 
    
    $result=mysqli_query($db, $sql); 

    //check that the insert worked
    if ($result){
        echo "<h3 style='text-align:center'>Succesfully registered! Happy selling!</h3>";
    }
    else {
        echo "<h3 style='text-align:center' >An error occured, you have not been registered!</h3>";
    }

    //close the connection
    mysqli_close($db);

}?>
        
    <p style=font-size:15px>Made by Georgy Bodrov:K00299992</p>
    </div>
    
    
    </body>
</html>


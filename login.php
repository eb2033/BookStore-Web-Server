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
        session_start();}
    ?>    
    
    <!--Title bar -->    
    <div class="titlebar">
        <h1>Welcome to Bookstore.com</h1>
    </div>
        
    <!--Navigation Bar -->   
    <div class="topnav">
    <a href="index.php">Home</a>
    <a href="buy.php">Buy</a>
    <a href="sell.php">Sell</a>
    <a href="register.php">Register</a>
    <?php if (isset($_SESSION['logged_in'])): ?> <!--Checks if session is logged in and display a Logout button -->
        <a href="logout.php">Logout</a>
    <?php else: ?><a  class="active" href="login.php">Login</a> <!--If user isnt logged in, show a login button -->
    <?php endif; ?>
    </div>
    
    <div class="Main">
    <div class="loginContainer">
        
        <!--Customer Login box --> 
        <div class="Cloginbox">
        <h3>Customer Login</h3>
        <form action="login.php" method="post">
            <table>
        <tr><td>User Name:</td><td><input required type="text" name="c_username"></td></tr>
        <tr><td>Password:</td><td><input required type="password" name="c_password"></td></tr>
        <tr><td><input type="submit" name="C_loginBtn" value="Log In"></td></tr>
            </table>
        </form>
        
        <!--Seller Login box --> 
        </div>
        <div class="Sloginbox">
        <h3>Seller Login</h3>
        <form action="login.php" method="post">
            <table>
        <tr><td>User Name:</td><td><input required type="text" name="s_username"></td></tr>
        <tr><td>Password:</td><td><input required type="password" name="s_password"></td></tr>
        <tr><td><input type="submit" name="S_loginBtn" value="Log In"></td></tr>
            </table>
        </form>
        
        </div>
        
        
        
    </div>
    
    <?php

        //Submit button functionality. 
    if(isset($_POST['C_loginBtn']))
    {
        $username = $_POST['c_username'];
        $password = $_POST['c_password'];
        $remember_me = isset($_POST['remember_me']);
        
        $sql = "SELECT * FROM customers WHERE username = '$username'";  
        $result = mysqli_query($db, $sql);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) 
        {
                echo "<h3>Welcome $username!</h3>";
                $userID = "Select 'CustomerID' From customers WHERE username = '$username'";
                session_regenerate_id(true);
                 $_SESSION['user_id'] = $username;
                 $_SESSION['user_type'] = "customer";
                 $_SESSION['logged_in'] = true;
                 $_SESSION['last_activity'] = time();
            
                 if ($remember_me) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token',$token, time() + 300,'/');
                    echo "<h3>",$token,"</h3>";
                }
            
        }
        else
        {
            //Outputting a message that login failed
            echo "<h3 style='text-align:center>Log in failed, please try again</h3>";
        }

    }

    ?>
    
    <?php

    if(isset($_POST['S_loginBtn']))
    {
        $username = $_POST['s_username'];
        $password = $_POST['s_password'];
        $remember_me = isset($_POST['remember_me']); 
        
        $sql = "SELECT * FROM sellers WHERE username = '$username'";  
        $result = mysqli_query($db, $sql);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) 
        {
                echo "<h3>Welcome $username!</h3>";
                $userID = "Select 'SellerID' From sellers WHERE username = '$username'";
                session_regenerate_id(true);
                 $_SESSION['user_id'] = $username; // 
                 $_SESSION['logged_in'] = true;
                 $_SESSION['user_type'] = 'seller';
                 $_SESSION['last_activity'] = time();
                 
            
                 if ($remember_me) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token',$token, time() + 300,'/');
                    echo "<h3>",$token,"</h3>";
                }
            
        }
        else
        {
            //Outputting a message that login failed
            echo "<h3 style='text-align:center>Log in failed, please try again</h3>";
        }

    }

     ?>
    
    



    <p style=font-size:15px>Made by Georgy Bodrov:K00299992</p>
    </div>
    
    
    </body>
</html>
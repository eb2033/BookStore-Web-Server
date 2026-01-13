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
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
    // Verify session
if (isset($_SESSION['logged_in'])) {
    // Session timeout (10m)
    $inactive = 600;
    if (time() - $_SESSION['last_activity'] > $inactive) {
        session_unset();
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        header("Location: login.php");
        exit();
    }
    $_SESSION['last_activity'] = time();
} else {
    // Not logged in - redirect to login page
    header("Location: login.php");
    exit();
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
    <a class="active" href="buy.php">Buy</a>
    <a href="sell.php">Sell</a>
    <a href="register.php">Register</a>
    <?php if (isset($_SESSION['logged_in'])): ?><!--Checks if session is logged in and display a Logout button -->
        <a href="logout.php">Logout</a>
    <?php else: ?><a href="login.php">Login</a> <!--If user isnt logged in, show a login button -->
    <?php endif; ?>
    </div>
    
    <div class="Main">
        <?php
            // Initialize cart if it doesn't exist
            if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

            // Cart action
            if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
                    $product_id = $_POST['product_id'];
    
            // Verify product exists
            $product_query = mysqli_query($db, "SELECT * FROM products WHERE productID = '$product_id'");
            if (mysqli_num_rows($product_query) > 0) {
            $product = mysqli_fetch_assoc($product_query);
        
            // Add to cart, -1 to quanitity
            if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['itemName'],
                'price' => $product['cost'],
                'quantity' => 1, // Only 1 allowed
                'seller' => $product['sellerID']
            ];
            
            // Update product quantity in database
            mysqli_query($db, "UPDATE products SET quantity = quantity - 1 WHERE productID = '$product_id'");
        }
    }
}

            // Remove from cart, +1 to quanitity
            if (isset($_GET['remove_from_cart'])) {
                $product_id = $_GET['remove_from_cart'];
            if (isset($_SESSION['cart'][$product_id])) {
                mysqli_query($db, "UPDATE products SET quantity = quantity + 1 WHERE productID = '$product_id'");
        
                // Remove from cart
                unset($_SESSION['cart'][$product_id]);
                }
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
}
?>


        
<!-- Product Listing -->
<h1>Available Products</h1>
<?php
$stock = mysqli_query($db, 'SELECT products.productID, products.itemName, products.cost, products.quantity, 
                           CONCAT(sellers.firstname, " ", sellers.lastname) AS sellername 
                           FROM sellers, products 
                           WHERE sellers.sellerID = products.sellerID');

if ($stock) {
    if (mysqli_num_rows($stock) == 0) {
        echo "<h3>Currently Nobody is Selling :(</h3>";
    } else {
        echo "<table>";
        echo "<tr><th>Item</th><th>Cost (€)</th><th>Seller</th><th>Quantity</th><th>Action</th></tr>";
        
        while ($row = mysqli_fetch_assoc($stock)) {
            echo "<tr>";
            echo "<td>".htmlspecialchars($row['itemName'])."</td>";
            echo "<td>".number_format($row['cost'], 2)."</td>";
            echo "<td>".htmlspecialchars($row['sellername'])."</td>";
            echo "<td>".number_format($row['quantity'])."</td>";
            echo "<td>";
            
            if (isset($_SESSION['cart'][$row['productID']])) {
                echo "<span>In Cart</span>";
            } else {
                echo "<form method='post' style='display:inline;'>
                        <input type='hidden' name='product_id' value='".$row['productID']."'>
                        <input type='submit' name='add_to_cart' value='Add to Cart'>
                      </form>";
            }
            
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
          }
           }
                    ?>
    
        <!-- Shopping Cart Display -->
                <div class="cart">
                <h2>Your Cart</h2>
                <?php if (!empty($_SESSION['cart'])): ?>
                <table border='1' >
            <tr><th>Item</th><th>Price</th><th>Action</th></tr>
            <?php 
            $cart_total = 0;
            foreach ($_SESSION['cart'] as $id => $item): 
                $cart_total += $item['price'];
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>€<?= number_format($item['price'], 2) ?></td>
                    <td><a href="?remove_from_cart=<?= $id ?>">Remove</a></td>
                </tr>
            <?php endforeach; ?>
            <tr><td><strong>Total:</strong></td><td>€<?= number_format($cart_total, 2) ?></td>
                <td><form action="buyConfirm.php" method="post">
                <button type="submit" name="proceed_to_checkout">Proceed to Checkout</button></form></td></tr>
        </table>
        
                <?php else: ?>
        <b>Your cart is empty</b>
                <?php endif; ?>
                    </div>
     <p style=font-size:15px>Made by Georgy Bodrov:K00299992</p>
        </div>

    </body>
</html>


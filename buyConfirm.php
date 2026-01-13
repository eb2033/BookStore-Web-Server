<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Bookstore.com</title>
        <link rel="stylesheet" href="styles.css" />
    </head>
    
    <!-- -->   
    <?php
session_start();
require 'connection.php';

// Redirect if cart is empty or user not logged in
if (empty($_SESSION['cart']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Process order confirmation
if (isset($_POST['confirm_order'])) {
    // Getting customer ID from session user_id / username
    $customer_query = "SELECT customerID FROM customers WHERE username = ?";
    $stmt = $db->prepare($customer_query);
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die("Customer not found!");
    }
    
    $customer_row = $result->fetch_assoc();
    $customer_id = $customer_row['customerID'];
    // Create order for each product in cart
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $insert_sql = "INSERT INTO orders (productID, sellerID, customerID) 
                      VALUES (?, ?, ?)";
        $stmt = $db->prepare($insert_sql);
        $stmt->bind_param("iii", $product_id, $item['seller'], $customer_id);
        
        if (!$stmt->execute()) {
            die("Error creating order: " .$customer_id. $db->error);
        }
    }
    
    //redirect to success page
    unset($_SESSION['cart']);
    header("Location: buySuccess.php");
    exit();
}

// Calculate cart total
$cart_total = array_sum(array_column($_SESSION['cart'], 'price'));
?>

    
    <body>
    <!--Title bar -->    
    <div class="titlebar">
        <h1>Confirm Your Order</h1>
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
        <h1>Order Confirmation</h1>
    <p>Please review your order before confirming:</p>
    
    <table>
        <tr>
            <th>Product</th>
            <th>Seller</th>
            <th>Price</th>
        </tr>
        <?php foreach ($_SESSION['cart'] as $product_id => $item): 
                // Get seller details
                $seller_stmt = $db->prepare("SELECT firstname, lastname FROM sellers WHERE sellerID = ?");
                $seller_stmt->bind_param("i", $item['seller']);
                $seller_stmt->execute();
                $seller = $seller_stmt->get_result()->fetch_assoc();
            ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($seller['firstname'] . ' ' . $seller['lastname']) ?></td>
                <td>€<?= number_format($item['price'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="2">Total:</td>
                <td>€<?= number_format($cart_total, 2) ?></td>
            </tr>
        </table>
        
        <div class="customer-info">
            <h3>Customer Information</h3>
            <?php
            // Get customer details
            $customer_stmt = $db->prepare("SELECT firstname, lastname FROM customers WHERE username = ?");
            $customer_stmt->bind_param("i", $_SESSION['user_id']);
            $customer_stmt->execute();
            $customer = $customer_stmt->get_result()->fetch_assoc();
            ?>
            <p>
                <strong>Name:</strong> <?= htmlspecialchars($customer['firstname'] . ' ' . $customer['lastname']) ?><br>
                
            </p>
        </div>
        
        <form method="post">
            <button type="submit" name="confirm_order" class="btn btn-confirm">Confirm Order</button>
            <a href="buy.php" class="btn btn-cancel">Cancel</a>
        </form>
    </div>
</body>
</html>

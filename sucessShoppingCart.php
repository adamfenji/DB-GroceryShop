<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <style>
        #buttonsForm{
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        #actionColumn{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        h1{
            padding-top: 25px;
            padding-bottom: 25px;
            text-align: center;    
        }

        .alert{
            width: 70%;
            margin: auto;
        }

        .table{
            width: 85vw;
            text-align: center;
            margin: auto;
        }
    </style>

</head>

<body>

<?php
    require "db.php";
    session_start();

    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }

    $userData = getUserData($_SESSION["username"]);
    $firstName = $userData['first_name'];
    $customer_id = $userData['customer_id'];

    // Handle update and remove actions
    if (isset($_POST['update'])) {
        $productId = $_POST['product_id'];
        $newQuantity = $_POST['update_quantity'];
        updateShoppingCartQuantity($customer_id, $productId, $newQuantity);
        
        echo "<br/>";
        echo "<div class='alert alert-success' role='alert'>Quantity updated!</div>";
    }

    if (isset($_POST['remove'])) {
        $productId = $_POST['product_id'];
        removeProductFromCart($customer_id, $productId);

        echo "<br/>";
        echo "<div class='alert alert-success' role='alert'>Product removed from the shopping cart!</div>";
    }

    // Get shopping cart contents
    $cartContents = getShoppingCartContents($customer_id);
    ?>

    <h1>Shopping Cart</h1>

    <?php

$total = 0;
    if (!empty($cartContents)) {
        echo "<table class='table table-hover table-bordered'>";
        echo "<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th><th>Action</th></tr>";

        

        foreach ($cartContents as $item) {

            $total = $total + $item['total'];

            echo "<tr>";
            echo "<td>{$item['product_name']}</td>";
            echo "<td>{$item['quantity']}</td>";
            echo "<td>{$item['price']}</td>";
            echo "<td>{$item['total']}</td>";
            echo "<td id='actionColumn'>
                    <form method='post'>
                        <input type='hidden' name='product_id' value='{$item['product_id']}'>
                        <label for='update_quantity'>Update Quantity:</label>
                        <div class='input-group mb-3'>
                        <input type='number' id='update_quantity' name='update_quantity' value='{$item['quantity']}' min='1' class='form-control' aria-label='Username' aria-describedby='basic-addon1'>
                        </div>
                        <button type='submit' name='update' class='btn btn-outline-secondary'>Update</button>
                    </form>
                    <form method='post'>
                        <input type='hidden' name='product_id' value='{$item['product_id']}'>
                        <button type='submit' name='remove' class='btn btn-outline-danger'>Remove</button>
                    </form>
                  </td>";
            echo "</tr>";
        }

        echo "</table>";

        // Checkout button
        echo '<br/>';
        echo "<form id='buttonsForm' method='post'>";
        echo "<button type='submit' name='checkout' class='btn btn-outline-primary'>Checkout</button>";
        echo "<button type='button' class='btn btn-outline-secondary' onclick=\"location.href='customerMainPage.php';\">Main Page</button>";
        echo "</form>";
    } else {
        echo "<p align='center'>Your shopping cart is empty.</p>";

        echo "<br/>";
        echo "<div class='alert alert-success' role='alert'>Checkout successful! Thank you for your purchase.</div>";
        echo "<br/>";

        echo "<button style='margin: auto; display: block;' type='button' class='btn btn-outline-secondary' onclick=\"location.href='customerMainPage.php';\">Main Page</button>";
    }

    // Handle checkout
    if (isset($_POST['checkout'])) {

        createOrder($customer_id, $cartContents, $total);
        clearShoppingCart($customer_id);

        echo "<br/>";
        echo "<div class='alert alert-success' role='alert'>Checkout successful! Thank you for your purchase.</div>";
    
        header("Location: sucessShoppingCart.php");
    }
    ?>

</body>

</html>

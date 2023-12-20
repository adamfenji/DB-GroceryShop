<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <style>
    #orderCarousel {
        border: 1px solid black;
        width: 80vw;
        margin: auto;
        text-align: center;
        padding-top: 25px;
        padding-bottom: 25px;
    }

    .alert{
        width: 70%;
        margin: auto;
    }

    .navButtons{
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    h1{
        padding-top: 25px;
        padding-bottom: 25px;
        text-align: center;    
    }

    .table{
        width: 70%;
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
        header("Location: browsingMainPage.php");
        exit();
    }

    $userData = getUserData($_SESSION["username"]);
    $firstName = $userData['first_name'];
    $customer_id = $userData['customer_id'];
    ?>

    <h1>Your Orders</h1>

    <?php
    // Fetch and display orders for the current user
    $orders = getOrders($customer_id);

    if (!empty($orders)) {
        echo '<div id="orderCarousel" class="carousel carousel-dark slide" data-bs-ride="carousel">';
        echo '<div class="carousel-inner">';

        $firstOrder = true;

        foreach ($orders as $order) {
            echo '<div class="carousel-item' . ($firstOrder ? ' active' : '') . '">';
            echo "<p><b>Order ID:</b> {$order['order_id']}</p>";
            echo "<p><b>Order Date:</b> {$order['order_date']}</p>";

            // Fetch and display order details
            $orderDetails = getOrderDetails($order['order_id']);

            echo "<div>";
            if (!empty($orderDetails)) {
                echo "<table class='table table-bordered'>";
                echo "<thead><tr><th>Product</th><th>Quantity</th><th>Price</th></tr></thead>";
                echo "<tbody>";

                foreach ($orderDetails as $detail) {
                    echo "<tr>";
                    echo "<td>{$detail['product_name']}</td>";
                    echo "<td>{$detail['quantity']}</td>";
                    echo "<td>{$detail['price_at_order']}</td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>No items in this order.</p>";
            }
            echo "</div>";

            echo '</div>';

            $firstOrder = false;
        }

        echo '</div>';

        echo '<button class="carousel-control-prev" type="button" data-bs-target="#orderCarousel" data-bs-slide="prev">';
        echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
        echo '<span class="visually-hidden">Previous</span>';
        echo '</button>';

        echo '<button class="carousel-control-next" type="button" data-bs-target="#orderCarousel" data-bs-slide="next">';
        echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
        echo '<span class="visually-hidden">Next</span>';
        echo '</button>';

        echo '</div>';
    } else {
        echo "<p align='center'>No orders available.</p>";
    }
    ?>

    <br/>
    <div class="navButtons">
    <button type="button" onclick="location.href='customerMainPage.php';" class='btn btn-outline-secondary'>Main Page</button>
    <button type="button" onclick="location.href='shoppingCart.php';" class='btn btn-outline-secondary'>Shopping Cart</button>
    </div>

</body>

</html>

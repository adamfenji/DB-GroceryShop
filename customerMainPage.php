<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Main Page</title>

    <style>
        #categoryForm{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 60%;
            margin: auto;
        }

        h1{
            padding-top: 25px;
            padding-bottom: 25px;
            text-align: center;
            
        }

        .navButtons{
            text-align: center;
        }

        #quantityForm{
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .alert{
            width: 70%;
            margin: auto;
        }

        .container{
            width: 90vw;
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

    $selectedCategory = isset($_POST['category']) ? $_POST['category'] : '';
    ?>
    
    <h1> Welcome, <?php echo $firstName; ?>!</h1>

    <div class="navButtons">
    <button type="button" onclick="location.href='viewOrder.php';" class="btn btn-outline-secondary">View Orders</button>
    <button type="button" onclick="location.href='shoppingCart.php';" class="btn btn-outline-secondary">Shopping Cart</button>
    <button type="button" onclick="location.href='passwordChange.php';" class="btn btn-outline-secondary">Change Password</button>
    <button type="button" onclick="location.href='logout.php';" class="btn btn-outline-secondary">Logout</button>
    <br/>
    <br/>
    </div>
    

    <form method="post" id="categoryForm">
        <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
        <select class="form-select" aria-label="Default select example" id="category" name="category">
            <option value="" disabled selected>Select a category...</option>
            <?php
                $categories = getAllCategories();
                foreach ($categories as $category) {
                    $selected = ($category['category_id'] == $selectedCategory) ? 'selected' : '';
                    echo "<option value='{$category['category_id']}' {$selected}>{$category['description']}</option>";
                }
            ?>
        </select>
        <button type="submit" name="search" class="btn btn-outline-primary">Search</button>
    </form>

    <?php
if (isset($_POST['search'])) {
    if (!empty($selectedCategory)) {
        $products = getProductsInCategory($selectedCategory);
        if (!empty($products)) {
            echo "<div class='container'>";
            echo "<div class='row row-cols-md-3'>";

            foreach ($products as $product) {
                echo "<div class='col' style='margin-top: 20px;'>";
                echo "<div class='card' style='width: 15rem;'>";
                echo "<img src='{$product['image']}' class='card-img-top' alt='{$product['name']}'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>{$product['name']}</h5>";
                echo "<p class='card-text'>{$product['description']}</p>";
                echo "<p>Price: $ {$product['price']}</p>";

                // Display quantity input and add to cart button if logged in
                if (isset($_SESSION["username"])) {
                    echo "<form id='quantityForm' method='post'>";
                    echo "<label for='quantity'>Quantity:</label>";
                    echo "<div class='input-group'>";
                    echo "<input type='number' id='quantity' name='quantity' value='1' min='1' class='form-control' aria-label='Dollar amount (with dot and two decimal places)'>";
                    echo "</div>";
                    echo "<input type='hidden' name='product_id' value='{$product['product_id']}'>";
                    echo "<button type='submit' name='addToCart' class='btn btn-outline-secondary'>Add to Cart</button>";
                    echo "</form>";
                }

                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            // Close the divs opened for the container and row
            echo "</div>";
            echo "</div>";
        } else {
            echo "<p>No products in the selected category.</p>";
        }
    }
}
?>

    <?php
        if (isset($_POST['addToCart'])) {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];
            addToCart($customer_id, $productId, $quantity);

            echo '<br/>';
            echo "<div class='alert alert-success' role='alert'>Product added to the shopping cart!</div>";

        }
    ?>

    

</body>

</html>
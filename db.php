<?php

function connectDB(){
    $config = parse_ini_file("/local/my_web_files/afenjiro/db.ini");
    $dbh = new PDO($config['dsn'], $config['username'], $config['password']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

function authenticate($user, $passwd) {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT COUNT(*) FROM Customer WHERE username = :username AND password = SHA2(:passwd, 256)");
        $statement->bindParam(":username", $user);
        $statement->bindParam(":passwd", $passwd);
        $statement->execute();
        $count = $statement->fetchColumn();
        $dbh = null;
        return $count;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function registerUser($username, $password, $firstName, $lastName, $email, $shippingAddress) {
    try {
        $dbh = connectDB();

        // Check if the username is already taken
        $checkUsernameStatement = $dbh->prepare("SELECT COUNT(*) FROM Customer WHERE username = :username");
        $checkUsernameStatement->bindParam(":username", $username);
        $checkUsernameStatement->execute();
        $usernameExists = $checkUsernameStatement->fetchColumn();

        // Check if the email is already taken
        $checkEmailStatement = $dbh->prepare("SELECT COUNT(*) FROM Customer WHERE email = :email");
        $checkEmailStatement->bindParam(":email", $email);
        $checkEmailStatement->execute();
        $emailExists = $checkEmailStatement->fetchColumn();

        if ($usernameExists || $emailExists) {
            $dbh = null;
            return 'userExist'; // Username or email already exists
        }
        if ($password !== $_POST["confirmPassword"]) {
            $dbh = null;
            return 'passwordMismatch'; // Passwords do not match
        }

        // Get the next available customer ID
        $statement = $dbh->query("SELECT MAX(CAST(SUBSTRING(customer_id, 5) AS SIGNED)) AS max_id FROM Customer");
        $maxId = $statement->fetch(PDO::FETCH_ASSOC)['max_id'];
        $nextId = 'CUST' . ($maxId + 1);

        $statement = $dbh->prepare("INSERT INTO Customer (customer_id, username, password, first_name, last_name, email, shipping_address) 
                                   VALUES (:customer_id, :username, SHA2(:password, 256), :firstName, :lastName, :email, :shippingAddress)");
        $statement->bindParam(":customer_id", $nextId);
        $statement->bindParam(":username", $username);
        $statement->bindParam(":password", $password);
        $statement->bindParam(":firstName", $firstName);
        $statement->bindParam(":lastName", $lastName);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":shippingAddress", $shippingAddress);

        $statement->execute();
        $dbh = null;
        return 'success'; // Registration successful
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function changePassword($username, $oldPassword, $newPassword) {
    try {
        $dbh = connectDB();

        $statement = $dbh->prepare("SELECT COUNT(*) FROM Customer WHERE username = :username AND password = SHA2(:oldPassword, 256)");
        $statement->bindParam(":username", $username);
        $statement->bindParam(":oldPassword", $oldPassword);
        $statement->execute();

        if ($statement->fetchColumn() == 1) {
            $hashedNewPassword = hash('sha256', $newPassword);
            $updateStatement = $dbh->prepare("UPDATE Customer SET password = :newPassword WHERE username = :username");
            $updateStatement->bindParam(":newPassword", $hashedNewPassword);
            $updateStatement->bindParam(":username", $username);
            $updateStatement->execute();

            $dbh = null;
            return true;
        } else {
            $dbh = null;
            echo '<p style="color:red">Invalid old password</p>';
            die();
        }
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getUserData($username) {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT * FROM Customer WHERE username = :username");
        $statement->bindParam(":username", $username);
        $statement->execute();
        $userData = $statement->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        return $userData;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getAllCategories() {
    try {
        $dbh = connectDB();
        $statement = $dbh->query("SELECT * FROM Category");
        $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
        $dbh = null;
        return $categories;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getProductsInCategory($category_id) {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT * FROM Product WHERE category_id = :category_id");
        $statement->bindParam(":category_id", $category_id);
        $statement->execute();
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
        $dbh = null;
        return $products;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function addToCart($customer_id, $product_id, $quantity) {
    try {
        $dbh = connectDB();

        // Check if the customer has a cart
        $checkCartStatement = $dbh->prepare("SELECT cart_id FROM ShoppingCart WHERE customer_id = :customer_id");
        $checkCartStatement->bindParam(":customer_id", $customer_id);
        $checkCartStatement->execute();
        $cartId = $checkCartStatement->fetchColumn();

        if(!checkProductQuantity($quantity, $product_id)){
            echo "<br/>";
            echo "<div class='alert alert-danger' role='alert'>Quantity Update Failed! Not enough stock.</div>";
            die();
        }

        if (!$cartId) {
            // If the customer doesn't have a cart, create a new one
            $idNum = substr($customer_id, 4);
            $cartId = 'CART' . $idNum;

            $createCartStatement = $dbh->prepare("INSERT INTO ShoppingCart (cart_id, customer_id) VALUES (:cart_id, :customer_id)");
            $createCartStatement->bindParam(":cart_id", $cartId);
            $createCartStatement->bindParam(":customer_id", $customer_id);
            $createCartStatement->execute();
        }

        // Check if the product is already in the shopping cart
        $checkProductStatement = $dbh->prepare("SELECT COUNT(*) FROM ShoppingCartContent WHERE cart_id = :cart_id AND product_id = :product_id");
        $checkProductStatement->bindParam(":cart_id", $cartId);
        $checkProductStatement->bindParam(":product_id", $product_id);
        $checkProductStatement->execute();
        $productExists = $checkProductStatement->fetchColumn();

        if ($productExists) {
            // If the product is already in the cart, update the quantity
            $updateStatement = $dbh->prepare("UPDATE ShoppingCartContent 
                                             SET quantity = quantity + :quantity 
                                             WHERE cart_id = :cart_id AND product_id = :product_id");
        } else {
            // If the product is not in the cart, insert a new record
            $updateStatement = $dbh->prepare("INSERT INTO ShoppingCartContent (cart_id, product_id, quantity) 
                                             VALUES (:cart_id, :product_id, :quantity)");
        }

        $updateStatement->bindParam(":cart_id", $cartId);
        $updateStatement->bindParam(":product_id", $product_id);
        $updateStatement->bindParam(":quantity", $quantity);
        $updateStatement->execute();

        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getShoppingCartContents($customer_id)
{
    try {
        $dbh = connectDB();

        // Retrieve shopping cart contents for the given customer
        $statement = $dbh->prepare("SELECT SCC.quantity, P.product_id, P.name AS product_name, P.price, SCC.quantity * P.price AS total
                                    FROM ShoppingCartContent SCC
                                    INNER JOIN Product P ON SCC.product_id = P.product_id
                                    WHERE SCC.cart_id = (SELECT cart_id FROM ShoppingCart WHERE customer_id = :customer_id)");
        $statement->bindParam(":customer_id", $customer_id);
        $statement->execute();
        $cartContents = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dbh = null;
        return $cartContents;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function updateShoppingCartQuantity($customer_id, $product_id, $newQuantity) {
    try {
        $dbh = connectDB();

        // Check if the customer has a cart
        $checkCartStatement = $dbh->prepare("SELECT cart_id FROM ShoppingCart WHERE customer_id = :customer_id");
        $checkCartStatement->bindParam(":customer_id", $customer_id);
        $checkCartStatement->execute();
        $cartId = $checkCartStatement->fetchColumn();

        if(!checkProductQuantity($newQuantity, $product_id)){
            echo "<br/>";
            echo "<div class='alert alert-danger' role='alert'>Quantity Update Failed! Not enough stock.</div>";
            echo "<br/>";
            echo "<button style='margin: auto; display: block;' type='button' class='btn btn-outline-secondary' onclick=\"location.href='shoppingCart.php';\">Shopping Cart</button>";
            die();
        }

        if ($cartId) {
            // If the customer has a cart, update the quantity
            $updateStatement = $dbh->prepare("UPDATE ShoppingCartContent 
                                             SET quantity = :newQuantity 
                                             WHERE cart_id = :cart_id AND product_id = :product_id");
            $updateStatement->bindParam(":cart_id", $cartId);
            $updateStatement->bindParam(":product_id", $product_id);
            $updateStatement->bindParam(":newQuantity", $newQuantity);
            $updateStatement->execute();
        }

        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function removeProductFromCart($customer_id, $product_id) {
    try {
        $dbh = connectDB();

        // Check if the customer has a cart
        $checkCartStatement = $dbh->prepare("SELECT cart_id FROM ShoppingCart WHERE customer_id = :customer_id");
        $checkCartStatement->bindParam(":customer_id", $customer_id);
        $checkCartStatement->execute();
        $cartId = $checkCartStatement->fetchColumn();

        if ($cartId) {
            // If the customer has a cart, remove the product
            $removeStatement = $dbh->prepare("DELETE FROM ShoppingCartContent 
                                              WHERE cart_id = :cart_id AND product_id = :product_id");
            $removeStatement->bindParam(":cart_id", $cartId);
            $removeStatement->bindParam(":product_id", $product_id);
            $removeStatement->execute();
        }

        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getOrders($customer_id) {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT * FROM `Order` WHERE customer_id = :customer_id");
        $statement->bindParam(":customer_id", $customer_id);
        $statement->execute();
        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
        $dbh = null;
        return $orders;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getOrderDetails($order_id) {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT oi.item_id, oi.product_id, p.name AS product_name, oi.quantity, oi.price_at_order
                                   FROM OrderItem oi
                                   JOIN Product p ON oi.product_id = p.product_id
                                   WHERE oi.order_id = :order_id");
        $statement->bindParam(":order_id", $order_id);
        $statement->execute();
        $orderDetails = $statement->fetchAll(PDO::FETCH_ASSOC);
        $dbh = null;
        return $orderDetails;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function clearShoppingCart($customer_id) {
    try {
        $dbh = connectDB();

        // Delete shopping cart contents for the given customer
        $clearCartStatement = $dbh->prepare("DELETE FROM ShoppingCartContent 
                                            WHERE cart_id = (SELECT cart_id FROM ShoppingCart WHERE customer_id = :customer_id)");
        $clearCartStatement->bindParam(":customer_id", $customer_id);
        $clearCartStatement->execute();

        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function createOrder($customer_id, $cartContents, $total_cost) {
    try {
        $dbh = connectDB();

        $dbh->beginTransaction();
        $latestOrder = getLatestOrderCount() + 1;

        $nextOrderId = "ORDER" . $latestOrder;

        // Create a new order
        $orderStatement = $dbh->prepare("INSERT INTO `Order` (order_id, customer_id, order_date, order_status, total_cost) VALUES (:order_id, :customer_id, NOW(), 'Pending...', :total_cost)");
        $orderStatement->bindParam(":order_id", $nextOrderId);
        $orderStatement->bindParam(":customer_id", $customer_id);
        $orderStatement->bindParam(":total_cost", $total_cost);
        $orderStatement->execute();

        // Get the ID of the newly created order
        $order_id = $dbh->lastInsertId();

        
        $counter = 1;
        // Add order items based on the shopping cart contents
        foreach ($cartContents as $item) {
            
            $latestOrderItem = getLatestOrderItemCount() + $counter;
            $nextItemId = "ITEM" . $latestOrderItem;

            $addItemStatement = $dbh->prepare("INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
                                              VALUES (:item_id, :order_id, :product_id, :quantity, :price_at_order)");
            $addItemStatement->bindParam(":item_id", $nextItemId);
            $addItemStatement->bindParam(":order_id", $nextOrderId);
            $addItemStatement->bindParam(":product_id", $item['product_id']);
            $addItemStatement->bindParam(":quantity", $item['quantity']);
            $addItemStatement->bindParam(":price_at_order", $item['price']);
            $addItemStatement->execute();

            $statement2 = $dbh->prepare("SELECT stock_quantity_actual FROM Product WHERE product_id = :product_id");
            $statement2->bindParam(":product_id", $item['product_id']);
            $statement2->execute();
            $actual_quantity = $statement2->fetchColumn();

            $statement3 = $dbh->prepare("UPDATE Product SET stock_quantity_actual = (:actual_quantity - :quantityToReduce) WHERE product_id = :product_id");
            $statement3->bindParam(":quantityToReduce", $item['quantity']);
            $statement3->bindParam(":actual_quantity", $actual_quantity);
            $statement3->bindParam(":product_id", $item['product_id']);
            $statement3->execute();

            $counter++;

        }

        if(!checkProductQuantity($newQuantity, $product_id)){
            echo "<br/>";
            echo "<div class='alert alert-danger' role='alert'>Quantity Update Failed! Not enough stock.</div>";
            echo "<br/>";
            echo "<button style='margin: auto; display: block;' type='button' class='btn btn-outline-secondary' onclick=\"location.href='shoppingCart.php';\">Shopping Cart</button>";
            die();
        }

        // Commit the transaction
        $dbh->commit();

        $dbh = null;
    } catch (PDOException $e) {
        // Rollback the transaction on error
        $dbh->rollBack();
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function orderExists($order_id) {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT COUNT(*) as count FROM `Order` WHERE order_id = :order_id");
        $statement->bindParam(":order_id", $order_id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $dbh = null;

        return $result['count'] > 0;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getLatestOrderCount() {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT COUNT(*) FROM `Order`");
        $statement->execute();
        $latestOrderCount = $statement->fetchColumn();
        $dbh = null;

        return $latestOrderCount;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getLatestOrderItemCount() {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT COUNT(*) FROM OrderItem");
        $statement->execute();
        $latestOrderCount = $statement->fetchColumn();
        $dbh = null;

        return $latestOrderCount;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function checkProductQuantity($requestedAmmount, $product_id) {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT stock_quantity_actual FROM Product WHERE product_id = :product_id");
        $statement->bindParam(":product_id", $product_id);
        $statement->execute();
        $actual_quantity = $statement->fetchColumn();
        $dbh = null;

        if($requestedAmmount > $actual_quantity){
            return false;
        }
        else{
            return true;
        }
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

?>

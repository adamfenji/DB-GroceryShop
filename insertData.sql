/*
Authors: Adam Fenjiro, Kyle Hoop
Class: CS3425
Course Project phase 1
insertData.sql
*/

DELETE FROM Employee;
DELETE FROM Product;
DELETE FROM Category;
DELETE FROM Customer;
DELETE FROM ShoppingCartContent;
DELETE FROM ShoppingCart;
DELETE FROM OrderItem;
DELETE FROM `Order`;
DELETE FROM `History`;

-- Insert Employee data
INSERT INTO Employee (employee_id, username, email, password, password_change_required)
VALUES ('E1', 'employee1', 'employee1@email.com', SHA2('password1', 256), 1);
INSERT INTO Employee (employee_id, username, email, password, password_change_required)
VALUES ('E2', 'employee2', 'employee2@email.com', SHA2('password2', 256), 1);

-- Insert Category data
INSERT INTO Category (category_id, description)
VALUES ('CAT1', 'Category 1 description');
INSERT INTO Category (category_id, description)
VALUES ('CAT2', 'Category 2 description');
INSERT INTO Category (category_id, description)
VALUES ('CAT3', 'Category 3 description');
INSERT INTO Category (category_id, description)
VALUES ('CAT4', 'Category 4 description');

-- Insert Product data
INSERT INTO Product (product_id, name, description, price, stock_quantity_threshold, stock_quantity_actual, category_id, image, isDiscontinued)
VALUES ('PROD1', 'Product 1', 'Product 1 description', 8.00, 50, 50, 'CAT1', 'product1.jpg', 0);
INSERT INTO Product (product_id, name, description, price, stock_quantity_threshold, stock_quantity_actual, category_id, image, isDiscontinued)
VALUES ('PROD2', 'Product 2', 'Product 2 description', 20.00, 30, 27, 'CAT1', 'product2.jpg', 0);
INSERT INTO Product (product_id, name, description, price, stock_quantity_threshold, stock_quantity_actual, category_id, image, isDiscontinued)
VALUES ('PROD3', 'Product 3', 'Product 3 description', 17.00, 40, 38, 'CAT2', 'product3.jpg', 0);
INSERT INTO Product (product_id, name, description, price, stock_quantity_threshold, stock_quantity_actual, category_id, image, isDiscontinued)
VALUES ('PROD4', 'Product 4', 'Product 4 description', 12.00, 41, 38, 'CAT2', 'product4.jpg', 0);
INSERT INTO Product (product_id, name, description, price, stock_quantity_threshold, stock_quantity_actual, category_id, image, isDiscontinued)
VALUES ('PROD5', 'Product 5', 'Product 5 description', 13.00, 50, 45, 'CAT3', 'product5.jpg', 0);
INSERT INTO Product (product_id, name, description, price, stock_quantity_threshold, stock_quantity_actual, category_id, image, isDiscontinued)
VALUES ('PROD6', 'Product 6', 'Product 6 description', 16.00, 30, 27, 'CAT3', 'product6.jpg', 0);
INSERT INTO Product (product_id, name, description, price, stock_quantity_threshold, stock_quantity_actual, category_id, image, isDiscontinued)
VALUES ('PROD7', 'Product 7', 'Product 7 description', 33.00, 40, 38, 'CAT1', 'product7.jpg', 0);
INSERT INTO Product (product_id, name, description, price, stock_quantity_threshold, stock_quantity_actual, category_id, image, isDiscontinued)
VALUES ('PROD8', 'Product 8', 'Product 8 description', 58.00, 41, 38, 'CAT2', 'product8.jpg', 0);
INSERT INTO Product (product_id, name, description, price, stock_quantity_threshold, stock_quantity_actual, category_id, image, isDiscontinued)
VALUES ('PROD9', 'Product 9', 'Product 9 description', 14.00, 50, 45, 'CAT4', 'product9.jpg', 0);
INSERT INTO Product (product_id, name, description, price, stock_quantity_threshold, stock_quantity_actual, category_id, image, isDiscontinued)
VALUES ('PROD10', 'Product 10', 'Product 10 description', 18.00, 30, 27, 'CAT4', 'product10.jpg', 0);

-- Update Product data
UPDATE Product
SET price = 10.00
WHERE  product_id = 'PROD1';

UPDATE Product
SET price = 15.00
WHERE  product_id = 'PROD2';

UPDATE Product
SET price = 20.00
WHERE  product_id = 'PROD3';

UPDATE Product
SET price = 25.00
WHERE  product_id = 'PROD4';

UPDATE Product
SET price = 10.00
WHERE  product_id = 'PROD5';

UPDATE Product
SET price = 15.00
WHERE  product_id = 'PROD6';

UPDATE Product
SET price = 23.00
WHERE  product_id = 'PROD7';

UPDATE Product
SET price = 45.00
WHERE  product_id = 'PROD8';

UPDATE Product
SET price = 16.00
WHERE  product_id = 'PROD9';

UPDATE Product
SET price = 17.00
WHERE  product_id = 'PROD10';

-- Insert Customer data
INSERT INTO Customer (customer_id, username, password, first_name, last_name, email, shipping_address)
VALUES ('CUST1', 'customer1', SHA2('customer1password', 256), 'John', 'Doe', 'customer1@email.com', '123 Main St');
INSERT INTO Customer (customer_id, username, password, first_name, last_name, email, shipping_address)
VALUES ('CUST2', 'customer2', SHA2('customer2password', 256), 'Jane', 'Smith', 'customer2@email.com', '456 Elm St');
INSERT INTO Customer (customer_id, username, password, first_name, last_name, email, shipping_address)
VALUES ('CUST3', 'customer3', SHA2('customer3password', 256), 'Sue', 'Carm', 'customer3@email.com', '9426 third St');

-- Insert ShoppingCart data
INSERT INTO ShoppingCart (cart_id, customer_id)
VALUES ('CART1', 'CUST1');
INSERT INTO ShoppingCart (cart_id, customer_id)
VALUES ('CART2', 'CUST2');

-- Insert ShoppingCartContent data
INSERT INTO ShoppingCartContent (cart_id, product_id, quantity)
VALUES ('CART1', 'PROD1', 3);
INSERT INTO ShoppingCartContent (cart_id, product_id, quantity)
VALUES ('CART1', 'PROD2', 2);
INSERT INTO ShoppingCartContent (cart_id, product_id, quantity)
VALUES ('CART1', 'PROD3', 1);
INSERT INTO ShoppingCartContent (cart_id, product_id, quantity)
VALUES ('CART2', 'PROD6', 1);
INSERT INTO ShoppingCartContent (cart_id, product_id, quantity)
VALUES ('CART2', 'PROD5', 1);
INSERT INTO ShoppingCartContent (cart_id, product_id, quantity)
VALUES ('CART2', 'PROD10', 1);

-- Insert Order data
INSERT INTO `Order` (order_id, customer_id, order_date, order_status, total_cost)
VALUES ('ORDER1', 'CUST1', '2023-11-04', 'Pending', 40.00);
INSERT INTO `Order` (order_id, customer_id, order_date, order_status, total_cost)
VALUES ('ORDER2', 'CUST1', '2023-11-04', 'Pending', 10.00);
INSERT INTO `Order` (order_id, customer_id, order_date, order_status, total_cost)
VALUES ('ORDER3', 'CUST2', '2023-11-04', 'Pending', 30.00);
INSERT INTO `Order` (order_id, customer_id, order_date, order_status, total_cost)
VALUES ('ORDER4', 'CUST1', '2023-11-03', 'Pending', 61.00);
INSERT INTO `Order` (order_id, customer_id, order_date, order_status, total_cost)
VALUES ('ORDER5', 'CUST3', '2023-11-03', 'Pending', 25.00);
INSERT INTO `Order` (order_id, customer_id, order_date, order_status, total_cost)
VALUES ('ORDER6', 'CUST2', '2023-11-03', 'Pending', 55.00);
INSERT INTO `Order` (order_id, customer_id, order_date, order_status, total_cost)
VALUES ('ORDER7', 'CUST2', '2023-10-31', 'Pending', 57.00);
INSERT INTO `Order` (order_id, customer_id, order_date, order_status, total_cost)
VALUES ('ORDER8', 'CUST3', '2023-10-28', 'Pending', 70.00);
INSERT INTO `Order` (order_id, customer_id, order_date, order_status, total_cost)
VALUES ('ORDER9', 'CUST3', '2023-10-25', 'Pending', 68.00);
INSERT INTO `Order` (order_id, customer_id, order_date, order_status, total_cost)
VALUES ('ORDER10', 'CUST1', '2023-10-17', 'Pending', 100.00);

-- Insert OrderItem data
INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM1', 'ORDER1', 'PROD1', 1, 10.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD1';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM2', 'ORDER1', 'PROD2', 2, 15.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 2 WHERE product_id = 'PROD2';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM3', 'ORDER2', 'PROD1', 1, 10.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD1';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM4', 'ORDER3', 'PROD5', 3, 10.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 3 WHERE product_id = 'PROD5';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM5', 'ORDER4', 'PROD6', 1, 15.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD6';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM6', 'ORDER4', 'PROD7', 2, 23.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 2 WHERE product_id = 'PROD7';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM7', 'ORDER5', 'PROD8', 1, 45.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD8';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM8', 'ORDER5', 'PROD1', 1, 10.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 5 WHERE product_id = 'PROD1';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM9', 'ORDER6', 'PROD10', 1, 17.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD10';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM10', 'ORDER6', 'PROD2', 2, 15.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 2 WHERE product_id = 'PROD2';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM11', 'ORDER6', 'PROD1', 1, 10.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD1';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM12', 'ORDER1', 'PROD6', 1, 10.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD6';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM13', 'ORDER7', 'PROD1', 1, 10.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD1';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM14', 'ORDER7', 'PROD4', 1, 25.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD4';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM15', 'ORDER8', 'PROD1', 1, 10.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD1';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM16', 'ORDER8', 'PROD5', 1, 10.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD5';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM17', 'ORDER8', 'PROD5', 1, 10.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD1';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM18', 'ORDER8', 'PROD6', 2, 15.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 2 WHERE product_id = 'PROD6';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM19', 'ORDER8', 'PROD3', 1, 20.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD3';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM20', 'ORDER9', 'PROD9', 3, 16.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 3 WHERE product_id = 'PROD9';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM21', 'ORDER9', 'PROD3', 1, 20.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 1 WHERE product_id = 'PROD3';

INSERT INTO OrderItem (item_id, order_id, product_id, quantity, price_at_order)
VALUES ('ITEM22', 'ORDER10', 'PROD1', 10, 10.00);
UPDATE Product SET stock_quantity_actual = stock_quantity_actual - 10 WHERE product_id = 'PROD1';

SELECT * FROM Employee;
SELECT * FROM Category;
SELECT * FROM Product;
SELECT * FROM Customer;
SELECT * FROM ShoppingCart;
SELECT * FROM ShoppingCartContent;
SELECT * FROM `Order`;
SELECT * FROM OrderItem;
SELECT * FROM `History`;

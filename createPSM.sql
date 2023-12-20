/*
Authors: Adam Fenjiro, Kyle Hoop
Class: CS3425
Course Project phase 1
createPSM.sql
*/

DROP PROCEDURE IF EXISTS create_employee;
DROP PROCEDURE IF EXISTS insert_category;
DROP PROCEDURE IF EXISTS insert_product;
DROP PROCEDURE IF EXISTS update_product_price;
DROP PROCEDURE IF EXISTS restock_product;

DROP FUNCTION IF EXISTS insert_order;
DROP FUNCTION IF EXISTS insert_order_item;

DROP TRIGGER IF EXISTS product_insert_history;
DROP TRIGGER IF EXISTS product_update_history;
DROP TRIGGER IF EXISTS product_update_reject_id_change;
DROP TRIGGER IF EXISTS product_delete_reject;

-- Procedures creation 

DELIMITER //
CREATE PROCEDURE create_employee(
	IN emp_id VARCHAR(10),
    IN emp_username VARCHAR(30),
    IN emp_email VARCHAR(30),
    IN emp_password VARCHAR(255)
)
BEGIN
	INSERT INTO Employee(employee_id, username, email, password)
	VALUES (emp_id, emp_username, emp_email, SHA2(emp_password, 256));
    UPDATE Employee SET password_change_required = 1 WHERE employee_id = emp_id; 
END;//
DELIMITER ;


DELIMITER //
CREATE PROCEDURE insert_category(
	IN cat_id VARCHAR(10),
	IN cat_description TEXT
)
BEGIN
	INSERT INTO Category (category_id, description)
    VALUES (cat_id, cat_description);
END;//
DELIMITER ;


DELIMITER //
CREATE PROCEDURE insert_product(
	IN prod_id VARCHAR(10),
    IN prod_name VARCHAR(30),
    IN prod_description TEXT,
    IN prod_price DECIMAL(10, 2),
    IN prod_threshold INT,
    IN prod_quantity INT,
    IN prod_cat_id VARCHAR(10),
    IN prod_image VARCHAR(255),
    IN prod_discontinued BOOLEAN
)
BEGIN
    INSERT INTO Product (product_id, name, description, price, stock_quantity_threshold, stock_quantity_actual, category_id, image, isDiscontinued)
    VALUES (prod_id, prod_name, prod_description, prod_price, prod_threshold, prod_quantity, prod_cat_id, prod_image, prod_discontinued);
END;//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE update_product_price(
	IN prod_id VARCHAR(10),
    IN new_price DECIMAL(10, 2)
)
BEGIN
	UPDATE Product SET price = new_price WHERE product_id = prod_id;
END;//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE restock_product(
	IN prod_id VARCHAR(10),
	IN restock_amount INT
)
BEGIN
	UPDATE Product SET stock_quantity_actual = stock_quantity_actual + restock_amount WHERE product_id = prod_id;
END;//
DELIMITER ;

-- Functions creation 

DELIMITER //
CREATE FUNCTION insert_order(
	cust_id VARCHAR(10),
    order_date DATE,
    order_status VARCHAR(30),
    total_cost DECIMAL(10, 2)
) RETURNS VARCHAR(10)
BEGIN
	DECLARE new_order_id VARCHAR(10);
    INSERT INTO `Order` (customer_id, order_date, order_status, total_cost)
    VALUES (cust_id, order_date, order_status, total_cost);
    SET new_order_id = LAST_INSERT_ID();
    RETURN new_order_id;
END;//
DELIMITER ;

DELIMITER //
CREATE FUNCTION insert_order_item(
    ord_id VARCHAR(10),
    prod_id VARCHAR(10),
    ord_quantity INT
) RETURNS BOOLEAN
BEGIN
    DECLARE enough_stock BOOLEAN;
    
    -- Check if there's enough stock 
    SELECT (stock_quantity_actual >= ord_quantity) INTO enough_stock
    FROM Product
    WHERE product_id = prod_id;
    
    -- If stock is enough
    IF enough_stock THEN
        INSERT INTO OrderItem (order_id, product_id, quantity, price_at_order)
        VALUES (ord_id, prod_id, ord_quantity, (SELECT price FROM Product WHERE product_id = prod_id));

        UPDATE Product
        SET stock_quantity_actual = stock_quantity_actual - ord_quantity
        WHERE product_id = prod_id;
        
        RETURN TRUE;
    ELSE
		-- Not enough stock
        RETURN FALSE; 
    END IF;
END;//
DELIMITER ;

-- Triggers creation 

DELIMITER //
CREATE TRIGGER product_insert_history
AFTER INSERT ON Product
FOR EACH ROW
BEGIN
	INSERT INTO History(product_id, employee_id, timestamp, price_change)
    VALUES(NEW.product_id, employee_id, NOW(), NEW.price);
END;//
DELIMITER ;

DELIMITER //
CREATE TRIGGER product_update_history
AFTER UPDATE ON Product
FOR EACH ROW
BEGIN
    INSERT INTO History (product_id, employee_id, timestamp, price_change)
    VALUES (NEW.product_id, employee_id, NOW(), NEW.price);
END;//
DELIMITER ;

DELIMITER //
CREATE TRIGGER product_update_reject_id_change
BEFORE UPDATE ON Product
FOR EACH ROW
BEGIN
    IF NEW.product_id != OLD.product_id THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'The product ID is not allowed to be changed';
    END IF;
END;//
DELIMITER ;

DELIMITER //
CREATE TRIGGER product_delete_reject
BEFORE DELETE ON Product
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Deletion of products is not allowed';
END;//
DELIMITER ;


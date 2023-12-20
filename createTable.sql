/*
Authors: Adam Fenjiro, Kyle Hoop
Class: CS3425
Course Project phase 1
createTable.sql
*/

DROP TABLE IF EXISTS `History`;
DROP TABLE IF EXISTS OrderItem;
DROP TABLE IF EXISTS `Order`;
DROP TABLE IF EXISTS ShoppingCartContent;
DROP TABLE IF EXISTS ShoppingCart;
DROP TABLE IF EXISTS Customer;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Employee;

-- creating Employee table 
CREATE TABLE Employee(
	employee_id VARCHAR(10),
	username VARCHAR(30) NOT NULL,
	email VARCHAR(30),
	password VARCHAR(255) NOT NULL,
    password_change_required BOOLEAN DEFAULT 1,
	PRIMARY KEY (employee_id)
);

-- creating Category table 
CREATE TABLE Category(
	category_id VARCHAR(10),
	description TEXT,
	PRIMARY KEY (category_id)
);

-- creating Product table 
CREATE TABLE Product(
	product_id VARCHAR(10),
	name VARCHAR(30) NOT NULL,
	description TEXT,
	price DECIMAL(10, 2) NOT NULL,
	stock_quantity_threshold INT NOT NULL,
	stock_quantity_actual INT NOT NULL,
	category_id VARCHAR(10),
	image VARCHAR(255),
	isDiscontinued BOOLEAN,
	PRIMARY KEY (product_id),
	FOREIGN KEY (category_id) REFERENCES Category(category_id)
);

-- creating Customer table 
CREATE TABLE Customer(
	customer_id VARCHAR(10),
	username VARCHAR(30) NOT NULL,
	password VARCHAR(255) NOT NULL,
	first_name VARCHAR(30),
	last_name VARCHAR(30),
	email VARCHAR(30),
	shipping_address TEXT,
	PRIMARY KEY (customer_id)
);

-- creating ShoppingCart table
CREATE TABLE ShoppingCart(
	cart_id VARCHAR(10),
	customer_id VARCHAR(10),
	PRIMARY KEY (cart_id),
	FOREIGN KEY (customer_id) REFERENCES Customer(customer_id)
);

-- creating ShoppingCartContent table
CREATE TABLE ShoppingCartContent(
	cart_id VARCHAR(10),
	product_id VARCHAR(10),
	quantity INT,
	PRIMARY KEY (cart_id, product_id),
	FOREIGN KEY (cart_id) REFERENCES ShoppingCart(cart_id),
	FOREIGN KEY (product_id) REFERENCES Product(product_id)
);

-- creating Order table
CREATE TABLE `Order`(
	order_id VARCHAR(10),
	customer_id VARCHAR(10),
	order_date DATE,
	order_status VARCHAR(30),
	total_cost DECIMAL(10, 2),
	PRIMARY KEY (order_id),
	FOREIGN KEY (customer_id) REFERENCES Customer(customer_id)
);

-- creating OrderItem table
CREATE TABLE OrderItem(
	item_id VARCHAR(10),
	order_id VARCHAR(10),
	product_id VARCHAR(10),
	quantity INT,
	price_at_order DECIMAL(10, 2),
	PRIMARY KEY (item_id),
	FOREIGN KEY (order_id) REFERENCES `Order`(order_id),
	FOREIGN KEY (product_id) REFERENCES Product(product_id)
);

-- creating History table
CREATE TABLE `History` (
    history_id INT AUTO_INCREMENT,
    product_id VARCHAR(10),
    employee_id VARCHAR(10),
    timestamp TIMESTAMP,
    price_change DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (history_id),
    FOREIGN KEY (product_id) REFERENCES Product(product_id),
    FOREIGN KEY (employee_id) REFERENCES Employee(employee_id)
);

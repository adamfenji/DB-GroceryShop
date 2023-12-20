/*
Authors: Adam Fenjiro, Kyle Hoop
Class: CS3425
Course Project phase 1
genReport.sql
*/

DROP PROCEDURE IF EXISTS get_price_history_of;
DROP PROCEDURE IF EXISTS get_highest_and_lowest_price;
DROP PROCEDURE IF EXISTS get_products_sold_within_period;
DROP PROCEDURE IF EXISTS needs_restocking;

-- historic price of product
delimiter //
CREATE PROCEDURE get_price_history_of(
	in this_product_id VARCHAR(10) 
)
BEGIN
	SELECT product_id AS product, price_change AS prices
	FROM `History`
    WHERE product_id = this_product_id;
End;//
delimiter ;

-- get highest and lowest price of a product
DELIMITER //
CREATE PROCEDURE get_highest_and_lowest_price(
	in this_product_id VARCHAR(10)
)
begin
	SELECT min(price_change) as lowest_price, max(price_change) as highest_price
    FROM `History`
    WHERE product_id = this_product_id;
end;//
delimiter ;

-- get how many of each product has been sold in a period of time
DROP PROCEDURE IF EXISTS get_products_sold_within_period;

DELIMITER //
CREATE PROCEDURE get_products_sold_within_period(
    IN start_date DATE,
    IN end_date DATE
)
BEGIN
    SELECT OrderItem.product_id AS product, SUM(OrderItem.quantity) AS total_quantity_sold
    FROM `Order`
    JOIN OrderItem ON `Order`.order_id = OrderItem.order_id
    WHERE `Order`.order_date BETWEEN start_date AND end_date
    GROUP BY OrderItem.product_id;
END;//
DELIMITER ;


-- all products that need to be restocked and how many
delimiter //
CREATE PROCEDURE needs_restocking()
BEGIN
SELECT product_id as product, (stock_quantity_threshold - stock_quantity_actual) AS total_product_needed
FROM Product
WHERE (stock_quantity_threshold - stock_quantity_actual) > 0;
END;//
delimiter ;

call get_price_history_of('PROD1');
CALL get_products_sold_within_period('2023-01-01', '2023-12-31');
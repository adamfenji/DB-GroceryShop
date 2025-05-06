## 🛒 DB-GroceryShop – Online Store System

This repository contains the final project for **CS3425: Introduction to Database Systems**, a course taken at **Michigan Technological University** in **Fall 2023** under **Professor Ruihong Zhang**. The project simulates a fully functional **online grocery store** system with both **customer and administrative operations**, using **MySQL**, **PHP**, **HTML/CSS/JS**, and **SQL triggers, procedures, and transactions**.


## 📌 Project Description

DB-GroceryShop is a simplified e-commerce platform where:
- **Customers** can register, log in, browse products by category, add items to their cart, and checkout
- **Employees** can insert and update products, generate reports, and track historical changes (Phase 1 only)

The database supports:
- Authentication with hashed passwords using SHA-256
- Inventory control and stock validation
- Full transaction support for safe checkouts
- Automatic product update logging via triggers

The system was developed in **two phases** with a final **live demo** presented to course staff.

## 🔧 Technologies Used

- **MySQL (SQL)** – Schema, Triggers, Stored Procedures, Views
- **PHP (PDO)** – Server-side logic with prepared statements
- **HTML / CSS / JavaScript** – Frontend interface
- **SQLite (local dev only)** – Lightweight testing
- **Apache / XAMPP** – Web server environment
- **MySQL Workbench** – Schema and query management

## 🧪 Features Implemented

### 👤 Customer Functions
- Register, log in/out, and change password securely
- Browse categories and view products
- Add, update, and remove products in cart
- View shopping cart and complete checkout
- View order history with details

### 👨‍💼 Employee Functions *(Phase 1)*
- Insert and update products with timestamp tracking
- View inventory and identify low-stock items
- Generate business reports:
  - Price history
  - Sales quantities
  - Stock below threshold
  - Price trends over time

### 🧠 Backend Logic
- Password hashing with SHA-256
- SQL Transactions on checkout to ensure atomicity
- SQL Triggers to prevent deletion or ID mutation
- Stored procedures to encapsulate business rules
- Separate SQL scripts: `createTable.sql`, `createPSM.sql`, `insertData.sql`, `genReport.sql`

---

## 🧠 Key Learnings

- **End-to-end database design**: ER modeling, schema normalization, and relational design
- **Security-focused development**: Password hashing, prepared statements, and session handling
- **Modular architecture**: PHP function libraries for database interaction
- **Transactional logic**: Safe and atomic order processing
- **Data validation & integrity**: Triggers and constraints to enforce business rules

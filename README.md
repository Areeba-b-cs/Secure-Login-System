
# Secure Login System (PHP + MySQL)

A secure user authentication system developed using PHP and MySQL.

## Security Features

- Password hashing using password_hash() and password_verify()
- SQL injection prevention using PDO prepared statements
- XSS protection using htmlspecialchars()
- CSRF token protection on forms
- Server-side input validation
- Secure session management
- Login attempt limiting (5 failed attempts within 10 minutes)
- Login activity monitoring and logging

## Technologies Used

- PHP
- MySQL
- HTML
- XAMPP
- phpMyAdmin

## How to Run the Project

1. Install and open XAMPP.
2. Start Apache and MySQL.
3. Open phpMyAdmin in your browser.
4. Select the SQL tab.
5. Copy and execute the code from schema.sql.
6. Open the following URL:

   http://localhost/Secure-Login-System/register.php

7. Create a new account.
8. Login using your registered username and password.
9. Access the dashboard to view recent login attempts.

## Project Files

- config.php - Database connection and session settings
- functions.php - Security helper functions
- register.php - User registration
- login.php - User authentication
- dashboard.php - User dashboard and login monitoring
- logout.php - Secure logout
- schema.sql - Database and table creation
- README.md - Project documentation

## What I Learned

- How to build a secure authentication system using PHP and MySQL.
- How to protect applications against SQL injection, XSS and CSRF attacks.
- How to implement password hashing and session security.

## Author

Areeba Bilal

BS Computer Science  
Virtual University of Pakistan

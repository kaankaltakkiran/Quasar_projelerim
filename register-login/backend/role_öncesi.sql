-- Create Database
CREATE DATABASE IF NOT EXISTS register_login_db;

-- Use the database
USE register_login_db;

-- Create Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Optional: Create an index for faster queries
CREATE INDEX idx_username ON users(username);
CREATE INDEX idx_email ON users(email);

CREATE DATABASE fitsphere_booking;
USE fitsphere_booking;

CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL,
    suit VARCHAR(255) NOT NULL,
    period VARCHAR(50) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    deposite DECIMAL(10,2) NOT NULL,
    late_days INT DEFAULT 0,
    late_fees DECIMAL(10,2) DEFAULT 0,
    refund_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    returned_date DATE,
    manual_late_days INT DEFAULT 0,
    manual_late_fee ENUM('yes', 'no') DEFAULT 'no',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
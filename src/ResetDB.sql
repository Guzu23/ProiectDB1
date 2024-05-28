-- Drop the tables
DROP TABLE IF EXISTS carsPDO.cars_logs;
DROP TABLE IF EXISTS carsPDO.cars;

-- Drop the procedures
DROP PROCEDURE IF EXISTS carsPDO.addCar;
DROP PROCEDURE IF EXISTS carsPDO.editCar;
DROP PROCEDURE IF EXISTS carsPDO.deleteCar;

-- Drop the triggers
DROP TRIGGER IF EXISTS carsPDO.afterAddCar;
DROP TRIGGER IF EXISTS carsPDO.afterEditCar;
DROP TRIGGER IF EXISTS carsPDO.beforeRemoveCar;

-- Drop the database
DROP DATABASE IF EXISTS carsPDO;

-- Create the database
CREATE DATABASE carsPDO
    DEFAULT CHARACTER SET = 'utf8mb4';

CREATE TABLE carsPDO.cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(255) NOT NULL,
    model VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NULL
);

CREATE TABLE carsPDO.cars_logs (
    audit_id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT,
    old_brand VARCHAR(255) NOT NULL,
    new_brand VARCHAR(255) NOT NULL,
    old_model VARCHAR(255) NOT NULL,
    new_model VARCHAR(255) NOT NULL,
    old_price DECIMAL(10, 2) NOT NULL,
    new_price DECIMAL(10, 2) NOT NULL,
    cars_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE SET NULL
);

-- Do not create the procedures and the triggers here. They should be created in php and used in php
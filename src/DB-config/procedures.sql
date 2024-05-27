DELIMITER $$

CREATE PROCEDURE AddCar(
    IN carBrand VARCHAR(255),
    IN carModel VARCHAR(255),
    IN carPrice DECIMAL(10, 2),
    IN carImage VARCHAR(255)
)
BEGIN
    INSERT INTO carsPDO (brand, model, price, image)
    VALUES (carBrand, carModel, carPrice, carImage);
END $$


CREATE PROCEDURE UpdateCarPrice(
    IN carID INT,
    IN newPrice DECIMAL(10, 2)
)
BEGIN
    UPDATE carsPDO
    SET price = newPrice
    WHERE id = carID;
END $$


CREATE PROCEDURE DeleteCar(
    IN carID INT
)
BEGIN
    DELETE FROM carsPDO
    WHERE id = carID;
END $$

DELIMITER ;
DELIMITER //

DELIMITER ;

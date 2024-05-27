CREATE TRIGGER after_car_insert
AFTER INSERT ON carsPDO
FOR EACH ROW
BEGIN
    INSERT INTO cars_audit (car_id, old_brand, new_brand, old_model, new_model, old_price, new_price)
    VALUES (NEW.id, 0, NEW.brand, 0, NEW.model, 0, NEW.price);
END;


CREATE TRIGGER after_car_brand_update
AFTER UPDATE ON carsPDO
FOR EACH ROW
BEGIN
    IF OLD.brand <> NEW.brand THEN
        INSERT INTO cars_audit (car_id, old_brand, new_brand, old_model, new_model, old_price, new_price)
        VALUES (NEW.id, OLD.brand, NEW.brand, OLD.model, NEW.model, OLD.price, NEW.price);
    END IF;
END;


CREATE TRIGGER after_car_model_update
AFTER UPDATE ON carsPDO
FOR EACH ROW
BEGIN
    IF OLD.model <> NEW.model THEN
        INSERT INTO cars_audit (car_id, old_brand, new_brand, old_model, new_model, old_price, new_price)
        VALUES (NEW.id, OLD.brand, NEW.brand, OLD.model, NEW.model, OLD.price, NEW.price);
    END IF;
END;


CREATE TRIGGER after_car_price_update
AFTER UPDATE ON carsPDO
FOR EACH ROW
BEGIN
    IF OLD.price <> NEW.price THEN
        INSERT INTO cars_audit (car_id, old_brand, new_brand, old_model, new_model, old_price, new_price)
        VALUES (NEW.id, OLD.brand, NEW.brand, OLD.model, NEW.model, OLD.price, NEW.price);
    END IF;
END;





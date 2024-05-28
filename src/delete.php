<?php
require_once 'connection.php';

if(isset($_GET['id'])) {
    // Drop procedure deleteCar if exists
    $sql1 = "DROP PROCEDURE IF EXISTS deleteCar";
    $stmt1 = $con->prepare($sql1);
    $stmt1->execute();

    // Create procedure deleteCar
    $sql2 = "CREATE PROCEDURE deleteCar(
        IN carID INT
    )
    BEGIN
        DELETE FROM cars WHERE id = carID;
    END";
    $stmt2 = $con->prepare($sql2);
    $stmt2->execute();

    // Drop trigger beforeDeleteCar if exists
    $sql3 = "DROP TRIGGER IF EXISTS beforeDeleteCar";
    $stmt3 = $con->prepare($sql3);
    $stmt3->execute();

    // Create trigger beforeDeleteCar
    $sql4 = "CREATE TRIGGER beforeDeleteCar
             BEFORE DELETE ON cars
             FOR EACH ROW
             BEGIN
                 INSERT INTO cars_logs (
                     car_id, old_brand, new_brand, old_model, new_model, old_price, new_price, cars_timestamp
                 ) VALUES (
                     OLD.id, OLD.brand, '-1', OLD.model, '-1', OLD.price, -1, NOW()
                 );
             END";
    $stmt4 = $con->prepare($sql4);
    $stmt4->execute();

    // Call procedure deleteCar
    $car_id = $_GET['id'];
    $sql = "CALL deleteCar(:carID)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':carID', $car_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting the car.";
    }
} else {
    echo "The ID is missing trying to delete the car.";
}
?>

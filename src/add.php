<?php
require_once 'connection.php';

if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price'];

    $imageName = $_FILES['image']['name'];
    $imageTemp = $_FILES['image']['tmp_name'];
    $imagePath = "assets/" . $imageName;

    if (!file_exists('assets/')) {
        mkdir('assets/', 0755, true);
    }

    if (move_uploaded_file($imageTemp, $imagePath)) {
        require_once 'connection.php';

        // Drop procedure addCar if exists
        $sql1 = "DROP PROCEDURE IF EXISTS addCar";
        $stmt1 = $con->prepare($sql1);
        $stmt1->execute();

        // Create procedure addCar and use it
        $sql2 = "CREATE PROCEDURE addCar(
            IN Brand VARCHAR(255),
            IN Model VARCHAR(255),
            IN Price DECIMAL(10, 2),
            IN Image VARCHAR(255)
        )
        BEGIN
            INSERT INTO cars (brand, model, price, image) VALUES (Brand, Model, Price, Image);
        END";
        $stmt2 = $con->prepare($sql2);
        $stmt2->execute();

        // Drop trigger afterAddCar if exists
        $sql3 = "DROP TRIGGER IF EXISTS afterAddCar";
        $stmt3 = $con->prepare($sql3);
        $stmt3->execute();

        // Create trigger afterAddCar
        $sql4 = "CREATE TRIGGER afterAddCar
                 AFTER INSERT ON cars
                 FOR EACH ROW
                 BEGIN
                     INSERT INTO cars_logs (
                         car_id, old_brand, new_brand, old_model, new_model, old_price, new_price, cars_timestamp
                     ) VALUES (
                         NEW.id, NEW.brand, NEW.brand, NEW.model, NEW.model, NEW.price, NEW.price, NOW()
                     );
                 END";
        $stmt4 = $con->prepare($sql4);
        $stmt4->execute();

        // Call procedure addCar
        $sql = "CALL addCar(:Brand, :Model, :Price, :Image)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':Brand', $brand, PDO::PARAM_STR);
        $stmt->bindParam(':Model', $model, PDO::PARAM_STR);
        $stmt->bindParam(':Price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':Image', $imageName, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            echo "Error adding the car.";
        }
    } else {
        echo "Error uploading the image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a car</title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Add a car</h1>
        <form method="post" action="add.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="brand" class="form-label">Brand:</label>
                <input type="text" class="form-control" id="brand" name="brand" required>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model:</label>
                <input type="text" class="form-control" id="model" name="model" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image:</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <a href="index.php" class="back-button">Back</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

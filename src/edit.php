<?php
require_once 'connection.php';

if (isset($_GET['id'])) {
    $car_id = $_GET['id'];
    
    $sql = "SELECT * FROM cars WHERE id = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $car_id, PDO::PARAM_INT);
    $stmt->execute();
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$car) {
        echo "The car does not exist.";
        exit;
    }
} 
else {
    echo "The car ID does not exist or is invalid."; 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Drop procedure editCar if exists
    $sql1 = "DROP PROCEDURE IF EXISTS editCar";
    $stmt1 = $con->prepare($sql1);
    $stmt1->execute();

    // Create procedure editCar
    $sql2 = "CREATE PROCEDURE editCar(
        IN carID INT,
        IN Brand VARCHAR(255),
        IN Model VARCHAR(255),
        IN Price DECIMAL(10, 2),
        IN Image VARCHAR(255)
    )
    BEGIN
        IF Image IS NULL THEN
            UPDATE cars
            SET brand = Brand, model = Model, price = Price
            WHERE id = carID;
        ELSE
            UPDATE cars
            SET brand = Brand, model = Model, price = Price, image = Image
            WHERE id = carID;
        END IF;
    END";
    $stmt2 = $con->prepare($sql2);
    $stmt2->execute();

    // Drop trigger afterCarEdit if exists
    $sql3 = "DROP TRIGGER IF EXISTS afterCarEdit";
    $stmt3 = $con->prepare($sql3);
    $stmt3->execute();

    // Create trigger afterCarEdit
    $sql4 = "CREATE TRIGGER afterCarEdit
             AFTER UPDATE ON cars
             FOR EACH ROW
             BEGIN
                 INSERT INTO cars_logs (
                     car_id, old_brand, new_brand, old_model, new_model, old_price, new_price, cars_timestamp
                 ) VALUES (
                     OLD.id, OLD.brand, NEW.brand, OLD.model, NEW.model, OLD.price, NEW.price, NOW()
                 );
             END";
    $stmt4 = $con->prepare($sql4);
    $stmt4->execute();


    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = $_FILES['image']['name'];
        $imageTemp = $_FILES['image']['tmp_name'];
        $imagePath = "assets/" . $imageName;
        
        if (move_uploaded_file($imageTemp, $imagePath)) {
            // Call procedure editCar
            // Update with image
            $sql = "CALL editCar(:id, :brand, :model, :price, :image)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':image', $imageName, PDO::PARAM_STR);
        } else {
            echo "Error uploading image.";
            exit;
        }
    } else {
        // Call procedure editCar
        // Update without image
        $sql = "CALL editCar(:id, :brand, :model, :price, NULL)";
        $stmt = $con->prepare($sql);
    }

    $stmt->bindParam(':id', $car_id, PDO::PARAM_INT);
    $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
    $stmt->bindParam(':model', $model, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "Error updating the car.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Edit Car</h1>
        <form method="post" action="edit.php?id=<?php echo $car_id; ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="brand" class="form-label">Brand:</label>
                <input type="text" class="form-control" id="brand" name="brand" value="<?php echo htmlspecialchars($car['brand']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model:</label>
                <input type="text" class="form-control" id="model" name="model" value="<?php echo htmlspecialchars($car['model']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($car['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image:</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <a href="index.php" class="back-button">Back</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

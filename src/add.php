<?php
require_once 'connection.php';

if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price'];

    $imageName= $_FILES['image']['name'];
    $imageTemp = $_FILES['image']['tmp_name'];
    $imagePath = "assets/" . $imageName;

    if (!file_exists('assets/')) {
        mkdir('assets/', 0755, true);
    }

    if (move_uploaded_file($imageTemp, $imagePath)) {

        $sql = "INSERT INTO carsPDO (brand, model, price, image) VALUES (:brand, :model, :price, :image)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
        $stmt->bindParam(':model', $model, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':image', $imageName, PDO::PARAM_STR);

        if ($stmt->execute()) {
            //echo "Masina adaugata cu succes!<br> <a href='index.php'>Toate masinile</a>";
            header('Location: index.php');
            exit();
        } else {
            echo "Eroare la adaugat masina.";
        }
    } else {
        echo "Eroare imagine";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta brand="viewport" content="width=device-width, initial-scale=1.0">
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
    <a href="index.php" class="back-button">Inapoi</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once 'connection.php';

if (isset($_GET['id'])) {
    $car_id = $_GET['id'];
    
    $sql = "SELECT * FROM carsPDO WHERE id = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $car_id, PDO::PARAM_INT);
    $stmt->execute();
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$car) {
        echo "masina nu exista";
        exit;
    }
} else {
    echo "Probleme la ID masina"; 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price'];

    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
       
        $imageName = $_FILES['image']['name'];
        $imageTemp = $_FILES['image']['tmp_name'];
        $imagePath = "assets/" . $imageName;
        
        if (move_uploaded_file($imageTemp, $imagePath)) {
            $sql = "UPDATE carsPDO SET brand = :brand, model = :model, price = :price, image = :image WHERE id = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':image', $imageName, PDO::PARAM_STR);
        } else {
            echo "Eroare imagine";
            exit;
        }
    } else {
        $sql = "UPDATE carsPDO SET price = :price, brand = :brand, model = :model WHERE id = :id";
        $stmt = $con->prepare($sql);
    }
    
    $stmt->bindParam(':brand', $brand, PDO::PARAM_STR);
    $stmt->bindParam(':model', $model, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price, PDO::PARAM_INT);
    $stmt->bindParam(':id', $car_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        //echo "Masina a fost actualizata cu succes!<br> <a href='index.php'>Toate masinile</a>";
        header('Location: index.php');
    } else {
        echo "Eroare la actualizarea masinii.";
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Editare masina</title>
    <link rel="stylesheet" href="../../styles1.css">
</head>
<body>
    <div>
    <h1>Editare masina</h1>
    <form action="edit.php?id=<?php echo $car_id; ?>" method="post" enctype="multipart/form-data">
        <label for="brand">Brand:</label>
        <input type="text" id="brand" name="brand" value="<?php echo htmlspecialchars($car['brand']); ?>" required><br><br>
        
        <label for="model">Model:</label>
        <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($car['model']); ?>" required><br><br>
        
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($car['price']); ?>" required><br><br>

        <label for="image">Car Image:</label>
        <input type="file" id="image" name="image" accept="image/*"><br><br>
        
        <button type="submit">Actualizează Masina</button>
    </form>
    <a href="index.php" class="back-button">Inapoi</a>
    </div>
</body>
</html>


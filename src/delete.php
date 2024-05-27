<?php
require_once 'connection.php';

if(isset($_GET['id'])) {
    
    $car_id = $_GET['id'];

    $sql = "DELETE FROM carsPDO WHERE id = :car_id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':car_id', $car_id);

    if ($stmt->execute()) {
       
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
} else {
   
    echo "id lipseste din url";
}
?>

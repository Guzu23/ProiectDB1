<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Cars Exposition</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container px-5">
            <a class="navbar-brand" href="#!">Cars Exposition</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="add.php">Add cars</a></li>
                </ul>
            </div>
        </div>
    </nav>
        <!-- Page Content-->
        <div class="container px-4 px-lg-5">

            <!-- Call to Action-->
            <div class="card text-white bg-secondary my-5 py-4 text-center">
                <div class="card-body"><p class="text-white m-0">Mai jos sunt afisate toate masinile din baza de date</p></div>
            </div>
            <!-- Content Row-->
            <div class="row gx-4 gx-lg-5">
                <!-- Show the current cars -->
                <?php
                require_once 'connection.php';
                $sql = "SELECT * FROM cars";
                $result = $con->query($sql);
                while ($car = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="col-md-4 mb-5">';
                        echo '<div class="card h-100">';
                            echo '<div class="card-body">';
                                echo '<h2 class="card-title">' . $car['brand'] . '</h2>';
                                echo '<p class="card-text">Model: ' .$car['model'] .'';
                                echo '<br>Price: ' .$car['price'] .'</p>';
                                echo '<img height="300" width="300" src="assets/' . $car['image'] . '" onerror="this.onerror=null; this.src=\'assets/404.jpg\'" alt="The image does not exist">';
                            echo '</div>';
                            echo '<div class="card-footer"><a class="btn btn-primary btn-sm" href="edit.php?id='.$car['id'] . '">Edit</a></div>';
                            echo '<div class="card-footer"><a class="btn btn-primary btn-sm" href="delete.php?id='.$car['id'] . '">Delete</a></div>';
                        echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container px-4 px-lg-5"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>

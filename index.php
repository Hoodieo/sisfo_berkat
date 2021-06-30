<?php require './inc/functions.php';
    if (!isset($_SESSION['userid'])) redirect_js('login');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sisfo Persediaan Toko Berkat</title>

    <link rel="stylesheet" href="assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="assets/js/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
</head>
<body>
    <div id="app">

        <?php include 'inc/partials/sidebar.php'; ?>

        <div id="main">
            <?php include 'inc/partials/navbar.php'; ?>

<div class="main-content container-fluid">
    <section class="section">
        <div class="row mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">

                    <?php
                        $module = (isset($_GET['m'])) ? $_GET['m'] : false ;

                        if($module){
                            $module_file = "module/$module/index.php";
                            if (file_exists($module_file)) {
                                include $module_file;
                            }else{
                                include "inc/partials/page404.php";
                            }
                        } else {
                            include "inc/partials/homepage.php";
                        }
                    ?>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

        </div>
    </div>

    <script src="assets/js/feather-icons/feather.min.js"></script>
    <script src="assets/js/app.js"></script>

    <script src="assets/js/main.js"></script>
</body>
</html>

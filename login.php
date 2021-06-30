<?php require './inc/functions.php';
    if (isset($_SESSION['userid'])) redirect_js('index');
?>

<!DOCTYPE html>
<html lang="en">
<!-- Analisis dan perancangan sistem informasi persediaan barang pada toko berkat berbasis web -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Persediaan Barang Toko Berkat</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/app.css">
</head>

<body>
<div class="container d-flex justify-content-center align-items-center">
    <div class="row mt-5">
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4 mx-auto">
            <div class="card pt-4">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4>Sistem Informasi Persediaan Barang pada Toko Berkat berbasis Web</h4>
                        <p>Silahkan login terlebih dahulu</p>
                    </div>

                    <div class="alert-container"></div>

                    <form id="login-form" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="position-relative">
                                <input type="text" class="form-control" name="username" id="username" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="clearfix">
                                <label for="password">Password</label>
                            </div>
                            <div class="position-relative">
                                <input type="password" class="form-control" name="password" id="password" autocomplete="off">
                            </div>
                        </div>

                        <div class="clearfix">
                            <button type="submit" id="btn-login" class="btn btn-primary float-right">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>

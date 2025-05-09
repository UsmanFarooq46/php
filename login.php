<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invisible Intercom | Log in</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="css/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="css/adminlte.min.css">
    <script>sessionStorage.clear();localStorage.clear()</script>
</head>

<body class="hold-transition login-page" style="background-color: #fff;">
    <div class="login-box">
        <div class="login-logo">
            <!-- <a href="index.php"><b>Invisible</b>Intercom</a> -->
            <a href="index.php"><img src="/include/images/Invisible_Intercom.png" alt="Invisible Logo"  style="height:100%;width:60%; margin-bottom: 15px;"></a>
        </div>
        <!-- /.login-logo -->
        <div class="">
            <div class=" login-card-body">
                <!-- <p class="login-box-msg">Sign in to start your session</p> -->

                <form action="include/auth/login_db.php" method="post">
                    <div class="input-group mb-3">
                        <input type="email" name="lemail" class="form-control" placeholder="Email" required style="border-radius: 7px 0px 0px 7px; background-color: #f8f8f8;">
                        <div class="input-group-append">
                            <div class="input-group-text" style="border-radius: 0px 7px 7px 0px; background-color: #f8f8f8;">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="lpwd" class="form-control" placeholder="Password" required style="border-radius: 7px 0px 0px 7px; background-color: #f8f8f8;">
                        <div class="input-group-append">
                            <div class="input-group-text" style="border-radius: 0px 7px 7px 0px; background-color: #f8f8f8;">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-secondary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="submit" class="btn btn-secondary btn-block" style="border-radius: 7px 7px 7px 7px; ">Sign In</button>
                        </div>
                        <!-- <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <a href="forgot-password.php"><button type="button" class="btn btn-secondary btn-block" style="border-radius: 7px 7px 7px 7px; margin-top: 7px;">I forgot my password</button></a>
                        </div>
                    </div>
                </form>

                <!-- <div class="social-auth-links text-center mb-3">
                    <p>- OR -</p>
                    <a href="#" class="btn btn-block btn-primary">
                        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                    </a>
                    <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                    </a>
                </div> -->
                <!-- /.social-auth-links -->

                <!-- <p class="mb-1">
                    <a href="forgot-password.php">I forgot my password</a>
                </p> -->
                <!-- <p class="mb-0">
                    <a href="register.php" class="text-center">Register a new membership</a>
                </p> -->
            </div>
            <!-- /.login-card-body -->
        </div>
        <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "emptyinput") {
                    echo "<p>Please Fill in all Fields!</p>";
                } else if ($_GET["error"] == "invalidemail") {
                    echo "<p>Email is Not Valid!</p>";
                } else if ($_GET["error"] == "passwordsdonotmatch") {
                    echo "<p>Passwords Do Not Match!</p>";
                }
            }
        ?>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="js/adminlte.min.js"></script>
    <script>

        
    </script>
</body>

</html>
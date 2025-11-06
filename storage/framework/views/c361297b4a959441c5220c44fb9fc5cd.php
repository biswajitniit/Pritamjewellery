<!DOCTYPE html>
<html lang="en" data-bs-theme="blue-theme">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Pritam Jewellers</title>
        <!--favicon-->
        <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
        <!-- loader-->
        <link href="assets/css/pace.min.css" rel="stylesheet" />
        <script src="assets/js/pace.min.js"></script>

        <!--plugins-->
        <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="assets/plugins/metismenu/metisMenu.min.css" />
        <link rel="stylesheet" type="text/css" href="assets/plugins/metismenu/mm-vertical.css" />
        <!--bootstrap css-->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet" />
        <!--main css-->
        <link href="assets/css/bootstrap-extended.css" rel="stylesheet" />
        <link href="sass/main.css" rel="stylesheet" />
        <!-- <link href="sass/dark-theme.css" rel="stylesheet">
        <link href="sass/blue-theme.css" rel="stylesheet"> -->
        <link href="sass/responsive.css" rel="stylesheet" />
    </head>

    <body>
        <!--authentication-->

        <div class="mx-3 mx-lg-0">
            <div class="card my-5 col-xl-9 col-xxl-8 mx-auto rounded-4 overflow-hidden p-4">
                <div class="row g-4">
                    <div class="col-lg-6 d-flex">
                        <div class="card-body">
                            <div class="text-center">

                                    <img src="assets/images/logo1.png" alt />
                                    <h4>Pritam Jewellers</h4>

                            </div>

                            <?php if(Session('error')): ?>
                                <div class="text-danger"><?php echo e(session('error')); ?></div>
                            <?php endif; ?>

                            <div class="form-body mt-4">

                                <form class="row g-3" action="<?php echo e(route('login.post')); ?>" method="POST" name="loginpage">
                                    <?php echo csrf_field(); ?>
                                    <div class="col-12">
                                        <label for="inputEmailAddress" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" id="inputEmailAddress" placeholder="jhon@example.com" />
                                    </div>
                                    <div class="col-12">
                                        <label for="inputChoosePassword" class="form-label">Password</label>
                                        <div class="input-group" id="show_hide_password">
                                            <input type="password" name="password" class="form-control border-end-0" id="inputChoosePassword" value="" placeholder="Enter Password" />
                                            <a href="javascript:;" class="input-group-text bg-transparent"><i class="bi bi-eye-slash-fill"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked />
                                            <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <a href="auth-boxed-forgot-password.html">Forgot Password ?</a>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-grd-primary">Login</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-lg-flex d-none">
                        <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center bg-grd-primary">
                            <img src="assets/images/auth/3796706_9644.jpg" class="img-fluid" alt />
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>

        <!--authentication-->

        <!--plugins-->
        <script src="assets/js/jquery.min.js"></script>

        <script>
            $(document).ready(function () {
                $("#show_hide_password a").on("click", function (event) {
                    event.preventDefault();
                    if ($("#show_hide_password input").attr("type") == "text") {
                        $("#show_hide_password input").attr("type", "password");
                        $("#show_hide_password i").addClass("bi-eye-slash-fill");
                        $("#show_hide_password i").removeClass("bi-eye-fill");
                    } else if ($("#show_hide_password input").attr("type") == "password") {
                        $("#show_hide_password input").attr("type", "text");
                        $("#show_hide_password i").removeClass("bi-eye-slash-fill");
                        $("#show_hide_password i").addClass("bi-eye-fill");
                    }
                });
            });
        </script>
    </body>
</html>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/auth/login.blade.php ENDPATH**/ ?>
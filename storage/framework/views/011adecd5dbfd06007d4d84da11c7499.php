<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pritam Jewellers</title>
    <!--favicon-->
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon-32x32.png')); ?>" type="image/png">
    <!-- loader-->
    <link href="<?php echo e(asset('assets/css/pace.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('assets/js/pace.min.js')); ?>"></script>

    <!--plugins-->
    <link href="<?php echo e(asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/plugins/metismenu/metisMenu.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/plugins/metismenu/mm-vertical.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/plugins/simplebar/css/simplebar.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/font.awesome.css')); ?>">
    <!--bootstrap css-->
    <!--bootstrap css-->
    <link href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <!--main css-->
    <link href="<?php echo e(asset('assets/css/bootstrap-extended.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/horizontal-menu.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('sass/bordered-theme.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('sass/responsive.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('sass/main.css')); ?>" rel="stylesheet">
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


</head>

<body>


    <!--start header-->
    <header class="top-header">
        <nav class="navbar navbar-expand align-items-center justify-content-between gap-4 border-bottom">
            <div class="logo-header d-none d-xl-flex align-items-center gap-2">
                <div class="logo-icon">
                    <img src="<?php echo e(asset('assets/images/logo1.png')); ?>" class="logo-img" width="45" alt>
                </div>
                <div class="logo-name">
                    <h5 class="mb-0">Pritam Jewellers</h5>
                </div>
            </div>
            <div class="btn-toggle d-xl-none" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
                <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
            </div>
            <div class="search-bar">
                <div class="position-relative">
                    <input class="form-control rounded-5 px-5 search-control d-lg-block d-none" type="text"
                        placeholder="Search">
                    <span
                        class="material-icons-outlined position-absolute d-lg-block d-none ms-3 translate-middle-y start-0 top-50">search</span>
                    <span
                        class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 search-close">close</span>
                    <div class="search-popup p-3">
                        <div class="card rounded-4 overflow-hidden">
                            <div class="card-header d-lg-none">
                                <div class="position-relative">
                                    <input class="form-control rounded-5 px-5 mobile-search-control" type="text"
                                        placeholder="Search">
                                    <span
                                        class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50">search</span>
                                </div>
                            </div>

                            <div class="card-footer text-center bg-transparent">
                                <a href="javascript:;" class="btn w-100">See All Search
                                    Results</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="navbar-nav gap-1 nav-right-links align-items-center">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                        data-bs-auto-close="outside" data-bs-toggle="dropdown" href="javascript:;"><i
                            class="material-icons-outlined">notifications</i>
                        <span class="badge-notify">5</span>
                    </a>
                    <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow">
                        <div class="px-3 py-1 d-flex align-items-center justify-content-between border-bottom">
                            <h5 class="notiy-title mb-0">Notifications</h5>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle dropdown-toggle-nocaret option"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="material-icons-outlined">
                                        more_vert
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-option dropdown-menu-end shadow">
                                    <div><a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                            href="javascript:;"><i
                                                class="material-icons-outlined fs-6">inventory_2</i>Archive
                                            All</a></div>
                                    <div><a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                            href="javascript:;"><i class="material-icons-outlined fs-6">done_all</i>Mark
                                            all as read</a></div>
                                    <div><a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                            href="javascript:;"><i
                                                class="material-icons-outlined fs-6">mic_off</i>Disable
                                            Notifications</a></div>
                                    <div><a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                            href="javascript:;"><i class="material-icons-outlined fs-6">grade</i>What's
                                            new ?</a></div>
                                    <div>
                                        <hr class="dropdown-divider">
                                    </div>
                                    <div><a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                            href="javascript:;"><i
                                                class="material-icons-outlined fs-6">leaderboard</i>Reports</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="notify-list">
                            <div>
                                <a class="dropdown-item border-bottom py-2" href="javascript:;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class>
                                            <img src="<?php echo e(asset('assets/images/avatars/01.png')); ?>"
                                                class="rounded-circle" width="45" height="45" alt>
                                        </div>
                                        <div class>
                                            <h5 class="notify-title">Congratulations Jhon</h5>
                                            <p class="mb-0 notify-desc">Many congtars jhon. You have
                                                won the gifts.</p>
                                            <p class="mb-0 notify-time">Today</p>
                                        </div>
                                        <div class="notify-close position-absolute end-0 me-3">
                                            <i class="material-icons-outlined fs-6">close</i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div>
                                <a class="dropdown-item border-bottom py-2" href="javascript:;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-wrapper bg-primary text-primary bg-opacity-10">
                                            <span>RS</span>
                                        </div>
                                        <div class>
                                            <h5 class="notify-title">New Account Created</h5>
                                            <p class="mb-0 notify-desc">From USA an user has
                                                registered.</p>
                                            <p class="mb-0 notify-time">Yesterday</p>
                                        </div>
                                        <div class="notify-close position-absolute end-0 me-3">
                                            <i class="material-icons-outlined fs-6">close</i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div>
                                <a class="dropdown-item border-bottom py-2" href="javascript:;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class>
                                            <img src="<?php echo e(asset('assets/images/apps/13.png')); ?>" class="rounded-circle"
                                                width="45" height="45" alt>
                                        </div>
                                        <div class>
                                            <h5 class="notify-title">Payment Recived</h5>
                                            <p class="mb-0 notify-desc">New payment recived
                                                successfully</p>
                                            <p class="mb-0 notify-time">1d ago</p>
                                        </div>
                                        <div class="notify-close position-absolute end-0 me-3">
                                            <i class="material-icons-outlined fs-6">close</i>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a href="javascrpt:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
                        <img src="<?php echo e(asset('assets/images/avatars/01.png')); ?>" class="rounded-circle p-1 border"
                            width="45" height="45" alt>
                    </a>
                    <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                                class="material-icons-outlined">person_outline</i>Profile</a>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                                class="material-icons-outlined">local_bar</i>Setting</a>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                                class="material-icons-outlined">dashboard</i>Dashboard</a>

                        <hr class="dropdown-divider">
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?php echo e(route('logout')); ?>"><i
                                class="material-icons-outlined">power_settings_new</i>Logout</a>
                    </div>
                </li>
            </ul>

        </nav>
    </header>
    <!--end top header-->

    <!--navigation-->
    <div class="primary-menu">
        <nav class="navbar navbar-expand-xl align-items-center">
            <div class="offcanvas offcanvas-start w-260" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header border-bottom h-70">
                    <div class="d-flex align-items-center gap-2">
                        <div class>
                            <img src="<?php echo e(asset('assets/images/logo-icon.png')); ?>" class="logo-icon" width="45"
                                alt="logo icon" />
                        </div>
                        <div class>
                            <h4 class="logo-text">Bhagya Laxmi Jewellery</h4>
                        </div>
                    </div>
                    <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="offcanvas">
                        <i class="material-icons-outlined">close</i>
                    </a>
                </div>
                <div class="offcanvas-body p-0">
                    <ul class="navbar-nav align-items-center flex-grow-1 navbar_">



                        <?php if(Auth::user()->id == 1): ?>
                        <!-- For access only super admin -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                                data-bs-toggle="dropdown">
                                <div class="menu-title d-flex align-items-center">Company</div>
                                <div class="ms-auto dropy-icon"><i class="material-icons-outlined">expand_more</i>
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('rolepermissionusers.index')); ?>">
                                        Roles & Permissions
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('users.index')); ?>">
                                        Admins
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                                data-bs-toggle="dropdown">
                                <div class="menu-title d-flex align-items-center">Master</div>
                                <div class="ms-auto dropy-icon">
                                    <i class="material-icons-outlined">expand_more</i>
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                <?php
                                $menus = [
                                'pcodes' => 'PCODE',
                                'uoms' => 'UOM',
                                'sizes' => 'Size',
                                'patterns' => 'Pattern',
                                'stones' => 'Stone',
                                'customers' => 'Party',
                                //'companies' => 'Company',
                                'vendors' => 'Vendors',
                                'karigars' => 'Karigar',
                                'itemdescriptionheaders' => 'Item description header',
                                'products' => 'Product',
                                'metals' => 'Metal',
                                'metalpurities' => 'Metal Purity',
                                'tollerences' => 'Tollerence',
                                'locations' => 'Location',
                                'miscellaneouses' => 'Miscellaneous',
                                ];
                                ?>

                                <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $permission = getUserMenuPermission(
                                Auth::user()->id,
                                $route,
                                'menu_permissions',
                                );
                                ?>

                                <?php if($permission && $permission->menu_permissions == 1): ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route($route . '.index')); ?>">
                                        <?php echo e($label); ?>

                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Reason
                                    </a>
                                </li>

                                <!--<li class="nav-item dropend">
                                        <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;">
                                        <i class='material-icons-outlined'>pie_chart</i>Maps
                                        </a>
                                        <ul class="dropdown-menu submenu">
                                        <li>
                                            <a class="dropdown-item" href="#">
                                            <i class='material-icons-outlined'>navigate_next</i>Google Maps
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#">
                                            <i class='material-icons-outlined'>navigate_next</i>Vector Maps
                                            </a>
                                        </li>
                                        </ul>
                                    </li>-->
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                                data-bs-toggle="dropdown">
                                <div class="menu-title d-flex align-items-center">Transaction</div>
                                <div class="ms-auto dropy-icon">
                                    <i class="material-icons-outlined">expand_more</i>
                                </div>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item dropend">
                                    <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret"
                                        href="javascript:;">
                                        Order
                                    </a>
                                    <ul class="dropdown-menu submenu">

                                        



                                <?php
                                $permission_customerorders = getUserMenuPermission(
                                Auth::user()->id,
                                'customerorders',
                                'menu_permissions',
                                );
                                ?>

                                <?php if($permission_customerorders && $permission_customerorders->menu_permissions == 1): ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('customerorders.index')); ?>">
                                        Customer Order
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php
                                $permission_issuetokarigars = getUserMenuPermission(
                                Auth::user()->id,
                                'issuetokarigars',
                                'menu_permissions',
                                );
                                ?>

                                <?php if($permission_issuetokarigars && $permission_issuetokarigars->menu_permissions == 1): ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('issuetokarigars.index')); ?>">
                                        Issue To Karigar
                                    </a>
                                </li>
                                <?php endif; ?>


                                <!--<li>-->
                                <!--    <a class="dropdown-item" href="#">-->
                                <!--        Regular Order-->
                                <!--    </a>-->
                                <!--</li>-->
                                
                                <!--<li>-->
                                <!--    <a class="dropdown-item" href="#">-->
                                <!--        Modify Karigar Issue-->
                                <!--    </a>-->
                                <!--</li>-->
                                <!--<li>-->
                                <!--    <a class="dropdown-item" href="#">-->
                                <!--        Customer Order-->
                                <!--        <br />-->
                                <!--        Modification-->
                                <!--    </a>-->
                                <!--</li>-->
                                <!--<li>-->
                                <!--    <a class="dropdown-item" href="#">-->
                                <!--        Order Cancel-->
                                <!--    </a>-->
                                <!--</li>-->
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Tolerence Problem
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <li class="nav-item dropend">
                            <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;">
                                Gold Receipt Entry
                            </a>
                            <ul class="dropdown-menu submenu">
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('metalreceiveentries.index')); ?>">
                                        Stdbar/Alloy/Finding received
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('finishproductreceivedentries.index')); ?>">
                                        Finished Product Received
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Return Gold From Karigar
                                    </a>
                                </li>
                                <li></li>
                            </ul>
                        </li>



                        <li class="nav-item dropend">
                            <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;">
                                Gold Issue Entry
                            </a>
                            <ul class="dropdown-menu submenu">
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('metalissueentries.index')); ?>">
                                        Gold Issue To Karigar </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#"> </a>
                                </li>
                            </ul>
                        </li>


                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('qualitychecks.index')); ?>">
                                Quality check
                            </a>
                        </li>


                        <!--<li class="nav-item dropend">-->
                        <!--    <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;">-->
                        <!--        Not Order-->
                        <!--    </a>-->
                        <!--    <ul class="dropdown-menu submenu">-->
                        <!--        <li>-->
                        <!--            <a class="dropdown-item" href="#"> </a>-->
                        <!--        </li>-->
                        <!--        <li>-->
                        <!--            <a class="dropdown-item" href="#"> </a>-->
                        <!--        </li>-->
                        <!--        <li>-->
                        <!--            <a class="dropdown-item" href="#"> </a>-->
                        <!--        </li>-->
                        <!--    </ul>-->
                        <!--</li>-->


                        <!--<li class="nav-item dropend">-->
                        <!--    <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;">-->
                        <!--        Modify Q.C.-->
                        <!--    </a>-->
                        <!--    <ul class="dropdown-menu submenu">-->
                        <!--        <li>-->
                        <!--            <a class="dropdown-item" href="#"> </a>-->
                        <!--        </li>-->
                        <!--        <li>-->
                        <!--            <a class="dropdown-item" href="#"> </a>-->
                        <!--        </li>-->
                        <!--    </ul>-->
                        <!--</li>-->



                        <!--<li>-->
                        <!--    <a class="dropdown-item" href="#">-->
                        <!--        Voucher Printing-->
                        <!--    </a>-->
                        <!--</li>-->

                        <li class="nav-item dropend">
                            <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;">
                                Stock Out
                            </a>
                            <ul class="dropdown-menu submenu">
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('finishedproductpdis.index')); ?>">
                                        Finished Product PDI
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('stockoutpdilists.index')); ?>">
                                        PDI List
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Auto Update PDI
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Courier Detail
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Cancel Item 4m D.C.
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropend">
                            <a class="dropdown-item dropdown-toggle dropdown-toggle-nocaret" href="javascript:;">
                                Rejection
                            </a>
                            <ul class="dropdown-menu submenu">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Rejection Recd. From Customer
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Rejection Repair at site
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Rejection issue to Karigar
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Rejection during 2nd QC
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        issue NPIM Gold to Karigar
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <?php
                        $permission_purchases = getUserMenuPermission(
                        Auth::user()->id,
                        'purchases',
                        'menu_permissions',
                        );
                        ?>

                        <?php if($permission_purchases && $permission_purchases->menu_permissions == 1): ?>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('purchases.index')); ?>">Purchases</a>
                        </li>
                        <?php endif; ?>


                        <?php
                        $permission_sales = getUserMenuPermission(
                        Auth::user()->id,
                        'sales',
                        'menu_permissions',
                        );
                        ?>

                        <?php if($permission_sales && $permission_sales->menu_permissions == 1): ?>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('sales.index')); ?>">Sales</a>
                        </li>
                        <?php endif; ?>

                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('stock-transfers.index')); ?>">Stock Transfer</a>
                        </li>


                    </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                            data-bs-toggle="dropdown">
                            <div class="menu-title d-flex align-items-center">Reports</div>
                            <div class="ms-auto dropy-icon">
                                <i class="material-icons-outlined">expand_more</i>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            

                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('pendinglist.index')); ?>">
                                    Pending List
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('stockeffect.index')); ?>">
                                    Stock Ledger
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('new-report')); ?>">
                                    Day wise Report
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('daywisereport.index')); ?>">
                                    Gold Statement - Karigar
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('daywisereport.index')); ?>">
                                    Gold Statement
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('purchase-ledger')); ?>">
                                    Purchase Ledger
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('sales-register')); ?>">
                                    Sales Register
                                </a>
                            </li>

                            <li>
                                    <a class="dropdown-item" href="<?php echo e(route('karigar.itemcodes')); ?>">
                                        Item Code Wise Details - Karigar
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('karigar.jobdetails')); ?>">
                                        Job Wise Delivery Details
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('karigar.qualitycheck')); ?>">
                                        Quality Check Report
                                    </a>
                                </li>

                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('itemcodedetailorder.index')); ?>">
                                    Item code detail order
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('purchase-ledger')); ?>">
                                    Purchase Ledger
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('sales-register')); ?>">
                                    Sales Register
                                </a>
                            </li>

                            
                        </ul>
                    </li>

                    <?php if(Auth::user()->id == 1): ?>
                    <!-- For access only super admin -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                            data-bs-toggle="dropdown">
                            <div class="menu-title d-flex align-items-center">Settings</div>
                            <div class="ms-auto dropy-icon"><i class="material-icons-outlined">expand_more</i>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('financial-years.index')); ?>">
                                    Financial Year
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('vouchertypes.index')); ?>">
                                    Voucher Type
                                </a>
                            </li>

                        </ul>
                    </li>
                    <?php endif; ?>


                    

                    </ul>
                </div>
            </div>

        </nav>
    </div>
    <!--end navigation-->
<?php /**PATH D:\Lara\Pritamjewellery\resources\views/include/header.blade.php ENDPATH**/ ?>
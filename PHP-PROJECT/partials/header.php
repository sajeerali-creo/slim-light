<?php
require 'config/database.php';
require_once 'services/commonService.php';

if (!isset($_SESSION['user']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    $query = mysqli_query($connection, "SELECT * FROM remember_tokens WHERE token = '$token' AND expires_at > NOW()");
    $row = mysqli_fetch_assoc($query);
    if ($row) {
        $userData = json_decode($row['user'], true);
        $_SESSION['user'] = $userData;
    } else {
        // Token expired or invalid
        setcookie('remember_token', '', time() - 3600, '/');
    }
}
$fullname = "";
$clientprofileData = [];
if (isset($_SESSION['user'])) {
    $paramData = [
        'PersonId' => $_SESSION['user']['UserId']
    ];
    $response = fetchUserProfile($paramData);
    if (
        isset($response['ValidationDetails']['StatusCode']) &&
        $response['ValidationDetails']['StatusCode'] == 200 &&
        !empty($response['MasterDataList'][0])
    ) {
        $clientprofileData = $response['MasterDataList'];
        $fullname = $clientprofileData[0]['FirstName'] . ' ' . $clientprofileData[0]['MiddleName'] . ' ' . $clientprofileData[0]['LastName'];
        $profilePicture = $clientprofileData[0]['ProfilePicture'];
    }
}


?>
<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="max-age=3600">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Blite</title>
    <!-- main framework css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- iconfont -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <!-- main font -->
    <link href="https://fonts.cdnfonts.com/css/helvetica-neue-55" rel="stylesheet">
    <!-- main style -->
    <link rel="stylesheet" href="css/core_60.css">
    <!-- animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- slider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- jQuery UI JS (must be after jQuery) -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>

    <!-- datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</head>

<body>
    <div id="preloader" aria-hidden="false">
        <div class="spinner-body" role="status" aria-label="Loading"></div>
    </div>

    <header class="main-header d-flex align-items-center">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="<?= ROOT_URL ?>" class="d-flex align-items-center">
                <img src="img/logo-animated2.gif" class="logo" alt="">
            </a>
            <div class="d-none d-lg-block">
                <div class="d-flex gap-5 menus align-items-center">
                    <a href="#" class="drawer-trigger position-relative" id="myBtn">Meal Plans</a>
                    <div class="drawer mx-5">
                        <div class="container">
                            <div class="drawer-inner p-4">
                                <div class="row row-gap-3">
                                    <div class="col-lg-4">
                                        <div class="transp-box">
                                            <a href="<?= ROOT_URL ?>plan_details.php?id=1" class="d-flex gap-3 align-items-center">
                                                <div>
                                                    <img src="img/ic-1.svg" alt="">
                                                </div>
                                                <div class="text-brand">SHAPE UP</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="transp-box">
                                             <a href="<?= ROOT_URL ?>plan_details.php?id=10" class="d-flex gap-3 align-items-center">
                                                <div>
                                                    <img src="img/ic-2.svg" alt="">
                                                </div>
                                                <div class="text-brand">KETO BYTE</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="transp-box">
                                            <a href="<?= ROOT_URL ?>plan_details.php?id=3" class="d-flex gap-3 align-items-center">
                                                <div>
                                                    <img src="img/ic-3.svg" alt="">
                                                </div>
                                                <div class="text-brand">BULK UP</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="transp-box">
                                            <a href="<?= ROOT_URL ?>plan_details.php?id=9" class="d-flex gap-3 align-items-center">
                                                <div>
                                                    <img src="img/ic-4.svg" alt="">
                                                </div>
                                                <div class="text-brand">V Lite</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="transp-box">
                                            <a href="<?= ROOT_URL ?>plan_details.php?id=11" class="d-flex gap-3 align-items-center">
                                                <div>
                                                    <img src="img/ic-5.svg" alt="">
                                                </div>
                                                <div class="text-brand">EnergizeHER</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="transp-box">
                                             <a href="<?= ROOT_URL ?>plan_details.php?id=12" class="d-flex gap-3 align-items-center">
                                                <div>
                                                    <img src="img/ic-6.svg" alt="">
                                                </div>
                                                <div class="text-brand">MED Lite</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="transp-box">
                                            <a href="<?= ROOT_URL ?>plan_details.php?id=13" class="d-flex gap-3 align-items-center">
                                                <div>
                                                    <img src="img/ic-7.svg" alt="">
                                                </div>
                                                <div class="text-brand">MUMZ Fuel</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="transp-box">
                                            <a href="<?= ROOT_URL ?>plan_details.php?id=14" class="d-flex gap-3 align-items-center">
                                                <div>
                                                    <img src="img/ic-8.svg" alt="">
                                                </div>
                                                <div class="text-brand">LIL TOTS</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="transp-box">
                                            <a href="<?= ROOT_URL ?>plan_details.php?id=10" class="d-flex gap-3 align-items-center">
                                                <div>
                                                    <img src="img/ic-9.svg" alt="">
                                                </div>
                                                <div class="text-brand">B FIT</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 d-flex justify-content-end">
                                        <a href="<?= ROOT_URL ?>meal_plans.php" class="text-brand">View all Meal
                                            Plans&nbsp;<i class="ti ti-arrow-narrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="<?= ROOT_URL ?>our_services.php">Our Services</a>
                    <a href="<?= ROOT_URL ?>about.php">About Us</a>
                    <a href="<?= ROOT_URL ?>contact.php">Contact Us</a>
                </div>
            </div>
            <div class="d-none d-lg-block">
                <?php if (!isset($_SESSION['user'])) { ?>
                    <div class="d-flex align-items-center gap-2">
                        <a href="tel:800-4387546" class="text-white fs-4"><i class="ti ti-phone"></i></a>
                        <a href="<?= ROOT_URL ?>login.php" class="fs- px-3 text-white">Login</a>
                        <a href="<?= ROOT_URL ?>signup.php" class="btn-first">Sign Up</a>
                    </div>
                <?php } else { ?>
                    <div class="d-flex align-items-center gap-2">
                        <a href="<?= ROOT_URL ?>profile.php"><span class="text-white"><?php echo $fullname; ?></span></a>
                        <div class="user-head">

                            <a href="<?= ROOT_URL ?>profile.php">
                                <?php
                                $base64img = $profilePicture != null ? $profilePicture : ROOT_URL . 'img/defaultimagex2.webp';
                                if ($profilePicture != null) { ?>
                                    <img src="data:image/jpeg;base64,<?= $base64img ?>" alt="">
                                <?php } else { ?>
                                    <img src="<?= $base64img ?>" alt="">
                                <?php }
                                ?>

                            </a>
                        </div>
                        <!-- <a href="<?= ROOT_URL ?>logout.php" class="btn-first w-auto px-4">Logout</a> -->
                    </div>
                <?php } ?>
            </div>
            <div class="d-lg-none">
                <a href="#" class="btn-menu" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"><i
                        class="ti ti-menu-4"></i></a>
            </div>
        </div>



        <!-- offcanvas -->
        <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
            aria-labelledby="offcanvasWithBothOptionsLabel">
            <div class="offcanvas-body">
                <div class="d-flex flex-column gap-4 justify-content-between h-100">
                    <div class="d-flex gap-4 flex-column">
                        <?php if (isset($_SESSION['user'])) { ?>
                            <div class="user-card">
                                <div class="user-avtar">
                                    <!-- <img src="img/avatar.webp" alt=""> -->
                                    <i class="ti ti-user"></i>
                                </div>
                                <a href="<?= ROOT_URL ?>profile.php"><strong
                                        class="text-white mt-2 d-flex"><?php echo $fullname; ?></strong></a>
                            </div>
                        <?php } ?>
                        <div class="d-flex flex-column gap-5 menus p-4">
                            <a href="<?= ROOT_URL ?>meal_plans.php">Meal Plans</a>
                            <!-- <div class="d-flex flex-column gap-3">
                                <a href="<?= ROOT_URL ?>plan_details.php?id=1" class="px-4">SHAPE UP</a>
                                <a href="<?= ROOT_URL ?>plan_details.php?id=10" class="px-4">KETO BYTE</a>
                                <a href="<?= ROOT_URL ?>plan_details.php?id=3" class="px-4">BULK UP</a>
                                <a href="<?= ROOT_URL ?>plan_details.php?id=9" class="px-4">V Lite</a>
                                <a href="<?= ROOT_URL ?>plan_details.php?id=11" class="px-4">EnergizeHER</a>
                                <a href="<?= ROOT_URL ?>plan_details.php?id=12" class="px-4">MED Lite</a>
                                <a href="<?= ROOT_URL ?>plan_details.php?id=13" class="px-4">MUMZ Fuel</a>
                                <a href="<?= ROOT_URL ?>plan_details.php?id=14" class="px-4">LIL TOTS</a>
                                <a href="<?= ROOT_URL ?>plan_details.php?id=10" class="px-4">B FIT</a>
                            </div> -->
                            <a href="<?= ROOT_URL ?>our_services.php">Our Services</a>
                            <a href="<?= ROOT_URL ?>about.php">About Us</a>
                            <a href="<?= ROOT_URL ?>contact.php">Contact Us</a>
                            <?php if (isset($_SESSION['user'])) { ?><a href="<?= ROOT_URL ?>profile.php">My
                                    Profile</a><?php } ?>
                        </div>
                    </div>
                    <?php if (!isset($_SESSION['user'])) { ?>
                        <div class="d-flex flex-column gap-2 p-4">
                            <a href="<?= ROOT_URL ?>login.php" class="btn-second w-100">Login</a>
                            <a href="<?= ROOT_URL ?>signup.php" class="btn-first w-100">Sign Up</a>
                        </div>
                    <?php } else { ?>
                        <div class="d-flex flex-column gap-2 p-4">
                            <a href="<?= ROOT_URL ?>logout.php" class="btn-first w-100">Logout</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="whatsap">
            <a href="https://wa.me/+971588052025" target="_blank" class="pulse"><i class="ti ti-brand-whatsapp"></i></a>
        </div>
    </header>
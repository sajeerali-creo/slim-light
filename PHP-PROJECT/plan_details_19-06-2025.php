<?php
include 'partials/header.php';

if (isset($_GET['id'])) {
    $qry = $connection->query("SELECT * from `meal_plans` where id = '{$_GET['id']}' ");
    foreach ($qry->fetch_array() as $k => $v) {
        if (!is_numeric($k)) {
            $$k = $v;
        }
    }
}
$packageGroup = [];
$response = GetPackageGroupList();
if (
    isset($response['ValidationDetails']['StatusCode']) &&
    $response['ValidationDetails']['StatusCode'] == 200 &&
    !empty($response['MasterDataList'][0])
) {
    $packageGroup = $response['MasterDataList'];
}
$packages = [];
$response = GetPackageList();
if (
    isset($response['ValidationDetails']['StatusCode']) &&
    $response['ValidationDetails']['StatusCode'] == 200 &&
    !empty($response['MasterDataList'][0])
) {
    $packages = $response['MasterDataList'];
}
$location_response = fetchLocations();
if ($location_response['ValidationDetails']['StatusCode'] == 200) {
    $locations = $location_response['MasterDataList'];
}
?>
<!-- ===========BANNER=========== -->
<section class="inner-banner">
</section>

<!-- ===========SUBHEADER=========== -->
<section class="py-5 bg-inner-sub-head">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between">
            <div class="d-flex flex-column align-items-start">
                <h4 class="h2">Plan Details</h4>
                <p>Plan Details: Full health, dental, vision, wellness, flexible premiums, 24/7 support.</p>
            </div>
            <div>
                <a class="btn-outline btn-back" href="<?= ROOT_URL ?>">Back</a>
            </div>
        </div>
    </div>
</section>


<!-- ===========SIGNATURE DISH=========== -->
<section class="padding-bottom padding-top">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 product-slider">
                <div id="customCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?php echo ADMIN_URL . $file_path; ?>" class="d-block w-100" alt="Slide 1">
                        </div>
                        <!-- <div class="carousel-item">
                                <img src="img/detail-2.webp" class="d-block w-100" alt="Slide 2">
                            </div>
                            <div class="carousel-item">
                                <img src="img/detail-3.webp" class="d-block w-100" alt="Slide 3">
                            </div>
                            <div class="carousel-item">
                                <img src="img/detail-4.webp" class="d-block w-100" alt="Slide 3">
                            </div>
                            <div class="carousel-item">
                                <img src="img/detail-5.webp" class="d-block w-100" alt="Slide 3">
                            </div> -->
                    </div>
                    <!-- <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="carousel-control-prev" type="button" data-bs-target="#customCarousel"
                                data-bs-slide="prev">
                                <i class="ti ti-arrow-narrow-left"></i>
                            </button>
                            <div class="carousel-indicators position-relative mt-3">
                                <button type="button" data-bs-target="#customCarousel" data-bs-slide-to="0"
                                    class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#customCarousel" data-bs-slide-to="1"
                                    aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#customCarousel" data-bs-slide-to="2"
                                    aria-label="Slide 3"></button>
                                <button type="button" data-bs-target="#customCarousel" data-bs-slide-to="3"
                                    aria-label="Slide 4"></button>
                                <button type="button" data-bs-target="#customCarousel" data-bs-slide-to="4"
                                    aria-label="Slide 5"></button>
                            </div>
                            <button class="carousel-control-next" type="button" data-bs-target="#customCarousel"
                                data-bs-slide="next">
                                <i class="ti ti-arrow-narrow-right"></i>
                            </button>
                        </div> -->
                </div>
            </div>
            <div class="col-lg-5">
                <h4>About the Plan</h4>
                <h2 class="h2"><?php echo isset($title) ? $title : '' ?></h2>
                <p><?php echo (isset($description)) ? html_entity_decode(($description)) : '' ?></p>
                <div class="d-flex mt-5 light-bg">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo"
                        class="btn-fancy">
                        <span>Get Started Today</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container py-5">
                    <div class="row">
                        <div class="col-lg-8">
                            <h2 class="h2 text-brand d-flex mb-4">BEGIN YOUR HEALTHY JOURNEY</h2>
                            <h4>Package Type</h4>
                            <p>All our meal packages can be customized to meet your budget.</p>
                            <div>
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <?php
                                    //echo "<pre>";
                                    //print_r($packageGroup);
                                    ?>
                                    <?php if ($packageGroup) {
                                        foreach ($packageGroup as $index => $item): ?>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link <?= $index == 0 ? 'active' : ''; ?>"
                                                    id="package-tab-<?= $item['GroupCode'] ?>" data-bs-toggle="tab"
                                                    data-bs-target="#package-tab-<?= $item['GroupCode'] ?>-pane" type="button"
                                                    role="tab" aria-controls="package-tab-<?= $item['GroupCode'] ?>-pane"
                                                    aria-selected="true"><?php echo $item['GroupName']; ?></button>
                                            </li>
                                        <?php endforeach;
                                    } ?>
                                    <!-- <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="b-slim-tab" data-bs-toggle="tab"
                                                data-bs-target="#b-slim-tab-pane" type="button" role="tab"
                                                aria-controls="b-slim-tab-pane" aria-selected="false">B-Slim</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="lunch-plus-tab" data-bs-toggle="tab"
                                                data-bs-target="#lunch-plus-tab-pane" type="button" role="tab"
                                                aria-controls="lunch-plus-tab-pane" aria-selected="false">Lunch
                                                plus</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="lunch-tab" data-bs-toggle="tab"
                                                data-bs-target="#lunch-tab-pane" type="button" role="tab"
                                                aria-controls="lunch-tab-pane" aria-selected="false">Lunch</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="sunset-tab" data-bs-toggle="tab"
                                                data-bs-target="#sunset-tab-pane" type="button" role="tab"
                                                aria-controls="sunset-tab-pane" aria-selected="false">Sunset</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="sunrise-tab" data-bs-toggle="tab"
                                                data-bs-target="#sunrise-tab-pane" type="button" role="tab"
                                                aria-controls="sunrise-tab-pane" aria-selected="false">Sunrise</button>
                                        </li> -->

                                </ul>
                                <div class="tab-content mt-4 product-book" id="myTabContent">
                                    <?php if ($packages) {
                                        foreach ($packages as $index => $item): ?>
                                            <div class="tab-pane fade <?= $index == 0 ? 'active show' : ''; ?>"
                                                id="package-tab-<?= $item['GroupCode'] ?>-pane" role="tabpanel"
                                                aria-labelledby="home-tab" tabindex="0">
                                                <h4 class="text-brand d-flex mb-4">Plan Duration</h4>
                                                <div class="mt-4">
                                                    <div class="radio-group">
                                                        <?php
                                                        //echo "<pre>";
                                                        //print_r($packages);
                                                        ?>
                                                        <?php foreach ($packages as $index => $subitem) {
                                                            if ($subitem['GroupCode'] == $item['GroupCode']) { ?>
                                                                <input type="radio" id="r1" name="duration" class="radio-input">
                                                                <label for="r1"
                                                                    class="radio-label"><?php echo $subitem['DurationDays']; ?>
                                                                    days</label>
                                                            <?php }
                                                        } ?>
                                                        <!-- <input type="radio" id="r2" name="duration" class="radio-input">
                                                            <label for="r2" class="radio-label">20 days</label>

                                                            <input type="radio" id="r3" name="duration" class="radio-input">
                                                            <label for="r3" class="radio-label">24 days</label>

                                                            <input type="radio" id="r4" name="duration" class="radio-input">
                                                            <label for="r4" class="radio-label">28 days</label> -->
                                                    </div>
                                                </div>
                                                <div class="mt-4 d-flex mb-3">
                                                    <h4 class="text-brand">Book Your Consultation</h4>
                                                </div>
                                                <div class="d-flex gap-3 mb-4">
                                                    <label class="custom-checkbox">
                                                        <input type="checkbox" />
                                                        <span class="checkmark"></span>
                                                        In Person
                                                    </label>
                                                    <label class="custom-checkbox">
                                                        <input type="checkbox" />
                                                        <span class="checkmark"></span>
                                                        Virtual/Tele-consultation
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach;
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="plan-detail-card p-5">
                                <h4 class="d-flex text-white mb-4">Gourmet Package</h4>
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="ti ti-circle-check-filled text-success"></i>
                                        <div class="text-white">Breakfast</div>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="ti ti-circle-check-filled text-success"></i>
                                        <div class="text-white">Lunch + Lunch Salad</div>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="ti ti-circle-check-filled text-success"></i>
                                        <div class="text-white">Dinner + Dinner Soup/Salad</div>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="ti ti-circle-check-filled text-success"></i>
                                        <div class="text-white">Fruit</div>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="ti ti-circle-check-filled text-success"></i>
                                        <div class="text-white">Snack</div>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="ti ti-circle-check-filled text-success"></i>
                                        <div class="text-white">Dairy Drink</div>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="ti ti-circle-check-filled text-success"></i>
                                        <div class="text-white">1 Free Dietary Consultation</div>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <i class="ti ti-circle-check-filled text-success"></i>
                                        <div class="text-white">3 Free Follow Ups</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-4">
                            <div class="row form">
                                <div class="col-lg-3 col-md-6">
                                    <label for="">First Name</label>
                                    <input type="email" class="form-control w-100" id="exampleFormControlInput1"
                                        placeholder="Enter">
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label for="">Email ID</label>
                                    <input type="email" class="form-control w-100" id="exampleFormControlInput1"
                                        placeholder="name@example.com">
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label for="">Phone Number</label>
                                    <input type="email" class="form-control w-100" id="exampleFormControlInput1"
                                        placeholder="+971">
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label for="">Location</label>
                                    <select name="" class="form-select" id="">
                                        <option value="">Select Location</option>
                                        <?php foreach ($locations as $loc): ?>
                                            <?php if ($loc['IsActive']): ?>
                                                <option value="<?= $loc['LocationCode'] ?>">
                                                    <?= $loc['LocationName'] ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <label for="">Location</label>
                                    <textarea name="" class="form-control" id=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="d-flex justify-content-end gap-3 align-items-center">
                                <a data-bs-dismiss="modal" class="btn-link">Cancel</a>
                                <div class="d-flex light-bg">
                                    <a href="#" class="btn-fancy">
                                        <span>Submit & Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="bg-gray padding-bottom padding-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <h4 class="text-brand">What’s in your bag? Take a look</h4>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                <div class="team-box">
                    <div class="team-details">
                        <h5>Chicken Fajita & Cauliflower Rice</h5>
                    </div>
                    <img src="img/extra-1.webp" alt="">
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                <div class="team-box">
                    <div class="team-details">
                        <h5>Grilled Chicken & Pearl Couscous
                            with Tomato Olive Sauce</h5>
                    </div>
                    <img src="img/extra-2.webp" alt="">
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                <div class="team-box">
                    <div class="team-details">
                        <h5>Seafood Fettuccine</h5>
                    </div>
                    <img src="img/extra-3.webp" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
<?php
include './partials/footer.php';
?>
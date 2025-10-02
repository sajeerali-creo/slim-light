<?php
include 'partials/header.php';
$islogin = false;
if (isset($_SESSION['user'])) {
    $islogin = true;
    //header('Location: login.php');
    //exit;
}

if (isset($_REQUEST['id']) && $_REQUEST['id'] != "") {
    $qry = $connection->query("SELECT * from `meal_plans` where id = '{$_GET['id']}' ");
    foreach ($qry->fetch_array() as $k => $v) {
        if (!is_numeric($k)) {
            $$k = $v;
        }
    }
} else {
    header('Location: ' . ROOT_URL);
    exit;
}

$packagesByGroup = [];
$packageGroup = [];
$package_details = "";
$response = GetPackageGroupList();
if (
    isset($response['ValidationDetails']['StatusCode']) &&
    $response['ValidationDetails']['StatusCode'] == 200 &&
    !empty($response['MasterDataList'][0])
) {
    $packageGroup = $response['MasterDataList'];

    if (!empty($packageGroup)) {
        // Get package by group
        $packageParam = [
            'GroupCode' => $packageGroup[0]['GroupCode']
        ];
        $packresponse = GetPackageByGroup($packageParam);
        if (
            isset($packresponse['ValidationDetails']['StatusCode']) &&
            $packresponse['ValidationDetails']['StatusCode'] == 200 &&
            !empty($packresponse['MasterDataList'][0])
        ) {
            $packagesByGroup = $packresponse['MasterDataList'];
            if (!empty($packagesByGroup)) {
                $package_details = $packagesByGroup[0];
            }
        }
    }
}

// $packages= [];
// $response = GetPackageList();
// if (
//     isset($response['ValidationDetails']['StatusCode']) &&
//     $response['ValidationDetails']['StatusCode'] == 200 &&
//     !empty($response['MasterDataList'][0])
// ) {
//     $packages = $response['MasterDataList'];   
// }
$locations = [];
$location_response = fetchLocations();
if ($location_response['ValidationDetails']['StatusCode'] == 200) {
    $locations = $location_response['MasterDataList'];
}

$gallery_dir = ADMIN_UPLOAD_URL . "meal_gallery/";
//$gallery_url = BASE_APP . "/admin/uploads/meal_gallery/";
//$query=mysqli_query($connection,"select * from meal_plans");
?>
<!-- ===========BANNER=========== -->
<section class="inner-banner">
</section>

<!-- ===========SUBHEADER=========== -->
<section class="py-5 bg-inner-sub-head">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between">
            <div class="d-flex flex-column align-items-start">
                <h4 class="h2 text-white">Plan <span class="font-weight-200">Details</span></h4>
                <p class="text-white opacity-50">Healthy, Delicious, and Fully Customizable Meals – Flexible Plans with
                    24/7 Support for Your Wellness
                    Journey!</p>
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
                    <?php $q = $connection->query("SELECT gallery_images FROM meal_plans WHERE id = {$id}");
                    $gallery_list = []; ?>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?php echo ADMIN_URL . $file_path; ?>" class="d-block w-100" alt="Slide 1">
                        </div>
                        <?php
                        if ($q && $q->num_rows > 0) {
                            $gallery_images = $q->fetch_assoc()['gallery_images'];
                            $gallery_list = explode(',', $gallery_images);

                            foreach ($gallery_list as $file) {

                                if (empty($file))
                                    continue; ?>
                                <div class="carousel-item">
                                    <img src="<?php echo $gallery_dir . $file ?>" class="d-block w-100" alt="Slide 1">
                                </div>
                            <?php }
                        } ?>
                    </div>
                    <?php
                    if (!empty($gallery_list) && $gallery_list[0] != '') { ?>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="carousel-control-prev" type="button" data-bs-target="#customCarousel"
                                data-bs-slide="prev">
                                <i class="ti ti-arrow-narrow-left"></i>
                            </button>
                            <div class="carousel-indicators position-relative mt-3">
                                <button type="button" data-bs-target="#customCarousel" data-bs-slide-to="0" class="active"
                                    aria-current="true" aria-label="Slide"></button>
                                <?php foreach ($gallery_list as $index => $file) { ?>
                                    <button type="button" data-bs-target="#customCarousel"
                                        data-bs-slide-to="<?php echo $index + 1; ?>" class="" aria-current="true"
                                        aria-label="Slide"></button>
                                <?php } ?>
                            </div>
                            <button class="carousel-control-next" type="button" data-bs-target="#customCarousel"
                                data-bs-slide="next">
                                <i class="ti ti-arrow-narrow-right"></i>
                            </button>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-lg-5">
                <h4>About the Plan</h4>
                <h2 class="h2 text-brand"><?php echo isset($title) ? $title : '' ?></h2>
                <p><?php echo (isset($description)) ? html_entity_decode(($description)) : '' ?></p>
                <span class="text-lime fs-4">
                    Starting 99 AED/ full day
                </span>
                <div class="d-flex flex-column flex-xl-row mt-5 light-bg gap-2">
                    <button type="button" class="btn btn-primary w-100 d-flex justify-content-center" id="get-started-btn">
                        <span>Get Started Today</span>
                    </button>
                    <a href="img/menu.pdf" class="btn-outline w-100 px-4 d-flex gap-1" id="get-started-btn">
                        <i class="ti ti-menu-4"></i><span>View Sample Menus</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- related meals -->
<?php
// Get current meal plan ID from URL
$current_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch all meal plans except the selected one
$query = mysqli_query($connection, "SELECT * FROM meal_plans WHERE id != $current_id");
?>

<section class="padding-bottom padding-top bg-body-tertiary">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-5">
                <h2>Other Meal Plans</h2>
            </div>
            <?php while ($result = mysqli_fetch_array($query)) { ?>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up">
                    <div class="meal-box">
                        <a href="<?= ROOT_URL ?>plan_details.php?id=<?= $result['id'] ?>" class="plan-link">
                            <div class="over-lay">
                                <h4><?= $result['title']; ?></h4>
                                <p><?= strip_tags(stripcslashes(html_entity_decode($result['sub_title']))); ?></p>
                                <a href="<?= ROOT_URL ?>plan_details.php?id=<?= $result['id'] ?>"
                                    class="d-flex gap-2 btn-booknow">
                                    <i class="ti ti-calendar-due text-white"></i>Book Now
                                </a>
                            </div>
                            <img src="<?= ADMIN_URL . $result['file_path']; ?>" alt="">
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('get-started-btn');

        button.addEventListener('click', function (e) {
            e.preventDefault();
            const isLoggedIn = '<?= $islogin ? true : false ?>';
            if (isLoggedIn) {
                var myModal = new bootstrap.Modal(document.getElementById('subscriptionModal'));
                myModal.show();
            } else {
                window.location.href = "<?= ROOT_URL ?>login.php?id=<?= $_REQUEST['id'] ?>";
            }
        });
    });
</script>
<!-- modal -->
<style>
    /* 🔲 Full overlay with opacity */
    /* .modal-loader-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.7); 
  z-index: 999;
  display: flex;
  align-items: center;
  justify-content: center;
  display: none;
}
.modal-loader-overlay.fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to   { opacity: 1; }
} */
    .modal-loader-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        z-index: 999;
        align-items: center;
        justify-content: center;
        display: none;
    }

    .modal-loader-overlay.show {
        display: flex;
    }
</style>
<div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-body">
                <!-- <div id="modalLoader" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden"><svg id="modalloader" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg></span>
                    </div>
                    </div> -->
                <div id="modalLoaderOverlay" class="modal-loader-overlay">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <form method="POST" id="packageBookForm">
                    <div class="container py-5">
                        <div class="row">
                            <div class="col-lg-12 d-flex justify-content-between align-items">
                                <h2 class="h2 text-brand d-flex mb-4"><?php echo $title; ?></h2>
                                <a href="#" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-square-rounded-x-filled text-brand fs-2"></i></a>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="h4 text-dark d-flex mb-5">BEGIN YOUR HEALTHY JOURNEY</h4>
                                        <h4>Package Type</h4>
                                        <p>All our meal packages can be customized to meet your budget.</p>
                                        <div>
                                            <ul class="nav nav-tabs" id="myTab1" role="tablist1">
                                                <?php if ($packageGroup) {
                                                    foreach ($packageGroup as $index => $item): ?>
                                                        <li class="nav-item" role="presentation"
                                                            onclick="getDurationData('<?= $item['GroupCode'] ?>')">
                                                            <button class="nav-link <?= $index == 0 ? 'active' : ''; ?>"
                                                                id="package-tab-<?= $item['GroupCode'] ?>" data-bs-toggle="tab"
                                                                type="button">
                                                                <?php echo $item['GroupName']; ?></button>
                                                        </li>
                                                    <?php endforeach;
                                                } ?>

                                            </ul>
                                            <div class="tab-content mt-4 product-book mb-4 mb-lg-0" id="myTabContent">
                                                <div class="tab-pane fade show active" id="gourmet-tab-pane"
                                                    role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                                    <h4 class="text-brand d-flex mb-4">Plan Duration</h4>
                                                    <div class="mt-4">
                                                        <div class="radio-group" id="durationslote">
                                                            <?php
                                                            if ($packagesByGroup) {
                                                                foreach ($packagesByGroup as $index => $item):
                                                                    if ($item['IsActive']):
                                                                        ?>
                                                                        <input type="radio" id="r<?php echo $index; ?>"
                                                                            name="duration"
                                                                            data-package='<?php echo json_encode($item, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'
                                                                            onclick="getpackageDetailBygroupCode(this)"
                                                                            value="<?php echo $item; ?>"
                                                                            class="radio-input duration-options <?php echo "group-" . $item['GroupCode']; ?>"
                                                                            <?php if ($index == 0) {
                                                                                echo 'checked';
                                                                            } ?>>
                                                                        <label for="r<?php echo $index; ?>"
                                                                            class="radio-label"><?php echo $item['DurationDays']; ?>
                                                                            days</label>
                                                                    <?php endif; endforeach;
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!isset($_SESSION['user'])) { ?>
                                        <!-- <div class="col-lg-12 mt-4" >
                                            <div class="row form">
                                                <div class="col-lg-6 col-md-6 mb-4">
                                                    <label for="">First Name</label>
                                                    <input type="text" class="form-control w-100" value="<?php echo $fullname; ?>"
                                                        id="fullname" placeholder="Enter First Name" required>
                                                </div>
                                                <div class="col-lg-6 col-md-6 mb-4">
                                                    <label for="">Email ID</label>
                                                    <input type="text" class="form-control w-100" name="EmailId" value="<?php echo $clientprofileData ? $clientprofileData[0]['EmailId'] : ''; ?>"
                                                        id="EmailId" placeholder="Enter Email ID" 
                                                        pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                                        title="Please enter a valid email address" required>
                                                </div>
                                                <div class="col-lg-6 col-md-6 mb-4">
                                                    <label for="">Phone Number</label>
                                                    <input type="text" class="form-control w-100"
                                                        id="MobileNumber" placeholder="Enter PhoneNumber"
                                                        name="MobileNumber"
                                                        value="<?php echo $clientprofileData ? $clientprofileData[0]['MobileNumber'] : ''; ?>"
                                                        pattern="^[0-9]{10}$"
                                                        title="Please enter a 10-digit phone number" required>
                                                </div>
                                                <div class="col-lg-6 col-md-6 mb-4">
                                                    <label for="">Location</label>
                                                    <select name="LocationCode" class="form-select" id="LocationCode" required>
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
                                                <div class="col-lg-12 mb-4">
                                                    <label for="">Message</label>
                                                    <textarea name="" id="" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div> -->
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="plan-detail-card p-5">
                                    <h4 class="d-flex text-brand mb-4" id="packagename">
                                        <?php echo $package_details['PackageName']; ?>
                                    </h4>
                                    <div class="d-flex flex-column gap-3 text-brand">
                                        <hr>
                                        <span id="package-desc">
                                            <?php echo $package_single_details['PackageMoreInfo']; ?>
                                        </span>
                                        <?php
                                        if ($package_single_details['PackageMoreInfo'] != "") { ?>
                                            <hr>
                                        <?php } ?>
                                        <div>Plan Duration: <span
                                                id="package-duration"><?php echo $package_details['DurationDays']; ?></span>
                                            Days</div>
                                        <hr>
                                        <?php
                                        $subscriptionData = [];
                                        $subParamData = [
                                            'PersonId' => $_SESSION['user']['UserId']
                                        ];
                                        $subscription_response = GetClientSubscriptionList($subParamData);

                                        if ($subscription_response['ValidationDetails']['StatusCode'] == 200) {
                                            if (empty($subscription_response['MasterDataList'])) {
                                                //$subscriptionData = $subscription_response['MasterDataList'];
                                                ?>
                                                <small>Complimentary Dietary Consultation & Body Fat Analysis worth <b>250
                                                        AED</b></small>
                                                <small>Complimentary 2 Follow-Ups worth <b>300 AED</b></small>
                                                <hr>
                                                <?php
                                            }
                                        } ?>
                                        <!-- <div class="d-flex gap-1 coupen-code">
                                                <input type="text" class="form-control w-100" placeholder="COUPEN CODE">
                                                <button class="btn-fancy">Submit</button>
                                            </div>
                                            <hr> -->
                                        <div class="d-flex justify-content-between opacity-50">
                                            <span>Package Price</span>
                                            <strong>AED <span
                                                    id="package-unit-rate"><?php echo $package_details['UnitRate']; ?><span></strong>
                                        </div>
                                        <?php
                                        $coolerBagtitle = '';
                                        $coolerBagAmount = '';
                                        $collerBagData = [];
                                        $ParamData = [
                                            'PersonId' => $_SESSION['user']['UserId']
                                        ];
                                        $response = GetCoolerBagAvailability($ParamData);

                                        if (
                                            isset($response['ValidationDetails']['StatusCode']) &&
                                            $response['ValidationDetails']['StatusCode'] == 200 &&
                                            !empty($response['MasterDataList'])
                                        ) {
                                            $collerBagData = $response['MasterDataList'];

                                            if ($collerBagData) {
                                                if (!$collerBagData[0]['IsCoolerBagAvailable']) {
                                                    $feesdataData = [];
                                                    $response_cool = GetFeesCharges();
                                                    if (
                                                        isset($response_cool['ValidationDetails']['StatusCode']) &&
                                                        $response_cool['ValidationDetails']['StatusCode'] == 200 &&
                                                        !empty($response_cool['MasterDataList'])
                                                    ) {
                                                        $feesdataData = $response_cool['MasterDataList'];
                                                        if ($feesdataData) {
                                                            foreach ($feesdataData as $index => $item):
                                                                if ($item['FeeCode'] == 'CB') {
                                                                    $coolerBagAmount = $item['Amount'];
                                                                    $coolerBagtitle = $item['FeeDescription'];
                                                                }
                                                            endforeach;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <div class="d-flex justify-content-between opacity-50">
                                                    <?php if ($coolerBagtitle != '') { ?>
                                                        <span><?php echo $coolerBagtitle; ?></span>
                                                    <?php } ?>
                                                    <?php if ($coolerBagAmount != "") { ?>
                                                        <strong>AED <?php echo $coolerBagAmount; ?></strong>
                                                    <?php } ?>
                                                </div>
                                                <?php
                                            }
                                        } ?>
                                        <div class="d-flex justify-content-between">
                                            <span class="fs-5">Subtotal (incl. VAT)</span>
                                            <strong class="d-flex justify-content-end fs-5">
                                                <?php
                                                $taxper = ($package_details['UnitRate'] * $package_details['TaxPercentage']) / 100;
                                                $subtotal = $package_details['UnitRate'] + $taxper;
                                                if ($coolerBagAmount != '') {
                                                    $subtotal = $subtotal + $coolerBagAmount;
                                                }
                                                ?>
                                                AED <span id="package-subtotal"><?php echo $subtotal; ?></span>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <div class="d-flex justify-content-end gap-3 align-items-center">
                                            <input type="hidden" name="selectedPackageID" id="selectedPackageID"
                                                value="<?php echo $package_details['PackageId']; ?>">
                                            <input type="hidden" name="selectedGroup" id="selectedGroup"
                                                value="<?php echo $package_details['GroupCode']; ?>">
                                            <a href="<?= ROOT_URL . 'meal_plans.php' ?>" class="btn-link">Back</a>
                                            <div class="d-flex light-bg">
                                                <button type="button" name="appointmentsubmit" id="subscriptionbtn"
                                                    class="subscriptionbtn btn-fancy w-100 w-lg-auto"
                                                    value="appointmentform"><span>
                                                        Submit & Next</span>
                                                    <!-- <a href="#" class="btn-fancy">
                                                        <span>Submit & Next</span>
                                                        <i class="ti ti-arrow-up-right"></i>
                                                    </a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
$res = $connection->query("SELECT gallery_2_images FROM meal_plans WHERE id = {$id}");
$data = $res->fetch_assoc();
$gallery_2 = array_filter(explode(',', $data['gallery_2_images']));

if ($res && !empty($gallery_2)) { ?>
    <section class="bg-gray padding-bottom padding-top d-none">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <h4 class="text-brand">What’s in your bag? Take a look</h4>
                </div>
                <?php
                foreach ($gallery_2 as $item) {
                    [$img, $title] = array_pad(explode('::', $item, 2), 2, '');
                    ?>

                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                        <div class="team-box">
                            <div class="team-details">
                                <h5><?php echo $title; ?></h5>
                            </div>
                            <img src="<?php echo $gallery_dir . $img ?>" alt="">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>
<?php
include './partials/footer.php';
?>
<script>
    function getDurationData(id) {
        $('#modalLoaderOverlay').addClass('show');
        $.ajax({
            url: 'ajax_file/get_duration.php',
            type: 'POST',
            data: { id: id }, // 🔁 pass the ID
            success: function (response) {
                if (response.success == true) {
                    jQuery('#durationslote').html(response.data);
                    const checkedRadio = $('input[name="duration"]:checked')[0];
                    if (checkedRadio) {
                        getpackageDetailBygroupCode(checkedRadio);
                    }
                } else {

                }

                $('#modalLoaderOverlay').removeClass('show');
            },
            error: function (error) {

            }
        });
    }
    function getpackageDetailBygroupCode(element) {

        $('#modalLoaderOverlay').addClass('show');
        const packageData = JSON.parse($(element).attr('data-package'));
        $('#packagename').text(packageData.PackageName);
        $('#package-desc').text(packageData.PackageMoreInfo);
        $('#package-duration').text(packageData.DurationDays);
        var taxtPer = (packageData.UnitRate * packageData.TaxPercentage) / 100;
        var subtotal = packageData.UnitRate + taxtPer;
        $('#package-subtotal').text(subtotal);
        $('#selectedPackageID').val(packageData.PackageId);
        $('#selectedGroup').val(packageData.GroupCode);

        $('#modalLoaderOverlay').removeClass('show');
    }
</script>
<script>
    $('#subscriptionbtn').click(function (e) {
        //$('.duration').val();

        var packageID = $('#selectedPackageID').val();
        var GroupID = $('#selectedGroup').val();
        var params = {
            group: GroupID,
            package: packageID,
            mealplan: '<?= $_REQUEST['id'] ?>'
        };

        // Build query string
        var queryString = $.param(params);
        const redUrl = '<?= ROOT_URL . 'plan_detail_next.php?' ?>' + queryString;
        e.preventDefault();
        window.location.href = redUrl;
    });

    $('#packageBookForm').on('submit', function (e) {
        // e.preventDefault(); 
        // const c1 = document.getElementById("inPersion").checked;
        // const c2 = document.getElementById("vertual").checked;

        // if (!c1 && !c2) {
        //     document.getElementById("checkbox-error").style.display = "block";
        //     return false; 
        // }

        // document.getElementById("checkbox-error").style.display = "none";
        // return true;
    });
</script>
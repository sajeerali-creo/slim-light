<?php
include 'partials/header.php';
// require_once 'services/commonService.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$clientAreaData = "";
$areaParamData = [
'CityId' => $clientprofileData[0]['CityId'],
'CountryId' => $clientprofileData[0]['CountryId']
];
$areaDataResp = GetAreaByCity($areaParamData);
if (isset($areaDataResp['ValidationDetails']['StatusCode']) &&
    $areaDataResp['ValidationDetails']['StatusCode'] == 200 &&
    !empty($areaDataResp['MasterDataList'][0]))
{
    $clientAreaData = $areaDataResp['MasterDataList'][0]['AreaName'];
}
$location_response = fetchLocations();
if($location_response['ValidationDetails']['StatusCode'] == 200){
    $locations = $location_response['MasterDataList'];
}

$dietician_response = GetDietician();
if($dietician_response['ValidationDetails']['StatusCode'] == 200){
    $dietician = $dietician_response['MasterDataList'];
}

$reason_response = GetAppointmentReason();
if($reason_response['ValidationDetails']['StatusCode'] == 200){
    $appReason = $reason_response['MasterDataList'];
}

$appointmentListData = [];
$appParamData = [
'PersonId' => $_SESSION['user']['UserId']
];
$app_response = GetAppoinmentDetails($appParamData);
if($app_response['ValidationDetails']['StatusCode'] == 200){
    $appointmentListData = $app_response['MasterDataList'];
}

$trackListData = [];
$trackParamData = [
'PersonId' => $_SESSION['user']['UserId']
];
$track_response = GetTrackDelivery($trackParamData);
if($track_response['ValidationDetails']['StatusCode'] == 200){
    $trackListData = $track_response['MasterDataList'];
}

$subscriptionData = [];
$subParamData = [
    'PersonId' => $_SESSION['user']['UserId']
];
$subscription_response = GetClientSubscriptionList($subParamData);
if($subscription_response['ValidationDetails']['StatusCode'] == 200){
    if(!empty($subscription_response['MasterDataList'])){
        $subscriptionData = $subscription_response['MasterDataList'];
    }
}

$healthProfileData = [];
$BMIData = [];
$healthParamData = [
'PersonId' => $_SESSION['user']['UserId']
];
$clientHealthProfile_response = GetClientHealthProfile($healthParamData);
if($clientHealthProfile_response['ValidationDetails']['StatusCode'] == 200){
    if(!empty($clientHealthProfile_response['MasterDataList'])){
        $healthProfileData = $clientHealthProfile_response['MasterDataList'][0];
        if($healthProfileData){
            $BMIParamData = [
            'PersonHeight' => $healthProfileData['PersonHeight'],
            'PersonWeight' => $healthProfileData['PersonWeight']
            ];
            $bmi_response = GetBMIStatus($BMIParamData);
            if($bmi_response['ValidationDetails']['StatusCode'] == 200){
                if(!empty($bmi_response['MasterDataList'])){
                    //print_r($bmi_response['MasterDataList'][0]);
                    $BMIData = $bmi_response['MasterDataList'][0];
                }
            }
        }
    }
}

$packages= [];
$response = GetPackageList();
if (
    isset($response['ValidationDetails']['StatusCode']) &&
    $response['ValidationDetails']['StatusCode'] == 200 &&
    !empty($response['MasterDataList'][0])
) {
    $packages = $response['MasterDataList'];   
}

$errors = [];
?>
 <!-- ===========BANNER=========== -->
    <section class="inner-banner">
    </section>

    <!-- ===========SUBHEADER=========== -->
    <section class="py-5 bg-body-tertiary">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center">
                <div class="d-flex flex-column align-items-start">
                    <div class="d-lg-flex gap-4 align-items-center text-center text-lg-start">
                        <div class="user-profile mb-4 mb-lg-0">
                            <?php
                            $base64img = $profilePicture != null ? $profilePicture : ROOT_URL.'img/defaultimagex2.webp';
                            if($profilePicture != null){ ?>
                                <img src="data:image/jpeg;base64,<?= $base64img ?>" alt="">
                            <?php }else{ ?>
                                <img src="<?= $base64img ?>" alt="">
                            <?php }
                            ?>
                            <!-- <img src="data:image/jpeg;base64,<?= $base64img ?>" alt=""> -->
                        </div>
                        <div class="user-details-area d-flex flex-column gap-2 mb-4 mb-lg-">
                            <h5 class="text-brand"><?php echo $clientprofileData[0]['FirstName'].' '.$clientprofileData[0]['MiddleName'].' '.$clientprofileData[0]['LastName']; ?></h5>
                            <span><?php echo $clientAreaData; ?></span>
                            <span><?php echo $clientprofileData[0]['EmailId']; ?></span>
                            <span><?php echo $clientprofileData[0]['MobileNumber']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
<div>
                    <a href="<?= ROOT_URL ?>account_settings.php" class="btn-outline w-auto px-4 gap-2"><i class="ti ti-settings"></i>Account
                        Settings</a>
                </div>
                <a href="<?= ROOT_URL ?>logout.php" class="btn-outline w-auto px-4">Logout</a>
                </div>
                

            </div>
        </div>
    </section>

    <!-- ===========TABS=========== -->
    
    <section class="padding-top padding-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-tab-page">
                        <!-- tab menus -->
                        <div class="">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="my-package-tab" data-bs-toggle="tab"
                                        data-bs-target="#my-package-tab-pane" type="button" role="tab"
                                        aria-controls="my-package-tab-pane" aria-selected="true"><i
                                            class="ti ti-list-details"></i>&nbsp;My Package</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="menu-selection-tab" data-bs-toggle="tab"
                                        data-bs-target="#menu-selection-tab-pane" type="button" role="tab"
                                        aria-controls="menu-selection-tab-pane" aria-selected="false"><i
                                            class="ti ti-menu-3"></i>&nbsp;Menu Selection</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="my-health-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#my-health-profile-tab-pane" type="button" role="tab"
                                        aria-controls="my-health-profile-tab-pane" aria-selected="false"><i
                                            class="ti ti-brand-google-fit"></i>&nbsp;My Health Profile</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="my-appointments-tab" data-bs-toggle="tab"
                                        data-bs-target="#my-appointments-tab-pane" type="button" role="tab"
                                        aria-controls="my-appointments-tab-pane" aria-selected="false"><i
                                            class="ti ti-circle-check"></i>&nbsp;My Appointments</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="track-deliveries-tab" data-bs-toggle="tab"
                                        data-bs-target="#track-deliveries-tab-pane" type="button" role="tab"
                                        aria-controls="track-deliveries-tab-pane" aria-selected="false"><i
                                            class="ti ti-truck-delivery"></i>&nbsp;Track Deliveries</button>
                                </li>
                            </ul>
                        </div>
                        <!-- -- -->
                        <div class="tab-content mt-4 product-book" id="myTabContent">
                           <!-- tab package -->
                            <div class="tab-pane fade show active" id="my-package-tab-pane" role="tabpanel"
                                aria-labelledby="home-tab" tabindex="0">
                                <div class="py-4">
                                    <h4 class="text-uppercase mb-4 d-flex text-brand fs-5">Active Subscription</h4>
                                    <div class="row">                                   
                                        <div class="col-lg-8 d-flex flex-column gap-3">
                                            <!-- package 1 -->
                                              <?php 
                                            function getPackageDetailsById($packageId, $data) {
                                                foreach ($data as $package) {
                                                    if ($package['PackageId'] == $packageId) {
                                                        return $package; // Found the matching package
                                                    }
                                                }
                                                return null; // No package found with that PackageId
                                            }
                                            
                                            if(!empty($subscriptionData)){ 
                                            foreach ($subscriptionData as $index =>$item): 
                                                //$packageDetails = getPackageDetailsById($item['PackageId'], $packages);
                                            
                                                ?>
                                            <div class="box-subscription d-flex flex-column flex-lg-row">
                                                <div class="card-meal">
                                                    <div class="over-lay">
                                                        <div>
                                                            <h4 class="text-uppercase"><?php echo $item['PackageName']; ?></h4>
                                                            <?php
                                                            $expiredDate = new DateTime($item['ExpiredDate']);
                                                            $currentDate = new DateTime(); // Current date and time

                                                            if ($expiredDate < $currentDate) { ?>
                                                                    <div class="status-label expired-label">Expired</div>
                                                                <?php 
                                                                }else{ ?>
                                                                    <div class="status-label active-label">Active</div>
                                                                <?php }
                                                            ?>
                                                            <!-- <div class="status-label expired-label">Expired</div> -->
                                                            
                                                        </div>
                                                        <!-- <p>Weight gain and increase muscle mass meal plan</p> -->
                                                    </div>
                                                    <img src="img/default-package.webp" alt="">
                                                </div>
                                                <div class="p-4 w-100">
                                                    <div class="d-flex justify-content-between w-100 mb-4">
                                                        <div class="w-100">Start Date</div>
                                                        <div class="w-100 d-flex justify-content-end">
                                                            <strong><?php echo !empty($item['StartDate']) ? date('d/m/Y', strtotime($item['StartDate'])) : '-';  ?></strong>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between w-100 mb-4">
                                                        <div class="w-100">Last Delivery Date</div>
                                                        <div class="w-100 d-flex justify-content-end">
                                                            <strong><?php echo !empty($item['LastDeliveryDate']) ? date('d/m/Y', strtotime($item['LastDeliveryDate'])) : ''; ?></strong>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between w-100 mb-4">
                                                        <div class="w-100">Payment Status</div>
                                                        <div class="w-100 d-flex justify-content-end">
                                                            <strong class="text-success"><?php echo $item['PaymentStatus']; ?></strong>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between w-100 mb-4">
                                                        <div class="w-100">Expiry Date</div>
                                                        <div class="w-100 d-flex justify-content-end">
                                                            <strong class="text-danger"><?php echo !empty($item['ExpiredDate']) ? date('d/m/Y', strtotime($item['ExpiredDate'])) : ''; ?></strong>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between w-100 mb-4">
                                                        <div class="w-100">
                                                            Remaining Boxes For
                                                            Delivery As Of <strong><?php echo date('d/m/Y'); ?></strong>
                                                        </div>
                                                        <div class="w-100 d-flex justify-content-end main-switch">
                                                            <div class="number"><?php echo $item['ReminingBoxes']; ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex gap-2 bg-start-actions">
                                                        <?php 
                                                        if($item['PackageStatus'] == null && strtolower($item['PaymentStatus']) == "done"){ ?>
                                                                <a href="javascript:void(0)" class="btn-start" onclick="select_date('<?php echo $index; ?>')">
                                                                    <i class="ti ti-player-play-filled"></i>&nbsp;Start</a>
                                                        <?php }else{ ?>
                                                            <a href="javascript:void(0)" class="btn-start disablebtnlcls">
                                                                    <i class="ti ti-player-play-filled"></i>&nbsp;Start</a>
                                                        <?php } 

                                                        if(($item['PackageStatus'] == "ST" || $item['PackageStatus'] == "RS") 
                                                        && ($item['PackageStatus'] != "RN" && $item['PaymentStatus'] != 'pending' && $item['ReminingBoxes'] != 0)){ ?>
                                                                <a href="javascript:void(0)" class="btn-stop" onclick="package_action('<?php echo $item['SubscriptionId']; ?>','<?php echo $item['LocationId']; ?>','stop')">
                                                                <svg id="loaderStop" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                                                                <i class="ti ti-player-stop-filled"></i>&nbsp;Stop</a>
                                                        <?php }else{ ?>
                                                            <a href="javascript:void(0)" class="btn-stop disablebtnlcls">
                                                                <svg id="loaderStop" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                                                                <i class="ti ti-player-stop-filled"></i>&nbsp;Stop</a>
                                                        <?php } 

                                                          if($item['PackageStatus'] == "SP"){ ?>
                                                            <a href="javascript:void(0)" class="btn-resume" onclick="package_action('<?php echo $item['SubscriptionId']; ?>','<?php echo $item['LocationId']; ?>','resume', '<?php echo $index; ?>')">
                                                                <svg id="loaderResume" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                                                                <i class="ti ti-player-track-next"></i>&nbsp;Resume</a>
                                                        <?php }else{ ?>
                                                            <a href="javascript:void(0)" class="btn-resume disablebtnlcls">
                                                                <svg id="loaderResume" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                                                                <i class="ti ti-player-track-next"></i>&nbsp;Resume</a>
                                                        <?php } ?>
                                                    </div>
                                                 
                                                    <div class="mb-3 d-none gap-2 startdatecomoncls <?php echo "startdate-".$index; ?>" style="display:none;">
                                                        <div class="position-relative w-75">
                                                            <input type="text" id="datepickerStartDate" onchange="removeError(this, '<?php echo $index; ?>')" name="packageStartDate" class="form-control packagedatecomoncls packageStartDate<?php echo $index; ?>"
                                                                placeholder="Select Start date" />
                                                            <i class="ti ti-calendar-event"></i>
                                                        </div>
                                                        <a href="javascript:void(0)" class="btn-start w-25 btn-outline" href="javascript:void(0)" onclick="package_action('<?php echo $item['SubscriptionId']; ?>','<?php echo $item['LocationId']; ?>','start', '<?php echo $index; ?>')">
                                                        <svg id="loaderStart<?php echo "startdate-".$index; ?>" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>                                                             
                                                        Submit</a>
                                                    </div>
                                                    <div class="error-msg text-danger mt-1" style="display: none;">Start date is required.</div>


                                                    <div class="d-flex gap-2">
                                                        <?php if($item['PackageStatus'] == null && strtolower($item['PaymentStatus']) == "pending"){ ?>
                                                            <a href="javascript:void(0)" class="btn-payment w-100 btn-outline">
                                                                <svg id="paymentbtn" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                                                                Payment</a>
                                                        <?php }else if(strtolower($item['PaymentStatus']) == "done"){ ?>
                                                            <a href="javascript:void(0)" class="w-100 btn-outline" id="receiptbtnId" onclick="getreceiptlist('<?= $item['SubscriptionId'] ?>', '<?= $index ?>')"><i
                                                                class="ti ti-receipt"></i>&nbsp;Receipt
                                                                <svg id="recieptbtn<?php echo $index; ?>" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                                                            </a>
                                                        <?php } ?>
                                                        
                                                        <a href="javascript:void(0)" class="btn-first w-100" id="ediMenuplan">Edit Meal Plan</a>
                                                    </div>
                                                </div>
                                            </div>
                                             <?php  endforeach; } ?>
                                        </div>
                                         <div class="col-lg-4" id="allrecieptdata" style="display:none">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- tab menu selection -->
                           <div class="tab-pane fade" id="menu-selection-tab-pane" role="tabpanel"
                                aria-labelledby="profile-tab" tabindex="0">
                                <div class="py-4">
                                    <!-- Generate your menus list from here -->
                                    <div class="p-5 d-flex flex-column align-items-center rounded-3 bg-gray my-4">

                                        <div class="form row justify-content-center align-items-center w-100 mb-4">
                                            <div class="col-12 text-center mb-5">
                                                <h3>Select preferred date and package</h3>
                                            </div>
                                            <div class="col-lg-4">
                                                <input type="text" class="form-control" id="menuDateId" placeholder="14/04/2025">
                                            </div>
                                            <div class="col-lg-4">
                                                <select class="form-select" id="packagenm">
                                                    <option value="">Package Name</option>
                                                </select>
                                            </div>
                                        </div>

                                        <p>Generate your menu list from here</p>
                                        <a href="javascript:void(0)" class="btn-first w-auto px-4 gap-2" id="generatemenu">
                                            Generate Your Menu
                                            <svg class="spinner button-spinner" id="generatespinner" viewBox="0 0 50 50">
                                                <circle class="path" cx="25" cy="25" r="20" fill="none"
                                                    stroke-width="5" />
                                            </svg>
                                        </a>
                                        <!-- spinner -->
                                        <!-- <div class="spinner-container">

                                        </div> -->
                                        <!-- spinner -->
                                    </div>
                                    <div id="menulistitems">
                                    
                                    </div>
                                </div>
                            </div>

                            <!-- tab health Profile -->
                            <div class="tab-pane fade" id="my-health-profile-tab-pane" role="tabpanel"
                                aria-labelledby="contact-tab" tabindex="0">
                                <div class="py-4">
                                    <h4 class="text-uppercase mb-3 d-flex text-brand fs-5">Current Status</h4>
                                    <p>To assess your weight,body composition and ideal health and fiitness targer,
                                        refer to your dietitian.</p>
                                    <div class="d-flex flex-column flex-lg-row gap-3 mt-5">
                                        <!-- <div class="w-100 box-stats p-5 current-bg flex-column">
                                            <img src="img/current-weight.svg" alt="">
                                            <strong class="pt-4 fs-4"><span id="personwieghtlabel"><?= !empty($healthProfileData) ? round($healthProfileData['PersonWeight'], 1) : '' ?></span> kg</strong>
                                            <input type="hidden" value="<?php echo !empty($healthProfileData) ? round($healthProfileData['PersonWeight'], 1) : '' ?>" name="CurrentWeightHidden" id="CurrentWeightHidden">
                                            <p>Current Weight</p>
                                            <a data-bs-toggle="modal" data-bs-target="#current-weight" onclick="setcurrentweight()" class="btn-outline-small">
                                                <i class="ti ti-pencil"></i>&nbsp;Edit</a>
                                        </div> -->
                                       <div class="w-100 box-stats p-5 current-bg flex-column justify-content-center">

                                            <div class="d-flex flex-column flex-lg-row gap-4 w-100 mb-4">
                                                <div
                                                    class="w-100 d-flex flex-column text-center justify-content-center align-items-center border p-3 rounded-3">
                                                    <svg width="49" height="48" viewBox="0 0 49 48" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_1199_2787)">
                                                            <mask id="mask0_1199_2787" style="mask-type:luminance"
                                                                maskUnits="userSpaceOnUse" x="0" y="0" width="49"
                                                                height="48">
                                                                <path d="M48.5 0H0.5V48H48.5V0Z" fill="white" />
                                                            </mask>
                                                            <g mask="url(#mask0_1199_2787)">
                                                                <path
                                                                    d="M9.56941 20.6525H39.4386C39.9584 20.6521 40.3753 20.2252 40.3753 19.715C40.3673 10.9355 33.2496 3.8457 24.5036 3.8457C15.7961 3.8457 8.62544 10.9181 8.63181 19.7155C8.63228 20.2331 9.052 20.6525 9.56941 20.6525ZM24.9703 16.9167C25.4825 16.9167 25.8992 17.3334 25.8992 17.8457V18.7745H24.0412V17.8456C24.0412 17.3334 24.458 16.9167 24.9703 16.9167ZM16.7118 8.09361L17.626 9.67705C17.8858 10.127 18.4597 10.2783 18.9066 10.0202C19.355 9.76123 19.5087 9.18786 19.2498 8.73955L18.3367 7.15827C19.9731 6.35796 21.7489 5.88207 23.5662 5.75689V7.58286C23.5662 8.10055 23.986 8.52036 24.5037 8.52036C25.0214 8.52036 25.4412 8.10055 25.4412 7.58286V5.75436C27.26 5.87327 29.0376 6.34903 30.6727 7.15452L29.7576 8.73955C29.4987 9.18795 29.6523 9.76123 30.1007 10.0202C30.5487 10.2787 31.1223 10.1261 31.3814 9.67705L32.2967 8.09173C33.825 9.11764 35.1175 10.4267 36.1209 11.9249L34.5412 12.8369C34.0928 13.0957 33.9392 13.6691 34.1981 14.1175C34.4576 14.5669 35.0311 14.719 35.4787 14.4606L37.0584 13.5486C37.8539 15.166 38.3418 16.9399 38.4677 18.7771L27.7741 18.7775V17.8458C27.7741 16.6283 26.994 15.5901 25.9077 15.2035V13.1796C25.9077 12.662 25.4879 12.2421 24.9702 12.2421C24.4525 12.2421 24.0327 12.662 24.0327 13.1796V15.2034C22.9463 15.59 22.1662 16.6282 22.1662 17.8456V18.7774L10.5384 18.777C10.6617 16.9583 11.139 15.1811 11.9435 13.5454L13.5286 14.4605C13.9765 14.7191 14.5499 14.5665 14.8092 14.1174C15.0681 13.669 14.9145 13.0957 14.4661 12.8368L12.8816 11.922C13.8947 10.4069 15.1962 9.10602 16.7118 8.09361ZM18.7218 27.9506C17.4649 24.8395 13.93 23.3398 10.8198 24.5965C7.71634 25.8502 6.21175 29.3951 7.46556 32.4985L10.2622 39.4205C11.519 42.5316 15.0541 44.0315 18.1643 42.7747C21.2755 41.518 22.7753 37.9831 21.5185 34.8726L18.7218 27.9506ZM17.4619 41.0363C17.4619 41.0364 17.4619 41.0364 17.4619 41.0363C15.3117 41.9047 12.8693 40.8682 12.0007 38.7182L9.20397 31.7961C8.33547 29.6462 9.37234 27.2035 11.5222 26.3349C13.6804 25.463 16.1184 26.5118 16.9835 28.6531L19.7802 35.5751C20.6488 37.7252 19.6118 40.1679 17.4619 41.0363ZM38.0458 24.5965C34.9348 23.3395 31.4002 24.8404 30.1438 27.9506L27.347 34.8726C26.09 37.9837 27.5908 41.5183 30.7012 42.7747C33.8255 44.0371 37.3519 42.5182 38.6032 39.4205L41.4 32.4985C42.6569 29.3875 41.1564 25.853 38.0458 24.5965ZM39.6617 31.7961L36.8648 38.7182C35.9963 40.8683 33.5535 41.9047 31.4037 41.0364C29.2534 40.1679 28.2169 37.725 29.0854 35.5751L31.8822 28.6531C32.7424 26.5239 35.1738 25.4584 37.3434 26.3349C39.4936 27.2036 40.53 29.6462 39.6617 31.7961Z"
                                                                    fill="#2CB058" />
                                                                <path
                                                                    d="M40.242 0.09375H8.75797C4.25619 0.09375 0.59375 3.75619 0.59375 8.25797V39.742C0.59375 44.2438 4.25619 47.9062 8.75797 47.9062H40.242C44.7438 47.9062 48.4062 44.2438 48.4062 39.742V8.25797C48.4062 3.75619 44.7438 0.09375 40.242 0.09375ZM46.5312 39.742C46.5312 43.2099 43.7099 46.0312 40.242 46.0312H8.75797C5.29006 46.0312 2.46875 43.2099 2.46875 39.742V8.25797C2.46875 4.79006 5.29006 1.96875 8.75797 1.96875H40.242C43.7099 1.96875 46.5312 4.79006 46.5312 8.25797V39.742Z"
                                                                    fill="#2CB058" />
                                                            </g>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_1199_2787">
                                                                <rect width="49" height="48" fill="white" />
                                                            </clipPath>
                                                        </defs>
                                                    </svg>

                                                    <strong class="pt-4 fs-4"><span id="personwieghtlabel"><?= !empty($healthProfileData) ? round($healthProfileData['PersonWeight'], 1) : '' ?></span> kg</strong>
                                                    <input type="hidden" value="<?php echo !empty($healthProfileData) ? round($healthProfileData['PersonWeight'], 1) : '' ?>" name="CurrentWeightHidden" id="CurrentWeightHidden">
                                                    <p>Current Weight</p>
                                                </div>
                                                <div
                                                    class="w-100 d-flex flex-column text-center justify-content-center align-items-center border p-3 rounded-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="52"
                                                        height="52" x="0" y="0" viewBox="0 0 64 64"
                                                        style="enable-background:new 0 0 512 512" xml:space="preserve"
                                                        class="">
                                                        <g>
                                                            <path
                                                                d="M27.271 16.305c3.393 0 6.153-2.76 6.153-6.153S30.664 4 27.27 4c-3.392 0-6.151 2.76-6.151 6.152s2.76 6.153 6.151 6.153zm0-10.305c2.29 0 4.153 1.862 4.153 4.152s-1.863 4.153-4.153 4.153-4.151-1.863-4.151-4.153S24.982 6 27.27 6z"
                                                                fill="#000000" opacity="1" data-original="#000000"
                                                                class="">
                                                            </path>
                                                            <path
                                                                d="M53.824 4H36.396a1 1 0 1 0 0 2h6.132v52H33.53c.315-.56.509-1.197.509-1.884V39.82c.461.222.972.358 1.517.358a3.521 3.521 0 0 0 3.517-3.518V24.914a6.356 6.356 0 0 0-6.348-6.349H21.818a6.356 6.356 0 0 0-6.349 6.35V36.66a3.521 3.521 0 0 0 3.517 3.518c.546 0 1.056-.136 1.517-.358v16.296c0 .687.195 1.324.51 1.884H10.175a1 1 0 1 0 0 2h43.648a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1zm-31.32 52.116V29.607a1 1 0 1 0-2 0v7.053a1.519 1.519 0 0 1-3.034 0V24.914a4.354 4.354 0 0 1 4.348-4.349h10.908a4.354 4.354 0 0 1 4.348 4.35V36.66c0 .837-.68 1.518-1.517 1.518s-1.517-.68-1.517-1.518v-7.053a1 1 0 1 0-2 0v26.51c0 1.038-.845 1.883-1.884 1.883s-1.885-.845-1.885-1.884V39.178a1 1 0 1 0-2 0v16.938c0 1.04-.845 1.884-1.883 1.884a1.886 1.886 0 0 1-1.885-1.884zM52.823 58h-8.296v-4.627h4.148a1 1 0 1 0 0-2h-4.148v-3.821h2.234a1 1 0 1 0 0-2h-2.234v-3.82h4.148a1 1 0 1 0 0-2h-4.148V35.91h2.234a1 1 0 1 0 0-2h-2.234v-3.82h4.148a1 1 0 1 0 0-2h-4.148v-3.821h2.234a1 1 0 1 0 0-2h-2.234v-3.82h4.148a1 1 0 1 0 0-2h-4.148v-3.822h2.234a1 1 0 1 0 0-2h-2.234V6h8.296z"
                                                                fill="#003229" opacity="1" data-original="#000000"
                                                                class="">
                                                            </path>
                                                        </g>
                                                    </svg>
                                                    <strong class="pt-4 fs-4"><span id="personheightlabel"><?= !empty($healthProfileData) ? round($healthProfileData['PersonHeight'], 1) : '' ?></span> cm</strong>
                                                    <input type="hidden" value="<?php echo !empty($healthProfileData) ? round($healthProfileData['PersonHeight'], 1) : '' ?>" name="CurrentHeightHidden" id="CurrentHeightHidden">
                                                    <p>Current Height</p>
                                                </div>
                                            </div>
                                            <a data-bs-toggle="modal" data-bs-target="#current-weight" onclick="setcurrentweight()"
                                                class="btn-outline-small"><i class="ti ti-pencil"></i>&nbsp;Edit</a>
                                        </div>

                                        <!-- update current weight -->
                                        <!-- Modal -->
                                        <div class="modal fade" id="current-weight" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="form">
                                                            <div class="mb-4">
                                                                <label for="">Current Weight</label>
                                                                <input type="text" class="form-control w-100" name="CurrentWeight"
                                                                    id="CurrentWeight" 
                                                                    placeholder="KG" value="">
                                                            </div>
                                                            <div>
                                                                <label for="">Current Height</label>
                                                                <input type="text" class="form-control w-100" name="CurrentHeight"
                                                                    id="CurrentHeight" placeholder="CM">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div
                                                            class="d-flex flex-column flex-lg-row justify-content-end gap-3 align-items-center">
                                                            <a data-bs-dismiss="modal"
                                                                class="btn-link w-100 w-lg-auto px-4">Cancel</a>
                                                            <div class="d-flex light-bg w-100 w-lg-auto">
                                                                <a href="javascript:void(0)" id="currentweightbtn" class="btn-fancy w-100 w-lg-auto" onclick="updatehealthVal('current_weight')">
                                                                <span class="w-100 w-lg-auto">
                                                                    <svg id="currentspinner" class="spinner" viewBox="0 0 50 50">
                                                                    <circle class="path" cx="25" cy="25" r="20" fill="none"
                                                                    stroke-width="5" />
                                                                    </svg>     
                                                                    Update</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="w-100 box-stats p-5 target-bg flex-column">
                                            <img src="img/current-s.svg" alt="">
                                            <strong class="pt-4 fs-4"> <span id="targetwieghtlabel"><?= !empty($healthProfileData) ? round($healthProfileData['TargetWeight'], 1) : '' ?></span> kg</strong>
                                            <p>Target Weight</p>
                                            <input type="hidden" value="<?php echo !empty($healthProfileData) ? round($healthProfileData['TargetWeight'], 1) : '' ?>" name="TargetWeightHidden" id="TargetWeightHidden">
                                            <a data-bs-toggle="modal" data-bs-target="#target-weight" class="btn-outline-small" onclick="setTargetweight()">
                                                <i class="ti ti-pencil"></i>&nbsp;Edit</a>
                                        </div>

                                        <!-- update Target Weight -->
                                        <!-- Modal -->
                                        <div class="modal fade" id="target-weight" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="form">
                                                            <label for="">Target Weight</label>
                                                            <input type="text" name="TargetWeight" class="form-control w-100" value="<?= !empty($healthProfileData) ? round($healthProfileData['TargetWeight'], 1) : '' ?>"
                                                                id="TargetWeight" placeholder="KG">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div
                                                            class="d-flex flex-column flex-lg-row justify-content-end gap-3 align-items-center">
                                                            <a data-bs-dismiss="modal"
                                                                class="btn-link w-100 w-lg-auto px-4">Cancel</a>
                                                            <div class="d-flex light-bg w-100 w-lg-auto">
                                                                <a href="javascript:void(0)" id="targetweightbtn" class="btn-fancy w-100 w-lg-auto" onclick="updatehealthVal('target_weight')">
                                                                    <span class="w-100 w-lg-auto">
                                                                        <svg id="targetspinner" class="spinner" viewBox="0 0 50 50">
                                                                        <circle class="path" cx="25" cy="25" r="20" fill="none"
                                                                        stroke-width="5" />
                                                                        </svg> 
                                                                        Update</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column flex-lg-row gap-3 mt-3">
                                        <div
                                            class="box-stats p-3 flex-column align-items-center justify-content-center bg-fat">
                                            <img src="img/increased.svg" alt="">
                                            <strong class="pt-4 fs-4"><?= !empty($healthProfileData) ? round($healthProfileData['PersonFatPerc'], 1) : '' ?>% (Increased)</strong>
                                            <p>Body Fat%</p>
                                        </div>
                                        <div
                                            class="box-stats p-3 flex-column align-items-center justify-content-center bg-bmi">
                                            <img src="img/bmi.svg" alt="">
                                            <strong class="pt-4 fs-4"><span id="bmistatusval"><?= !empty($BMIData) ? $BMIData['Status'] : '' ?></span> (<span id="bmival"><?= !empty($BMIData) ? round($BMIData['BMI'], 2) : '' ?></span> BMI)</strong>
                                            <p>BMI Status</p>
                                            <!-- <button class="btn-outline-small gap-2" onclick="BMICalculation()">
                                                Calculate
                                                <svg class="spinner" viewBox="0 0 50 50">
                                                    <circle class="path" cx="25" cy="25" r="20" fill="none"
                                                        stroke-width="5" />
                                                </svg>
                                            </button> -->
                                        </div>
                                        <div
                                            class="box-stats p-3 flex-column align-items-center justify-content-center bg-calori">
                                            <img src="img/caloric.svg" alt="">
                                            <strong class="pt-4 fs-4">Daily (<?= !empty($healthProfileData) ? round($healthProfileData['PersonCalories'], 1) : '' ?> Kcal)</strong>
                                            <p>Caloric Intake</p>
                                        </div>
                                    </div>
                                    <!-- butto -->
                                    <div class="d-flex mt-4 light-bg">
                                        <a href="#" class="btn-fancy" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" data-bs-whatever="@mdo">
                                            <span>Book an Appointment</span>
                                          
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- tab appointment -->
                            <div class="tab-pane fade" id="my-appointments-tab-pane" role="tabpanel"
                                aria-labelledby="contact-tab" tabindex="0">
                                <div class="py-4">
                                    <h4 class="text-uppercase mb-4 d-flex text-brand fs-5">My APPOINTMENTS</h4>
                                    <!-- card -->
                                     <?php
                                     if(!empty($appointmentListData)){
                                        foreach ($appointmentListData as $item): ?>
                                            <div class="booking-card mt-4">
                                                <div class="booking-info">
                                                    <h4><?= $item['AppointmentTitle']; ?></h4>
                                                    <p><?= $item['LocationName']; ?></p>
                                                    <div class="dietitian">Dietitian: <?= $item['DieticianName']; ?></div>
                                                </div>
                                                <div class="booking-meta">
                                                    <div class="meta-item">
                                                        <i class="ti ti-calendar-event"></i>
                                                        <span><?= date('d/m/Y', strtotime($item['AppointmentDate'])); ?></span>
                                                    </div>
                                                    <div class="meta-item">
                                                        <i class="ti ti-clock-hour-10"></i>
                                                        <span>
                                                            <?php
                                                            $fromTime = date("g:ia", strtotime($item['FromTime'])); // e.g. 04:00pm
                                                            $toTime   = date("g:ia", strtotime($item['ToTime']));   // e.g. 5:00pm
                                                            ?>
                                                            <?= $fromTime; ?> - <?= $toTime; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php endforeach;
                                     } ?>
                                    <!-- butto -->
                                    <div class="d-flex mt-4 light-bg">
                                        <a href="#" class="btn-fancy" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" data-bs-whatever="@mdo">
                                            <span>Book an Appointment</span>
                                        
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- tab deliveries -->
                            <div class="tab-pane fade" id="track-deliveries-tab-pane" role="tabpanel"
                                aria-labelledby="contact-tab" tabindex="0">
                                <div class="py-4">
                                    <!-- card -->
                                    <?php
                                    if(!empty($trackListData)){
                                    foreach ($trackListData as $item): ?>
                                    <div class="booking-card mt-4">
                                        <div class="booking-info">
                                            <h4><?= $item['BoxDelivery']; ?></h4>
                                            <p><?= $item['DatePeriod']; ?></p>
                                        </div>
                                        <div class="booking-meta">
                                            <div class="meta-item">
                                                <i class="ti ti-calendar-event"></i>
                                                <span><?= date('d/m/Y', strtotime($item['SubscriptionEndDate'])); ?></span>
                                            </div>
                                            <div class="meta-item">
                                                <i class="ti ti-clock-hour-10"></i>
                                                <span><?= $item['DeliveryTime']; ?></span>
                                            </div>
                                            <div class="meta-item">
                                                <i class="ti ti-circle-check-filled text-success fs-2"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach;
                                     }else{ ?>
                                         <div class="empty-state-card">
                                            <div class="empty-state-content">
                                                <i class="ti ti-truck-delivery fs-2 track"></i>
                                                <h3>No Deliveries</h3>
                                                <p>It looks like there aren't any deliveries listed at the moment!</p>
                                            </div>
                                        </div>
                                     <?php } ?>
                                    <!-- butto -->
                                    <div class="d-flex mt-4 light-bg">
                                        <a href="#" class="btn-fancy" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" data-bs-whatever="@mdo">
                                            <span>Book an Appointment</span>
                                  
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- modal book apointment -->
                 <div class="modal fade modal-half-height bookingmodalcls" id="exampleModal" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form method="POST" id="appointmentForm">
                                    <div class="container py-5">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <h2 class="h2 text-brand d-flex mb-4">BOOK AN APPOINTMENT</h2>
                                            </div>
                                            <div class="col-lg-12" id="appMessg">
                                                <div class="message error" id="global-error-msg">
                                                    <span class="errormsg-text"></span>
                                                    <a href="javascript:void(0)" class="close-btn" onclick="this.parentElement.style.display='none';">&times;</a>
                                                </div>
                                                <div class="message success" id="global-success-msg">
                                                    <span class="successmsg-text"></span>
                                                    <a href="javascript:void(0)" class="close-btn" onclick="this.parentElement.style.display='none';">&times;</a>
                                                </div>
                                                <!-- <small class="text-success successmsg-text"></small>
                                                <small class="text-danger errormsg-text"></small> -->
                                            </div>
                                            <div class="col-lg-12 mt-4">
                                                <div class="row form">
                                                    <div class="col-lg-3 col-md-6 mb-4">
                                                        <label for="" class="required-label">Location</label>
                                                        <select name="LocationCode" class="form-select" id="LocationCode" required>
                                                            <option value="">Select Location</option>
                                                            <?php foreach ($locations as $loc): ?>
                                                                <?php if (!$loc['IsExcludeFromRegistration']): ?>
                                                                    <option value="<?= $loc['LocationCode'] ?>">
                                                                        <?= $loc['LocationName'] ?>
                                                                    </option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 mb-4">
                                                        <label for="" class="required-label">Dietitian</label>
                                                        <select name="DieticianCode" class="form-select" id="DieticianCode" required>
                                                            <option value="">Select Dietitian</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 mb-4">
                                                        <label for="" class="required-label">Reason</label>
                                                        <select name="AppointmentReasonId" class="form-select" id="AppointmentReasonId" required>
                                                            <option value="">Select Reason</option>
                                                            <?php foreach ($appReason as $item): ?>
                                                                <?php if ($item['IsActive']): ?>
                                                                    <option value="<?= $item['AppointmentReasonId'] ?>">
                                                                        <?= $item['AppointmentReasonName'] ?>
                                                                    </option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 mb-4">
                                                        <label for="" class="required-label">Date</label>
                                                        <input type="text" class="form-control w-100" id="AppointmentDate" name="AppointmentDate" required>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 mb-4">
                                                        <label for="" class="required-label">Time (From)</label>
                                                        <select name="FromTime" class="form-select" id="FromTime" required>
                                                            <option value="">Select</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 mb-4">
                                                        <label for="" class="required-label">Time (To)</label>
                                                        <select name="ToTime" class="form-select" id="ToTime" required>
                                                            <option value="">Select</option>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <div
                                                    class="d-flex flex-column flex-lg-row justify-content-end gap-3 align-items-center">
                                                    <a data-bs-dismiss="modal"
                                                        class="btn-link w-100 w-lg-auto px-4" onclick="clearAppoForm()">Cancel</a>
                                                    <a data-bs-dismiss="modal" class="btn-outline w-100 w-lg-auto px-4"><i
                                                            class="ti ti-phone"></i>&nbsp;Call Us</a>
                                                    <div class="d-flex light-bg w-100 w-lg-auto">
                                                         <button type="submit" name="appointmentsubmit" class="btn-fancy w-100 w-lg-auto" value="appointmentform"><span>
                                                            <svg id="appointmentloader" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                                                            Request Appointment</span>
                                                            <i class="ti ti-arrow-up-right"></i></button>
                                                        <!-- <a href="#" class="btn-fancy w-100 w-lg-auto">
                                                            <span class="w-100 w-lg-auto">Request Appointment</span>
                                                            <i class="ti ti-arrow-up-right"></i>
                                                        </a> -->
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
            </div>
        </div>
    </section>

<?php
include './partials/footer.php';
?>
<style>
    .disablebtnlcls{
        pointer-events: none;   /* Disable click */
        cursor: default;        /* Remove hand cursor */
        color: #999;            /* Optional: make it look disabled */
        text-decoration: none;  /* Optional: remove underline */
        opacity: 0.6;
    }
</style>
<script>
$(document).on('click', '#ediMenuplan', function () {
  const tabTrigger = new bootstrap.Tab(document.querySelector('#menu-selection-tab'));
  tabTrigger.show();
});


function convertDMYtoISO(dateStr) {
    // Split input date: "17/07/2025"
    const [day, month, year] = dateStr.split('/');
    return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}T00:00:00`;
}

flatpickr("#menuDateId", {
dateFormat: "d/m/Y",
})

flatpickr("#datepickerStartDate", {
    dateFormat: "d/m/Y",
    minDate: new Date().fp_incr(1) // disables today and all past dates
});

flatpickr("#AppointmentDate", {
    dateFormat: "d/m/Y",
    minDate: "today" // disables all past dates
});


$('a#generatemenu').css({
'pointer-events': 'none',
'opacity': '0.5',
'color': '#aaa'
});
  $('#menuDateId').on('change', function () {
        var dateVal = $(this).val();
        let formattedDate = convertDMYtoISO(dateVal);
        if (formattedDate != "") {
            $.ajax({
                url: 'ajax_file/get_package_by_date.php',
                type: 'POST',
                data: { sel_date: formattedDate },
                success: function (response) {
                    if(response.success == true){
                        $('#packagenm').html(response.data);
                        if(response.found == 1){
                            $('a#generatemenu').css({
                                'pointer-events': 'auto',
                                'opacity': '1',
                                'color': ''
                            });
                        }else{
                            $('a#generatemenu').css({
                            'pointer-events': 'none',
                            'opacity': '0.5',
                            'color': '#aaa'
                            });
                        }
                    }
                }
            });
        } else {
            $('#packagenm').html('<option value="">Package Name</option>');
        }
    });
    
    //$('input[name="weekday"]').on('change', function () {
$(document).on('change', 'input[name="weekday"]', function () {
            const seldate = $('#menuDateId').val();
            let formattedDate = convertDMYtoISO(seldate);
            
            var selectedWeekDay = $('input[name="weekday"]:checked').val();
            
            if(formattedDate != ''){
                $('#generatespinner').show();
                $.ajax({
                    url: 'ajax_file/generate_menu.php',
                    type: 'POST',
                    data: { sel_date: formattedDate, weekDate: selectedWeekDay },
                    success: function (response) {
                        if(response.success == true){
                        $('#menulistitems').html(response.data);
                        $('#generatespinner').hide();
                        }
                    },
                    error: function (error) {
                        $('#generatespinner').hide();
                    }
                });
            }
    });
    $('#generatemenu').click(function() {
            const seldate = $('#menuDateId').val();
            let formattedDate = convertDMYtoISO(seldate);
            if(formattedDate != ''){
                $('#generatespinner').show();
                $.ajax({
                    url: 'ajax_file/generate_menu.php',
                    type: 'POST',
                    data: { sel_date: formattedDate },
                    success: function (response) {
                        if(response.success == true){
                        $('#menulistitems').html(response.data);
                        $('#generatespinner').hide();
                        }
                    },
                    error: function (error) {
                        $('#generatespinner').hide();
                    }
                });
            }
    });
</script>
<script>
    
    $('.mealallspin').hide();
    function changeAltermenu(selectElement, menuData){
        
        if(menuData != ''){
            const selmenu = selectElement.value;
            const selectedText = selectElement.options[selectElement.selectedIndex].text;
            
            $('#meal-spinner-'+menuData.MealTypeId).show();
            $.ajax({
                url: 'ajax_file/update_menu.php',
                type: 'POST',
                data: { sel_menu_id: selmenu, sel_menu_label: selectedText, menuData },
                success: function (response) {
                    debugger
                    if(response.success == true){
                        //$('#meal-1'+selectedText.MealTypeId).text();
                    //    $('#allrecieptdata').show();
                    //    $('#allrecieptdata').html(response.data);
                    }else{
                        $('#meal-'+menuData.MealTypeId).text(response.message);
                    }
                    $('#meal-spinner-'+menuData.MealTypeId).hide();
                },
                error: function (error) {
                    debugger
                    //$('#recieptbtn').hide();
                }
            });
        }
    }

    $('#global-success-msg').hide();
    $('#global-error-msg').hide();
    $('#appointmentForm').on('submit', function(e) {
        e.preventDefault(); 
        $('#appointmentloader').show(); 
        let formData = new FormData(this);
            $.ajax({
                url: 'ajax_file/create-appointment.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    
                    $('#appointmentloader').hide();
                    if(response.success == true){
                        $('.successmsg-text').text(response.message);
                        $('.errormsg-text').text('');
                        $('#global-success-msg').show();
                        $('#global-error-msg').hide();
                        $('#appointmentForm')[0].reset();
                    }else{
                        $('#global-error-msg').show();
                        $('#global-success-msg').hide();
                        $('.errormsg-text').text(response.message);
                        $('.successmsg-text').text('');
                    }
                },
                error: function (error) {
                    $('#appointmentloader').hide();
                    $('.errormsg-text').text('Technical error');
                    $('.successmsg-text').text('');
                }
            });
    });

    // get dietician by location
    $('#LocationCode').on('change', function () {
        var ID = $(this).val();
        if (ID) {
            $.ajax({
                url: 'ajax_file/get_dietician.php',
                type: 'POST',
                data: { sel_id: ID },
                success: function (response) {
                    if(response.success == true){
                        $('#DieticianCode').html(response.data);
                    }
                }
            });
        } else {
            $('#DieticianCode').html('<option value="">Select Dietitian</option>');
        }
    });

    // get time slot by dietician id
    $('#AppointmentDate').on('change', function () {
        var appDate = $(this).val();
        var diet_id = $('#DieticianCode').val();
        if (appDate !="" && diet_id != '') {
                $.ajax({
                    url: 'ajax_file/get_timeslot_by_dietician.php',
                    type: 'POST',
                    data: { app_date: appDate, diet_id: diet_id },
                    success: function (response) {
                        if(response.success == true){
                            $('#FromTime').html(response.from_data);
                            $('#ToTime').html(response.to_data);
                        }
                    }
                });
        }
    });

    
    function getreceiptlist(subID, indexNo){
        if(subID != ''){
            $('#recieptbtn'+indexNo).show();
            $.ajax({
                url: 'ajax_file/get_all_reciept.php',
                type: 'POST',
                data: { SubscriptionId: subID },
                success: function (response) {
                    if(response.success == true){
                       $('#allrecieptdata').show();
                       $('#allrecieptdata').html(response.data);
                    }
                    $('#recieptbtn'+indexNo).hide();
                },
                error: function (error) {
                    $('#recieptbtn'+indexNo).hide();
                }
            });
        }
    }
//     function pdfDownload(recNo) {
//     fetch('ajax_file/downalod_reciept.php', {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/x-www-form-urlencoded'
//                 },
//                 body: new URLSearchParams({ ReceiptEntryNo: recNo })
//             })
//             .then(response => response.blob())
//             .then(blob => {
//                 const url = window.URL.createObjectURL(blob);
//                 const a = document.createElement('a');
//                 a.href = url;
//                 a.download = 'Receipt_' + recNo + '.pdf';
//                 document.body.appendChild(a);
//                 a.click();
//                 a.remove();
//             });

// }
function pdfDownload(recNo) {
    const container = document.querySelector('.payment-container');
    const loader = container.querySelector('.loader-overlay');

    // Show loader
    loader.style.display = 'flex';

    fetch('ajax_file/downalod_reciept.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ ReceiptEntryNo: recNo })
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Receipt_' + recNo + '.pdf';
        document.body.appendChild(a);
        a.click();
        a.remove();
    })
    .catch(err => {
        console.error('Download failed:', err);
        alert('Download failed.');
    })
    .finally(() => {
        // Hide loader
        loader.style.display = 'none';
    });
}

 
    // change subscription status start, stop, resume
    function clearAppoForm(){
         $('#appointmentForm')[0].reset(); 
    }
    function select_date(indexNo){
        $('.startdatecomoncls').each(function(index, element) {
            if(indexNo != index){
                $(this).removeClass('d-flex').addClass('d-none'); // Hide
            }
        });

        $('.packagedatecomoncls').removeClass('is-invalid');
        $('.text-danger').remove();
        $('.packagedatecomoncls').val('');

        const $div = $('.startdate-' + indexNo);
        
        $('.text-danger').remove();
        if ($div.hasClass('d-none')) {
            $div.removeClass('d-none').addClass('d-flex'); // Show as flex
        } else {
            $div.removeClass('d-flex').addClass('d-none'); // Hide
        }
   
         $('.startdate-' + indexNo).toggle(); 
    }

   function removeError(el) {
    if (el.value) {
        el.classList.remove('is-invalid');
        $('.text-danger').remove();
    }
    }

    function package_action(subID, locationid, action_val, indexNo){
        var startDate = "";
        if(action_val == 'start'){
            $('.packagedatecomoncls').removeClass('is-invalid');
            $('.text-danger').remove();
            startDate = $('.packageStartDate'+indexNo).val();
            if (!startDate) {
                $('.packageStartDate'+indexNo).addClass('is-invalid');
                $('.packageStartDate'+indexNo).after('<div class="text-danger mt-1">Start date is required.</div>');
                return false;
            } else {
                $('#loaderStart').show();
                $('.packageStartDate'+indexNo).removeClass('is-invalid');
                $('.text-danger').remove();
                $('#loaderStart'+indexNo).show();
                window.location.href = window.location.href;
            }
            
        }else if(action_val == 'stop'){
            $('#loaderStop').show();
        }else if(action_val == 'resume'){
            $('#loaderResume').show();
        }
        $.ajax({
            url: 'ajax_file/update_subscription_action.php',
            type: 'POST',
            data: { subscription_ID: subID, action_val: action_val, startDate: startDate, locationID: locationid },
            success: function (response) {
                if(response.success == true){
                    window.location.href = window.location.href;
                }
            },
            error: function (error) {
            }
        });
    }

    function BMICalculation(){
        $.ajax({
            url: 'ajax_file/update_subscription_action.php',
            type: 'POST',
            data: { subscription_ID: subID, action_val: action_val },
            success: function (response) {
                if(response.success == true){
                    window.location.href = window.location.href;
                }
            },
            error: function (error) {

            }
        });
    }
    function setcurrentweight(){
        var weightval = $('#CurrentWeightHidden').val();
        if(weightval == ""){
            weightval = 0;    
        }
        $('#CurrentWeight').val(weightval);

        var heightval = $('#CurrentHeightHidden').val();
        if(heightval == ""){
            heightval = 0;    
        }
        $('#CurrentHeight').val(heightval);
    }
    function setTargetweight(){
        var weightval = $('#TargetWeightHidden').val();
        if(weightval == ""){
            weightval = 0;    
        }
        $('#TargetWeight').val(weightval);
    }
    $('#currentspinner').hide();
    $('#targetspinner').hide();
    function updatehealthVal(action_val){
        if(action_val == "current_weight"){
            $('#currentspinner').show();
            var weightval = $('#CurrentWeight').val();
            var heightval = $('#CurrentHeight').val();
            
            $('a#currentweightbtn').css({
            'pointer-events': 'none',
            'opacity': '0.5',
            'color': '#aaa'
            });
        }else if(action_val == "target_weight"){
            var weightval = $('#TargetWeight').val();
            $('#targetspinner').show();
            $('a#targetweightbtn').css({
            'pointer-events': 'none',
            'opacity': '0.5', // optional: make it look disabled
            'color': '#aaa'   // optional: dim the text color
            });
        }
         $.ajax({
            url: 'ajax_file/update_weight.php',
            type: 'POST',
            data: { weight: weightval, height: heightval, action_val: action_val },
            success: function (response) {
                if(response.success == true){
                    if(action_val == "current_weight"){
                        $('#CurrentWeightHidden').val(weightval);
                        $('#personwieghtlabel').text(weightval);

                        $('#CurrentHeightHidden').val(heightval);
                        $('#personheightlabel').text(heightval);
                    }else if(action_val == "target_weight"){
                        $('#TargetWeightHidden').val(weightval);
                        $('#targetwieghtlabel').text(weightval);
                    }
                    debugger
                    $('#bmistatusval').text(response.bmi_data.status);
                    $('#bmival').text(parseFloat(response.bmi_data.BMI.toFixed(2)));
                    
                }
                $('#current-weight').modal('hide');
                $('#target-weight').modal('hide');
                $('#currentspinner').hide();
                $('#targetspinner').hide();
                $('a#currentweightbtn, a#targetweightbtn').css({
                'pointer-events': 'auto',
                'opacity': '1',
                'color': ''
                });
            },
            error: function (error) {
            }
        });
    }
</script>
<style>
    .payment-container {
    position: relative;
}

.loader-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
}

.spinner {
    width: 50px;
    height: 50px;
    animation: rotate 1s linear infinite;
}

.spinner .path {
    stroke: #3498db;
    stroke-linecap: round;
    animation: dash 1.5s ease-in-out infinite;
}

@keyframes rotate {
    100% {
        transform: rotate(360deg);
    }
}

@keyframes dash {
    0% {
        stroke-dasharray: 1, 150;
        stroke-dashoffset: 0;
    }
    50% {
        stroke-dasharray: 90, 150;
        stroke-dashoffset: -35;
    }
    100% {
        stroke-dasharray: 90, 150;
        stroke-dashoffset: -124;
    }
}

</style>
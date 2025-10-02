<?php 
include 'partials/header.php';
$islogin = true;
if (!isset($_SESSION['user'])) {
    $islogin =false;
    header('Location: login.php');
    exit;
}

if(isset($_REQUEST['group']) && $_REQUEST['group'] != "" && isset($_REQUEST['package']) && $_REQUEST['package'] != ""){
    $mealplanId = isset($_REQUEST['mealplan']) && $_REQUEST['mealplan'] != "" ? $_REQUEST['mealplan'] : '';
    $package_single_details = "";
        $packageParam = [
        'GroupCode' => $_REQUEST['group']
        ];
        $packresponse = GetPackageByGroup($packageParam);
        
        if (
            isset($packresponse['ValidationDetails']['StatusCode']) &&
            $packresponse['ValidationDetails']['StatusCode'] == 200 &&
            !empty($packresponse['MasterDataList'][0])
        ) {
            $packagesByGroup = $packresponse['MasterDataList'];   
           
            if(!empty($packagesByGroup)){
                foreach ($packagesByGroup as $index => $item):
                    if($item['PackageId'] == $_REQUEST['package'] && $item['GroupCode'] == $_REQUEST['group']){
                       // print_r($item);
                        $package_single_details = $item;
                    }
                endforeach;
            }
        }
        $taxper = ($package_single_details['UnitRate'] * $package_single_details['TaxPercentage']) / 100;
        $subtotal = $package_single_details['UnitRate'] + $taxper;

        $coolerBagtitle = '';
                        $coolerBagAmount = '';
                        $collerBagData= [];
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
                        
                            if($collerBagData){
                                if(!$collerBagData[0]['IsCoolerBagAvailable'])
                                {
                                    $feesdataData= [];
                                    $response_cool = GetFeesCharges();
                                    if (
                                        isset($response_cool['ValidationDetails']['StatusCode']) &&
                                        $response_cool['ValidationDetails']['StatusCode'] == 200 &&
                                        !empty($response_cool['MasterDataList'])
                                    ) {
                                        $feesdataData = $response_cool['MasterDataList'];
                                        if($feesdataData){
                                            foreach ($feesdataData as $index =>$item): 
                                                if($item['FeeCode'] == 'CB'){
                                                    $coolerBagAmount = $item['Amount'];
                                                    $coolerBagtitle = $item['FeeDescription'];
                                                }
                                            endforeach;
                                        }
                                    } 
                                }
                            }
                        }  
        if($coolerBagAmount != ''){
            $subtotal = $subtotal + $coolerBagAmount;
        }
}else{
    header('Location: '. ROOT_URL);
    exit;
}

$errors = [];
$error_msg = '';
$success_msg = '';
if(isset($_POST["submit"]) && $_POST["submit"] == "profileform"){
     
    $spinner = true;
    //$FirstName = filter_var($_POST['FirstName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    //$LastName = filter_var($_POST['LastName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    //$MiddleName = filter_var($_POST['MiddleName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $AddressEmailId = $_POST['AddressEmailId'] ?? '';
    $MobileNumber = $_POST['MobileNumber'] ?? '';
    $Address1 = $_POST['Address1'] ?? '';
    $Address2 = $_POST['Address2'] ?? '';
    $AreaId = $_POST['AreaId'] ?? '';
    $CityId = $_POST['CityId'] ?? '';
    $CountryId = $_POST['CountryId'] ?? '';
    $TimeslotId = $_POST['TimeslotId'] ?? '';
    $StartTime = $_POST['StartTime'] ?? '';
    $EndTime = $_POST['EndTime'] ?? '';
    $RecipientNotes = $_POST['RecipientNotes'] ?? '';
    $IsShipAddress = $_POST['IsShipAddress'] ?? '';
    $IsSun = $_POST['IsSun'] ?? '';
    $IsMon = $_POST['IsMon'] ?? '';
    $IsTue = $_POST['IsTue'] ?? '';
    $IsWed = $_POST['IsWed'] ?? '';
    $IsThu = $_POST['IsThu'] ?? '';
    $IsFri = $_POST['IsFri'] ?? '';
    $IsSat = $_POST['IsSat'] ?? '';
    $IsDefault = $_POST['IsDefault'] ?? '';

    $card_number = $_POST['card_number'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? '';
    $card_cvv = $_POST['card_cvv'] ?? '';
    
    
    if($IsDefault == "false" && $IsSun == "" && $IsMon == "" && $IsTue == "" && $IsWed == "" && $IsThu == "" && $IsFri == "" && $IsSat == ""){
        $errors['IsDefault'] = "Please select atleast 1 day";
    }

   // if (empty($FirstName)) $errors['FirstName'] = "First name is required.";
    //if (empty($LastName)) $errors['LastName'] = "Last name is required.";
    if (empty($MobileNumber)) {
        $errors['MobileNumber'] = "Phone number is required.";
    } 
    elseif (!preg_match('/^\+?[0-9\s\-]{7,15}$/', $MobileNumber)) {
        $errors['MobileNumber'] = "Invalid phone number format.";
    }
    if (empty($AddressEmailId)) {
        $errors['AddressEmailId'] = "Email is required.";
    } elseif (!filter_var($AddressEmailId, FILTER_VALIDATE_EMAIL)) {
        $errors['AddressEmailId'] = 'Invalid email format.';
    }
    if (empty($Address1)) $errors['Address1'] = "Address1 is required.";
    if (empty($AreaId)) $errors['AreaId'] = 'Please select area.';
    if (empty($CityId)) $errors['CityId'] = 'Please select city.';
    if (empty($CountryId)) $errors['CountryId'] = 'Please select country.';
    if (empty($TimeslotId)) $errors['TimeslotId'] = 'Please select time slot.';

    if (empty($card_number)){
        $errors['card_number'] = "Card number is required.";
    }else if (!validate_card_number($card_number)) {
        $errors['card_number'] = "Invalid card number.";
    }

    if (empty($expiry_date)){
        $errors['expiry_date'] = "Expiry date is required.";
    }else if (!validate_expiry_date($expiry_date)) {
        $errors['expiry_date'] = "Invalid expiry date.";
    } 

    if (empty($card_cvv)){
        $errors['card_cvv'] = "CVV is required.";
    }else if (!validate_cvv($card_cvv, $card_number)) {
        $errors['card_cvv'] = "Invalid CVV.";
    } 

      $coupon_amount = 0;
      
      
        if($_POST['applied_coupon'] != ''){
            $meal_planID = $mealplanId ?? '';
            $couponcode = $_POST['applied_coupon'] ?? '';
            $couponcode = str_replace(' ', '', $couponcode);
            $msg = "";
            $qry = $connection->query("SELECT * from `coupon_codes` where name = '{$couponcode}' AND (meal_plan = '{$meal_planID}' OR meal_plan = '0') LIMIT 1");
           
            if ($qry && $qry->num_rows > 0) {

                $coupon = $qry->fetch_assoc();
                $discount = $coupon['amount_per'];
                $expiry = $coupon['expiry_date'];

                // Get today's date
                $today = date('Y-m-d');

                // Check if coupon is expired
                if ($expiry < $today) {
                    $coupon_amount = 0;
                } else {
                    $subscriptionData = [];
                    $subParamData = [
                        'PersonId' => $_SESSION['user']['UserId']
                    ];
                    $subscription_response = GetClientSubscriptionList($subParamData);
                    if($subscription_response['ValidationDetails']['StatusCode'] == 200){
                        $currentuser = empty($subscription_response['MasterDataList']) ? 'new' : 'exist';
                        
                        if($coupon['user_type'] == $currentuser){
                            $coupon_amount = $coupon['amount_per'];
                        }else{
                            $coupon_amount = 0;
                        }
                    } 
                }
            } 
        }
    if (empty($errors)) {
        
        $result = getItemByOrderNo($allTimeslots, $TimeslotId);
        if ($result) {
            //json_encode($result, JSON_PRETTY_PRINT);
            $StartTime = $result['StartTime'];
            $EndTime = $result['EndTime'];
        }
        // $result2 = getItemByOrderNo($allTimeslots, $AlterTimeslotId);
        // if ($result2) {
        //     $AlterStartTime = $result2['StartTime'];
        //     $AlterEndTime = $result2['EndTime'];
        // }
        $final_payable_amount= $subtotal;
        if($coupon_amount != 0){
            $discount_val = ($subtotal * 20) / 100;
            $final_payable_amount = $subtotal - $discount_val;
        }
        $formData = [
        'PersonId' => $_SESSION['user']['UserId'], 
        'FirstName' => $clientprofileData[0]['FirstName'],
        'MiddleName' => $clientprofileData[0]['MiddleName'],
        'LastName' => $clientprofileData[0]['LastName'],
        'Address1' => $Address1,
        'Address2' => $Address2,
        'AreaId' => $AreaId,
        'CityId' => $CityId,
        'CountryId' => $CountryId,
        'TimeslotId' => $TimeslotId,
        'StartTime' => $StartTime,
        'EndTime' => $EndTime,
        'EmailId' => $AddressEmailId,
        'MobileNumber' => $MobileNumber,
        'RecipientNotes' => $RecipientNotes,
        'IsDefault' => $IsDefault,
        'IsShipAddress' => $IsShipAddress == 1 ? 'true' : 'false',
        'IsSun' => $IsSun == 1 ? 'true' : 'false', 
        'IsMon' => $IsMon == 1 ? 'true' : 'false', 
        'IsTue' => $IsTue == 1 ? 'true' : 'false', 
        'IsWed' => $IsWed == 1 ? 'true' : 'false', 
        'IsThu' => $IsThu == 1 ? 'true' : 'false', 
        'IsFri' => $IsFri == 1 ? 'true' : 'false', 
        'IsSat' => $IsSat == 1 ? 'true' : 'false',
        'ModeOfEntry' => "website",
        'DateOfBirth' => $clientprofileData[0]['DateOfBirth'] != '' ? $clientprofileData[0]['DateOfBirth'] : "null",
        'Gender' => $clientprofileData[0]['Gender'] != '' ? $clientprofileData[0]['Gender'] : "M",
        'IsAlterAddress' => $IsAlterAddress == 1 ? 'true' : 'false',
        ];
      
        
        // $response = UpdateClientProfile($formData);
        // if($response['ValidationDetails']['StatusCode'] == 200){
        
        //     $success_msg = "Successfully";
        //     $_SESSION['form_succ'] = 1;
        //     //header('Location:account_settings.php');
        // }else{
        //     $error_msg = $response['ValidationDetails']['StatusMessage'] != '' ? $response['ValidationDetails']['StatusMessage'] : $response['ValidationDetails']['ErrorDetails'][0]['ErrorMessageDescription'];
        //     $_SESSION['form_succ'] = 2;
        // }
    }
}

// Get Area
$usercityID = $clientprofileData[0]['CityId'];
$usercountryID = $clientprofileData[0]['CountryId'];
$allArea = [];
$areaParamData = [
'CityId' => $usercityID,
'CountryId' => $usercountryID
];

$area_response = GetAreaByCity($areaParamData);
if (isset($area_response['ValidationDetails']['StatusCode']) &&
    $area_response['ValidationDetails']['StatusCode'] == 200 &&
    !empty($area_response['MasterDataList'][0])) {
    $allArea = $area_response['MasterDataList'];
}

// Get Alter Area
$userAltercityID = $clientprofileData[0]['AlterCityId'];
$userAltercountryID = $clientprofileData[0]['AlterCountryId'];
$AlterAreas = [];
$alterAreaParamData = [
'CityId' => $userAltercityID,
'CountryId' => $userAltercountryID
];

$area_response = GetAreaByCity($alterAreaParamData);
if (isset($area_response['ValidationDetails']['StatusCode']) &&
    $area_response['ValidationDetails']['StatusCode'] == 200 &&
    !empty($area_response['MasterDataList'][0])) {
    $AlterAreas = $area_response['MasterDataList'];
}
// Get City
$allCity = [];
$city_response = GetCity();
if (isset($city_response['ValidationDetails']['StatusCode']) &&
    $city_response['ValidationDetails']['StatusCode'] == 200 &&
    !empty($city_response['MasterDataList'][0])) {
    $allCity = $city_response['MasterDataList'];
}
// Get Country
$allCountry = [];
$country_response = GetCountry();
if (isset($country_response['ValidationDetails']['StatusCode']) &&
    $country_response['ValidationDetails']['StatusCode'] == 200 &&
    !empty($country_response['MasterDataList'][0])) {
    $allCountry = $country_response['MasterDataList'];
}

// Get Timeslote
$timeslotParamData = [
'CityId' => $usercityID,
'CountryId' => $usercountryID
];
$allTimeslots = [];
$timeslots_response = GetDeliveryTimeSlotByCity($timeslotParamData);
if (isset($timeslots_response['ValidationDetails']['StatusCode']) &&
    $timeslots_response['ValidationDetails']['StatusCode'] == 200 &&
    !empty($timeslots_response['MasterDataList'][0])) {
    $allTimeslots = $timeslots_response['MasterDataList'];
}

function getItemByOrderNo(array $data, int $orderNo): ?array {
    foreach ($data as $item) {
        if (isset($item['OrderNo']) && $item['OrderNo'] == $orderNo) {
            return $item; // Return first match
        }
    }
    return null; // No match found
}

// Function to validate the card number using the Luhn algorithm
function validate_card_number($card_number) {
    $card_number = str_replace(' ', '', $card_number); // Remove any spaces
    $length = strlen($card_number);
    $sum = 0;
    $is_even = false;

    // Loop through the card number digits from right to left
    for ($i = $length - 1; $i >= 0; $i--) {
        $digit = (int) $card_number[$i];

        if ($is_even) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9; // If the result is more than 9, subtract 9
            }
        }

        $sum += $digit;
        $is_even = !$is_even;
    }

    return ($sum % 10 === 0); // If sum modulo 10 is 0, the number is valid
}

// Function to validate the expiry date (MM/YY format)
function validate_expiry_date($expiry_date) {
    $date_parts = explode('/', $expiry_date);
    if (count($date_parts) !== 2) {
        return false;
    }

    $month = (int) $date_parts[0];
    $year = (int) $date_parts[1];

    // Ensure the expiry month is between 1 and 12
    if ($month < 1 || $month > 12) {
        return false;
    }

    // Ensure the year is valid and not in the past
    $current_year = (int) date('y');
    $current_month = (int) date('m');

    if ($year < $current_year || ($year === $current_year && $month < $current_month)) {
        return false;
    }

    return true;
}

// Function to validate the CVV (3 or 4 digits depending on the card)
function validate_cvv($cvv, $card_number) {
    $card_type = detect_card_type($card_number); // Determine the card type (VISA, MasterCard, etc.)

    if ($card_type === 'AMEX') {
        // AMEX cards have a 4-digit CVV
        return preg_match('/^\d{4}$/', $cvv);
    } else {
        // Other cards (e.g., VISA, MasterCard) have a 3-digit CVV
        return preg_match('/^\d{3}$/', $cvv);
    }
}

// Function to detect the card type based on the card number (e.g., VISA, AMEX, etc.)
function detect_card_type($card_number) {
    // Visa starts with 4, MasterCard starts with 5, AMEX starts with 34 or 37
    if (preg_match('/^4/', $card_number)) {
        return 'VISA';
    } elseif (preg_match('/^5/', $card_number)) {
        return 'MasterCard';
    } elseif (preg_match('/^(34|37)/', $card_number)) {
        return 'AMEX';
    } else {
        return 'Unknown';
    }
}



?>
<!-- ===========BANNER=========== -->
    <section class="inner-banner">
    </section>

    <!-- ===========SUBHEADER=========== -->

    <section class="py-5 form">
        <div class="container py-5">
            <div class="row">
                <?php if(isset($_SESSION['form_succ'])){ ?>
                    <div class="col-lg-12 col-lg-12 mb-lg-4" id="formMessage">
                        <?php if($_SESSION['form_succ'] == 2){ ?>
                        <div class="message error" id="global-error-msg">
                            Error submitting form. Try again.
                            <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
                        </div>
                        <?php } ?>
                        <?php if($_SESSION['form_succ'] == 1){ ?>
                        <div class="message success" id="global-succes-msg">
                        Profile Settings updated successfully.
                        <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
                        </div>
                        <?php } ?>
                    </div>
                <?php 
                unset($_SESSION['form_succ']);
                } 
                 ?>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <form enctype="multipart/form-data" method="POST" id="profileForm">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="h3 text-brand d-flex mb-4">Add your shiping Address</h2>
                                <div class="payment-container">
                                    <div class="row mt-4">
                                        <!-- <input type="hidden" name="FirstName" class="form-control w-100" id="FirstName" placeholder="Enter First Name" value="<?= $FirstName ?? $clientprofileData[0]['FirstName'] ?>">
                                        <input type="hidden" name="MiddleName" class="form-control w-100" id="MiddleName" placeholder="Enter Middle Name" value="<?= $MiddleName ?? $clientprofileData[0]['MiddleName'] ?>">
                                        <input type="hidden" name="LastName" class="form-control w-100" id="lastname" placeholder="Enter Last Name" value="<?= $LastName ?? $clientprofileData[0]['LastName'] ?>"> -->
                                        <div class="col-lg-12 product-book mb-4">
                                            <div class="radio-group">
                                                <?php
                                                $ischeck = 'checked';
                                                $ischeck2 = '';
                                                if($clientprofileData[0]['IsDefault'] === false){
                                                    $ischeck = '';
                                                    $ischeck2 = 'checked';
                                                }  ?>
                                                
                                                    <input type="radio" id="r11" name="IsDefault" class="radio-input" value="true"
                                                        <?= $ischeck ?>>
                                                    <label for="r11" class="radio-label">Default</label>

                                                    <input type="radio" id="r22" name="IsDefault" class="radio-input" value="false"
                                                        <?= $ischeck2 ?>>
                                                    <label for="r22" class="radio-label">Day Specific</label>
                                                </div>
                                                <div class="day-val-error"><?php if (isset($errors['IsDefault'])): ?>
                                                                <small class="text-danger"><?= $errors['IsDefault']; ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 product-book mb-4" id="daysoptionid" style="display: <?php echo $clientprofileData[0]['IsDefault'] !== true ? 'block' : 'none' ?>;">
                                                <div class="radio-group">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="IsSun" id="IsSun" value="1" <?php if ($clientprofileData[0]['IsSun']) echo 'checked'; ?>/>
                                                            <span class="checkmark"></span>
                                                            <span>Sun</span>
                                                        </label>
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="IsMon" id="IsMon" value="1" <?php if ($clientprofileData[0]['IsMon']) echo 'checked'; ?>/>
                                                            <span class="checkmark"></span>
                                                            <span>Mon</span>
                                                        </label>
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="IsTue" id="IsTue" value="1" <?php if ($clientprofileData[0]['IsTue']) echo 'checked'; ?>/>
                                                            <span class="checkmark"></span>
                                                            <span>Tue</span>
                                                        </label>
                                                        <label class="custom-checkbox">                                    
                                                            <input type="checkbox" name="IsWed" id="IsWed" value="1" <?php if ($clientprofileData[0]['IsWed']) echo 'checked'; ?>/>
                                                            <span class="checkmark"></span>
                                                            <span>Wen</span>
                                                        </label>
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="IsThu" id="IsThu" value="1" <?php if ($clientprofileData[0]['IsThu']) echo 'checked'; ?>/>
                                                            <span class="checkmark"></span>
                                                            <span>Thu</span>
                                                        </label>
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="IsFri" id="IsFri" value="1" <?php if ($clientprofileData[0]['IsFri']) echo 'checked'; ?>/>
                                                            <span class="checkmark"></span>
                                                            <span>Fri</span>
                                                        </label>
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox" name="IsSat" id="IsSat" value="1" <?php if ($clientprofileData[0]['IsSat']) echo 'checked'; ?>/>
                                                            <span class="checkmark"></span>
                                                            <span>Sat</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-4">
                                                <div>
                                                    <label for="" class="required-label">Address Line 1</label>
                                                    <input type="text" name="Address1" class="form-control w-100" id="Address1" placeholder="Enter Address Line 1" value="<?= $Address1 ?? $clientprofileData[0]['Address1'] ?>">
                                                    <?php if (isset($errors['Address1'])): ?>
                                                                <small class="text-danger"><?= $errors['Address1']; ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-4">
                                                <div>
                                                    <label for="" class="">Address Line 2 (Optional)</label>
                                                    <input type="text" name="Address2" class="form-control w-100" id="Address2" placeholder="Enter Address Line 2" value="<?= $Address2 ?? $clientprofileData[0]['Address2'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-4 d-none">
                                                <div>
                                                    <label for="" class="required-label">Country</label>
                                                    <select name="CountryId" class="form-select" id="CountryId">
                                                        <option value="">Select Country</option>
                                                        <?php foreach ($allCountry as $item):
                                                        if ($item['IsActive']): 
                                                            $countryid = $CountryId ?? $clientprofileData[0]['CountryId'];
                                                        ?>
                                                                <option value="<?php echo $item['CountryId']; ?>" <?php echo $item['CountryId'] == $countryid ? 'selected' : ''; ?>>
                                                                <?php echo $item['CountryName']; ?>
                                                            </option>
                                                        <?php endif; endforeach; ?>
                                                    </select>
                                                    <?php if (isset($errors['CountryId'])): ?>
                                                                <small class="text-danger"><?= $errors['CountryId']; ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-4">
                                                <div>
                                                     <?php 
                                                    usort($allCity, function ($a, $b) {
                                                        return strcmp($a['CityName'], $b['CityName']);
                                                    });
                                                    ?>
                                                    <label for="" class="required-label">City</label>
                                                    <select name="CityId" class="form-select" id="CityId">
                                                        <option value="">Select City</option>
                                                        <?php foreach ($allCity as $item):
                                                        //if ($item['IsActive']): 
                                                            $cityid = $CityId ?? $clientprofileData[0]['CityId'];
                                                        ?>
                                                                <option value="<?php echo $item['CityId']; ?>" <?php echo $item['CityId'] == $cityid ? 'selected' : ''; ?>>
                                                                <?php echo $item['CityName']; ?>
                                                            </option>
                                                        <?php //endif; 
                                                        endforeach ?>
                                                    </select>
                                                    <?php if (isset($errors['CityId'])): ?>
                                                                <small class="text-danger"><?= $errors['CityId']; ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>                    
                                            <div class="col-lg-6 mb-4">
                                                <div>
                                                     <?php 
                                                    usort($allArea, function ($a, $b) {
                                                        return strcmp($a['AreaName'], $b['AreaName']);
                                                    });
                                                    ?>
                                                    <label for="" class="required-label">Area</label>
                                                    <select name="AreaId" class="form-select" id="AreaId">
                                                        <option value="">Select Area</option>
                                                        <?php foreach ($allArea as $item):
                                                        //if ($item['IsActive']): 
                                                        $areaid = $AreaId ?? $clientprofileData[0]['AreaId']; ?>
                                                                <option value="<?php echo $item['AreaId']; ?>" <?php echo ($item['AreaId'] == $areaid) ? 'selected' : ''; ?>>
                                                                <?php echo $item['AreaName']; ?>
                                                            </option>
                                                        <?php //endif; 
                                                        endforeach ?>
                                                    </select>
                                                    <?php if (isset($errors['AreaId'])): ?>
                                                                <small class="text-danger"><?= $errors['AreaId']; ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-4">
                                                <div>
                                                    <label for="" class="required-label">Time Slot</label>
                                                    <select name="TimeslotId" id="TimeslotId" class="form-select" id="">
                                                        <option value="">Select Time</option>
                                                        <?php 
                                                        foreach ($allTimeslots as $item):
                                                        if ($item['IsActive']): 
                                                            $start = date("g:ia", strtotime($item['StartTime'])); // e.g. 04:00pm
                                                            $end = date("g:ia", strtotime($item['EndTime'])); // e.g. 04:00pm
                                                            $timeslotIDRec = $TimeslotId ?? $clientprofileData[0]['TimeslotId'];
                                                            ?>
                                                            <option value="<?php echo $item['OrderNo']; ?>" <?php echo ($item['OrderNo'] == $timeslotIDRec) ? 'selected' : ''; ?>> <?php echo $start.' -- '.$end; ?> </option>
                                                        <?php endif; endforeach; ?>
                                                    </select>
                                                    <?php if (isset($errors['TimeslotId'])): ?>
                                                                <small class="text-danger"><?= $errors['TimeslotId']; ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-4">
                                                <div>
                                                    <label for="" class="required-label">Email ID</label>
                                                    <input type="text" name="AddressEmailId" class="form-control w-100" id="AddressEmailId" placeholder="name@example.com" value="<?= $AddressEmailId ?? $clientprofileData[0]['AddressEmailId'] != '' ? $clientprofileData[0]['AddressEmailId'] : $clientprofileData[0]['EmailId'] ?>">
                                                    <?php if (isset($errors['AddressEmailId'])): ?>
                                                        <small class="text-danger"><?= $errors['AddressEmailId']; ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-4">
                                                <div>
                                                    <label for="" class="required-label">Phone</label>
                                                    <input type="text" name="MobileNumber" class="form-control w-100" id="MobileNumber" placeholder="Enter Phone" value="<?= $MobileNumber ?? $clientprofileData[0]['MobileNumber'] ?>">
                                                    <?php if (isset($errors['MobileNumber'])): ?>
                                                                <small class="text-danger"><?= $errors['MobileNumber']; ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-4" style="display: none;">
                                                <div>
                                                    <label for="" class="">Recipient Note</label>
                                                    <textarea name="RecipientNotes" class="form-control" id="RecipientNotes"><?= $RecipientNotes ?? $clientprofileData[0]['RecipientNotes'] ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="custom-checkbox">
                                                    <input type="checkbox" name="IsShipAddress" id="IsShipAddress" value="1" <?php if ($clientprofileData[0]['IsShipAddress']) echo 'checked'; ?>/>
                                                    <span class="checkmark"></span>
                                                    Ship to this address
                                                </label>
                                            </div>
                                    </div>
                                </div>
                                <h2 class="h3 text-brand d-flex my-4">Choose your payment method</h2>
                                <div class="payment-container">
                                    <div class="payment-options d-flex gap-3">
                                        <label class="payment-card payment-active">
                                            <input type="radio" name="payment" value="credit-card" checked>
                                            <div class="card-content">
                                                <img src="img/card.svg" alt="">
                                                <span>Credit / Debit Card</span>
                                            </div>
                                        </label>

                                        <label class="payment-card">
                                            <input type="radio" name="payment" value="paypal">
                                            <div class="card-content">
                                                <img src="img/gpay.svg" alt="">
                                                <span>Pay</span>
                                            </div>
                                        </label>

                                        <label class="payment-card">
                                            <input type="radio" name="payment" value="apple-pay">
                                            <div class="card-content">
                                                <img src="img/applepay.svg" alt="">
                                                <span>Pay</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="row form">
                                        <div class="col-lg-12 mt-4">
                                            <label for="">Card Number</label>
                                            <input type="text" name="card_number" id="card_number" placeholder="Card Number" class="form-control" maxlength="19" value="<?= $card_number ?>">
                                            <?php if (isset($errors['card_number'])): ?>
                                                        <small class="text-danger"><?= $errors['card_number']; ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label for="">Expiry Date</label>
                                            <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YY" class="form-control" maxlength="5" value="<?= $expiry_date ?>">
                                            <?php if (isset($errors['expiry_date'])): ?>
                                                        <small class="text-danger"><?= $errors['expiry_date']; ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <label for="">CCV</label>
                                            <input type="password" name="card_cvv" id="card_cvv" placeholder="***" class="form-control" maxlength="4" value="<?= $card_cvv ?>">
                                            <?php if (isset($errors['card_cvv'])): ?>
                                                        <small class="text-danger"><?= $errors['card_cvv']; ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="w-100 light-bg mt-4">
                                        <input type="hidden" name="applied_coupon" id="applied_coupon">
                                        <button type="submit" name="submit" class="btn-fancy" value="profileform" id="paybtn">
                                            <span id="payamountid">
                                                <svg id="payloader" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg> 
                                                Pay AED <?= $subtotal ?></span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4">
                    <div class="plan-detail-card p-5">
                    <h4 class="d-flex text-white mb-4" id="packagename"><?php 
                    echo $package_single_details['PackageName']; ?></h4>
                    <div class="d-flex flex-column gap-3 text-white">
                        <hr>
                        <?php if($package_single_details['PackageMoreInfo'] != ""){ ?>
                            <span id="package-desc"><?php  echo $package_single_details['PackageMoreInfo']; ?></span>
                            <hr>
                        <?php } ?>
                        <div>Plan Duration: <span id="package-duration"><?php  echo $package_single_details['DurationDays']; ?></span> Days</div>
                        <hr>
                        <?php
                        $subscriptionData = [];
                        $subParamData = [
                            'PersonId' => $_SESSION['user']['UserId']
                        ];
                        $subscription_response = GetClientSubscriptionList($subParamData);
                        if($subscription_response['ValidationDetails']['StatusCode'] == 200){
                            if(empty($subscription_response['MasterDataList'])){
                                //$subscriptionData = $subscription_response['MasterDataList'];
                                ?>
                                <small>Complimentary Dietary Consultation & Body Fat Analysis worth <b>250 AED</b></small>
                                <small>Complimentary 2 Follow-Ups worth <b>300 AED</b></small>
                                <hr>
                                <?php
                            }
                        } ?>
                        
                        <div class="d-flex gap-1 coupen-code">
                            <input type="text" class="form-control w-100" id="couponcodeid" class="couponcls" placeholder="COUPON CODE">
                            <button class="btn-fancy" id="couponbtn">
                                <svg id="couponloader" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                                Submit</button>
                        </div>
                        <div class="text-danger" id="copouncodemsgId"></div>
                        <div class="d-flex justify-content-between opacity-50" id="discountbox">
                            <span>Discount Price</span>
                            <strong>AED <span id="discountPrice"><span></strong>
                        </div>
                        
                        <div class="d-flex justify-content-between opacity-50">
                            <span>Package Price</span>
                            <strong>AED <span id="package-unit-rate"><?php  echo $package_single_details['UnitRate']; ?><span></strong>
                        </div>
                        <?php 
                        if($collerBagData){ ?>
                            <div class="d-flex justify-content-between opacity-50">
                                <?php if($coolerBagtitle != ''){ ?>
                                <span><?php echo $coolerBagtitle; ?></span>
                                <?php } ?>
                                <?php if($coolerBagAmount != ""){ ?>
                                <strong>AED <?php echo $coolerBagAmount; ?></strong>
                                <?php } ?>
                            </div>
                        <?php }
                        ?>
                        <div class="d-flex justify-content-between">
                            <span class="fs-5">Subtotal (incl. VAT)</span>
                            <strong class="d-flex justify-content-end fs-5">
                               
                                <input type="hidden" value="<?php  echo $subtotal; ?>" name="sbtotal" id="sbtotal">
                                <input type="hidden" value="<?php  echo $subtotal; ?>" name="final_payment" id="final_payment">
                                AED <span id="package-subtotal"> <?php  echo $subtotal; ?></span>
                            </strong>
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </div>
    </section>
<script>
       // get city by country
    $('#CountryId').on('change', function () {
        debugger
        var ID = $(this).val();
        if (ID) {
            $.ajax({
                url: 'ajax_file/get_city_byCountry.php',
                type: 'POST',
                data: { sel_id: ID },
                success: function (response) {
                    if(response.success == true){
                        $('#CityId').html(response.data);
                    }
                }
            });
        } else {
            $('#CityId').html('<option value="">Select City</option>');
        }
    });
    // get area by city
    $('#CityId').on('change', function () {
        debugger
        var ID = $(this).val();
        var countryId = $('#CountryId').val();
        if (ID) {
            $.ajax({
                url: 'ajax_file/get_area_byCity.php',
                type: 'POST',
                data: { sel_id: ID, countryID: countryId },
                success: function (response) {
                    if(response.success == true){
                        $('#AreaId').html(response.data);
                        $('#TimeslotId').html(response.timeslote);
                    }
                }
            });
        } else {
            $('#AreaId').html('<option value="">Select Area</option>');
        }
    });
        // Check the initial state when the page loads
    toggleDivState();
    // When the "Default" radio button is clicked
    $('#r11').change(function() {
        toggleDivState();
    });
    // When the "Day Specific" radio button is clicked
    $('#r22').change(function() {
        toggleDivState();
    });
    // Function to show/hide the div based on the selected radio button
    function toggleDivState() {
        if ($('#r22').is(':checked')) {
            $('#daysoptionid').show();  // Show the div if "Day Specific" is selected
        } else {
            $('#daysoptionid').hide();  // Hide the div if "Default" is selected
        }
    }
    $('#discountbox').addClass('d-none');
    $('#couponbtn').click(function (e) {
        let couponcode = $('#couponcodeid').val();
        let mealplanID = '<?= $mealplanId ?>';
        // Validate input
            if (couponcode === '') {
                $('#copouncodemsgId').html("Coupon Code is required.");
                return;
            }
       
            if(couponcode != "" && mealplanID != ""){
                $('#couponloader').show();
                $.ajax({
                    url: 'ajax_file/coupon_code.php',
                    type: 'POST',
                    data: { couponcode: couponcode, meal_planID: mealplanID },
                    success: function (response) {
                        if(response.success == true){
                            $('#copouncodemsgId').html(response.message);
                            let subtotal = $('#sbtotal').val();
                            let discountamount = parseFloat(subtotal) * parseFloat(response.cpnamt) / 100; 
                            let finalTotal = parseFloat(subtotal) - discountamount;
                            $('#package-subtotal').text(finalTotal);
                            $('#discountbox').removeClass('d-none');
                            let fndisc = discountamount+' ('+response.cpnamt+'%)';
                            $('#discountPrice').text(fndisc);
                            //$('#discPercId').text('('+response.cpnamt+')%');
                            $('#payamountid').text('Pay AED '+finalTotal);
                            $('#final_payment').val(finalTotal);
                            $('#applied_coupon').val(couponcode);
                        }else{
                            $('#copouncodemsgId').html(response.message);
                            let subtotal = $('#sbtotal').val();
                            $('#package-subtotal').text(subtotal);
                            $('#payamountid').text('Pay AED '+subtotal);
                            $('#discountbox').addClass('d-none');
                            $('#final_payment').val(subtotal);
                            $('#applied_coupon').val('');
                        }
                        $('#couponloader').hide();
                    }
                });
            }
    });
</script>
<script>
$(document).ready(function() {
    $('#expiry_date').on('input', function() {
        let value = $(this).val();
        
        // Allow only numeric input and add the slash after two digits
        value = value.replace(/[^\d]/g, ''); // Remove any non-numeric characters

        if (value.length >= 3) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        
        $(this).val(value); // Set the formatted value back into the input field
    });
});

$(document).ready(function() {
    $('#card_number').on('input', function() {
        let value = $(this).val();
        
        // Remove non-numeric characters
        value = value.replace(/\D/g, '');
        
        // Add space after every 4 digits
        if (value.length > 4) {
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
        }

        $(this).val(value); // Set the formatted value back into the input field
    });
});

$('#profileForm').on('submit', function() {
        $('#payloader').show(); // Show loader before form submits
        $('#paybtn')
            .css('opacity', '0.5')         // Set opacity to 0.5 (adjust as needed)
            .css('pointer-events', 'none');
    });
</script>
<?php
include './partials/footer.php';
?>
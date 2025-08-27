<?php 
include 'partials/header.php';
//require_once 'services/commonService.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$paramData = ['PersonId' => $_SESSION['user']['UserId']];

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

?>
    <?php
    $errors = [];
$error_msg = '';
$error_pass_msg = "";
$success_msg = '';
$success_pass_msg = '';
$email_form_errors_msg = '';
$email_form_success_msg = '';
if(isset($_POST["emailsubmit"]) && $_POST["emailsubmit"] == "emailform"){
    $profileEmailId = $_POST['profileEmailId'] ?? '';
    if (empty($profileEmailId)) {
        $errors['profileEmailId'] = "Email is required.";
    } elseif (!filter_var($profileEmailId, FILTER_VALIDATE_EMAIL)) {
        $errors['profileEmailId'] = 'Invalid email format.';
    }
     if (empty($errors)) {
        $formData = [
        'PersonId' => $_SESSION['user']['UserId'], 
        'EmailId' => $profileEmailId,
        'ModeOfEntry' => 'website'
        ];

        $response = UpdatePersonEmail($formData);
        $respcode = $response['ValidationDetails']['StatusCode'] ?? $response['StatusCode'];
        if($respcode == 200){
            //$email_form_success_msg = $response['ValidationDetails']['StatusMessage'];
            //$email_form_success_msg = "Email Successfully Updated";
            $_SESSION['form_succ'] = 1;
           // header('Location:account_settings.php');
        }else{
            $_SESSION['form_succ'] = 2;
            $email_form_errors_msg = $response['ValidationDetails']['StatusMessage'] != '' ? $response['ValidationDetails']['StatusMessage'] : $response['ValidationDetails']['ErrorDetails'][0]['ErrorMessageDescription'];
        }
    }
}

$personal_form_errors_msg = '';
$personal_form_success_msg = '';
if(isset($_POST["personalsubmit"]) && $_POST["personalsubmit"] == "personalform"){
    $DateOfBirth = $_POST['DateOfBirth'] ?? '';
    $Gender = $_POST['Gender'] ?? '';
    if (empty($DateOfBirth)) {
        $errors['DateOfBirth'] = "Date of Birth is required.";
    }
    if (empty($Gender)) {
        $errors['Gender'] = "Please select Gender.";
    }
     if (empty($errors)) {
        $dateObject = DateTime::createFromFormat('Y-m-d', $DateOfBirth);
        $DOBDate = $dateObject->format('Y-m-d');
         $formData = [
        'PersonId' => $_SESSION['user']['UserId'], 
        'DateOfBirth' => $DOBDate,
        'Gender' => $Gender,
        'EmailId' => $clientprofileData[0]['EmailId'],
        'FirstName' => $clientprofileData[0]['FirstName'],
        'MiddleName' => $clientprofileData[0]['MiddleName'],
        'LastName' => $clientprofileData[0]['LastName'],
        'MobileNumber' => $clientprofileData[0]['MobileNumber'],
        'ModeOfEntry' => 'website',
        ];
        $response = UpdatePersonDetails($formData);

        $respcode = $response['ValidationDetails']['StatusCode'] ?? $response['StatusCode'];
        
        if($respcode == 200){
            //$personal_form_success_msg = "Personal Information Successfully Updated";
            $_SESSION['form_succ'] = 1;
            //header('Location:account_settings.php');
        }else{
            $_SESSION['form_succ'] = 2;
            $personal_form_errors_msg = $response['ValidationDetails']['StatusMessage'] != '' ? $response['ValidationDetails']['StatusMessage'] : $response['ValidationDetails']['ErrorDetails'][0]['ErrorMessageDescription'];
        }
    }else{
        $_SESSION['form_succ'] = 2;
    }
}

if(isset($_POST["passwordsubmit"]) && $_POST["passwordsubmit"] == "passwordform"){
    $NewPassword = $_POST['NewPassword'] ?? '';
    $ConfirmPassword = $_POST['ConfirmPassword'] ?? '';

    if (empty($NewPassword)){
        $errors['NewPassword'] = 'New Password is required.';
    }else if(strlen($NewPassword) < 6){
        $errors['NewPassword'] = 'New Password must be at least 6 characters.';
    }
    if (empty($ConfirmPassword)){
        $errors['ConfirmPassword'] = 'Confirm Password is required.';
    }else if($NewPassword !== $ConfirmPassword){
        $errors['ConfirmPassword'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $formData = [
        'PersonId' => $_SESSION['user']['UserId'], 
        'NewPassword' => $NewPassword,
        'ConfirmPassword' => $ConfirmPassword
        ];
        
        $response = ChangeOrResetPassword($formData);
        $respcode = $response['ValidationDetails']['StatusCode'] ?? $response['StatusCode'];
        if($respcode == 200){
            $success_pass_msg = "Password Successfully Changed";
            $NewPassword = '';
            $ConfirmPassword = '';
            $_SESSION['form_succ'] = 1;
            //header('Location: account_settings.php');
        }else{
            $_SESSION['form_succ'] = 2;
            $error_pass_msg = "Error";
        }
    }else{
        $_SESSION['form_succ'] = 2;
    }
}
if(isset($_POST["submit"]) && $_POST["submit"] == "profileform"){
   
    $spinner = true;
    $FirstName = filter_var($_POST['FirstName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $LastName = filter_var($_POST['LastName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $MiddleName = filter_var($_POST['MiddleName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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
    
    if($IsDefault == "false" && $IsSun == "" && $IsMon == "" && $IsTue == "" && $IsWed == "" && $IsThu == "" && $IsFri == "" && $IsSat == ""){
        $errors['IsDefault'] = "Please select atleast 1 day";
    }

    if (empty($FirstName)) $errors['FirstName'] = "First name is required.";
    if (empty($LastName)) $errors['LastName'] = "Last name is required.";
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

    $AlterAddress1 = $_POST['AlterAddress1'] ?? '';
    $AlterAddress2 = $_POST['AlterAddress2'] ?? '';
    $AlterAddressEmailId = $_POST['AlterAddressEmailId'] ?? '';
    $AlterAreaId = $_POST['AlterAreaId'] ?? '';
    $AlterCityId = $_POST['AlterCityId'] ?? '';
    $AlterCountryId = $_POST['AlterCountryId'] ?? '';
    $AlterTimeslotId = $_POST['AlterTimeslotId'] ?? '';
    $AlterStartTime = $_POST['AlterStartTime'] ?? '';
    $AlterEndTime = $_POST['AlterEndTime'] ?? '';
    $AlterRecipientNotes = $_POST['AlterRecipientNotes'] ?? '';
    $AlterIsShipAddress = $_POST['AlterIsShipAddress'] ?? '';
    $AlterIsSun = $_POST['AlterIsSun'] ?? '';
    $AlterIsMon = $_POST['AlterIsMon'] ?? '';
    $AlterIsTue = $_POST['AlterIsTue'] ?? '';
    $AlterIsWed = $_POST['AlterIsWed'] ?? '';
    $AlterIsThu = $_POST['AlterIsThu'] ?? '';
    $AlterIsFri = $_POST['AlterIsFri'] ?? '';
    $AlterIsSat = $_POST['AlterIsSat'] ?? '';
    $AlterIsDefault = $_POST['AlterIsDefault'] ?? '';
    $IsAlterAddress = $_POST['IsAlterAddress'] ?? '';
    //$AlterMobileNumber = $_POST['AlterMobileNumber'] ?? '';

    if($IsAlterAddress){
        if (empty($AlterAddressEmailId)) {
            $errors['AlterAddressEmailId'] = "Email is required.";
        } elseif (!filter_var($AlterAddressEmailId, FILTER_VALIDATE_EMAIL)) {
            $errors['AlterAddressEmailId'] = 'Invalid email format.';
        }
        if (empty($AlterAddress1)) $errors['AlterAddress1'] = "Address1 is required.";
        if (empty($AlterAreaId)) $errors['AlterAreaId'] = 'Please select area.';
        if (empty($AlterCityId)) $errors['AlterCityId'] = 'Please select city.';
        if (empty($AlterCountryId)) $errors['AlterCountryId'] = 'Please select country.';
        if (empty($AlterTimeslotId)) $errors['AlterTimeslotId'] = 'Please select time slot.';
        
        if($AlterIsDefault == "false" && $AlterIsSun == "" && $AlterIsMon == "" && $AlterIsTue == "" && $AlterIsWed == "" && $AlterIsThu == "" && $AlterIsFri == "" && $AlterIsSat == ""){
           $errors['AlterIsDefault'] = "Please select atleast 1 day";
        }
    }
    
    if (empty($errors)) {
        $result = getItemByOrderNo($allTimeslots, $TimeslotId);
        if ($result) {
            $StartTime = $result['StartTime'];
            $EndTime = $result['EndTime'];
        }

        
        $result2 = getItemByOrderNo($allTimeslots, $AlterTimeslotId);
        if ($result2) {
            $AlterStartTime = $result2['StartTime'];
            $AlterEndTime = $result2['EndTime'];
        }
        
        $formData = [
        'PersonId' => $_SESSION['user']['UserId'], 
        'FirstName' => $FirstName,
        'MiddleName' => $MiddleName,
        'LastName' => $LastName,
        'Address1' => $Address1,
        'Address2' => $Address2,
        'AreaId' => $AreaId,
        'CityId' => $CityId,
        'CountryId' => $CountryId,
        'TimeslotId' => $TimeslotId,
        'StartTime' => $StartTime,
        'EndTime' => $EndTime,
        'EmailId' => $clientprofileData[0]['EmailId'] != '' ? $clientprofileData[0]['EmailId'] : "null",
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
        'AddressEmailId' => $AddressEmailId,
        'AlterAddress1' => $AlterAddress1,
        'AlterAddress2' => $AlterAddress2,
        'AlterAddressEmailId' => $AlterAddressEmailId,
        'AlterAreaId' => $AlterAreaId,
        'AlterCityId' => $AlterCityId,
        // 'AlterMobileNumber' => $AlterMobileNumber,
        'AlterCountryId' => $AlterCountryId,
        'AlterTimeslotId' => $AlterTimeslotId,
        'AlterStartTime' => $AlterStartTime,
        'AlterEndTime' => $AlterEndTime,
        'AlterRecipientNotes' => $AlterRecipientNotes,
        'AlterIsDefault' => $AlterIsDefault,
        'IsAlterAddress' => $IsAlterAddress == 1 ? 'true' : 'false',
        'AlterIsSun' => $AlterIsSun == 1 ? 'true' : 'false', 
        'AlterIsMon' => $AlterIsMon == 1 ? 'true' : 'false', 
        'AlterIsTue' => $AlterIsTue == 1 ? 'true' : 'false', 
        'AlterIsWed' => $AlterIsWed == 1 ? 'true' : 'false', 
        'AlterIsThu' => $AlterIsThu == 1 ? 'true' : 'false', 
        'AlterIsFri' => $AlterIsFri == 1 ? 'true' : 'false', 
        'AlterIsSat' => $AlterIsSat == 1 ? 'true' : 'false',
        'AlterIsShipAddress' => $AlterIsShipAddress == 1 ? 'true' : 'false',
        'DateOfBirth' => $clientprofileData[0]['DateOfBirth'] != '' ? $clientprofileData[0]['DateOfBirth'] : "null",
        'Gender' => $clientprofileData[0]['Gender'] != '' ? $clientprofileData[0]['Gender'] : "null"
        ];
        
        
        $response = UpdateClientProfile($formData);
        
        if($response['ValidationDetails']['StatusCode'] == 200){
            //$success_msg = "Address Successfully Updated";
            $_SESSION['form_succ'] = 1;
            //header('Location:account_settings.php');
        }else{
            $_SESSION['form_succ'] = 2;
            $error_msg = $response['ValidationDetails']['StatusMessage'] != '' ? $response['ValidationDetails']['StatusMessage'] : $response['ValidationDetails']['ErrorDetails'][0]['ErrorMessageDescription'];
        }
    }else{
        $_SESSION['form_succ'] = 2;
    }
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
                    <h4 class="h2">Profile Settings</h4>
                    <p>Update your personal details, photo, and preferences here.</p>
                </div>
                <!-- <div>
                    <div class="bredcums">
                        <a href="<?= ROOT_URL ?>profile.php">Profile</a>/ Settings
                    </div>
                </div> -->
            </div>
        </div>
    </section>

</style>
    <!-- ===========SIGNATURE DISH=========== -->
    <section class="padding-bottom padding-top">
        <div class="container form">
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
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <form method="POST" id="emailUpdateForm">
                        <h5 class="text-uppercase text-brand d-flex mb-4">Update Email address</h5>
                        <div class="col-lg-12">
                        <?php if (isset($email_form_errors_msg)): ?>
                            <small class="text-danger"><?= $email_form_errors_msg; ?></small>
                        <?php endif; ?>
                        <?php if (isset($email_form_success_msg)): ?>
                            <small class="text-success"><?= $email_form_success_msg; ?></small>
                        <?php endif; ?>
                        </div>
                        <div>
                            <label for="" class="required-label">Email Address</label>
                            <input type="text" name="profileEmailId" class="form-control w-100" id="EmailId" placeholder="Enter email address" value="<?= $profileEmailId ?? $clientprofileData[0]['EmailId'] ?>">
                                <?php if (isset($errors['profileEmailId'])): ?>
                                    <small class="text-danger"><?= $errors['profileEmailId']; ?></small>
                                <?php endif; ?>
                        </div>
                        <div class="d-flex mt-4">
                            <button type="submit" name="emailsubmit" class="btn-first w-auto px-4" value="emailform"><span>
                            <svg id="emailloader" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                            Save Update</span>
                            </button>
                            <!-- <a href="#" class="btn-first w-auto px-4">Save Update</a> -->
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    <form method="POST" id="profilePictureUpdateForm">
                        <h5 class="text-uppercase text-brand d-flex mb-4">Update Profile Picture</h5>
                        <div class="upload-wrapper">
                             <?php
                        $base64img = $profilePicture != null ? $profilePicture : ROOT_URL.'/img/defaultImg.jpg';
                            if($profilePicture != null){ ?>
                                <img src="data:image/jpeg;base64,<?= $base64img ?>" alt="" alt="Preview" class="preview-img" id="preview">
                            <?php }else{ ?>
                                <img src="<?= $base64img ?>" alt="" alt="Preview" class="preview-img" id="preview">
                            <?php }
                            ?>
                            <div>
                                <label for="file-upload" class="upload-btn">Upload
                                    <svg id="profilePicloader" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                                </label>
                                <input type="file" accept="image/*" id="file-upload" class="file-input" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <form method="POST" id="passwordUpdateForm">
                <div class="row mt-5">
                    <div class="col-lg-12">
                        <h5 class="text-uppercase text-brand d-flex mb-4">UPDATE Password </h5>
                    </div>
                    <div class="col-lg-12">
                    <?php if (isset($error_pass_msg)): ?>
                        <small class="text-danger"><?= $error_pass_msg; ?></small>
                    <?php endif; ?>
                    <?php if (isset($success_pass_msg)): ?>
                        <small class="text-success"><?= $success_pass_msg; ?></small>
                    <?php endif; ?>
                    </div>
                    <div class="col-lg-6">
                        <div>
                            <label for="" class="required-label">New Password</label>
                            <input type="password" name="NewPassword" class="form-control w-100" id="NewPassword" placeholder="Enter New Password" value="<?= $NewPassword ?? '' ?>">
                            <?php if (isset($errors['NewPassword'])): ?>
                                        <small class="text-danger"><?= $errors['NewPassword']; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div>
                            <label for="" class="required-label">Confirm New Password</label>
                            <input type="password" name="ConfirmPassword" class="form-control w-100" id="ConfirmPassword" placeholder="Enter Confirm Password" value="<?= $ConfirmPassword ?? '' ?>">
                            <?php if (isset($errors['ConfirmPassword'])): ?>
                                        <small class="text-danger"><?= $errors['ConfirmPassword']; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="d-flex mt-4">
                            <button type="submit" name="passwordsubmit" class="btn-first w-auto px-4" value="passwordform"><span>
                                <svg id="passwordloader" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                                Save Update</span></button>
                        </div>
                    </div>
                </div>
            </form>
            <form method="POST" id="persondetailUpdateForm">
                 <div class="row mt-5">
                <div class="col-lg-12">
                    <h5 class="text-uppercase text-brand d-flex mb-4">Personal Information</h5>
                    <div class="col-lg-12">
                        <?php if (isset($personal_form_errors_msg)): ?>
                            <small class="text-danger"><?= $personal_form_errors_msg; ?></small>
                        <?php endif; ?>
                        <?php if (isset($personal_form_success_msg)): ?>
                            <small class="text-success"><?= $personal_form_success_msg; ?></small>
                        <?php endif; ?>
                        </div>
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div>
                        <label for="">Date of Birth</label>
                        <div class="position-relative">
                            <input type="text" name="DateOfBirth" value="<?= $DateOfBirth ?? $clientprofileData[0]['DateOfBirth'] ?>" id="datepickerDOB" class="form-control" placeholder="Select date" />
                            <i class="ti ti-calendar-event"></i>
                            <?php if (isset($errors['DateOfBirth'])): ?>
                                    <small class="text-danger"><?= $errors['DateOfBirth']; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label for="" class="required-label">Gender</label>
                    <div class="d-flex gap-3 align-items-center mt-3">
                       
                        <label class="custom-radio">
                            <input type="radio" name="Gender" value="M" <?php if ($clientprofileData[0]['Gender'] === "M") echo 'checked'; ?> />
                            <span class="radio-label">Male</span>
                        </label>
                            
                        <label class="custom-radio">
                            <input type="radio" name="Gender" value="F" <?php if ($clientprofileData[0]['Gender'] === "F") echo 'checked'; ?> />
                            <span class="radio-label">Female</span>
                        </label>
                        <?php if (isset($errors['Gender'])): ?>
                                    <small class="text-danger"><?= $errors['Gender']; ?></small>
                            <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="d-flex mt-4">
                        <button type="submit" name="personalsubmit" class="btn-first w-auto px-4" value="personalform"><span>
                            <svg id="personalloader" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                            Save Update</span></button>
                        <!-- <a href="#" class="btn-first w-auto px-4">Save Update</a> -->
                    </div>
                </div>
            </div>
            </form>
            <form enctype="multipart/form-data" method="POST" id="profileForm">
                <div class="row mt-5">
                    <div class="col-lg-12">
                        <h5 class="text-uppercase text-brand d-flex mb-4">UPDATE your address</h5>
                        <?php if (isset($error_msg)): ?>
                        <small class="text-danger"><?= $error_msg; ?></small>
                    <?php endif; ?>
                    <?php if (isset($success_msg)): ?>
                        <small class="text-success"><?= $success_msg; ?></small>
                    <?php endif; ?>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <div>
                            <label for="FirstName" class="required-label">First Name</label>
                            <input type="text" name="FirstName" class="form-control w-100" id="FirstName" placeholder="Enter First Name" value="<?= $FirstName ?? $clientprofileData[0]['FirstName'] ?>">
                            <?php if (isset($errors['FirstName'])): ?>
                                        <small class="text-danger"><?= $errors['FirstName']; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <div>
                            <label for="MiddleName" class="">Middle Name</label>
                            <input type="text" name="MiddleName" class="form-control w-100" id="MiddleName" placeholder="Enter Middle Name" value="<?= $MiddleName ?? $clientprofileData[0]['MiddleName'] ?>">
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <div>
                            <label for="LastName" class="required-label">Last Name</label>
                            <input type="text" name="LastName" class="form-control w-100" id="lastname" placeholder="Enter Last Name" value="<?= $LastName ?? $clientprofileData[0]['LastName'] ?>">
                            <?php if (isset($errors['LastName'])): ?>
                                        <small class="text-danger"><?= $errors['LastName']; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-12 product-book mb-4">
                       <div class="radio-group">
                            <input type="radio" id="r11" name="IsDefault" class="radio-input" value="true"
                                <?php if ($clientprofileData[0]['IsDefault'] === true) echo 'checked'; ?>>
                            <label for="r11" class="radio-label">Default</label>

                            <input type="radio" id="r22" name="IsDefault" class="radio-input" value="false"
                                <?php if ($clientprofileData[0]['IsDefault'] === false) echo 'checked'; ?>>
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
                                        <option value="<?php echo $item['CountryId']; ?>" selected>
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
                    <div class="col-lg-12 mb-4">
                        <div>
                            <label for="" class="">Recipient Note</label>
                            <textarea name="RecipientNotes" class="form-control" id="RecipientNotes"><?= $RecipientNotes ?? $clientprofileData[0]['RecipientNotes'] ?></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="IsShipAddress" id="IsShipAddress" class="exclusive-ship" value="1" <?php if ($clientprofileData[0]['IsShipAddress']) echo 'checked'; ?>/>
                            <span class="checkmark"></span>
                            Ship to this address
                        </label>
                    </div>
                </div>
                    <!-- Start Alter address form-->
                     
                <input type="hidden" name="IsAlterAddress" id="IsAlterAddress" value="<?= $IsAlterAddress ?? $clientprofileData[0]['IsAlterAddress'] ?>">
                  <div class="row mt-5" id="alternAddressID" style="display: <?php echo $IsAlterAddress ?? $clientprofileData[0]['IsAlterAddress'] ? 'flex' : 'none' ?>;">
                                <div class="col-lg-12">
                                    <h5 class="text-uppercase text-brand d-flex mb-4">Alternative Address</h5>
                                </div>
                                <div class="col-lg-12 product-book mb-4">
                                    <div class="radio-group">
                                        <input type="radio" id="r11alter" name="AlterIsDefault" class="radio-input" value="true"
                                            <?php if ($clientprofileData[0]['AlterIsDefault'] === true) echo 'checked'; ?>>
                                        <label for="r11alter" class="radio-label">Default</label>

                                        <input type="radio" id="r22alter" name="AlterIsDefault" class="radio-input" value="false"
                                            <?php if ($clientprofileData[0]['AlterIsDefault'] === false) echo 'checked'; ?>>
                                        <label for="r22alter" class="radio-label">Day Specific</label>
                                    </div>
                                     <div class="day-val-error"><?php if (isset($errors['AlterIsDefault'])): ?>
                                                    <small class="text-danger"><?= $errors['AlterIsDefault']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                               
                                <div class="col-lg-12 product-book mb-4" id="alterdaysoptionid" style="display: <?php echo $clientprofileData[0]['AlterIsDefault'] !== true ? 'block' : 'none' ?>;">
                                    <div class="radio-group">
                                        <div class="d-flex align-items-center gap-3">
                                            <label class="custom-checkbox">
                                                <input type="checkbox" name="AlterIsSun" id="AlterIsSun" value="1" <?php if ($clientprofileData[0]['AlterIsSun']) echo 'checked'; ?>/>
                                                <span class="checkmark"></span>
                                                <span>Sun</span>
                                            </label>
                                            <label class="custom-checkbox">
                                                <input type="checkbox" name="AlterIsMon" id="AlterIsMon" value="1" <?php if ($clientprofileData[0]['AlterIsMon']) echo 'checked'; ?>/>
                                                <span class="checkmark"></span>
                                                <span>Mon</span>
                                            </label>
                                            <label class="custom-checkbox">
                                                <input type="checkbox" name="AlterIsTue" id="AlterIsTue" value="1" <?php if ($clientprofileData[0]['AlterIsTue']) echo 'checked'; ?>/>
                                                <span class="checkmark"></span>
                                                <span>Tue</span>
                                            </label>
                                            <label class="custom-checkbox">                                    
                                                <input type="checkbox" name="AlterIsWed" id="AlterIsWed" value="1" <?php if ($clientprofileData[0]['AlterIsWed']) echo 'checked'; ?>/>
                                                <span class="checkmark"></span>
                                                <span>Wen</span>
                                            </label>
                                            <label class="custom-checkbox">
                                                <input type="checkbox" name="AlterIsThu" id="AlterIsThu" value="1" <?php if ($clientprofileData[0]['AlterIsThu']) echo 'checked'; ?>/>
                                                <span class="checkmark"></span>
                                                <span>Thu</span>
                                            </label>
                                            <label class="custom-checkbox">
                                                <input type="checkbox" name="AlterIsFri" id="AlterIsFri" value="1" <?php if ($clientprofileData[0]['AlterIsFri']) echo 'checked'; ?>/>
                                                <span class="checkmark"></span>
                                                <span>Fri</span>
                                            </label>
                                            <label class="custom-checkbox">
                                                <input type="checkbox" name="AlterIsSat" id="AlterIsSat" value="1" <?php if ($clientprofileData[0]['AlterIsSat']) echo 'checked'; ?>/>
                                                <span class="checkmark"></span>
                                                <span>Sat</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div>
                                        <label for="" class="required-label">Address Line 1</label>
                                        <input type="text" name="AlterAddress1" class="form-control w-100" id="AlterAddress1" placeholder="Enter Address Line 1" value="<?= $AlterAddress1 ?? $clientprofileData[0]['AlterAddress1'] ?>">
                                        <?php if (isset($errors['AlterAddress1'])): ?>
                                                    <small class="text-danger"><?= $errors['AlterAddress1']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div>
                                        <label for="" class="">Address Line 2 (Optional)</label>
                                        <input type="text" name="AlterAddress2" class="form-control w-100" id="AlterAddress2" placeholder="Enter Address Line 2" value="<?= $AlterAddress2 ?? $clientprofileData[0]['AlterAddress2'] ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4 d-none">
                                    <div>
                                        <label for="" class="required-label">Country</label>
                                        <select name="AlterCountryId" class="form-select" id="AlterCountryId">
                                            <option value="">Select Country</option>
                                            <?php foreach ($allCountry as $item):
                                            if ($item['IsActive']): 
                                                $AlterCountryId = $AlterCountryId ?? $clientprofileData[0]['AlterCountryId'];
                                            ?>
                                                    <option value="<?php echo $item['CountryId']; ?>" selected>
                                                    <?php echo $item['CountryName']; ?>
                                                </option>
                                            <?php endif; endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['AlterCountryId'])): ?>
                                                    <small class="text-danger"><?= $errors['AlterCountryId']; ?></small>
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
                                        <select name="AlterCityId" class="form-select" id="AlterCityId">
                                            <option value="">Select City</option>
                                            <?php foreach ($allCity as $item):
                                            //if ($item['IsActive']): 
                                                $AlterCityId = $AlterCityId ?? $clientprofileData[0]['AlterCityId'];
                                            ?>
                                                    <option value="<?php echo $item['CityId']; ?>" <?php echo $item['CityId'] == $AlterCityId ? 'selected' : ''; ?>>
                                                    <?php echo $item['CityName']; ?>
                                                </option>
                                            <?php //endif; 
                                            endforeach ?>
                                        </select>
                                        <?php if (isset($errors['AlterCityId'])): ?>
                                                    <small class="text-danger"><?= $errors['AlterCityId']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>                    
                                <div class="col-lg-6 mb-4">
                                    <div>
                                        <?php 
                                        usort($AlterAreas, function ($a, $b) {
                                            return strcmp($a['AreaName'], $b['AreaName']);
                                        });
                                        ?>
                                        <label for="" class="required-label">Area</label>
                                        <?php //echo $clientprofileData[0]['AlterAreaId']; ?>
                                        <select name="AlterAreaId" class="form-select" id="AlterAreaId">
                                            <option value="">Select Area</option>
                                            <?php
                                            
                                            foreach ($AlterAreas as $item):
                                            //if ($item['IsActive']): 
                                            $AlterAreaId = $AlterAreaId ?? $clientprofileData[0]['AlterAreaId']; ?>
                                                    <option value="<?php echo $item['AreaId']; ?>" <?php echo ($item['AreaId'] == $AlterAreaId) ? 'selected' : ''; ?>>
                                                    <?php echo $item['AreaName']; ?>
                                                </option>
                                            <?php //endif;
                                             endforeach ?>
                                        </select>
                                        <?php if (isset($errors['AlterAreaId'])): ?>
                                                    <small class="text-danger"><?= $errors['AlterAreaId']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div>
                                        <?php 
                                        $AlterTimeslotIdRec = $AlterTimeslotId ?? $clientprofileData[0]['AlterTimeslotId']; ?>
                                        <label for="" class="required-label">Time Slot</label>
                                        <select name="AlterTimeslotId" id="AlterTimeslotId" class="form-select" id="">
                                            <option value="">Select Time</option>
                                            <?php 
                                            foreach ($allTimeslots as $item):
                                            if ($item['IsActive']): 
                                                $start = date("g:ia", strtotime($item['StartTime'])); // e.g. 04:00pm
                                                $end = date("g:ia", strtotime($item['EndTime'])); // e.g. 04:00pm
                                                ?>
                                                <option value="<?php echo $item['OrderNo']; ?>" <?php echo ($item['OrderNo'] == $AlterTimeslotIdRec) ? 'selected' : ''; ?>> <?php echo $start.' -- '.$end; ?> </option>
                                            <?php endif; endforeach; ?>
                                        </select>
                                        <!-- <input type="text" name="AlterStartTime" id="AlterStartTime" value="<?= $AlterStartTime ?? $clientprofileData[0]['AlterStartTime'] ?>">
                                        <input type="text" name="AlterEndTime" id="AlterEndTime" value="<?= $AlterEndTime ?? $clientprofileData[0]['AlterEndTime'] ?>"> -->
                                        <?php if (isset($errors['AlterTimeslotId'])): ?>
                                                    <small class="text-danger"><?= $errors['AlterTimeslotId']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div>
                                        <label for="" class="required-label">Email ID</label>
                                        <input type="text" name="AlterAddressEmailId" class="form-control w-100" id="AlterAddressEmailId" placeholder="name@example.com" value="<?= $AlterAddressEmailId ?? $clientprofileData[0]['AlterAddressEmailId'] ?>">
                                        <?php if (isset($errors['AlterAddressEmailId'])): ?>
                                            <small class="text-danger"><?= $errors['AlterAddressEmailId']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div>
                                        <label for="" class="required-label">Phone</label>
                                        <input type="text" name="AlterMobileNumber" class="form-control w-100" id="AlterMobileNumber" placeholder="Enter Phone" 
                                        value="">
                                        <?php if (isset($errors['AlterMobileNumber'])): ?>
                                                    <small class="text-danger"><?= $errors['AlterMobileNumber']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-4">
                                    <div>
                                        <label for="" class="">Recipient Note</label>
                                        <textarea name="AlterRecipientNotes" class="form-control" id="AlterRecipientNotes"><?= $AlterRecipientNotes ?? $clientprofileData[0]['AlterRecipientNotes'] ?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <label class="custom-checkbox">
                                        <input type="checkbox" name="AlterIsShipAddress" id="AlterIsShipAddress" class="exclusive-ship" value="1" <?php if ($clientprofileData[0]['AlterIsShipAddress']) echo 'checked'; ?>/>
                                        <span class="checkmark"></span>
                                        Ship to this address
                                    </label>
                                </div>
                    </div>
                    <!-- End Alter address form-->
                    <div class="row mt-5">
                        <div class="col-lg-12">
                        <div class="d-flex justify-content-end gap-3 align-items-center">
                            <a href="javascript:void(0)" class="btn-link d-flex gap-2 w-auto px-4" id="addnewalterAddr"><i class="ti ti-plus"></i>Add New Address</a>
                            <div class="d-flex light-bg">
                                <button type="submit" name="submit" class="btn-fancy" value="profileform"><span>
                                <svg id="loader" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                                Save Update</span>
                                <i class="ti ti-arrow-up-right"></i></button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
    $(document).ready(function() {
        $('.exclusive-ship').on('change', function() {
            if ($(this).is(':checked')) {
            $('.exclusive-ship').not(this).prop('checked', false);
            }
        });
    });

    $('#profileForm').on('submit', function() {
        $('#loader').show(); // Show loader before form submits
    });
    $('#passwordUpdateForm').on('submit', function() {
        $('#passwordloader').show(); // Show loader before form submits
    });
    $('#emailUpdateForm').on('submit', function() {
        $('#emailloader').show(); // Show loader before form submits
    });
    $('#persondetailUpdateForm').on('submit', function() {
        $('#personalloader').show(); // Show loader before form submits
    });    
    
    var profileData = <?php echo json_encode($clientprofileData); ?>;
    $('#file-upload').on('change', function () {

        let formData = new FormData();
        let file = this.files[0];
        if (file) {
            formData.append('profile_picture', file);
            //formData.append('profiledata', '<?php  $clientprofileData; ?>'); // PHP variable passed here
            formData.append('profiledata', JSON.stringify(profileData)); // convert JS object to string
            $('#profilePicloader').show();
            $.ajax({
                url: 'ajax_file/upload-profile-picture.php', // PHP handler
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // Optional: update preview image
                    //$('#preview').attr('src', response);
                    $('#preview').attr('src', URL.createObjectURL(file));
                    $('#profilePicloader').hide();
                },
                error: function () {
                    alert('Failed to upload image.');
                    $('#profilePicloader').hide();
                }
            });
        }
    });

    // get city by country
    $('#CountryId').on('change', function () {
        
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
    // get Alter city by country
    $('#AlterCountryId').on('change', function () {
        
        var ID = $(this).val();
        if (ID) {
            $.ajax({
                url: 'ajax_file/get_city_byCountry.php',
                type: 'POST',
                data: { sel_id: ID },
                success: function (response) {
                    if(response.success == true){
                        $('#AlterCityId').html(response.data);
                    }
                }
            });
        } else {
            $('#AlterCityId').html('<option value="">Select City</option>');
        }
    });
    // get area by city
    $('#AlterCityId').on('change', function () {        
        var ID = $(this).val();
        var countryId = $('#AlterCountryId').val();
        if (ID) {
            $.ajax({
                url: 'ajax_file/get_area_byCity.php',
                type: 'POST',
                data: { sel_id: ID, countryID: countryId },
                success: function (response) {
                    if(response.success == true){
                        $('#AlterAreaId').html(response.data);
                        $('#AlterTimeslotId').html(response.timeslote);
                    }
                }
            });
        } else {
            $('#AlterAreaId').html('<option value="">Select Area</option>');
        }
    });
</script>
<script>
if($('#IsAlterAddress').val()){
    $('#addnewalterAddr').html('<i class="ti ti-minus"></i>Remove Address');
}

$('#addnewalterAddr').click(function() {
    if ($(this).text().trim() === 'Add New Address') {
        $('#IsAlterAddress').val(1);
        $(this).html('<i class="ti ti-minus"></i>Remove Address');  // Change to "Remove Address"
    } else {
        $('#IsAlterAddress').val('');
        $(this).html('<i class="ti ti-plus"></i>Add New Address');  // Change back to "Add New Address"
    }
    $('#alternAddressID').toggle();
});

$(document).ready(function() {
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

    // Check the initial state when the page loads
    toggleDivStateAlter();
    // When the "Default" radio button is clicked
    $('#r11alter').change(function() {
        toggleDivStateAlter();
    });
    // When the "Day Specific" radio button is clicked
    $('#r22alter').change(function() {
        toggleDivStateAlter();
    });
    // Function to show/hide the div based on the selected radio button
    function toggleDivStateAlter() {
        
        if ($('#r22alter').is(':checked')) {
            $('#alterdaysoptionid').show();  // Show the div if "Day Specific" is selected
        } else {
            $('#alterdaysoptionid').hide();  // Hide the div if "Default" is selected
        }
    }

    
});

flatpickr("#datepickerDOB", {
    dateFormat: "Y-m-d",
    maxDate: "today"  // disables all future dates
});
</script>
<?php
include './partials/footer.php';
?>
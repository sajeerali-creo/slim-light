<?php
//require_once 'services/apiService.php';
require_once __DIR__ . '/apiService.php'; // ✅ CORRECT

function fetchUserProfile($data) {
    return apiRequest('ClientProfile/GetClientProfile', 'GET', $data);
}

function GetArea() {
    return apiRequest('Area/GetArea', 'GET');
}
function GetAreaByCity($data) {
    return apiRequest('Area/GetAreaByCity', 'GET', $data);
}
function GetCity() {
    return apiRequest('City/GetCity', 'GET');
}
function GetCityByCountry($data) {
    return apiRequest('City/GetCityByCountry', 'GET', $data); 
}
function GetClientPackageDetailsByDate($data) {
    return apiRequest('MealPlan/GetClientPackageDetailsByDate', 'GET', $data);
}
function GetMealPlanWeekByDate($data) {
    return apiRequest('MealPlan/GetMealPlanWeekByDate', 'GET', $data);
}

function GetReceiptDetails($data) {
    return apiRequest('ClientSubscription/GetReceiptDetails', 'GET', $data); 
}
function GetReceiptNos($data) {
    return apiRequest('ClientSubscription/GetReceiptNos', 'GET', $data); 
}
function GetReceiptPdf($data) {
    return downloadPdfViaGet('ClientSubscription/GetReceiptPdf', $data); 
}
function GetCountry() {
    return apiRequest('Country/GetCountry', 'GET');
}
function fetchLocations() {
    return apiRequest('Location/GetLocation', 'GET');
}
function UpdateClientProfile($data) {
    return apiRequest('ClientProfile/UpdateClientProfile', 'POST', $data);
}
function UpdatePersonProfilePicture($data){
    return apiRequest('ClientProfile/UpdatePersonProfilePicture', 'POST', $data);
}

function UpdatePersonEmail($data) {
    return apiRequest('ClientProfile/UpdatePersonEmail', 'POST', $data);
}
function UpdatePersonDetails($data) {
    return apiRequest('ClientProfile/UpdatePersonDetails', 'POST', $data);
}   
function ChangeOrResetPassword($data) {
    return apiRequest('ChangePassword/ChangeOrResetPassword', 'POST', $data);
}
function GetClientProfileByEmailId($data) {
    return apiRequest('ClientProfile/GetClientProfileByEmailId', 'GET', $data);
}
function GetDietician() {
    return apiRequest('Dietician/GetDietician', 'GET');
}
function GetAppointmentReason() {
    return apiRequest('AppointmentReason/GetAppointmentReason', 'GET');
}
function GetAppoinmentDetails($data){
    return apiRequest('ClientAppoinment/GetAppoinmentDetails', 'GET',$data);
}
function GetTrackDelivery($data){
    return apiRequest('MealPlan/GetTrackDelivery', 'GET',$data);
}
function GetDeliveryTimeSlot() {
    return apiRequest('TimeSlot/GetDeliveryTimeSlot', 'GET');
}
function GetDeliveryTimeSlotByCity($data) {
    return apiRequest('TimeSlot/GetDeliveryTimeSlotByCity', 'GET',$data);
}
function GetDieticianAvailableSlots($data) {
    return apiRequest('DieticianDutyRoster/GetDieticianAvailableSlots', 'GET',$data);
}
function GetClientMenu($data){
    return apiRequest('MealPlan/GetClientMenu', 'GET',$data);
}
function UpdateClientMenuPlan($data){
    return apiRequest('MealPlan/UpdateClientMenuPlan', 'POST',$data);
}
function GetMealsMaster(){
    return apiRequest('MealPlan/GetMealsMaster', 'GET');
}
function GetAlternativeMealMastersByMealType($data){
    return apiRequest('MealPlan/GetAlternativeMealMastersByMealType', 'GET', $data);
}


function GetClientFullHealthProfile($data) {
    return apiRequest('ClientHealthProfile/GetClientFullHealthProfile', 'GET', $data);
}
function GetClientHealthProfile($data) {
    return apiRequest('ClientHealthProfile/GetClientHealthProfile', 'GET', $data);
}
function GetBMIStatus($data) {
    return apiRequest('ClientHealthProfile/GetBMIStatus', 'GET', $data);
}

function SaveHealthProfile($data) {
    return apiRequest('ClientHealthProfile/SaveHealthProfile', 'POST', $data);
}
function CreateAppointment($data) {
    return apiRequest('ClientAppoinment/CreateAppointment', 'POST', $data);
}
function GetCurrentClientSubscription($data) {
    return apiRequest('ClientSubscription/GetCurrentClientSubscription', 'GET', $data);
}
function GetClientSubscriptionList($data) {
    return apiRequest('ClientSubscription/GetClientSubscriptionList', 'GET', $data);
}

function GetClientSubscriptionCurrentStatus($data) {
    return apiRequest('ClientSubscription/GetClientSubscriptionCurrentStatus', 'GET', $data);
}

function StartPlanner($data) {
    return apiRequest('Package/StartPlanner', 'POST', $data);
}
function StopPlanner($data) {
    return apiRequest('Package/StopPlanner', 'POST', $data);
}
function ResumePlanner($data) {
    return apiRequest('Package/ResumePlanner', 'POST', $data);
}

// Package API
function GetPackageGroupList() {
    return apiRequest('Package/GetPackageGroupList', 'GET');
}
function GetPackageList() {
    return apiRequest('Package/GetPackageList', 'GET');
}
function GetPackageByGroup($data) {
    return apiRequest('Package/GetPackageByGroup', 'GET', $data);
}
function GetCoolerBagAvailability($data) {
    return apiRequest('CoolerBag/GetCoolerBagAvailability', 'GET', $data);
}
function GetFeesCharges() {
    return apiRequest('FeesCharges/GetFeesCharges', 'GET');
}
function GetDieticianByLocation($data) {
    return apiRequest('Dietician/GetDieticianByLocation', 'GET', $data);
}
function safeLimitString($string, $limit = 50) {
    $decoded = html_entity_decode(stripslashes($string));
    $clean = strip_tags($decoded);

    if (strlen($clean) > $limit) {
        return substr($clean, 0, $limit) . '...';
    } else {
        return $clean;
    }
}

function convertDateFormate($date){
    $date = DateTime::createFromFormat('d/m/Y', $date);
    // Set time to midnight
    $date->setTime(0, 0, 0);
    // Format as ISO 8601 without timezone or microseconds
    return $formatted = $date->format('Y-m-d\TH:i:s');
}

function convertOnesToTrue($array) {
    foreach ($array as $key => $value) {
        // If it's a nested array, recurse
        if (is_array($value)) {
            $array[$key] = convertOnesToTrue($value);
        } elseif ($value === 1 || $value === '1') {
            $array[$key] = true;
        }
    }
    return $array;
}
<?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error'];

    $LocationCode = $_POST['LocationCode'] ?? '';
    $DieticianCode = $_POST['DieticianCode'] ?? '';
    $AppointmentReasonId = $_POST['AppointmentReasonId'] ?? '';
    $AppointmentDate_val = $_POST['AppointmentDate'] ?? '';
    $date = DateTime::createFromFormat('d/m/Y', $AppointmentDate_val);
    $AppointmentDate = $date->format('Y-m-d');
    $FromTime = $_POST['FromTime'] ?? '';
    $ToTime = $_POST['ToTime'] ?? '';
    if (empty($LocationCode)) {
        $errors['LocationCode'] = "Location is required.";
    }
    if (empty($DieticianCode)) {
        $errors['DieticianCode'] = "Dietician is required.";
    }
    if (empty($AppointmentReasonId)) {
        $errors['AppointmentReasonId'] = "Appointment Reason is required.";
    }
    if (empty($AppointmentDate)) {
        $errors['AppointmentDate'] = "Appointment Date is required.";
    }
    if (empty($LocationCode)) {
        $errors['LocationCode'] = "Location is required.";
    }
    if (empty($FromTime)) {
        $errors['FromTime'] = "From Time is required.";
    }
    if (empty($ToTime)) {
        $errors['ToTime'] = "To Time is required.";
    }
     if (empty($errors)) {
        $formData = [
        'PersonId' => $_SESSION['user']['UserId'], 
        'LocationCode' => $LocationCode,
        'DieticianCode' => $DieticianCode,
        'AppointmentReasonId' => $AppointmentReasonId,
        'AppointmentDate' => $AppointmentDate,
        'FromTime' => $FromTime,
        'ToTime' => $ToTime,
        ];

        $apiresponse = CreateAppointment($formData);

        $respcode = $apiresponse['StatusCode'] ?? $apiresponse['StatusCode'];
        
        if($respcode == 200){
            $response = ['success' => true, 'message' => 'Appointment Successfully Created'];
        }else if($respcode == 400)
        {
            $errors_msg = $apiresponse['ErrorDetails'][0]['ErrorMessageDescription'];
            $response = ['success' => false, 'message' => $errors_msg];
        }
        else
        {
            $errors_msg = $apiresponse['StatusMessage'] != '' ? $apiresponse['StatusMessage'] : $apiresponse['ErrorDetails'][0]['ErrorMessageDescription'];
            $response = ['success' => false, 'message' => $errors_msg];
        }
    }

header('Content-Type: application/json');
echo json_encode($response);
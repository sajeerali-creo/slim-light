<?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error'];

    $PersonId = $_POST['PersonId'] ?? '';
    $NewPassword = $_POST['NewPassword'] ?? '';
    $ConfirmPassword = $_POST['ConfirmPassword'] ?? '';
   
   $formData = [
        'PersonId' => $PersonId, 
        'NewPassword' => $NewPassword,
        'ConfirmPassword' => $ConfirmPassword
        ];
        
        $response = ChangeOrResetPassword($formData);
        $respcode = $response['ValidationDetails']['StatusCode'] ?? $response['StatusCode'];
        if($respcode == 200){
            $success_pass_msg = "Password Successfully Changed";
            $response = ['success' => true, 'message' => $success_pass_msg]; 
            //header('Location: account_settings.php');
        }else{
            $response = ['success' => false, 'message' => 'Invalid']; 
        }

header('Content-Type: application/json');
echo json_encode($response);
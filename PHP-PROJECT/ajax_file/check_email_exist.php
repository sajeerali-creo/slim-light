<?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error'];

    $EmailId = $_POST['EmailId'] ?? '';
   
   $formData = [
        'EmailId' => $EmailId 
        ];
        
        $response = GetClientProfileByEmailId($formData);
        if($response['ValidationDetails']['StatusCode'] == 200){
            if($response['MasterDataList']){
                $response = ['success' => true, 'perId' => $response['MasterDataList'][0]['PersonId'], 'message' => 'Valid']; 
            }else{
                $response = ['success' => false, 'message' => 'Invalid Email address']; 
            }
        }else{
            $response = ['success' => false, 'message' => 'Invalid Email address']; 
        }

header('Content-Type: application/json');
echo json_encode($response);
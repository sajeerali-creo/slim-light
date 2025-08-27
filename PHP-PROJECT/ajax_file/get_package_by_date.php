<?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error', 'found' => 0];

    $sel_date = $_POST['sel_date'] ?? '';
    $Data = [
    'PersonId' => $_SESSION['user']['UserId'],
    'Date' => $sel_date
    ];
    
    $optionData = "";
    $dataresponse = GetClientPackageDetailsByDate($Data);
    
    if (
            isset($dataresponse['ValidationDetails']['StatusCode']) &&
            $dataresponse['ValidationDetails']['StatusCode'] == 200
        ) {
            if($dataresponse['MasterDataList']){
                //$optionData .= '<option value="">Package Name</option>';
                foreach ($dataresponse['MasterDataList'] as $item):                     
                        $optionData .= '<option value="'.$item['PackageID'].'">'.$item['PackageName'].'</option>';
                endforeach;
                $response = ['success' => true, 'data' => $optionData, 'found' => 1]; 
            }else{
                $optionData .= '<option value="">Not Found</option>';
                $response = ['success' => true, 'data' => $optionData, 'found' => 0]; 
            } 
        }else{
            $optionData .= '<option value="">Not Found</option>';
            $response = ['success' => true, 'data' => $optionData, 'found' => 0];
        }

header('Content-Type: application/json');
echo json_encode($response);
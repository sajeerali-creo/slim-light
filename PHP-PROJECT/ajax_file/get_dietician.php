<?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error'];

    $id = $_POST['sel_id'] ?? '';
    $Data = [
    'LocationCode' => $id, 
    'IsActive' => true
    ];
    
    $optionData = "";
    $dataresponse = GetDieticianByLocation($Data);
    
    if (
            isset($dataresponse['ValidationDetails']['StatusCode']) &&
            $dataresponse['ValidationDetails']['StatusCode'] == 200
        ) {
            if($dataresponse['MasterDataList']){
                $optionData .= '<option value="">Select Dietitian</option>';
                foreach ($dataresponse['MasterDataList'] as $item):                     
                    if ($item['IsActive']):
                        $optionData .= '<option value="'.$item['DieticianCode'].'">'.$item['DieticianName'].'</option>';
                    endif;
                endforeach;
                $response = ['success' => true, 'data' => $optionData]; 
            }else{
                $optionData .= '<option value="">Not Found</option>';
                $response = ['success' => true, 'data' => $optionData]; 
            } 
        }

header('Content-Type: application/json');
echo json_encode($response);
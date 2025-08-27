<?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error'];

    $id = $_POST['sel_id'] ?? '';
    $Data = [
    'CountryId' => $id
    ];
    
    $optionData = "";
    $dataresponse = GetCityByCountry($Data);
    
    if (
            isset($dataresponse['ValidationDetails']['StatusCode']) &&
            $dataresponse['ValidationDetails']['StatusCode'] == 200
        ) {
            if($dataresponse['MasterDataList']){
                $allCity = $dataresponse['MasterDataList'];
                usort($allCity, function ($a, $b) {
                    return strcmp($a['AreaName'], $b['AreaName']);
                });
                $optionData .= '<option value="">Select City</option>';
                foreach ($allCity as $item):                     
                        $optionData .= '<option value="'.$item['CityId'].'">'.$item['CityName'].'</option>';
                endforeach;
                $response = ['success' => true, 'data' => $optionData]; 
            }else{
                $optionData .= '<option value="">Not Found</option>';
                $response = ['success' => true, 'data' => $optionData]; 
            } 
        }

header('Content-Type: application/json');
echo json_encode($response);
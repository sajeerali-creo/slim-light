<?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error'];

$id = $_POST['diet_id'] ?? '';
$app_date = $_POST['app_date'] ?? '';

$date = DateTime::createFromFormat('d/m/Y', $app_date);
$month = $date->format('m'); // 07
$year  = $date->format('Y'); // 2025


    $Data = [
    'DieticianCode' => $id, 
    'AppointmentDate' => $date->format('Y-m-d'),
    'Month' => $month,
    "Year" => $year
    ];
    
    $from_optionData = "";
    $to_optionData = "";
    $dataresponse = GetDieticianAvailableSlots($Data);

    if (
            isset($dataresponse['ValidationDetails']['StatusCode']) &&
            $dataresponse['ValidationDetails']['StatusCode'] == 200
        ) {
            //print_r($dataresponse);
            if($dataresponse['MasterDataList']){
                $from_optionData .= '<option value="">Select</option>';
                foreach ($dataresponse['MasterDataList'] as $item):                     
                        $start = date("g:ia", strtotime($item['StartTime']));
                        $from_optionData .= '<option value="'.$item['StartTime'].'">'.$start.'</option>';
                endforeach;

                $to_optionData .= '<option value="">Select</option>';
                foreach ($dataresponse['MasterDataList'] as $item):                     
                        $end   = date("g:ia", strtotime($item['EndTime']));  
                        $to_optionData .= '<option value="'.$item['EndTime'].'">'.$end.'</option>';
                endforeach;
                
                $response = ['success' => true, 'from_data' => $from_optionData, 'to_data' => $to_optionData]; 
            }else{
                $from_optionData .= '<option value="">Not Found</option>';
                $to_optionData .= '<option value="">Not Found</option>';
                $response = ['success' => true, 'from_data' => $from_optionData, 'to_data' => $to_optionData]; 
            } 
        }

header('Content-Type: application/json');
echo json_encode($response);
<?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error'];

    $id = $_POST['sel_id'] ?? '';
    $countryID = $_POST['countryID'] ?? '';
    $Data = [
    'CityId' => $id,
    'CountryId' => $countryID
    ];
    
    $time_optionData = "";
    $timeSlotedataresponse = GetDeliveryTimeSlotByCity($Data);

    if (
            isset($timeSlotedataresponse['ValidationDetails']['StatusCode']) &&
            $timeSlotedataresponse['ValidationDetails']['StatusCode'] == 200
        ) {
        if($timeSlotedataresponse['MasterDataList']){
            $allTimeslots = $timeSlotedataresponse['MasterDataList'];
            $time_optionData .= '<option value="">Select</option>';
            foreach ($allTimeslots as $item):
                if ($item['IsActive']): 
                    $start = date("g:ia", strtotime($item['StartTime']));
                    $end = date("g:ia", strtotime($item['EndTime']));
                    $timeslotIDRec = $TimeslotId ?? $clientprofileData[0]['TimeslotId'];
                    $time_optionData .='<option value="'.$item['OrderNo'].'">'.$start.' -- '.$end.'</option>';
                endif;
                endforeach;
            
        }else{
            $time_optionData .= '<option value="">Not Found</option>';
        } 
    }

    $optionData = "";
    $dataresponse = GetAreaByCity($Data);
    
    if (
            isset($dataresponse['ValidationDetails']['StatusCode']) &&
            $dataresponse['ValidationDetails']['StatusCode'] == 200
        ) {
            if($dataresponse['MasterDataList']){
                $allArea = $dataresponse['MasterDataList'];
                usort($allArea, function ($a, $b) {
                    return strcmp($a['AreaName'], $b['AreaName']);
                });
                $optionData .= '<option value="">Select Area</option>';
                foreach ($allArea as $item):                     
                        $optionData .= '<option value="'.$item['AreaCode'].'">'.$item['AreaName'].'</option>';
                endforeach;
                $response = ['success' => true, 'data' => $optionData, 'timeslote' => $time_optionData]; 
            }else{
                $optionData .= '<option value="">Not Found</option>';
                $response = ['success' => true, 'data' => $optionData, 'timeslote' => $time_optionData]; 
            } 
        }

header('Content-Type: application/json');
echo json_encode($response);
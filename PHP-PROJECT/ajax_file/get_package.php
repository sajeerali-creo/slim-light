<?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error'];

    $packageid = $_POST['id'] ?? '';
    $Data = [
    'GroupCode' => $groupid, 
    ];
    $packagesByGroup = [];
    $durationData = "";
    $packresponse = GetPackageByGroup($Data);
    
    if (
            isset($packresponse['ValidationDetails']['StatusCode']) &&
            $packresponse['ValidationDetails']['StatusCode'] == 200
        ) {
            $packagesByGroup = $packresponse['MasterDataList'];   
            if($packagesByGroup){
                    foreach ($packagesByGroup as $index => $item): 
                        if ($item['IsActive']):
                            $checkedvar = "";
                            if($index == 0){ $checkedvar = 'checked'; }
                            $durationData .='<input type="radio" id="r'.$index.'" name="duration" data-package="'.json_encode($item, JSON_HEX_APOS | JSON_HEX_QUOT).'" onclick="getpackageDetailBygroupCode(this)"
                                class="radio-input group-'.$item['GroupCode'].'" '.$checkedvar.'>
                            <label for="r'.$index.'" class="radio-label">'.$item['DurationDays'].' days</label>';
                    endif; endforeach; 
                 $response = ['success' => true, 'data' => $durationData]; 
            }else{
                $response = ['success' => true, 'data' => "Not Found"]; 
            } 
        }

header('Content-Type: application/json');
echo json_encode($response);
    <?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error'];

    $action_val = $_POST['action_val'] ?? '';

    $healthProfileData = [];
    $healthParamData = [
    'PersonId' => $_SESSION['user']['UserId']
    ];
    $clientHealthProfile_response = GetClientHealthProfile($healthParamData);
    
    if($clientHealthProfile_response['ValidationDetails']['StatusCode'] == 200){
        if(!empty($clientHealthProfile_response['MasterDataList'])){
            $healthProfileData = $clientHealthProfile_response['MasterDataList'][0];
        }
    }
    
    if($action_val == "current_weight"){
        $healthProfileData['PersonWeight'] = $_POST['weight'];
        $healthProfileData['PersonHeight'] = $_POST['height'];
    }else if($action_val == "target_weight"){
        $healthProfileData['TargetWeight'] = $_POST['weight'];
    }
    
    $dataresponse = SaveHealthProfile($healthProfileData);
    
    $BMIData = [];
    if (
            isset($dataresponse['ValidationDetails']['StatusCode']) &&
            $dataresponse['ValidationDetails']['StatusCode'] == 200
        ) {
            $BMIParamData = [
            'PersonHeight' => round($healthProfileData['PersonHeight'], 2),
            'PersonWeight' => round($healthProfileData['PersonWeight'], 2)
            ];
            
            $bmi_response = GetBMIStatus($BMIParamData);
            
            if($bmi_response['ValidationDetails']['StatusCode'] == 200){
                if(!empty($bmi_response['MasterDataList'])){
                    $BMIData = $bmi_response['MasterDataList'][0];
                     $response = ['success' => true, 'data' => $dataresponse, 'bmi_data' => $BMIData];  
                }
            }
           
        }

header('Content-Type: application/json');
echo json_encode($response);
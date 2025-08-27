<?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error'];

    $subscription_ID = $_POST['subscription_ID'] ?? '';
    $action_val = $_POST['action_val'] ?? '';
    $locationID = $_POST['locationID'] ?? '';


  $startdate_val = $_POST['startDate'] ?? '';
  $final_startdate = convertDateFormate($startdate_val);

    $cuurentSubStatusData = [];
    if($subscription_ID != ''){
            $subStatParamData = [
            'PersonId' => $_SESSION['user']['UserId'],
            'SubscriptionId' => $subscription_ID
            ];
            $currentSubStatus_response = GetClientSubscriptionCurrentStatus($subStatParamData);

            if($currentSubStatus_response['ValidationDetails']['StatusCode'] == 200){
                if(!empty($currentSubStatus_response['MasterDataList'])){
                    $cuurentSubStatusData = $currentSubStatus_response['MasterDataList'][0];
                    if(!empty($cuurentSubStatusData)){
                        if($action_val == "start"){
                            $startParamData = [
                            'PersonId' => $_SESSION['user']['UserId'],
                            'SubscriptionId' => $subscription_ID,
                            'StartDate' => $final_startdate,
                            'StopDate' => $cuurentSubStatusData['StopDate'],
                            'ResumeDate' => $cuurentSubStatusData['ResumeDate'],
                            'LocationId' => $locationID,
                            'UserId' => null,
                            'ModeOfEntry' => "website",
                            ];
                            
                            StartPlanner($startParamData);
                            $response = ['success' => true, 'message' => 'success']; 
                        }
                        else if($action_val == "stop"){
                            $stopParamData = [
                            'PersonId' => $_SESSION['user']['UserId'],
                            'SubscriptionId' => $subscription_ID,
                            'StartDate' => $cuurentSubStatusData['StartDate'],
                            'StopDate' => $cuurentSubStatusData['StopDate'],
                            'ResumeDate' => $cuurentSubStatusData['ResumeDate'],
                            'LocationId' => $cuurentSubStatusData['LocationId'],
                            'UserId' => null,
                            'ModeOfEntry' => "website",
                            ];
                            StopPlanner($stopParamData);
                            $response = ['success' => true, 'message' => 'success']; 
                        }else if($action_val == "resume"){
                            $resumeParamData = [
                            'PersonId' => $_SESSION['user']['UserId'],
                            'SubscriptionId' => $subscription_ID,
                            'StartDate' => $cuurentSubStatusData['StartDate'],
                            'StopDate' => $cuurentSubStatusData['StopDate'],
                            'ResumeDate' => $cuurentSubStatusData['ResumeDate'],
                            'LocationId' => $cuurentSubStatusData['LocationId'],
                            'UserId' => null,
                            'ModeOfEntry' => "website",
                            ];
                            ResumePlanner($resumeParamData);
                            $response = ['success' => true, 'message' => 'success']; 
                        }
                    }
                }else{
                    if($action_val == "start"){
                            $startParamData = [
                            'PersonId' => $_SESSION['user']['UserId'],
                            'SubscriptionId' => $subscription_ID,
                            'StartDate' => $final_startdate,
                            'StopDate' => null,
                            'ResumeDate' => null,
                            'LocationId' => $locationID,
                            'UserId' => null,
                            'ModeOfEntry' => "website",
                            ];
                            StartPlanner($startParamData);
                            $response = ['success' => true, 'message' => 'success']; 
                        }
                }
            }
    }
    
  

header('Content-Type: application/json');
echo json_encode($response);
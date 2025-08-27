<?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error'];

    $SubscriptionId = $_POST['SubscriptionId'] ?? '';
    
    $Data = [
    'PersonId' => $_SESSION['user']['UserId'],
    'SubscriptionId' => $SubscriptionId,
    ];
    
    $optionData = "";
    $dataresponse = GetReceiptNos($Data);
    if (
            isset($dataresponse['ValidationDetails']['StatusCode']) &&
            $dataresponse['ValidationDetails']['StatusCode'] == 200
        ) {
            if($dataresponse['MasterDataList']){
                $optionData .= '<div class="payment-container" >';
                if($dataresponse['MasterDataList']){
                    $optionData .= '
                    <div class="loader-overlay" style="display: none;">
        <svg id="currentspinner" class="spinner" viewBox="0 0 50 50">
            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
        </svg>
    </div><div class="mb-4"><h4>All Receipts</h4></div>';
                    foreach ($dataresponse['MasterDataList'] as $item):                     
                        $optionData .='<div class="d-flex gap-3 border p-3 rounded-3 align-items-center justify-content-between mb-3" >
                        <div class="d-flex gap-3 align-items-center">
                        <i class="ti ti-receipt"></i>
                        <div>'.$item['ReceiptEntryNo'].'</div>
                        </div>
                        <a href="javascript:void(0)" class="btn-download" id="recieptid" onclick="pdfDownload(\'' . $item['ReceiptEntryNo'] . '\')"><i class="ti ti-download"></i></a>
                        </div>';
                    endforeach;
                }else{
                    $optionData = '<div class="d-flex flex-column p-4 border rounded-3 text-center align-items-center justify-content-center mb-4">
                    <i class="ti ti-receipt-off fs-2 text-dark d-block mb-4"></i>
                    <small class="opacity-50">No receipt available</small>
                    </div>';
                }
                $optionData .= '</div>';
                $response = ['success' => true, 'data' => $optionData]; 
            }
        }

header('Content-Type: application/json');
echo json_encode($response);


<?php
require_once __DIR__ . '/../services/commonService.php';
require_once __DIR__ . '/../config/database.php';

$response = ['success' => false, 'message' => 'error', 'cpnamt' => 0];

    $meal_planID = $_POST['meal_planID'] ?? '';
    $couponcode = $_POST['couponcode'] ?? '';
    $couponcode = str_replace(' ', '', $couponcode);
    $msg = "";
    $qry = $connection->query("SELECT * from `coupon_codes` where name = '{$couponcode}' AND (meal_plan = '{$meal_planID}' OR meal_plan = '0') LIMIT 1");
	if ($qry && $qry->num_rows > 0) {

        $coupon = $qry->fetch_assoc();
        $discount = $coupon['amount_per'];
        $expiry = $coupon['expiry_date'];

        // Get today's date
        $today = date('Y-m-d');

        // Check if coupon is expired
        if ($expiry < $today) {
            $msg = "Coupon has expired on $expiry.";
            $response = ['success' => false, 'message' => $msg, 'cpnamt' => 0]; 
        } else {
            $subscriptionData = [];
            $subParamData = [
                'PersonId' => $_SESSION['user']['UserId']
            ];
            $subscription_response = GetClientSubscriptionList($subParamData);
            if($subscription_response['ValidationDetails']['StatusCode'] == 200){
                $currentuser = empty($subscription_response['MasterDataList']) ? 'new' : 'exist';
                // check user is new or old
                if($coupon['user_type'] == $currentuser){
                    $msg = '<span class="text-success">Coupon applied successfully.</span>';
                    $response = ['success' => true, 'message' => $msg, 'cpnamt' => $coupon['amount_per']]; 
                }else{
                    $msg = "No valid coupon found.";
                    $response = ['success' => false, 'message' => $msg, 'cpnamt' => 0]; 
                }
            } 
                        
            
            
        }
    } else {
        $msg = "No valid coupon found.";
        $response = ['success' => false, 'message' => $msg, 'cpnamt' => 0]; 
    }

    

header('Content-Type: application/json');
echo json_encode($response);
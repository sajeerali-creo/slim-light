    <?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => 'error'];

    $sel_menu_id = $_POST['sel_menu_id'] ?? '';
    $sel_menu_label = $_POST['sel_menu_label'] ?? '';
    $menuData = $_POST['menuData'] ?? '';

    
    $menuData['MealId'] = $sel_menu_id;
    $menuData['MealDesc'] = $sel_menu_label;

    $menuParamData = [
    $menuData
    ];
    $menuDataRes = UpdateClientMenuPlan($menuParamData);
    if( isset($menuDataRes['ValidationDetails']) && $menuDataRes['ValidationDetails']['StatusCode'] == 200){
        if(!empty($menuDataRes['MasterDataList'])){
            //$MenuData = $menuDataRes['MasterDataList'][0];
            $response = ['success' => true, 'message' => 'Menu Updated'];  
        }
    }
    else if($menuDataRes['StatusCode'] == 400)
    {
        $errors_msg = $menuDataRes['ErrorDetails'][0]['ErrorMessageDescription'];
        $response = ['success' => false, 'message' => $errors_msg];
    }
    else
    {
        $errors_msg = $menuDataRes['ValidationDetails']['StatusMessage'] != '' ? $menuDataRes['ValidationDetails']['StatusMessage'] : $menuDataRes['ValidationDetails']['ErrorDetails'][0]['ErrorMessageDescription'];
        $response = ['success' => false, 'message' => $errors_msg];
    }

header('Content-Type: application/json');
echo json_encode($response);
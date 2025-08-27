
<?php
require_once __DIR__ . '/../services/commonService.php';

function getWeekDates($startDate, $endDate) {
    $dates = [];

    $start = new DateTime($startDate);
    $end = new DateTime($endDate);

    while ($start <= $end) {
        $dates[] = $start->format('Y-m-d');
        $start->modify('+1 day');
    }

    return $dates;
}
$response = ['success' => false, 'message' => 'error'];

    $sel_date = $_POST['sel_date'] ?? '';
    $singleweekDate = $_POST['weekDate'] ?? '';

    $Data = [
    'PersonId' => $_SESSION['user']['UserId'],
    'Date' => $sel_date
    ];
    
    $menuDataList = "";
    $weekDates = [];
    $startDate = "";
    $endDate = "";
    
    $paramData = [
    'SelectedDate' => $sel_date
    ];
    $dtresp = GetMealPlanWeekByDate($paramData);
    
    if (isset($dtresp['ValidationDetails']['StatusCode']) &&
        $dtresp['ValidationDetails']['StatusCode'] == 200 &&
        !empty($dtresp['MasterDataList'][0])) {
         if($dtresp['MasterDataList'][0]){
                $startDate = $dtresp['MasterDataList'][0]['WeekStartDate'] ?? '';
                $endDate = $dtresp['MasterDataList'][0]['WeekEndDate'] ?? '';
                $weekDates = getWeekDates($startDate, $endDate);
         }
    }
  
    // if($weekDate != ''){
    //     $sel_date = $weekDate;
    // }

    $menuParamData = [
    'PersonId' => $_SESSION['user']['UserId'],
    'DeliveryDate' => $sel_date
    ];
    //'DeliveryDate' => '2024-12-13T00:00:00'
    $MenuData = [];
    $menu_response = GetClientMenu($menuParamData);
    if (
            isset($menu_response['ValidationDetails']['StatusCode']) &&
            $menu_response['ValidationDetails']['StatusCode'] == 200
        ) {
            if($menu_response['MasterDataList']){   
                $MenuData = $menu_response['MasterDataList'];

                //$all_menu_list = [];
                //$all_menu_resp = GetMealsMaster();
                

                $startdatelable = date('d/m/Y', strtotime($startDate));
                $enddatelable = date('d/m/Y', strtotime($endDate));
                $menuDataList .='<div class="d-flex align-items-center gap-2">
                    <strong class="fs-5">'.$startdatelable.'</strong>
                    <span class="fs-5">-</span>
                    <strong class="fs-5">'.$enddatelable.'</strong>
                </div>
                <div class="d-flex gap-2 mt-4 mb-4">
                    <div class="radio-group">';
                        if ($weekDates) {
                           foreach ($weekDates as $index => $day) {
                                $dayObj = new DateTime($day);
                                $dateValue = $dayObj->format('Y-m-d'); 
                                $dateLabel = $dayObj->format('d');
                                $inputId = "r" . $index;

                                $checked = ($index === 0) ? 'checked' : '';
                                if($singleweekDate != ''){
                                     $checked = ($dateValue == $singleweekDate) ? 'checked' : '';
                                }
                               
                                $menuDataList .= '<input type="radio" id="'.$inputId.'" name="weekday" value="'.$dateValue.'" class="radio-input weekdaycls" '.$checked.' />
                                <label for="'.$inputId.'" class="radio-label">'.$dateLabel.'</label>';
                            }
                        }
                $menuDataList .='</div></div>';
                        if($MenuData){
                            $targetDate = $startDate;
                            if($singleweekDate != ''){
                                $singledateTime = new DateTime($singleweekDate);
                                $targetDate = $singledateTime->format('Y-m-d\T00:00:00');
                            }
                            
                            $filteredData = array_filter( $MenuData, function ($item) use ($targetDate) {
                                return $item['DeliveryDate'] === $targetDate;
                            });
                            $unique = [];
                            $finalResult = [];

                            foreach ($filteredData as $item) {
                                if (!isset($unique[$item['MealTypeId']])) {
                                    $unique[$item['MealTypeId']] = true;
                                    $finalResult[] = $item;
                                }
                            }
                            foreach ($finalResult as $item):
                                $MealTypeId = $item['MealTypeId'];
                                $filteredDataBymealID = array_filter( $filteredData, function ($item) use ($MealTypeId) {
                                    return $item['MealTypeId'] === $MealTypeId;
                                });
                                $menuDataList .='<div class="d-flex border-bottom">
                                    <h3 class="text-brand">'.$item['MealTypeDesc'].'</h3><span id="meal-'.$item['MealTypeId'].'"></span>
                                </div>
                                <div class="row mt-4">';
                                    foreach ($filteredDataBymealID as $mealitem):
                                        $disableChbx = '';
                                        if(!$mealitem['IsAlternative'] || $mealitem['IsClientSelected']){ 
                                            $disableChbx = 'disabled';
                                        }
                                        $checkboxchecked = '';
                                        //$menuDataList .= $mealitem['IsDefault']. '---'.$mealitem['IsClientSelected']; 
                                        if($mealitem['IsDefault']){ 
                                            $checkboxchecked = 'checked';
                                        }else if($mealitem['IsAlternative'] && $mealitem['IsClientSelected'] != "false"){
                                            $checkboxchecked = 'checked';
                                        }

                                        $menuDataList .='<div class="col-lg-4 mb-4">
                                                            <div class="d-flex mb-3">
                                                                <div class="w-100">
                                                                    <label for="" class="required-label">Select Dish</label>
                                                                </div>
                                                                <div>
                                                                    <label class="custom-checkbox">
                                                                        <input type="checkbox" '. $checkboxchecked.' '.$disableChbx.' />
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form">';
                                                            //$menuDataList .= $mealitem['IsAlternative'].'---'.$mealitem['IsClientSelected'];
                                                                if(($mealitem['IsAlternative'] && $mealitem['IsClientSelected'] == "false")){ 
                                                                    
                                                                    //  $MealTypeId = $item['MealTypeId'];
                                                                    //  $all_menu_list_sort = array_filter( $all_menu_list, function ($itemmenu) use ($MealTypeId) {
                                                                    //     return $itemmenu['MealTypeId'] === $MealTypeId;
                                                                    // });
                                                                    $all_menu_list = [];
                                                                    $altermealData = [
                                                                        'MealTypeId' => $mealitem['MealTypeId']
                                                                        ];
                                                                        $all_menu_resp = GetAlternativeMealMastersByMealType($altermealData);
                                                                        
                                                                        if (
                                                                            isset($all_menu_resp['ValidationDetails']['StatusCode']) &&
                                                                            $all_menu_resp['ValidationDetails']['StatusCode'] == 200
                                                                        ) {
                                                                            if($all_menu_resp['MasterDataList']){   
                                                                                $all_menu_list = $all_menu_resp['MasterDataList'];
                                                                            
                                                                            }
                                                                        }
                                                                        //$menuDataList .='<select name="" class="form-select" id="alternativeoption" onchange="changeAltermenu(this, '.$mealJson.')">';
                                                                    $mealJson = htmlspecialchars(json_encode($mealitem), ENT_QUOTES, 'UTF-8');
                                                                    $menuDataList .='<select name="" class="form-select" id="alternativeoption">';
                                                                        foreach ($all_menu_list as $menuitem):
                                                                            $selected = ($menuitem['MealId'] == $mealitem['MealId']) ? 'selected' : '';
                                                                            $menuDataList .='<option value="'.$menuitem['MealId'].'" '.$selected.'>'.$menuitem['MealDesc'].'</option>';
                                                                        endforeach;
                                                                        $menuDataList .='</select>';
                                                                        $menuDataList .='<svg id="meal-spinner-'.$item['MealTypeId'].'" style="display:none" class="spinner mealallspin" viewBox="0 0 50 50">
                                                                        <circle class="path" cx="25" cy="25" r="20" fill="none"
                                                                        stroke-width="5" />
                                                                        </svg>';
                                                                }else{
                                                                    $menuDataList .='<select name="" class="form-select" id="" disabled>
                                                                        <option value="'.$mealitem['MealId'].'">'.$mealitem['MealDesc'].'</option>
                                                                    </select>';
                                                                }
                                                            $menuDataList .='</div>
                                                        </div>';
                                    endforeach; 
                                $menuDataList .='</div>';
                            endforeach; 
                            $menuDataList .='<div class="alert alert-warning text-center" role="alert">
                                Want to make a change? Please submit it at least 48 hours ahead, and we’ll take care of the rest!
                            </div>';
                        } 
                $response = ['success' => true, 'data' => $menuDataList]; 
            }else{ 
                $menuDataList .='<div class="menu-notfound" >
                        Not Found
                    </div>';
                $response = ['success' => true, 'data' => $menuDataList]; 
            } 
        }else{
            $menuDataList .='<div class="menu-notfound">
                Not Found
            </div>';
            $response = ['success' => true, 'data' => $menuDataList];
        }

header('Content-Type: application/json');
echo json_encode($response);

// <div class="col-lg-4 mb-4">
//     <div class="d-flex mb-3">
//         <div class="w-100">
//             <label for="" class="required-label">Alternative</label>
//         </div>
//         <div>
//             <label class="custom-checkbox">
//                 <input type="checkbox" />
//                 <span class="checkmark"></span>
//             </label>
//         </div>
//     </div>
//     <div class="form">
//         <select name="" class="form-select" id="">
//             <option value="">Select</option>
//             <option value="true">Food Name</option>
//             <option value="false">Food Name</option>
//         </select>
//     </div>
// </div>
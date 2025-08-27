<?php
require_once __DIR__ . '/../services/commonService.php';

$response = ['success' => false, 'message' => ''];

if (isset($_FILES['profile_picture'])) {
    $clientprofileData = isset($_POST['profiledata']) ? json_decode($_POST['profiledata'], true) : null;

    $image = $_FILES['profile_picture']['tmp_name'];
    //$imageBytes = file_get_contents($image); // raw binary data    
    //$byteArray = unpack("C*", $imageBytes);  // convert to byte array

// $array = array();
// foreach(str_split(file_get_contents($image)) as $byte){
//   array_push($array, ord($byte));
// }

    //'ProfilePicture' => "iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABPUlEQVR4AWIYFKC0tBTAbhlodAxFcfgR9gR7g73BAGigkcRg7DUCJkAw/kAEvUAXQSGrhBAKFGKBoAeIwsmHc3VPV2loxT0crvvd8zsfsMkS7QWappElWvdTslD/EYEksL3al3Ecg+667tOAsmEYLOPOc8vIsvns9AIHFzdiq+97G6QouoQ7LcvIssXOJJAEkkAS+FagKAopy1LoLMsCgTzPlXEOBHirjIy5Anyz9eeBJYEA4co4fxTgrTIy/qeAc07qupa1ekPatuWD4tk0TVJVlaxvbtGcuVPMW2aYJYOsnwtofcXO7x7pGbNJIAkkASOwe3QpL69v0Yer4yu5f3qOspPbBzrKmGE2VuxipxfYOTzjAqugCYDRlu2dXivjbLkyMixjFywQcFz8crv3QWxHOEIBiB3ohBVg9gIAsCXlcPy5hk8AAAAASUVORK5CYII=",
$base64Image = base64_encode(file_get_contents($image));

        $Data = [
        'PersonId' => $_SESSION['user']['UserId'],
        'ProfilePicture' => $base64Image,
        'ModeofEntry' => 'website'
        ];
   //echo $base64Image;
        $response = UpdatePersonProfilePicture($Data);
        
        $respcode = $response['ValidationDetails']['StatusCode'] ?? $response['StatusCode'];     
        if($respcode == 200){
            $success_msg = "Profile Picture Successfully Updated";
        }else{
            $errors_msg = $response['ValidationDetails']['StatusMessage'] != '' ? $response['ValidationDetails']['StatusMessage'] : $response['ValidationDetails']['ErrorDetails'][0]['ErrorMessageDescription'];
        }
} else {
    $response['message'] = 'No image received.';
}

header('Content-Type: application/json');
echo json_encode($response);

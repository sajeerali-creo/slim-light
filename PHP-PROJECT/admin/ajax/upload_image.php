<?php 

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../config.php');

if ($_FILES['file']['name']) {
   // $target_dir = "uploads/"; // Directory where images will be uploaded
    //$target_file = $target_dir . basename($_FILES["file"]["name"]);
    
    // Move the uploaded file to the server
    $fname = 'uploads/'.time().'_'.$_FILES['file']['name'];
   // $move = move_uploaded_file($_FILES['img']['tmp_name'],base_app.$fname);
    // if($move){
    //     $data .=" , `file_path` = '{$fname}' ";
    // }

    if (move_uploaded_file($_FILES["file"]["tmp_name"], base_app.$fname)) {
        // Return the URL of the uploaded image
        $final_response = ['url' => base_url.$fname];
        //$final_response = json_encode($response);
    } else {
        $final_response = ["error" => "Failed to upload image."];
    }
} else {
    $final_response = ["error" => "No file uploaded."];
}

//$response = ['url' => base_url.$fname];
header('Content-Type: application/json');
echo json_encode($final_response);
?>

<?php
require_once 'services/apiService.php';

function signinUser($data) {
    return apiRequest('Login/ValidateUserLogin', 'GET', $data);
}
function signupUser($data) {
    return apiRequest('SignUp/Register', 'POST', $data);
}

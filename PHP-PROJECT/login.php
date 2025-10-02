<?php
include 'partials/second_header.php';

if (isset($_SESSION['user'])) {
    header('Location: profile.php');
    exit;
}

require_once 'services/authService.php';
$errors = [];
if(isset($_POST["submit"])){

    $Username = filter_var($_POST['Username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password'] ?? '';
    
    if (empty($Username)) $errors['Username'] = "Username is required.";
    if (empty($password)) $errors['password'] = "Password is required.";
    
    $error_msg = '';
    if (empty($errors)) {
        $formData = [
        'Username' => $Username,
        'password' => $password,
        ];
        $response = signinUser($formData);
        if (
            isset($response['ValidationDetails']['StatusCode']) &&
            $response['ValidationDetails']['StatusCode'] == 200 &&
            !empty($response['MasterDataList'][0])
        ) {
            $user = $response['MasterDataList'][0];
            $_SESSION['user'] = [
                'UserId'   => $user['UserId'],
                'Username' => $user['Username'],
                'Email'    => $user['EmailId'],
                'ModeofLogin'    => $user['ModeofLogin']
            ];
            $user_data_json = json_encode($user);
            $remember = isset($_POST['remember_me']);
            
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expire = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 days

                // Save token in DB
                mysqli_query($connection, "INSERT INTO remember_tokens (user, token, expires_at) VALUES ('$user_data_json', '$token', '$expire')");
                // Set cookie
                setcookie('remember_token', $token, [
                    'expires' => time() + (86400 * 30),  // 30 days
                    'path' => '/creators/',               // important: match the path where your app runs
                    'domain' => 'virammarines.com',      // not with www, and no dot prefix
                    'secure' => true,                    // required for HTTPS
                    'httponly' => true,                  // safer, prevents JavaScript access
                    'samesite' => 'Lax'                  // default behavior works across most browsers
                ]);
            }
            // Redirect to dashboard or home page
            if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
                header('Location:'.ROOT_URL.'plan_details.php?id='.$_REQUEST['id']);    
            }else{
                header('Location:'.ROOT_URL);
            }
            
            exit;
        } else {
            $error_msg = $response['ValidationDetails']['StatusMessage'];
            $Username = '';
            $password = '';
        }
    }
}
$system_arr = [];
$system_query=mysqli_query($connection, "SELECT * FROM system_info");

while($result=mysqli_fetch_array($system_query)){
    $system_arr[$result['meta_field']] = $result['meta_value'];
}
?>

<section class="row">
        <div class="col-lg-6 col-xl-8 col-md-12">
            <img src="<?= $system_arr && isset($system_arr['banner']) ? ADMIN_URL.$system_arr['banner'] : ADMIN_URL.'dist/img/no-image-available.png' ?>" class="login-bg" alt="">
        </div>
        <div class="col-lg-6 col-xl-4 col-md-12 d-flex align-items-center">
            <div class="p-4 p-lg-5">
                <a href="<?= ROOT_URL ?>">
                    <img src="img/logo-dark.svg" alt="">
                </a>
                <div class="my-4" id="formMessage">
                    <h3>Nice to see you again</h3>
                    <?php if (isset($error_msg)): ?>
                        <div class="message error" id="global-error-msg">
                            <?= $error_msg; ?>
                            <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
                        </div>
                    
                        <!-- <small class="text-danger"><?= $error_msg; ?></small> -->
                    <?php endif; ?>
                </div>
                <form enctype="multipart/form-data" method="POST" id="signinForm">
                    <div class="row justify-content-center form">
                        <div class="col-lg-12 mb-4">
                            <input type="text" name="Username" class="form-control w-100" id="Username" placeholder="Enter Username" value="<?= htmlspecialchars($Username ?? '') ?>">
                            <?php if (isset($errors['Username'])): ?>
                                        <small class="text-danger"><?= $errors['Username']; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-12 mb-4">
                            <label for="userpassword">Password</label>
                            <input type="password" name="password" class="form-control w-100" id="password" placeholder="Enter Password" value="<?= htmlspecialchars($password ?? '') ?>">
                            <?php if (isset($errors['password'])): ?>
                                        <small class="text-danger"><?= $errors['password']; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-12 d-flex justify-content-between">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="remember_me" id="remember_me" />
                                <span class="checkmark"></span>
                                Remember me
                            </label>
                            <a href="<?= ROOT_URL ?>forgot.php" class="text-brand">Forgot password?</a>
                        </div>
                        <div class="d-flex mt-5 light-bg">
                            <button type="submit" name="submit" class="btn-fancy"><span><svg id="loader" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>Sign In</span></button>
                            
                        </div>
                    </div>
                </form>
                <div class="text-center mt-5">Don't have an account? <a href="<?= ROOT_URL ?>signup.php" class="text-brand">Sign up now</a></div>
            </div>
        </div>
    </section>
    <script>
    $('#signinForm').on('submit', function() {
        $('#loader').show(); // Show loader before form submits
    });
</script>
    <?php
include './partials/footer.php';
?>

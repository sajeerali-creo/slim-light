<?php 
include 'partials/second_header.php';
require_once 'services/authService.php';
require_once 'services/commonService.php';

if (isset($_SESSION['user'])) {
    header('Location: profile.php');
    exit;
}

?>
<section class="row">
        <div class="col-lg-6 col-xl-8 col-md-12">
            <img src="img/forgot.webp" class="login-bg" alt="">
        </div>
        <div class="col-lg-6 col-xl-4 col-md-12 d-flex align-items-center">
            <div class="p-4 p-lg-5">
                <a href="<?= ROOT_URL ?>">
                    <img src="img/logo-dark.svg" alt="">
                </a>
                <form method="POST" id="emailUpdateForm">
                <div class="my-4">
                    <h3>Forget password</h3>
                </div>
                <div class="col-lg-12">
                    <small class="text-success successmsg-text"></small>
                    <small class="text-danger errormsg-text"></small>
                </div>
                <div class="row justify-content-center form">
                    <div class="col-lg-12 mb-4" id="emailcontent">
                        <input type="text" name="profileEmailId" class="form-control w-100" id="EmailId" placeholder="Enter email address">
                        <small class="text-danger" id="emailID-error"></small>
                    </div>
                    <div class="col-lg-6 passwordfield" style="display: none;">
                        <div>
                            <label for="" class="required-label">New Password</label>
                            <input type="password" name="NewPassword" class="form-control w-100" id="NewPassword" placeholder="Enter New Password" value="<?= $NewPassword ?? '' ?>">
                            <small class="text-danger" id="newwp-error"></small>
                        </div>
                    </div>
                    <div class="col-lg-6 passwordfield" style="display: none;">
                        <div>
                            <label for="" class="required-label">Confiem New Password</label>
                            <input type="password" name="ConfirmPassword" class="form-control w-100" id="ConfirmPassword" placeholder="Enter Confirm Password" value="<?= $ConfirmPassword ?? '' ?>">
                            <small class="text-danger" id="confirmp-error"></small>
                        </div>
                    </div>
                    <input type="hidden" name="persionID" id="persionIdfield" value="">
                    <div class="d-flex mt-4 light-bg">
                        <button type="submit" name="emailsubmit" class="btn-fancy" value="emailform"><span>
                            <svg id="emailloader" class="button-spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" /></svg>
                            Submit</span>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </section>
    <script>
         $('#emailUpdateForm').on('submit', function(e) {
            e.preventDefault(); 
            const emailId = $('#EmailId').val();
            const perID = $('#persionID').val();
            if(emailId != ""){
                const perID = $('#persionIdfield').val();
                if(perID == ""){
                    $('#emailID-error').text('');
                    $('#emailloader').show(); 
                    //let formData = new FormData(this);
                    $.ajax({
                    url: 'ajax_file/check_email_exist.php',
                    type: 'POST',
                    data: { EmailId: emailId },
                    success: function (response) {
                        if(response.success == true){
                        //$('.successmsg-text').text(response.message);
                            $('.errormsg-text').text('');
                            $('.passwordfield').show();
                            $('#persionIdfield').val(response.perId);
                            $('#emailcontent').hide();
                        }else{
                            $('.errormsg-text').text(response.message);
                            $('.passwordfield').hide();
                            $('#persionIdfield').val();
                        }
                        $('#emailloader').hide(); 
                        },
                        error: function (error) {
                            $('#emailloader').show(); 
                        }
                    });
                }else{
                    const NewPassword = $('#NewPassword').val();
                    const ConfirmPassword = $('#ConfirmPassword').val();
                    $('#newwp-error').text('');
                    $('#confirmp-error').text('');
                    if(NewPassword == ""){
                        $('#newwp-error').text('New Password is require');
                        return false;
                    }else{
                        $('#newwp-error').text('');
                    }
                    if(ConfirmPassword == ""){
                        $('#confirmp-error').text('Confirm Password is require');
                        return false;
                    }else{
                        $('#confirmp-error').text('');
                    }
                    if(NewPassword != ConfirmPassword){
                        $('#confirmp-error').text('Confirm Password not match');
                        return false;
                    }
                    if(NewPassword != "" && ConfirmPassword != ""){
                        $('#emailloader').show(); 
                        $.ajax({
                        url: 'ajax_file/forgot_password_by_email.php',
                        type: 'POST',
                        data: { PersonId: perID, NewPassword: NewPassword, ConfirmPassword: ConfirmPassword },
                        success: function (response) {
                            if(response.success == true){
                                $('.successmsg-text').text(response.message);
                                $('.errormsg-text').text('');
                                $('#emailUpdateForm')[0].reset();
                                $('.passwordfield').hide();
                                $('#persionIdfield').val('');
                                $('#emailcontent').show();
                                //$('#emailloader').hide();
                            }else{
                                //$('.errormsg-text').text(response.message);
                            }
                                $('#emailloader').hide(); 
                            },
                            error: function (error) {
                                $('#emailloader').show(); 
                            }
                        });
                    }
                }
            }else{
                $('#emailID-error').text('Email address is require');
            }
            
            
            // $.ajax({
            //     url: 'ajax_file/create-appointment.php',
            //     type: 'POST',
            //     data: formData,
            //     contentType: false,
            //     processData: false,
            //     success: function (response) {
            //         $('#appointmentloader').hide();
            //         if(response.success == true){
            //             $('.successmsg-text').text(response.message);
            //             $('.errormsg-text').text('');
            //             $('#appointmentForm')[0].reset();
            //         }else{
            //             $('.errormsg-text').text(response.message);
            //             $('.successmsg-text').text('');
            //         }
            //     },
            //     error: function (error) {
            //         $('#appointmentloader').hide();
            //         $('.errormsg-text').text('Technical error');
            //         $('.successmsg-text').text('');
            //     }
            // });
    });
    </script>

<?php
include './partials/footer.php';
?>
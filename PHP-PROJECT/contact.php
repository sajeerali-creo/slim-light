<?php
include 'partials/header.php';
?>

<?php
$errors = [];
$success_msg = '';
if (isset($_POST["submit"]) && $_POST["submit"] == "contactform") {

    $spinner = true;
    $FirstName = filter_var($_POST['FirstName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $LastName = filter_var($_POST['LastName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $EmailId = $_POST['EmailId'] ?? '';
    $MobileNumber = $_POST['MobileNumber'] ?? '';
    $Message = $_POST['Message'] ?? '';


    if (empty($FirstName))
        $errors['FirstName'] = "First name is required.";
    if (empty($LastName))
        $errors['LastName'] = "Last name is required.";
    if (empty($MobileNumber)) {
        $errors['MobileNumber'] = "Phone number is required.";
    } elseif (!preg_match('/^\+?[0-9\s\-]{7,15}$/', $MobileNumber)) {
        $errors['MobileNumber'] = "Invalid phone number format.";
    } elseif (!preg_match('/^[0-9]{10}$/', $MobileNumber)) {
        $errors['MobileNumber'] = "Phone number must be exactly 10 digits.";
    }
    if (empty($EmailId)) {
        $errors['EmailId'] = "Email is required.";
    } elseif (!filter_var($EmailId, FILTER_VALIDATE_EMAIL)) {
        $errors['EmailId'] = 'Invalid email format.';
    }
    if (empty($Message))
        $errors['Message'] = "Message is required.";

    if (empty($errors)) {
        $full_name = $FirstName . ' ' . $LastName;
        //$sql ="INSERT INTO message set 'full_name'= $FirstName.' '.$LastName, 'email'= $EmailId, 'contact'= $MobileNumber, 'message'= $Message";
        $sql = "INSERT INTO messages (full_name, email, contact, message) VALUES ('$full_name', '$EmailId', '$MobileNumber', '$Message')";
        $query = mysqli_query($connection, $sql);
        //$success_msg = "Message Successfully Sent";
        $FirstName = '';
        $LastName = '';
        $EmailId = '';
        $MobileNumber = '';
        $Message = '';
        $_SESSION['form_succ'] = 1;
        //header('Location:contact.php');
    } else {

    }
}

?>
<!-- ===========BANNER=========== -->
<section class="inner-banner">
</section>

<!-- ===========SUBHEADER=========== -->
<section class="py-5 bg-inner-sub-head">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between">
            <div class="d-flex flex-column align-items-start">
                <h4 class="h2 text-white">we help shape lifestyles. <span class="font-weight-200">Connect with
                        us!</span></h4>
            </div>
            <div>
                <button class="btn-outline btn-back" id="backBtn">Back</button>
            </div>
        </div>
    </div>
</section>


<!-- ===========SIGNATURE DISH=========== -->
<section class="padding-bottom padding-top">
    <div class="container">
        <div class="row justify-content-between flex-column-reverse flex-lg-row">
            <div class="col-lg-5">
                <form method="POST" id="contactForm">
                    <?php if (isset($_SESSION['form_succ'])) { ?>
                        <div class="col-lg-12 col-lg-12 mb-lg-4" id="formMessage">
                            <?php if ($_SESSION['form_succ'] == 2) { ?>
                                <div class="message error" id="global-error-msg">
                                    Error submitting form. Try again.
                                    <button class="close-btn"
                                        onclick="this.parentElement.style.display='none';">&times;</button>
                                </div>
                            <?php } ?>
                            <?php if ($_SESSION['form_succ'] == 1) { ?>
                                <div class="message success" id="global-succes-msg">
                                    Thank you for your submission! We will be in touch soon
                                    <button class="close-btn"
                                        onclick="this.parentElement.style.display='none';">&times;</button>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                        unset($_SESSION['form_succ']);
                    }
                    ?>
                    <!-- <div class="col-lg-12">
                            <?php if (isset($success_msg)): ?>
                                <small class="text-success"><?= $success_msg; ?></small>
                            <?php endif; ?>
                        </div> -->
                    <div class="row contact-form">
                        <div class="col-lg-6 mb-4">
                            <label for="">First Name</label>
                            <input type="text" name="FirstName" class="form-control w-100" id="FirstName"
                                placeholder="Enter First Name" value="<?= $FirstName ?? '' ?>">
                            <?php if (isset($errors['FirstName'])): ?>
                                <small class="text-danger"><?= $errors['FirstName']; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label for="">Last Name</label>
                            <input type="text" name="LastName" class="form-control w-100" id="lastname"
                                placeholder="Enter Last Name" value="<?= $LastName ?? '' ?>">
                            <?php if (isset($errors['LastName'])): ?>
                                <small class="text-danger"><?= $errors['LastName']; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label for="">Email</label>
                            <input type="text" name="EmailId" class="form-control w-100" id="EmailId"
                                placeholder="name@example.com" value="<?= $EmailId ?? '' ?>">
                            <?php if (isset($errors['EmailId'])): ?>
                                <small class="text-danger"><?= $errors['EmailId']; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label for="">Phone Number</label>
                            <input type="text" name="MobileNumber" class="form-control w-100" id="MobileNumber"
                                placeholder="Enter Phone" value="<?= $MobileNumber ?? '' ?>">
                            <?php if (isset($errors['MobileNumber'])): ?>
                                <small class="text-danger"><?= $errors['MobileNumber']; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-12 mb-4">
                            <label for="">Select Dietitian</label>
                            <select class="form-select form-control w-100">
                                <option value="">Kiram El Tbayli</option>
                                <option value="">Marisa Bousaba</option>
                                <option value="">Mireille Ahmad</option>
                                <option value="">Clinical Dietitian</option>
                                <option value="">Sara Obeid</option>
                                <option value="">Eman Baker</option>
                            </select>
                        </div>
                        <div class="col-lg-12 mb-4">
                            <label for="">Write your message</label>
                            <textarea class="min-height form-control" name="Message"
                                id="Message"><?= $Message ?? '' ?></textarea>
                            <?php if (isset($errors['Message'])): ?>
                                <small class="text-danger"><?= $errors['Message']; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-12 mb-4">
                            <div class="d-flex light-bg">
                                <button type="submit" name="submit" class="btn-fancy" value="contactform"><span>
                                        <svg id="loader" class="button-spinner" viewBox="0 0 50 50">
                                            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5" />
                                        </svg>
                                        Send Message</span>
                                    <!-- <a href="#" class="btn-fancy">
                                        <span>Send Message</span>
                                        <i class="ti ti-arrow-up-right"></i>
                                    </a> -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <img src="img/contact-img.webp" class="w-100 mb-4 mb-lg-0 all-imag-height" alt="">
            </div>
        </div>
    </div>
</section>

<section class="bg-light pt-5 padding-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <h4>Locations</h4>
            </div>
            <div class="col-lg-4">
                <div class="box-border">
                    <h5>Abu Dhabi</h5>
                    <p>Al Bateen Street, Facing ADNOC Service Station. Villa A</p>
                    <div class="map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d649484.5144062917!2d53.739454689062484!3d24.458185999999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5e66072b263307%3A0xbdeaaad4b24e4804!2sADNOC%20Service%20Station%20%7C%20Souq%20Al%20Bateen%20(969)!5e1!3m2!1sen!2sin!4v1755008524635!5m2!1sen!2sin"
                            width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="box-border">
                    <h5> Al Ain</h5>
                    <p>402 Al Dirasah St - 'Asharij - Bida Bin Ammar</p>
                    <div class="map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5084.507517689781!2d55.66057867643064!3d24.198462371425553!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e8aae220160a509%3A0x5c4a072a3d6389a8!2s402%20Al%20Dirasah%20St%20-%20&#39;Asharij%20-%20Bida%20Bin%20Ammar%20-%20Abu%20Dhabi%20-%20United%20Arab%20Emirates!5e1!3m2!1sen!2sin!4v1755008602058!5m2!1sen!2sin"
                            width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="box-border">
                    <h5>Dubai</h5>
                    <p>Office 103, Al Mardoof Bldg, Block A, Shk Zayed Road,Landmark: Near Medcare</p>
                    <div class="map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5044.721914142285!2d55.243095076449094!3d25.17755053249413!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f6b08e07467bb%3A0xd7ffd0197e6cb1f9!2sMedcare%20Management%20Office!5e1!3m2!1sen!2sin!4v1755008663217!5m2!1sen!2sin"
                            width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // $('#contactForm').on('submit', function() {
    //     $('#loader').show(); // Show loader before form submits
    // });
</script>
<?php
include './partials/footer.php';
?>
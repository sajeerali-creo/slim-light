<?php
include 'partials/header.php'
    ?>
<!-- ===========BANNER=========== -->
<section class="inner-banner">
</section>

<!-- ===========SUBHEADER=========== -->
<section class="py-5 bg-inner-sub-head">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between">
            <div class="d-flex flex-column align-items-start">
                <h4 class="h2 text-white">Eat Better, <span class="font-weight-200">Not Less!</span></h4>
                <p class="text-white opacity-50">Leave Your Shopping, Cooking and Calorie Counting to us!</p>
            </div>
            <div>
                <button class="btn-outline btn-back" id="backBtn">Back</button>
            </div>
        </div>
    </div>
</section>


<!-- ===========MEALPLAN=========== -->
<?php
$query = mysqli_query($connection, "select * from meal_plans");
?>
<section class="padding-bottom padding-top">
    <div class="container">
        <div class="row">
            <?php while ($result = mysqli_fetch_array($query)) { ?>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up">
                    <div class="meal-box">
                        <a href="<?= ROOT_URL ?>plan_details.php?id=<?= $result['id'] ?>" class="plan-link">
                            <div class="over-lay">
                                <h4><?php echo $result['title']; ?></h4>
                                <p><?php echo strip_tags(stripcslashes(html_entity_decode($result['sub_title']))); ?></p>
                                <a href="<?= ROOT_URL ?>plan_details.php?id=<?= $result['id'] ?>"
                                    class="d-flex gap-2 btn-booknow"><i class="ti ti-calendar-due text-white"></i>Book
                                    Now</a>
                            </div>
                            <img src="<?php echo ADMIN_URL . $result['file_path']; ?>" alt="">
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="padding-bottom">
    <div class="container">
        <div class="w-100 hero-meal d-flex justify-content-center align-items-center flex-column">
            <h2 class="h2 text-center">Need help finding the <br class="d-none d-lg-block"><span
                    class="font-weight-200">right meal plan?</span></h2>
            <p class="text-white text-center">Tell us a little about your goals and we’ll create the <br
                    class="d-none d-lg-block">perfect plan tailored just for you.</p>
            <a href="<?= ROOT_URL ?>contact.php" class="btn btn-primary">Let’s go!</a>
        </div>
    </div>
</section>

<div id="myModal" class="modal-auto">
    <div class="modal-auto-content">
        <span class="close-auto">&times;</span>
        <div class="w-100 hero-meal d-flex justify-content-center align-items-center flex-column">
            <h2 class="h2 text-center">Need help finding the <br class="d-none d-lg-block"><span
                    class="font-weight-200">right meal plan?</span></h2>
            <p class="text-white text-center">Tell us a little about your goals and we’ll create the <br
                    class="d-none d-lg-block">perfect plan tailored just for you.</p>
            <a href="<?= ROOT_URL ?>contact.php" class="bg-lime-button btn btn-primary px-5">Let’s go!</a>
        </div>
    </div>
</div>


<?php
include './partials/footer.php';
?>
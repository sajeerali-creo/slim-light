<?php
include 'partials/header.php';
require_once 'services/commonService.php';
?>

<div class="message d-flex justify-content-center align-items-center w-100 h-100" id="notice">
  <img src="img/logo-animated2.gif" alt="">
</div>

<section class="hero-video" role="banner" aria-label="Hero video banner">
    <!-- Replace video.mp4 with your hosted mp4/webm. Add webm source first if you have it. -->
    <video id="heroVideo" autoplay muted loop playsinline preload="metadata" poster="poster.jpg" aria-hidden="true">
        <!-- Provide webm then mp4 for broad compatibility -->
        <source src="img/video.webm" type="video/webm">
        <!-- <source src="video.mp4" type="video/mp4"> -->
        <!-- If video not supported, the background image div will show -->
    </video>

    <!-- Fallback background (mobile/touch devices): set poster.jpg or any image -->
    <div class="hero-bg" id="heroBg" style="background-image: url('poster.jpg');" aria-hidden="true"></div>

    <div class="hero-overlay" aria-hidden="true"></div>

    <div class="hero-content">
        <h1 class="hero-title">Healthy, tasty meals <span class="font-weight-200">delivered to you.</span></h1>
        <p class="hero-sub">Your journey to a healthier lifestyle starts here—fresh, flavorful, and tailored just for you.</p>

        <div class="hero-actions">
            <a class="btn btn-primary" href="<?= ROOT_URL ?>contact.php" role="button" aria-label="Shop Now">Book Your Consultation</a>
            <a class="btn btn-secondary" href="<?= ROOT_URL ?>meal_plans.php" role="button" aria-label="Browse Collections">Browse
                Meal Plans</a>
        </div>
    </div>

</section>

<!-- hide -->
<section class="d-none">
    <?php
    $query = mysqli_query($connection, "SELECT * FROM banners");

    $row_count = mysqli_num_rows($query);
    ?>
    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <?php
            for ($i = 0; $i < $row_count; $i++) {
                $active = "";
                if ($i == 0) {
                    $active = 'active';
                }
                ; ?>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="<?php echo $i; ?>"
                    class="<?php echo $active; ?>"></button>
            <?php } ?>
            <!-- <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1"></button>
              <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2"></button> -->
        </div>

        <!-- Slides -->

        <div class="carousel-inner">
            <?php
            $i = 1;
            while ($result = mysqli_fetch_array($query)) {
                $active = "";
                if ($i == 1) {
                    $active = 'active';
                }
                ;
                ?>
                <div class="carousel-item <?php echo $active; ?>">
                    <img src="<?php echo ADMIN_URL . $result['file_path']; ?>" class="d-block w-100" alt="Slide 1">
                    <div class="carousel-caption">
                        <h1><?php echo $result['heading']; ?></h1>
                        <p class="mt-3"><?php echo $result['sub_heading']; ?></p>
                        <?php if ($result['button_text'] != '') { ?>
                            <div class="d-flex justify-content-center mt-3 dark-bg">
                                <a href="<?php echo $result['button_url'] != '' ? $result['button_url'] : '#'; ?>"
                                    class="btn-fancy">
                                    <span><?php echo $result['button_text']; ?></span>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php $i++;
            } ?>

        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
            <i class="ti ti-arrow-narrow-left"></i>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
            <i class="ti ti-arrow-narrow-right"></i>
        </button>
    </div>
</section>
<!-- ===========ABOUTUS=========== -->
<?php
$query = mysqli_query($connection, "select * from admin_home limit 1");
$who_we_are = "";
$how_it_work = "";
$why_bLite = "";
$exciting_news = "";
while ($result = mysqli_fetch_array($query)) {
    $who_we_are = html_entity_decode($result['who_we_are']);
    $how_it_work = html_entity_decode($result['how_it_work']);
    $why_bLite = html_entity_decode($result['why_bLite']);
    $exciting_news = html_entity_decode($result['exciting_news']);
}
?>
<section class="padding-top padding-bottom">
    <?php echo $who_we_are; ?>
</section>

<!-- ===========MEALPLAN=========== -->
<?php
$query = mysqli_query($connection, "select * from meal_plans limit 4");
?>
<section class="padding-bottom padding-top bgmeal-plan">
    <div class="container mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h2">Meal <span class="font-weight-200">Plans</span></h2>
            <div class="d-flex light-bg">
                <a href="<?php echo ROOT_URL . 'meal_plans.php'; ?>" class="btn-fancy">
                    <span>View All</span>
                </a>
            </div>
        </div>
    </div>
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

<!-- ===========HOWITWORKS=========== -->
<section class="padding-bottom padding-top">
    <?php echo $how_it_work; ?>
</section>
<!-- ===========SIGNATURE DISHES=========== -->
<?php
$query = mysqli_query($connection, "SELECT * FROM signature_dishes");
$row_count = mysqli_num_rows($query);
?>
<section class="padding-bottom padding-top bg-shade-1">
    <div class="container mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h2">Signature <span class="font-weight-200">Dishes</span></h2>
        </div>
    </div>
    <div class="px-lg-5 px-4">
        <div id="cardCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="story-slide">
                    <?php while ($result = mysqli_fetch_array($query)) { ?>
                        <div class="slide-2"><img src="<?php echo ADMIN_URL . $result['file_path']; ?>" alt="">
                            <p><?php echo $result['name']; ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===========Why BLite – Trusted by Thousands Across the UAE=========== -->
<section class="padding-bottom padding-top">
    <?php echo $why_bLite; ?>
</section>
<!-- ===========Testimonials=========== -->
<?php
$query = mysqli_query($connection, "SELECT * FROM testimonials ORDER BY id DESC LIMIT 3");
?>
<section class="padding-bottom padding-top border-top">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="h2">Our <span class="font-weight-200">Testimonials</span></h2>
        </div>
        <div class="row">
            <?php while ($result = mysqli_fetch_array($query)) { ?>
                <div class="col-lg-4 mb-4">
                    <div class="testibox p-4 p-lg-5">
                        <div class="stars mb-4">
                            <?php
                            for ($i = 0; $i < $result['rating']; $i++) {
                                echo '<i class="ti ti-star-filled"></i>';
                            } ?>
                        </div>
                        <p><?php
                        echo safeLimitString($result['message'], 270); ?></p>
                        <div class="d-flex gap-2 mt-4">
                            <div class="head">
                                <img src="<?php echo ADMIN_URL . $result['file_path']; ?>" alt="">
                            </div>
                            <div class="details">
                                <h5><?php echo $result['message_from']; ?></h5>
                                <small><?php echo $result['business_name']; ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<!-- ===========SUCCESS STORIES=========== -->
<?php
$query = mysqli_query($connection, "SELECT * FROM success_stories");
?>
<section class="padding-bottom padding-top bg-stories">
    <div class="container text-center mb-5">
        <h2 class="h2">SUCCESS <span class="font-weight-200">STORIES</span></h2>
    </div>
    <div id="cardCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="story-slide">
                <?php while ($result = mysqli_fetch_array($query)) { ?>
                    <div class="card"><img src="<?php echo ADMIN_URL . $result['file_path']; ?>" class="card-img-top"
                            alt="..."></div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<!-- ===========corporate clients=========== -->
<?php
$query = mysqli_query($connection, "SELECT * FROM clients");
?>
<section class="padding-bottom padding-top bg-client">
    <div class="container text-center mb-5">
        <h2 class="h2">corporate <span class="font-weight-200">clients</span></h2>
    </div>
    <div class="container">
        <div class="client-logo-slider">
            <?php while ($result = mysqli_fetch_array($query)) { ?>
                <div class="box-logo d-flex justify-content-center align-items-center"><img
                        src="<?php echo ADMIN_URL . $result['file_path']; ?>" alt="Client Logo"></div>
            <?php } ?>
            <!-- <div><img src="img/logo-2.webp" alt="Client Logo"></div>
              <div><img src="img/logo-3.webp" alt="Client Logo"></div>
              <div><img src="img/logo-4.webp" alt="Client Logo"></div>
              <div><img src="img/logo-5.webp" alt="Client Logo"></div> -->
        </div>
    </div>
</section>
<!-- ===========hereo=========== -->
<section class="padding-bottom padding-top bg-hero">
    <?php echo $exciting_news; ?>
</section>
<?php
include './partials/footer.php';
?>
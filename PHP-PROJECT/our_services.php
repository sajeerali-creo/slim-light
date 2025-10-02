<?php
include 'partials/header.php'
    ?>
<!-- ===========BANNER=========== -->
<section class="inner-banner">
</section>

<!-- ===========SUBHEADER=========== -->



<!-- ===========SIGNATURE DISH=========== -->
<?php
$query = mysqli_query($connection, "select * from services");
?>
<section class="padding-bottom padding-top bg-srv">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="row">
                    <div class="col-lg-7 mb-4">
                        <div class="service-hero-card">
                            <h3 class="text-lime mb-4 h2">Our <span class="font-weight-200">Services</span></h3>
                            <p class="paragraph text-lime opacity-50">From personalized meal plans and expert dietary consultations to
                                corporate
                                wellness
                                programs and
                                advanced slimming treatments, we provide complete solutions designed to help you look,
                                feel,
                                and
                                live your best every day.</p>
                            <div class="d-flex mt-5 light-bg" bis_skin_checked="1">
                                <a href="<?= ROOT_URL ?>contact.php" class="btn-outline">
                                    <span>Book Now</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 mb-4">
                        <div class="d-flex gap-3">
                            <div class="w-card">
                                <img src="img/s-m-1.webp" alt="">
                            </div>
                            <div class="w-card">
                                <img src="img/s-m-2.webp" alt="">
                            </div>
                            <div class="w-card">
                                <img src="img/s-m-3.webp" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="row row-gap-4">
                    <?php while ($result = mysqli_fetch_array($query)) { ?>
                        <div class="col-xl-3 col-lg-6" data-aos="fade-up">
                            <div class="service-main-box d-flex flex-column justify-content-between">
                                <!-- icon -->
                                <div>
                                    <div class="mb-4">
                                        <img src="<?php echo $result['icon_file_path'] != '' ? ADMIN_URL . $result['icon_file_path'] : ADMIN_URL . 'dist/img/no-image-available.png'; ?>"
                                            alt="" height="66">
                                    </div>
                                    <!-- content -->
                                    <div>
                                        <h3 class="fs-4 text-brand"><?php echo $result['title']; ?></h3>
                                        <p class="paragraph">
                                            <?php echo safeLimitString(html_entity_decode($result['description']), 100); ?>
                                        </p>
                                    </div>
                                </div>
                                <!-- action -->
                                <div class="d-flex">
                                    <a href="<?= ROOT_URL ?>service_details.php?id=<?= $result['id'] ?>"
                                        class="d-flex gap-2 btn btn-primary text-lime">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
include './partials/footer.php';
?>
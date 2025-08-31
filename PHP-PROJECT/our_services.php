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
<section class="padding-bottom padding-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <h3 class="text-brand mb-4 h2">Our Services</h3>
                <p class="paragraph">From personalized meal plans and expert dietary consultations to corporate wellness
                    programs and
                    advanced slimming treatments, we provide complete solutions designed to help you look, feel, and
                    live your best every day.</p>
                <div class="d-flex mt-5 light-bg mb-5" bis_skin_checked="1">
                    <a href="<?= ROOT_URL ?>contact.php" class="btn-fancy">
                        <span>Book Now</span>
                        <i class="ti ti-arrow-up-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row row-gap-4">
                    <?php while ($result = mysqli_fetch_array($query)) { ?>
                        <div class="col-lg-6" data-aos="fade-up">
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
                                    <a href="<?= ROOT_URL ?>service_details.php?id=<?= $result['id'] ?>" class="d-flex gap-2 btn-cta">
                                        Read More<i class="ti ti-arrow-up-right"></i>
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
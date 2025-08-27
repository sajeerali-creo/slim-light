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
                    <h4 class="h2">Our Services</h4>
                </div>
                <!-- <div>
                    <div class="bredcums">
                        <a href="<?= ROOT_URL ?>">Home</a>/ Our Services
                    </div>
                </div> -->
            </div>
        </div>
    </section>


    <!-- ===========SIGNATURE DISH=========== -->
     <?php
     $query=mysqli_query($connection,"select * from services");
     ?>
    <section class="padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <?php while($result=mysqli_fetch_array($query)){ ?>
                    <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up">
                        <div class="meal-box">
                            <a href="<?= ROOT_URL ?>service_details.php?id=<?= $result['id'] ?>" class="plan-link">
                                <div class="over-lay">
                                    <h4><?php echo $result['title'];?></h4>
                                    <p><?php echo safeLimitString(html_entity_decode($result['description']), 100); ?></p>
                                </div>
                                <img src="<?php echo $result['file_path'] != '' ? ADMIN_URL.$result['file_path'] : ADMIN_URL.'dist/img/no-image-available.png'; ?>" alt="">
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php
include './partials/footer.php';
?>
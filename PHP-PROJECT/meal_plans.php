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
                    <h4 class="h2">Eat Better, Not Less!</h4>
                    <p>Leave Your Shopping, Cooking and Calorie Counting to us!</p>
                </div>
                <!-- <div>
                    <div class="bredcums">
                        <a href="<?= ROOT_URL ?>">Home</a>/ Meal Plans
                    </div>
                </div> -->
            </div>
        </div>
    </section>


    <!-- ===========MEALPLAN=========== -->
     <?php
     $query=mysqli_query($connection,"select * from meal_plans");
     ?>
    <section class="padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <?php while($result=mysqli_fetch_array($query)){ ?>
                    <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up">
                        <div class="meal-box">
                            <a href="<?= ROOT_URL ?>plan_details.php?id=<?= $result['id'] ?>" class="plan-link">
                                <div class="over-lay">
                                    <h4><?php echo $result['title'];?></h4>
                                    <p><?php echo strip_tags(stripcslashes(html_entity_decode($result['sub_title']))); ?></p>
                                </div>
                                <img src="<?php echo ADMIN_URL.$result['file_path'];?>" alt="">
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
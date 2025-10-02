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
                    <h4 class="h2 text-white">Blogs</h4>
                </div>
                <div>
                <a class="btn-outline btn-back" href="<?= ROOT_URL ?>">Back</a>
            </div>
            </div>
        </div>
    </section>


    <!-- ===========SIGNATURE DISH=========== -->
     <?php
     $query=mysqli_query($connection,"select * from blogs");
     ?>
  <section class="padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <?php while($result=mysqli_fetch_array($query)){ ?>
                    <div class="col-lg-4 mb-4">
                        <div class="sig-box mb-4">
                            <img src="<?php echo $result['file_path'] != '' ? ADMIN_URL.$result['file_path'] : ADMIN_URL.'dist/img/no-image-available.png'; ?>" alt="">
                        </div>
                        <h3 class="h3"><?php echo safeLimitString($result['title'], 30);?></h3>
                        <div class="mb-4">
                            <p><i class="ti ti-calendar"></i> <?php
                            $blog_date = new DateTime($result['date_created']); // You can replace this with any date                            
                            echo $blog_date->format('d/m/Y'); ?></p>
                        </div>
                        <div class="min-height">
                            <p><?php 
                            echo safeLimitString(html_entity_decode($result['description']), 170);
                            ?></p>
                        </div>
                        <div class="d-flex light-bg">
                            <a href="<?= ROOT_URL ?>blog_details.php?id=<?= $result['id']; ?>" class="btn-fancy">
                                <span>Read More</span>
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
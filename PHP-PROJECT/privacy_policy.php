<?php 
include 'partials/header.php';

// Fetch Privacy Policy from database
$query = mysqli_query($connection, "SELECT * FROM privacy_policy WHERE is_active = 1 ORDER BY date_updated DESC LIMIT 1");
$policy = null;
if($query && mysqli_num_rows($query) > 0) {
    $policy = mysqli_fetch_assoc($query);
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
                    <h4 class="h2">Privacy Policy</h4>
                </div>
                <!-- <div>
                    <div class="bredcums">
                        <a href="/">Home</a>/ Privacy Policy
                    </div>
                </div> -->
            </div>
        </div>
    </section>

    <!-- ===========PRIVACY POLICY CONTENT=========== -->
    <section class="padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <?php if($policy): ?>
                        <div class="privacy-content">
                            <div class="content-body">
                                <?php echo html_entity_decode($policy['content']); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="alert alert-info">
                                <h5>Privacy Policy</h5>
                                <p>Privacy policy is currently being updated. Please check back soon.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php
include './partials/footer.php';
?>
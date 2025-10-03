<?php 
include 'partials/header.php';

// Fetch Terms & Conditions from database
$query = mysqli_query($connection, "SELECT * FROM terms_conditions WHERE is_active = 1 ORDER BY date_updated DESC LIMIT 1");
$terms = null;
if($query && mysqli_num_rows($query) > 0) {
    $terms = mysqli_fetch_assoc($query);
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
                    <h4 class="h2 text-white">Terms & <span class="font-weight-200">Conditions</span></h4>
                </div>
                <div>
                <button class="btn-outline btn-back" id="backBtn">Back</button>
            </div>
            </div>
        </div>
    </section>

    <!-- ===========TERMS & CONDITIONS CONTENT=========== -->
    <section class="padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <?php if($terms): ?>
                        <div class="terms-content">
                            <div class="content-body">
                                <?php echo html_entity_decode($terms['content']); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="alert alert-info">
                                <h5>Terms & Conditions</h5>
                                <p>Terms and conditions are currently being updated. Please check back soon.</p>
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
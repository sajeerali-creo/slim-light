<?php 
include 'partials/header.php';

// Fetch FAQs from database
$query = mysqli_query($connection, "SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
$faqs = [];
while($row = mysqli_fetch_assoc($query)) {
    $faqs[] = $row;
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
                    <h4 class="h2 text-white">Frequently Asked <span class="font-weight-200">Questions</span></h4>
                </div>
                <div>
                <button class="btn-outline btn-back" id="backBtn">Back</button>
            </div>
            </div>
        </div>
    </section>


    <!-- ===========FAQ SECTION=========== -->
    <section class="padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="faq-container">
                        <?php if(!empty($faqs)): ?>
                            <?php foreach($faqs as $index => $faq): ?>
                        <div class="faq-item">
                                    <input type="checkbox" id="faq<?php echo $faq['id']; ?>" class="faq-toggle">
                                    <label for="faq<?php echo $faq['id']; ?>" class="faq-question">
                                        <?php echo htmlspecialchars($faq['question']); ?>
                                <span class="arrow"></span>
                            </label>
                            <div class="faq-answer">
                                        <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                            </div>
                        </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <p class="text-muted">No FAQs available at the moment.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
include './partials/footer.php';
?>
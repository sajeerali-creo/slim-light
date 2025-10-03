<?php
include 'partials/header.php';

$gallery_dir = ADMIN_UPLOAD_URL . "team_gallary/";

$query = mysqli_query($connection, "SELECT * FROM about_us LIMIT 1");
$section_1 = "";
$section_2 = "";
$section_3 = "";
$exciting_news = "";
$team_section_title = "";
$team_section_sub_title = "";
$team_section_description = "";
$gallery_list = [];
if ($query) {
    // Fetch the first row of data
    while ($result = mysqli_fetch_array($query)) {
        $section_1 = html_entity_decode($result['section_1_content']);
        $section_2 = html_entity_decode($result['section_2_content']);
        $section_3 = html_entity_decode($result['section_3_content']);

        $team_section_title = $result['team_section_title'];
        $team_section_sub_title = $result['team_section_sub_title'];
        $team_section_description = $result['team_section_description'];

        $gallery_images = $result['gallery_images'];
        $gallery_list = explode('$$', $gallery_images);
    }

    // Optionally, print the last fetched row from the loop
//    print_r($result); 
} else {
    echo "Query failed: " . mysqli_error($connection);
}

?>

<?php echo $section_1; ?>

<!-- ===========SUBHEADER=========== -->
<section class="padding-bottom bg-gray padding-top" id="team">
    <div class="container mb-5" bis_skin_checked="1">
        <div class="row" bis_skin_checked="1">
            <div class="col-lg-6" data-aos="fade-up" bis_skin_checked="1">
                <?php if ($team_section_title != '') { ?>
                    <h2 class="h2"><?php echo $team_section_title; ?></h2>
                <?php } ?>
                <?php if ($team_section_sub_title != '') { ?>
                    <p><?php echo $team_section_sub_title; ?></p>
                <?php } ?>
            </div>
            <div class="col-lg-6" data-aos="fade-up" bis_skin_checked="1">
                <?php if ($team_section_description != '') { ?>
                    <p><?php echo $team_section_description; ?>
                    </p>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="container" bis_skin_checked="1">
        <div class="row" bis_skin_checked="1">
            <?php
            if ($gallery_list != '') {

                foreach ($gallery_list as $item) {
                    [$img, $name, $designation, $description] = array_pad(explode('::', $item, 4), 4, '');
                    $description_data = html_entity_decode($description);
                    ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" bis_skin_checked="1">
                        <a href="#" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                            aria-controls="offcanvasRight" data-img="<?= $gallery_dir . $img ?>"
                            data-name="<?= htmlspecialchars($name) ?>" data-designation="<?= htmlspecialchars($designation) ?>"
                            data-description="<?= htmlspecialchars($description_data) ?>">
                            <div class="main-box-team">
                                <div class="team-box">
                                    <img src="<?= $gallery_dir . $img ?>" alt="Uploaded Image" style="width: 100%;">
                                    
                                </div>
                                <div class="p-4">
                                    <h5><?= $name ?></h5>
                                    <div class="opacity-50"><?= $designation ?></div>
                                    <i class="ti ti-eye"></i>
                                </div>
                            </div>
                        </a>

                    </div>
                <?php }
            } ?>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">Dietitian Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column align-items-center">
            <!-- image -->
            <div class="dietitian-pic">Image</div>
            <div class="d-flex gap-3 justify-content-center flex-column align-items-center">
                <!-- name -->
                <div class="dietitian-pic-name fs-4">name</div>
                <!-- Designation -->
                <h3 class="fs-6">designation</h3>
            </div>

            <!-- text editor -->
            <div class="dietitian-description">
                description
            </div>

            <div class="d-flex justify-content-center mt-5 w-100 d-flex justify-content-center">
                <a href="<?= ROOT_URL ?>contact.php" class="btn btn-primary w-100 d-flex justify-content-center bg-lime-button">Book Dietitian Now</a>
            </div>
        </div>
    </div>

</section>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var offcanvasRight = document.getElementById('offcanvasRight');

        offcanvasRight.addEventListener('show.bs.offcanvas', function (event) {
            var trigger = event.relatedTarget; // the link that opened the offcanvas

            if (trigger) {
                var img = trigger.getAttribute('data-img');
                var name = trigger.getAttribute('data-name');
                var designation = trigger.getAttribute('data-designation');
                var description = trigger.getAttribute('data-description');

                // set values inside the sidebar
                offcanvasRight.querySelector('.dietitian-pic').innerHTML = '<img src="' + img + '" class="img-fluid rounded" style="max-width:200px;">';
                offcanvasRight.querySelector('.dietitian-pic-name').textContent = name;
                offcanvasRight.querySelector('.fs-6').textContent = designation;
                offcanvasRight.querySelector('.dietitian-description').innerHTML = description;
            }
        });
    });
</script>

<!-- ===========SUBHEADER=========== -->
<?php echo $section_2; ?>
<?php echo $section_3; ?>
<?php
//include "admin/about.html"; ?>

<!-- ===========Testimonials=========== -->
<?php
$query = mysqli_query($connection, "SELECT * FROM testimonials ORDER BY id DESC LIMIT 3");
?>
<section class="padding-bottom padding-top border-top" id="testimonial">
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

<?php
include './partials/footer.php';
?>
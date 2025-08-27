<?php 
include 'partials/header.php';

$gallery_dir = ADMIN_UPLOAD_URL . "team_gallary/";

$query = mysqli_query($connection, "SELECT * FROM about_us LIMIT 1");
$section_1 = "";
$section_2 = "";
$section_3 = "";
$exciting_news = "";
$team_section_title ="";
$team_section_sub_title = "";
$team_section_description = "";
$gallery_list = [];
if ($query) {
    // Fetch the first row of data
    while($result = mysqli_fetch_array($query)){
        $section_1 = html_entity_decode($result['section_1_content']);
        $section_2 = html_entity_decode($result['section_2_content']);
        $section_3 = html_entity_decode($result['section_3_content']);

        $team_section_title = $result['team_section_title'];
        $team_section_sub_title	 = $result['team_section_sub_title'];
        $team_section_description = $result['team_section_description'];

        $gallery_images = $result['gallery_images'];
        $gallery_list = explode(',', $gallery_images);
    }

    // Optionally, print the last fetched row from the loop
//    print_r($result); 
} else {
    echo "Query failed: " . mysqli_error($connection);
}

    ?>
      
    <?php echo $section_1; ?>

    <!-- ===========SUBHEADER=========== -->
    <section class="padding-bottom padding-top bg-gray">
        <div class="container mb-5" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="col-lg-6" data-aos="fade-up" bis_skin_checked="1">
                    <?php if($team_section_title != ''){ ?>
                        <h2 class="h2"><?php echo $team_section_title; ?></h2>
                    <?php } ?>
                    <?php if($team_section_sub_title != ''){ ?>
                        <p><?php  echo $team_section_sub_title; ?></p>
                    <?php } ?>
                </div>
                <div class="col-lg-6" data-aos="fade-up" bis_skin_checked="1">
                    <?php if($team_section_description != ''){ ?>
                    <p><?php echo $team_section_description; ?>
                    </p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="container" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
              <?php  
              if($gallery_list != ''){
                foreach ($gallery_list as $file) {                          
                    if (empty($file)) continue; ?>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4" data-aos="fade-up" bis_skin_checked="1">
                            <div class="team-box" bis_skin_checked="1">
                                <img src="<?php echo $gallery_dir . $file ?>" alt="Uploaded Image" style="width: 100%;">
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
        </div>

    </section>

    <!-- ===========SUBHEADER=========== -->
    <?php echo $section_2; ?>
    <?php echo $section_3; ?>
<?php
//include "admin/about.html"; ?>
<?php
include './partials/footer.php';
?>
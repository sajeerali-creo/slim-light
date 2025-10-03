<?php 
include 'partials/header.php';

if(isset($_REQUEST['id']) && $_REQUEST['id'] != ""){
	$qry = $connection->query("SELECT * from `services` where id = '{$_GET['id']}' ");
	foreach($qry->fetch_array() as $k => $v){
		if(!is_numeric($k)){
			$$k = $v;
		}
	}
}
else{
    header('Location: '. ROOT_URL);
    exit;
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
                    <h4 class="h2 text-white"><?php echo isset($title) ? $title : '' ?></h4>
                </div>
                     <div>
                <button class="btn-outline btn-back" id="backBtn">Back</button>
            </div>
            </div>
        </div>
    </section>


    <!-- ===========SIGNATURE DISH=========== -->
    <section class="padding-bottom padding-top">
        <div class="container">
            <div class="row flex-lg-row">
                
                 <div class="col-lg-7">
                    <div class="min-height">
                        <p><?= $description ? html_entity_decode($description) : '' ?></p>
                    </div>
                    <div class="d-flex light-bg">
                        <?php if($button_label != ""){ ?>
                                
                                    <a href="<?= $button_link == "" ? ROOT_URL.'contact.php' : $button_link ?>" 
                                    class="btn-fancy" 
                                    id="dynamic-link" 
                                    data-link="<?= $button_link ?>">

                                        <span><?= $button_label ?></span>
                                    </a>
                             
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var link = document.getElementById('dynamic-link');
                                        var linkUrl = link.getAttribute('data-link');
                                        if (linkUrl.toLowerCase().endsWith('.pdf')) {
                                            link.setAttribute('target', '_blank');  
                                        } else {
                                            link.setAttribute('target', '_self');  
                                        }
                                    });
                                </script>
                            <?php } ?>
                    </div>
                </div>

                 <?php if($file_path != ''){ ?>
                <div class="col-lg-5 mb-4">
                    <div class="sig-box-details mb-4">
                        <img src="<?= $file_path ? ADMIN_URL.$file_path : '' ?>" alt="">
                    </div>
                </div>
                <?php } ?>
               
            </div>
        </div>
    </section>
<?php
include './partials/footer.php';
?>
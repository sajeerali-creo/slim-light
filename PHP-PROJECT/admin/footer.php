 <?php 
require 'config/database.php';
 $query = mysqli_query($connection, "SELECT * FROM contacts");
                while($row = $query->fetch_assoc()){
                    $meta[$row['meta_field']] = $row['meta_value'];
                } ?>
<footer class="padding-top">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="mb-5">
                        <img src="img/logo.svg" alt="">
                    </div>
                    <p class="text-white">We don’t just offer meals, we help shape lifestyles. Connect with us!</p>
                    <div class="d-flex flex-column gap-2">
                       <a href="tel:<?= $meta['mobile'] ?>" class="d-flex gap-2 align-items-center text-white"><i
                                class="ti ti-phone color-shade-2"></i><span><?= $meta['mobile'] ?></span></a>
                        <a href="mailto:<?= $meta['email'] ?>" class="d-flex gap-2 align-items-center text-white"><i
                                class="ti ti-mail color-shade-2"></i><span><?= $meta['email'] ?></span></a>
                    </div>
                    <div class="d-flex gap-2 app-icon mt-5">
                        <a href="#">
                            <img src="img/app-store.webp" alt="">
                        </a>
                        <a href="#">
                            <img src="img/google-play.webp" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="row links">
                        <div class="col-lg-4  mb-5 mb-lg-0">
                            <h5>Quicklinks</h5>
                            <div class="d-flex flex-column gap-3">
                                <a href="<?= ROOT_URL ?>">Home</a>
                                <a href="<?= ROOT_URL ?>about.php">About Us</a>
                                <a href="<?= ROOT_URL ?>contact.php">Contact Us</a>
                            </div>
                        </div>
                        <div class="col-lg-4  mb-5 mb-lg-0">
                            <?php
                                $query=mysqli_query($connection,"select * from meal_plans");
                                ?>
                            <h5>Meal Plans</h5>
                            <div class="d-flex flex-column gap-3">
                                <?php while($result=mysqli_fetch_array($query)){ ?>
                                <a href="<?= ROOT_URL ?>plan_details.php?id=<?= $result['id'] ?>"><?php echo $result['title']; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <h5>Learn More</h5>
                            <div class="d-flex flex-column gap-3">
                                <a href="<?= ROOT_URL ?>faq.php">FAQs</a>
                                <a href="<?= ROOT_URL ?>blogs.php">Blogs</a>
                                <a href="<?= ROOT_URL ?>terms_conditions.php">Terms & Conditions</a>
                                <a href="<?= ROOT_URL ?>privacy_policy.php">Privacy Policy</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="sub-footer py-4">
            <div class="d-flex align-items-center container flex-column flex-lg-row gap-3 gap-lg-0">
                <div class="col-lg-6 text-white">
                    © 2025 BLite Regime and Weight Control Services - L.L.C - S.P.C. All Rights Reserved.
                </div>
                <div class="col-lg-6 d-flex justify-content-end">
                    <div class="social-media gap-2">
                        <a target="_blank" href="https://www.facebook.com/blite.uae"><i class="ti ti-brand-facebook"></i></a>
                        <a target="_blank" href="https://www.instagram.com/blite.uae/"><i class="ti ti-brand-instagram"></i></a>
                        <a target="_blank" href="https://www.linkedin.com/company/blite-uae/"><i class="ti ti-brand-linkedin"></i></a>
                        <a target="_blank" href="https://www.threads.com/@blite.uae"><i class="ti ti-brand-threads"></i></a>
                        <a target="_blank" href="https://api.whatsapp.com/send?phone=971588052025"><i class="ti ti-brand-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<style>
  #formMessage .message,
  .bookingmodalcls .message
  {
    padding: 12px 16px;
    border-radius: 5px;
    margin-bottom: 12px;
    font-size: 14px;
    position: relative;
    animation: fadeIn 0.3s ease-in-out;
  }

  #formMessage .success,
  .bookingmodalcls .success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }

  #formMessage .error,
  .bookingmodalcls .error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }

  #formMessage .close-btn,
  .bookingmodalcls .close-btn {
    position: absolute;
    right: 10px;
    top: 6px;
    font-weight: bold;
    color: inherit;
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
  }
</style>
    <style>
        .button-spinner{
        width: 35px;
        padding-right: 10px;
        display: none;
        }
        #currentspinner, #targetspinner{
            animation: rotate 2s linear infinite;
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <!-- animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/main_2.js"></script>
    <script src="js/custom.js"></script>
    <script>
        AOS.init();
    </script>
    
 
</body>
</html>



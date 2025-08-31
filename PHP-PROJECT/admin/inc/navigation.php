<!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="<?php echo base_url ?>" class="brand-link text-sm">
        <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Store Logo" class="brand-image img-circle elevation-3" style="opacity: .8;width: 2.5rem;height: 2.5rem;max-height: unset">
        <span class="brand-text font-weight-light"><?php echo $_settings->info('short_name') ?></span>
        </a>
        <!-- Sidebar -->
        <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
          <div class="os-resize-observer-host observed">
            <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
          </div>
          <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
            <div class="os-resize-observer"></div>
          </div>
          <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
          <div class="os-padding">
            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
              <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                  <div class="image">
                    <img src="<?php echo validate_image($_settings->userdata('avatar')) ?>" class="img-circle elevation-2" alt="User Image" style="height: 2rem;object-fit: cover">
                  </div>
                  <div class="info">
                    <a href="<?php echo base_url ?>?page=user" class="d-block"><?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?></a>
                  </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                   <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item dropdown">
                      <a href="./" class="nav-link nav-home">
                        <i class="nav-icon ti ti-dashboard"></i>
                        <p>
                          Dashboard
                        </p>
                      </a>
                    </li> 
                    <li class="nav-header">Contents</li>
                     <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=banners" class="nav-link nav-meal-plans">
                        <i class="nav-icon ti ti-picture-in-picture"></i>
                        <p>
                          Banners
                        </p>
                      </a>
                    </li>
                     <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=home_page" class="nav-link nav-home_page">
                        <i class="nav-icon ti ti-home"></i>
                        <p>
                          Home
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=about" class="nav-link nav-about">
                        <i class="nav-icon ti ti-user"></i>
                        <p>
                          About
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=meal_plans" class="nav-link nav-meal-plans">
                        <i class="nav-icon ti ti-play-card-1"></i>
                        <p>
                          Meal Plans
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=signature_dishes" class="nav-link nav-signature_dishes">
                        <i class="nav-icon ti ti-bowl-spoon"></i>
                        <p>
                          Signature Dishes
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=success_stories" class="nav-link nav-success_stories">
                        <i class="nav-icon ti ti-rosette-discount-check"></i>
                        <p>
                          Success Stories
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=services" class="nav-link nav-services">
                        <i class="nav-icon ti ti-layout-dashboard"></i>
                        <p>
                          Services
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=blogs" class="nav-link nav-blogs">
                        <i class="nav-icon ti ti-article"></i>
                        <p>
                          Blogs
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=clients" class="nav-link nav-clients">
                        <i class="nav-icon ti ti-square"></i>
                        <p>
                          Corporate Clients
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=testimonials" class="nav-link nav-testimonials">
                        <i class="nav-icon ti ti-stars"></i>
                        <p>
                          Testimonials
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=coupon_codes" class="nav-link nav-coupon_codes">
                        <i class="nav-icon ti ti-tag-starred"></i>
                        <p>
                          Coupon Codes
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=faqs" class="nav-link nav-faqs">
                        <i class="nav-icon ti ti-help-hexagon"></i>
                        <p>
                          FAQs
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=terms_conditions" class="nav-link nav-terms_conditions">
                        <i class="nav-icon ti ti-health-recognition"></i>
                        <p>
                          Terms & Conditions
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=privacy_policy" class="nav-link nav-privacy_policy">
                        <i class="nav-icon ti ti-alert-octagon"></i>
                        <p>
                          Privacy Policy
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=inquiries" class="nav-link nav-inquiries">
                        <i class="nav-icon ti ti-list"></i>
                        <p>
                          Inquiries
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=general_details" class="nav-link nav-general_details">
                        <i class="nav-icon ti ti-file-description"></i>
                        <p>
                          General Details
                        </p>
                      </a>
                    </li>
                  </ul>
                </nav>
                <!-- /.sidebar-menu -->
              </div>
            </div>
          </div>
          <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
              <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
            </div>
          </div>
          <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
              <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
            </div>
          </div>
          <div class="os-scrollbar-corner"></div>
        </div>
        <!-- /.sidebar -->
      </aside>
      <script>
    $(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
      var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      page = page.split('/');
      page = page[0];
      if(s!='')
        page = page+'_'+s;

      if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
        if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
          $('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
        }
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

      }
     
    })
  </script>
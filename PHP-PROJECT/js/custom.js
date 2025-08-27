/*try {
  const navItem = document.querySelector(".nav__items");
  const openNavBtn = document.querySelector("#open__nav-btn");
  const closeNavBtn = document.querySelector("#close__nav-btn");
  const openNav = () => {
    navItem.style.display = "flex";
    openNavBtn.style.display = "none";
    closeNavBtn.style.display = "inline-block";
  };
  const closeNav = () => {
    navItem.style.display = "none";
    openNavBtn.style.display = "inline-block";
    closeNavBtn.style.display = "none";
  };
  openNavBtn.addEventListener("click", openNav);
  closeNavBtn.addEventListener("click", closeNav);
} catch (error) {
  //   console.error("Error occurred in toggle functionality:", error);
}
if (window.innerWidth <= 600)
  try {
    const sidebar = document.querySelector("aside");
    const showSidebarBtn = document.querySelector("#show__sidebar-btn");
    const hideSidebarBtn = document.querySelector("#hide__sidebar-btn");

    const showSidebar = () => {
      sidebar.style.left = "0";
      showSidebarBtn.style.display = "none";
      hideSidebarBtn.style.display = "inline-block";
    };

    const hideSidebar = () => {
      sidebar.style.left = "-100%";
      showSidebarBtn.style.display = "inline-block";
      hideSidebarBtn.style.display = "none";
    };

    showSidebarBtn.addEventListener("click", showSidebar);
    hideSidebarBtn.addEventListener("click", hideSidebar);

    window.onload = showSidebar;
  } catch (error) {
    //   console.error("Error occurred in sidebar functionality:", error);
  }*/


$(document).ready(function () {
   /* $("#signupForm").validate({
        rules: {
            firstname: "required",
            lastname: "required",
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                minlength: 7
            },
            password: {
                required: true,
                minlength: 6
            },
            confirm_password: {
                required: true,
                equalTo: "[name='password']"
            },
            location: {
                required: true
            }
        },
        messages: {
            firstname: "Please enter your first name",
            lastname: "Please enter your last name",
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email address"
            },
            phone: {
                required: "Please enter your phone number",
                minlength: "Phone number must be at least 7 digits"
            },
            password: {
                required: "Please provide a password",
                minlength: "Password must be at least 6 characters"
            },
            confirm_password: {
                required: "Please confirm your password",
                equalTo: "Passwords do not match"
            },
            location: "Please select a location"
        },
        errorElement: "div",
        errorClass: "text-danger",
        // Removes old error on keypress
        onkeyup: function(element) {
            $(element).valid();
        }
    });*/
});

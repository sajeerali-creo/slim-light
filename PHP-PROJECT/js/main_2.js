function animateCounter(element) {
    const target = +element.getAttribute('data-target');
    const suffix = element.getAttribute('data-suffix') || '';
    const duration = 2000;
    const startTime = performance.now();

    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const value = Math.floor(progress * target);
        element.textContent = value + suffix;

        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        }
    }

    requestAnimationFrame(updateCounter);
}

function initCounters() {
    const counters = document.querySelectorAll('.counter');
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.6 });

    counters.forEach(counter => observer.observe(counter));
}

window.addEventListener('DOMContentLoaded', initCounters);


// header scroll class
window.addEventListener('scroll', function () {
    const header = document.querySelector('.main-header');
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// slider success stories
const carousel = document.querySelector('#cardCarousel');
const bootstrapCarousel = new bootstrap.Carousel(carousel, {
    interval: 3000,
    ride: 'carousel'
});

// sliders
$(document).ready(function () {
    $('.client-logo-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: false,
        dots: false,
        pauseOnHover: false,
        infinite: true

    });
});
$(document).ready(function () {
    $('.story-slide').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: false,
        dots: false,
        pauseOnHover: false,
        infinite: true,
        responsive: [
            {
                breakpoint: 768, // tablet and below
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 480, // mobile
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });
});



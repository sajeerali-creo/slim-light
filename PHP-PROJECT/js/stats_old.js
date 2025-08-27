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
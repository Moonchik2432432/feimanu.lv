document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll('.slide');
    const nextBtn = document.querySelector('.next');
    const prevBtn = document.querySelector('.prev');
    const slider = document.querySelector('.slider');

    if (!slides.length) return;

    let current = 0;
    let interval;
    let startX = 0;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        slides[index].classList.add('active');
    }

    function nextSlide() {
        current = (current + 1) % slides.length;
        showSlide(current);
    }

    function prevSlide() {
        current = (current - 1 + slides.length) % slides.length;
        showSlide(current);
    }

    function startAuto() {
        interval = setInterval(nextSlide, 5000);
    }

    function stopAuto() {
        clearInterval(interval);
    }

    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);

    if (slider) {
        slider.addEventListener('mouseenter', stopAuto);
        slider.addEventListener('mouseleave', startAuto);

        slider.addEventListener('touchstart', function (e) {
            startX = e.touches[0].clientX;
        });

        slider.addEventListener('touchend', function (e) {
            let endX = e.changedTouches[0].clientX;

            if (startX - endX > 50) {
                nextSlide();
            } else if (endX - startX > 50) {
                prevSlide();
            }
        });
    }

    showSlide(current);
    startAuto();
});

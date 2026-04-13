document.addEventListener("DOMContentLoaded", function () {

    const slides = document.querySelectorAll('.slide');
    const nextBtn = document.querySelector('.next');
    const prevBtn = document.querySelector('.prev');
    const slider = document.querySelector('.slider');

    if (!slides.length) return;

    let current = 0;
    let interval;

    // показать слайд
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.opacity = (i === index) ? '1' : '0';
            slide.style.zIndex = (i === index) ? '2' : '1';
        });
    }

    function nextSlide() {
        current = (current + 1) % slides.length;
        showSlide(current);
    }

    function prevSlide() {
        current = (current - 1 + slides.length) % slides.length;
        showSlide(current);
    }

    // автопрокрутка
    function startAuto() {
        interval = setInterval(nextSlide, 5000);
    }

    function stopAuto() {
        clearInterval(interval);
    }

    // кнопки
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);

    // пауза при наведении
    if (slider) {
        slider.addEventListener('mouseenter', stopAuto);
        slider.addEventListener('mouseleave', startAuto);
    }

    // свайп (телефон)
    let startX = 0;

    if (slider) {
        slider.addEventListener('touchstart', e => {
            startX = e.touches[0].clientX;
        });

        slider.addEventListener('touchend', e => {
            let endX = e.changedTouches[0].clientX;

            if (startX - endX > 50) {
                nextSlide();
            } else if (endX - startX > 50) {
                prevSlide();
            }
        });
    }

    // старт
    slides.forEach(slide => {
        slide.style.position = 'absolute';
        slide.style.top = '0';
        slide.style.left = '0';
        slide.style.width = '100%';
        slide.style.height = '100%';
        slide.style.transition = 'opacity 0.6s ease';
    });

    showSlide(current);
    startAuto();
});

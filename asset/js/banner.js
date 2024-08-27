document.addEventListener("DOMContentLoaded", () => {
    const slider = document.getElementById('slider');
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;
    let currentIndex = 0;
    const indicators = document.getElementById('indicators');

    function createIndicators() {
        for (let i = 0; i < totalSlides; i++) {
            const indicator = document.createElement('span');
            indicator.dataset.index = i;
            indicator.addEventListener('click', () => {
                currentIndex = i;
                updateSlider();
            });
            indicators.appendChild(indicator);
        }
    }

    function updateIndicators() {
        const allIndicators = document.querySelectorAll('.indicators span');
        allIndicators.forEach(indicator => indicator.classList.remove('active'));
        allIndicators[currentIndex].classList.add('active');
    }

    function showSlide(index) {
        slider.style.transform = `translateX(-${index * 100}%)`;
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalSlides;
        updateSlider();
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        updateSlider();
    }

    function updateSlider() {
        showSlide(currentIndex);
        updateIndicators();
    }

    document.getElementById('nextBtn').addEventListener('click', nextSlide);
    document.getElementById('prevBtn').addEventListener('click', prevSlide);

    createIndicators();
    updateSlider();

    setInterval(nextSlide, 3000);
});

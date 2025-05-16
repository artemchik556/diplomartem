// Инициализация слайдера
document.addEventListener('DOMContentLoaded', function() {
    const swiper = new Swiper('.mySwiper', {
        slidesPerView: 4,
        spaceBetween: 30,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            // Когда ширина экрана >= 1200px
            1200: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            // Когда ширина экрана >= 900px
            900: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            // Когда ширина экрана >= 600px
            600: {
                slidesPerView: 1,
                spaceBetween: 10,
            },
        },
    });
}); 
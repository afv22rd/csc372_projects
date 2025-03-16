// carousel.js
$(document).ready(function() {
    // Handle previous button click
    $(document).on('click', '.carousel-prev', function(e) {
        e.preventDefault();
        const vehicleId = $(this).data('vehicle');
        const currentIndex = parseInt($(this).data('current'));
        const targetIndex = parseInt($(this).data('target'));
        const carousel = $(this).closest('.carousel');
        
        // Get all slides in this carousel
        const slides = carousel.find('.carousel-item');
        const currentSlide = carousel.find(`#slide-${vehicleId}-${currentIndex}`);
        const targetSlide = carousel.find(`#slide-${vehicleId}-${targetIndex}`);
        
        // Hide all slides except current and target
        slides.css('display', 'none');
        currentSlide.css('display', 'block');
        targetSlide.css('display', 'block');
        
        // Position the target slide to the left of current slide
        targetSlide.css({
        'transform': 'translateX(-100%)',
        'transition': 'none'
        });
        
        // Force reflow to ensure the positioning takes effect before animation
        void targetSlide[0].offsetWidth;
        
        // Animate both slides
        currentSlide.css({
        'transform': 'translateX(100%)',
        'transition': 'transform 0.3s ease'
        });
        
        targetSlide.css({
        'transform': 'translateX(0)',
        'transition': 'transform 0.3s ease'
        });
        
        // After animation completes, reset and show only target slide
        setTimeout(function() {
        slides.css({
            'display': '',
            'transform': '',
            'transition': ''
        });
        slides.removeClass('active');
        targetSlide.addClass('active');
        }, 300);
    });

    // Handle next button click
    $(document).on('click', '.carousel-next', function(e) {
        e.preventDefault();
        const vehicleId = $(this).data('vehicle');
        const currentIndex = parseInt($(this).data('current'));
        const targetIndex = parseInt($(this).data('target'));
        const carousel = $(this).closest('.carousel');
        
        // Get all slides in this carousel
        const slides = carousel.find('.carousel-item');
        const currentSlide = carousel.find(`#slide-${vehicleId}-${currentIndex}`);
        const targetSlide = carousel.find(`#slide-${vehicleId}-${targetIndex}`);
        
        // Hide all slides except current and target
        slides.css('display', 'none');
        currentSlide.css('display', 'block');
        targetSlide.css('display', 'block');
        
        // Position the target slide to the right of current slide
        targetSlide.css({
        'transform': 'translateX(100%)',
        'transition': 'none'
        });
        
        // Force reflow to ensure the positioning takes effect before animation
        void targetSlide[0].offsetWidth;
        
        // Animate both slides
        currentSlide.css({
        'transform': 'translateX(-100%)',
        'transition': 'transform 0.3s ease'
        });
        
        targetSlide.css({
        'transform': 'translateX(0)',
        'transition': 'transform 0.3s ease'
        });
        
        // After animation completes, reset and show only target slide
        setTimeout(function() {
        slides.css({
            'display': '',
            'transform': '',
            'transition': ''
        });
        slides.removeClass('active');
        targetSlide.addClass('active');
        }, 300);
    });
});
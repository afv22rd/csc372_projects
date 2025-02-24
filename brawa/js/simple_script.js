$(document).ready(function() {
    // Cache jQuery selections
    const $vehicleYes = $('#vehicleYes');
    const $vehicleNo = $('#vehicleNo');
    const $vehicleList = $('#vehicleCheckboxes');
    const $priceRange = $('#priceRange');
    const $form = $('#appointmentModal form');
    const $modalBodyContent = $('#modalBodyContent');
    const $submitButton = $('#submitAppointment');
    const $confirmButton = $('#confirmAppointment');
    const $toast = $('#liveToast');
    const $apptButton = $('#appt-button');

    // Initialize Bootstrap modals
    const appointmentModal = new bootstrap.Modal($('#appointmentModal')[0]);
    const summaryModal = new bootstrap.Modal($('#summaryModal')[0]);

    // Enhanced vehicle selection with smooth animations
    $vehicleYes.on('change', function() {
        if ($vehicleYes.prop('checked')) {
            $priceRange.slideUp(400, function() {
                $vehicleList.slideDown(400);
            });
        }
    });

    $vehicleNo.on('change', function() {
        if ($vehicleNo.prop('checked')) {
            $vehicleList.slideUp(400, function() {
                $priceRange.slideDown(400);
            });
        }
    });

    // Add hover effect to appointment button
    $apptButton.hover(
        function() { $(this).css({'transform': 'scale(1.1)', 'transition': 'transform 0.3s'}); },
        function() { $(this).css({'transform': 'scale(1)', 'transition': 'transform 0.3s'}); }
    );

    // Add visual feedback when inputs are focused
    $('.form-floating input').on('focus', function() {
        $(this).parent().animate({
            marginLeft: '10px'
        }, 200).animate({
            marginLeft: '0px'
        }, 200);
    });

    function formValidation() {
        const formFields = [
            {
                element: $('.form-floating').eq(0),
                input: $('#name'),
                feedback: $('.input-fb').eq(0),
                valid: false
            },
            {
                element: $('.form-floating').eq(1),
                input: $('#email'),
                feedback: $('.input-fb').eq(1),
                valid: false
            },
            {
                element: $('.form-floating').eq(2),
                input: $('#phone'),
                feedback: $('.input-fb').eq(2),
                valid: false
            }
        ];

        // Add event listeners to input fields
        $.each(formFields, function(i, field) {
            field.input.on('focus', function() {
                if (field.valid) {
                    field.element.removeClass('is-invalid').addClass('mb-3');
                    field.input.removeClass('is-invalid');
                } else {
                    field.element.addClass('is-invalid').removeClass('mb-3');
                    field.input.addClass('is-invalid');
                }
            });

            field.input.on('blur', function() {
                if (!field.input.val()) {
                    invalidField(field);
                }

                switch(i) {
                    case 0:
                        validateName(field);
                        break;
                    case 1:
                        validateEmail(field);
                        break;
                    case 2:
                        validatePhone(field);
                        break;
                }
            });
        });

        function invalidField(field) {
            field.element
                .addClass('is-invalid')
                .removeClass('mb-3');
            field.input
                .addClass('is-invalid')
                .removeClass('is-valid');
            field.valid = false;
        }

        function validField(field) {
            field.element
                .removeClass('is-invalid')
                .addClass('is-valid mb-3');
            field.input
                .removeClass('is-invalid')
                .addClass('is-valid');
            field.valid = true;
        }

        function validateName(field) {
            if (field.input.val().split(' ').length < 2) {
                invalidField(field);
                field.feedback.html('You must enter your full name. Please try again.');
            } else {
                validField(field);
            }
        }

        function validateEmail(field) {
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(field.input.val())) {
                invalidField(field);
                field.feedback.html('You must enter a valid email address. Please try again.');
            } else {
                validField(field);
            }
        }

        function validatePhone(field) {
            const phoneRegex = [/^\d{3}-\d{3}-\d{4}$/, /^\d{10}$/];
            if (!phoneRegex[0].test(field.input.val()) && !phoneRegex[1].test(field.input.val())) {
                invalidField(field);
                field.feedback.html('You must enter a valid phone number. Please try again.');
            } else {
                validField(field);
            }
        }

        return formFields;
    }

    const formFields = formValidation();

    // Form submission handling
    $submitButton.on('click', function(event) {
        event.preventDefault();

        const invalidFields = formFields.filter(field => !field.valid);
        if (invalidFields.length > 0) {
            // Shake effect for invalid fields
            invalidFields.forEach(field => {
                field.element.animate({marginLeft: '-10px'}, 100)
                           .animate({marginLeft: '10px'}, 100)
                           .animate({marginLeft: '0px'}, 100);
            });
            
            alert('Please fill out all the required fields before submitting your offer.');
            invalidFields[0].input.focus();
            return;
        }

        // Fade out appointment modal and show summary
        $('#appointmentModal').fadeOut(400, function() {
            appointmentModal.hide();
            updateSummaryContent();
            summaryModal.show();
            $('#summaryModal').hide().fadeIn(400);
        });
    });

    // Function to update summary content
    function updateSummaryContent() {
        const appointmentInfo = {
            name: $('#name').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            date: $('#date').val(),
            time: $('#time').val()
        };

        let appointmentDetails = `
            <p><strong>Name:</strong> ${appointmentInfo.name}</p>
            <p><strong>Email:</strong> ${appointmentInfo.email}</p>
            <p><strong>Phone:</strong> ${appointmentInfo.phone}</p>
            <p><strong>Date:</strong> ${appointmentInfo.date}</p>
            <p><strong>Time:</strong> ${appointmentInfo.time}</p>
        `;

        if ($vehicleYes.prop('checked')) {
            const selectedVehicles = $('#vehicleCheckboxes input:checked')
                .map(function() {
                    return $(this).next().text();
                })
                .get();

            appointmentDetails += `
                <p><strong>Vehicles selected:</strong> ${selectedVehicles.length}<br>
                ${selectedVehicles.length ? selectedVehicles.join('<br>') : 'None selected'}</p>
            `;
        } else {
            const priceRangeMap = {
                '1': '$0 - $5,000',
                '2': '$5,000 - $10,000',
                '3': '$10,000 - $15,000',
                '4': '$15,000 - $20,000',
                '5': '$20,000 - $25,000',
                '6': '$25,000+',
                'default': 'Not selected'
            };

            const selectedRange = $('#priceRangeDropdown').val();
            const priceRangeText = priceRangeMap[selectedRange] || priceRangeMap.default;
            appointmentDetails += `<p><strong>Price Range:</strong> ${priceRangeText}</p>`;
        }

        // Fade in the new content
        $modalBodyContent.fadeOut(200, function() {
            $(this).html(appointmentDetails).fadeIn(200);
        });
    }

    // Confirm button handler with fade effect
    $confirmButton.off('click').on('click', function() {
        $('#summaryModal').fadeOut(400, function() {
            sessionStorage.setItem('formSubmitted', true);
            $form.submit();
        });
    });

    // Show toast notification with slide effect
    if (sessionStorage.getItem('formSubmitted')) {
        const $toastElement = $('#liveToast');
        $toastElement.hide();
        const btToast = bootstrap.Toast.getOrCreateInstance($toast[0]);
        btToast.show();
        $toastElement.slideDown(400);
        sessionStorage.removeItem('formSubmitted');
    }
});
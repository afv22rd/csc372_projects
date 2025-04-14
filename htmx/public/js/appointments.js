/**
 * Appointment Form Handling and Validation
 */
$(document).ready(function () {
    // Cache DOM elements
    const $appointmentForm = $('#appointment-form');
    const $formResponse = $('#form-response');
    const appointmentModal = document.getElementById('appointment-modal');

    // Format date for display (YYYY-MM-DD to MM/DD/YYYY)
    function formatDate(dateStr) {
        if (!dateStr) return '';
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        return `${parts[1]}/${parts[2]}/${parts[0]}`;
    }

    // Format time for display (24h to 12h format)
    function formatTime(timeStr) {
        if (!timeStr) return '';
        const [hours, minutes] = timeStr.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${minutes} ${ampm}`;
    }

    // Format currency for display
    function formatCurrency(value) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(value);
    }

    // Client-side validation function
    function validateForm() {
        let isValid = true;
        const errors = [];

        // First Name validation
        const firstName = $('#first_name').val().trim();
        if (firstName.length < 2 || firstName.length > 20) {
            errors.push('First name must be between 2 and 20 characters.');
            isValid = false;
        }

        // Last Name validation
        const lastName = $('#last_name').val().trim();
        if (lastName.length < 2 || lastName.length > 20) {
            errors.push('Last name must be between 2 and 20 characters.');
            isValid = false;
        }

        // Phone validation (simple check for digits and length)
        const phone = $('#phone').val().replace(/[^0-9]/g, '');
        if (phone.length < 10 || phone.length > 15) {
            errors.push('Phone number must have between 10 and 15 digits.');
            isValid = false;
        }

        // Email validation
        const email = $('#email').val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errors.push('Please enter a valid email address.');
            isValid = false;
        }

        // Date validation
        const appointmentDate = $('#appointment_date').val();
        if (!appointmentDate) {
            errors.push('Please select an appointment date.');
            isValid = false;
        } else {
            // Check if date is in the future
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const selectedDate = new Date(appointmentDate);
            if (selectedDate <= today) {
                errors.push('Appointment date must be in the future.');
                isValid = false;
            }
        }

        // Time validation
        const appointmentTime = $('#appointment_time').val();
        if (!appointmentTime) {
            errors.push('Please select an appointment time.');
            isValid = false;
        }

        // Budget validation
        const budget = $('#budget').val();
        if (!budget || isNaN(budget) || budget < 1000 || budget > 100000) {
            errors.push('Budget must be between $1,000 and $100,000.');
            isValid = false;
        }

        // Display errors if any
        if (!isValid) {
            let errorHtml = '<div class="alert alert-error shadow-lg mb-4"><div>';
            errorHtml += '<svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
            errorHtml += '<div><h3 class="font-bold">Please correct the following errors:</h3>';
            errorHtml += '<ul class="mt-1 list-disc list-inside">';

            errors.forEach(error => {
                errorHtml += `<li>${error}</li>`;
            });

            errorHtml += '</ul></div></div></div>';
            $formResponse.html(errorHtml);
        }

        return isValid;
    }

    // Intercept form submission for client-side validation
    $appointmentForm.on('htmx:beforeRequest', function (event) {
        if (!validateForm()) {
            event.preventDefault(); // Stop HTMX from submitting the form
        } else {
            // Clear any previous error messages when submitting a valid form
            $formResponse.empty();
        }
    });

    // Open appointment modal when "Make an Appointment" button is clicked
    $('button[data-target="#appointment-modal"]').on('click', function () {
        appointmentModal.showModal();
    });

    // Set min date for appointment date picker to today
    const today = new Date().toISOString().split('T')[0];
    $('#appointment_date').attr('min', today);
});
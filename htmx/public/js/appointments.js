document.addEventListener('DOMContentLoaded', function() {
    const appointmentForm = document.getElementById('appointment-form');
    const appointmentModal = document.getElementById('appointment-modal');
    const successModal = document.getElementById('success-modal');
    const modalButtons = document.querySelectorAll('[data-toggle="modal"]');
    const closeButtons = document.querySelectorAll('[data-dismiss="modal"]');

    // Show modal
    modalButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = document.querySelector(this.dataset.target);
            if (target) {
                target.classList.add('modal-open');
            }
        });
    });

    // Close modal
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.classList.remove('modal-open');
            }
        });
    });

    // Handle form submission
    appointmentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        
        fetch('api/appointments.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide appointment modal
                appointmentModal.classList.remove('modal-open');
                // Show success modal
                successModal.classList.add('modal-open');
                // Reset form
                appointmentForm.reset();
            } else {
                alert(data.message || 'An error occurred. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
});
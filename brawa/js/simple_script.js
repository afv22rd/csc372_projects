document.addEventListener("DOMContentLoaded", function() {
    const vehicleYes = document.getElementById("vehicleYes"); // Yes radio button
    const vehicleNo = document.getElementById("vehicleNo"); // No radio button
    const vehicleList = document.getElementById("vehicleCheckboxes"); // Vehicle checkboxes
    const priceRange = document.getElementById("priceRange");

    // Show/hide vehicle list and price range based on user selection
    // If user selects "Yes" for vehicle interest, show vehicle list
    vehicleYes.addEventListener("change", function() {
        if (vehicleYes.checked) {
            vehicleList.style.display = "block";
            priceRange.style.display = "none";
        }
    });

    // If user selects "No" for vehicle interest, show price range
    vehicleNo.addEventListener("change", function() {
        if (vehicleNo.checked) {
            vehicleList.style.display = "none";
            priceRange.style.display = "block";
        }
    });

    // Initialize the appointment modal and summary modal
    const appointmentModal = new bootstrap.Modal(document.getElementById("appointmentModal"));
    const form = document.querySelector("#appointmentModal form");
    const summaryModal = new bootstrap.Modal(document.getElementById("summaryModal"));
    const modalBodyContent = document.getElementById("modalBodyContent");

    // Submit and confirm button
    const submitButton = document.querySelector("#submitAppointment");
    const confirmButton = document.getElementById("confirmAppointment")

    function formValidation() {
        const formFields = [{ 
                element: document.querySelectorAll(".form-floating")[0], 
                input: document.getElementById("name"),
                feedback: document.querySelectorAll(".input-fb")[0],
                valid: false
            }, 
            { 
                element: document.querySelectorAll(".form-floating")[1], 
                input: document.getElementById("email"),
                feedback: document.querySelectorAll(".input-fb")[1],
                valid: false
            },
            { 
                element: document.querySelectorAll(".form-floating")[2], 
                input: document.getElementById("phone"),
                feedback: document.querySelectorAll(".input-fb")[2],
                valid: false
            }
        ];
        
        // Loop thru all form fields and add event listeners to the input fields
        for (let i = 0; i < formFields.length; i++) {
            // Add event listener to the input fields when they are focused
            formFields[i].input.addEventListener("focus", function() {
                if (formFields[i].valid){
                    // If the input is valid, remove initial classes
                    formFields[i].element.classList.remove("is-invalid");
                    formFields[i].element.classList.add("mb-3");
                    formFields[i].input.classList.remove("is-invalid");
                } else if (!formFields[i].valid) {
                    // If the input is invalid, add invalid class
                    formFields[i].element.classList.add("is-invalid");
                    formFields[i].element.classList.remove("mb-3");
                    formFields[i].input.classList.add("is-invalid");
                }
            });
    
            formFields[i].input.addEventListener("blur", function() {
                // If the input is empty, add invalid class
                if (!formFields[i].input.value) {
                    invalidField(formFields[i]);
                }

                // Validate input fields
                switch(i) {
                    // Validate name
                    case 0:
                        validateName(formFields[i]);
                        break;
                    // Validate email
                    case 1:
                        validateEmail(formFields[i]);
                        break;
                    // Validate phone
                    case 2:
                        validatePhone(formFields[i]);
                        break;
                }
            });
        }

        // Function to add bootstrap classes to the input fields when they are invalid
        function invalidField(field){
            field.element.classList.add("is-invalid");
            field.element.classList.remove("mb-3");
            field.input.classList.add("is-invalid");
            field.input.classList.remove("is-valid");
            field.valid = false;
        }
        // Function to add bootstrap classes to the input fields when they are valid
        function validField(field){
            field.element.classList.remove("is-invalid");
            field.element.classList.add("is-valid", "mb-3");
            field.input.classList.remove("is-invalid");
            field.input.classList.add("is-valid");
            field.valid = true;
        }

        // Function to validate name input
        function validateName(field) {
            // Check if name is less than 2 words
            if (field.input.value.split(" "). length < 2) {
                invalidField(field);
                field.feedback.innerHTML = "You must enter your full name. Please try again.";
            } else { // If name is valid
                validField(field);
            }
        }

        // Function to validate email input
        function validateEmail(field){
            // Email regex to verify if it's an email format
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(field.input.value)) {
                invalidField(field);
                field.feedback.innerHTML = "You must enter a valid email address. Please try again.";
            } else {
                validField(field);
            }
        }

        function validatePhone(field){
            // Phone regex to verify if it's a phone number format
            const phoneRegex = [/^\d{3}-\d{3}-\d{4}$/, /^\d{10}$/];
            if (!phoneRegex[0].test(field.input.value) && !phoneRegex[1].test(field.input.value)) {
                invalidField(field);
                field.feedback.innerHTML = "You must enter a valid phone number. Please try again.";
            } else {
                validField(field);
            }
        }

        // Return the form fields
        return formFields;
    }

    const formFields = formValidation();

    // Add content to offer summary modal
    submitButton.addEventListener("click", function(event) {
        event.preventDefault();

        // Check if all input fields are valid
        if (!formFields[0].valid || !formFields[1].valid || !formFields[2].valid) {
            // If any input field is invalid, show an alert
            alert("Please fill out all the required fields before submitting your offer.");
            // Focus on the first invalid input field
            for (let i = 0; i < formFields.length; i++) {
                if (!formFields[i].valid) {
                    formFields[i].input.focus();
                    break;
                }
            }
            // Remove modal attributes from the submit button
            submitButton.removeAttribute("data-bs-toggle");
            submitButton.removeAttribute("data-bs-target");
            return;
        }

        // If all input fields are valid, add bootstrap modal attributes to the submit button
        submitButton.setAttribute("data-bs-toggle", "modal");
        submitButton.setAttribute("data-bs-target", "#summaryModal");
        submitButton.click()

        appointmentModal.hide();  // Hide the appointment modal

        // Appt info
        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const phone = document.getElementById("phone").value;
        const date = document.getElementById("date").value;
        const time = document.getElementById("time").value;

        // Determine vehicle selection or price range
        let appointmentDetails = `<p><strong>Name:</strong> ${name}</p>
                                  <p><strong>Email:</strong> ${email}</p>
                                  <p><strong>Phone:</strong> ${phone}</p>
                                  <p><strong>Date:</strong> ${date}</p>
                                  <p><strong>Time:</strong> ${time}</p>`;

        // Check if the user is interested in specific vehicles
        const isInterestedInVehicle = document.getElementById("vehicleYes").checked;
        if (isInterestedInVehicle) {
            let selectedVehicles = []; // Array to store selected vehicles
            document.querySelectorAll("#vehicleCheckboxes input[type='checkbox']:checked").forEach(checkbox => { // Loop through selected checkboxes
                selectedVehicles.push(checkbox.nextElementSibling.textContent); // Get the vehicle name of each checkbox
            });
            // This will show the number and names of selected vehicles
            // Join selected vehicles to the appointment details. If selectedVehicles is empty, join "None selected"
            appointmentDetails += `<p><strong>Vehicles selected:</strong> ${selectedVehicles.length}<br> ${selectedVehicles.length ? selectedVehicles.join("<br>") : "None selected"}</p>`;
        } else {
            // Get price range value depending on where the dropdown is set
            const priceRangeValue = document.getElementById("priceRangeDropdown").value;
            let priceRangeText;
            switch (priceRangeValue) {
                case "1":
                    priceRangeText = "$0 - $5,000";
                    break;
                case "2":
                    priceRangeText = "$5,000 - $10,000";
                    break;
                case "3":
                    priceRangeText = "$10,000 - $15,000";
                    break;
                case "4":
                    priceRangeText = "$15,000 - $20,000";
                    break;
                case "5":
                    priceRangeText = "$20,000 - $25,000";
                    break;
                case "6":
                    priceRangeText = "$25,000+";
                    break;
                default:
                    priceRangeText = "Not selected";
            }
            // Add price range to appointment details
            appointmentDetails += `<p><strong>Price Range:</strong> ${priceRangeText}</p>`;
        }

        // Insert appointment details into modal body
        modalBodyContent.innerHTML = appointmentDetails;

        // Show the summary modal
        // Submit form
        const appointmentForm = document.querySelector("#appointmentModal form");
        confirmButton.addEventListener("click", function() {
            // Use session storage to know if the form was submitted
            sessionStorage.setItem("formSubmitted", true);
            appointmentForm.submit();
        });
    });

    // Show bootstrap toast alert when form was submitted
    const toast = document.getElementById("liveToast");
    if (sessionStorage.getItem("formSubmitted")) {
        const btToast = bootstrap.Toast.getOrCreateInstance(toast);
        btToast.show();
        sessionStorage.removeItem("formSubmitted");
    }
});
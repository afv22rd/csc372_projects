document.addEventListener('DOMContentLoaded', function() {
    function formValidation() {
        const formFields = [{ 
                element: document.querySelectorAll(".form-floating")[0], 
                input: document.getElementById("name"),
                feedback: document.querySelectorAll(".input-fb")[0],
                valid: false,
                required: true
            }, 
            { 
                element: document.querySelectorAll(".form-floating")[1], 
                input: document.getElementById("email"),
                feedback: document.querySelectorAll(".input-fb")[1],
                valid: false,
                required: true
            },
            { 
                element: document.querySelectorAll(".form-floating")[2], 
                input: document.getElementById("phone"),
                feedback: document.querySelectorAll(".input-fb")[2],
                valid: false,
                required: true
            },
            {
                element: document.querySelectorAll(".form-floating")[3],
                input: document.getElementById("message"),
                feedback: document.querySelectorAll(".input-fb")[3],
                valid: true,
                required: false
            }
        ];
        
        // Loop thru all form fields and add event listeners to the input fields
        for (let i = 0; i < formFields.length; i++) {
            // Add event listener to the input fields when they are focused
            formFields[i].input.addEventListener("focus", function() {
                // For required fields
                if (formFields[i].required) {
                    if (formFields[i].valid) {
                        // If the input is valid, remove initial classes
                        formFields[i].element.classList.remove("is-invalid");
                        formFields[i].element.classList.add("mb-3");
                        formFields[i].input.classList.remove("is-invalid");
                    } else {
                        // If the input is invalid, add invalid class
                        formFields[i].element.classList.add("is-invalid");
                        formFields[i].element.classList.remove("mb-3");
                        formFields[i].input.classList.add("is-invalid");
                    }
                }
                // For optional message field, only apply classes if it has content
                else if (!formFields[i].required && formFields[i].input.value.length > 0) {
                    if (formFields[i].valid) {
                        formFields[i].element.classList.remove("is-invalid");
                        formFields[i].element.classList.remove("mb-3");
                        formFields[i].input.classList.remove("is-invalid");
                    } else {
                        formFields[i].element.classList.add("is-invalid");
                        formFields[i].element.classList.remove("mb-3");
                        formFields[i].input.classList.add("is-invalid");
                    }
                }
            });
    
            formFields[i].input.addEventListener("blur", function() {
                // For required fields
                if (formFields[i].required) {
                    if (!formFields[i].input.value) {
                        invalidField(formFields[i]);
                        formFields[i].feedback.innerHTML = "This field is required.";
                        return;
                    }
                } else {
                    // For optional fields (message), remove all validation classes if empty
                    if (!formFields[i].input.value) {
                        resetField(formFields[i]);
                        return;
                    }
                }
    
                // Validate input fields
                switch(i) {
                    case 0:
                        validateName(formFields[i]);
                        break;
                    case 1:
                        validateEmail(formFields[i]);
                        break;
                    case 2:
                        validatePhone(formFields[i]);
                        break;
                    case 3:
                        validateMessage(formFields[i]);
                        break;
                }
            });
        }
    
        // Function to add bootstrap classes to the input fields when they are invalid
        function invalidField(field) {
            field.element.classList.add("is-invalid");
            field.element.classList.remove("mb-3");
            field.input.classList.add("is-invalid");
            field.input.classList.remove("is-valid");
            field.valid = false;
        }
    
        // Function to add bootstrap classes to the input fields when they are valid
        function validField(field) {
            field.element.classList.remove("is-invalid");
            if (field.required) {
                field.element.classList.add("mb-3");
            }
            field.element.classList.add("valid");
            field.input.classList.remove("is-invalid");
            field.input.classList.add("is-valid");
            field.valid = true;
        }
    
        // Function to reset field to neutral state (no validation)
        function resetField(field) {
            field.element.classList.remove("is-invalid", "is-valid", "mb-3");
            field.input.classList.remove("is-invalid", "is-valid");
            field.valid = true;  // Set to true since empty optional field is considered valid
            field.feedback.innerHTML = "";
        }
    
        function validateName(field) {
            if (field.input.value.split(" ").length < 2) {
                invalidField(field);
                field.feedback.innerHTML = "You must enter your full name. Please try again.";
            } else {
                validField(field);
            }
        }
    
        function validateEmail(field) {
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(field.input.value)) {
                invalidField(field);
                field.feedback.innerHTML = "You must enter a valid email address. Please try again.";
            } else {
                validField(field);
            }
        }
    
        function validatePhone(field) {
            const phoneRegex = [/^\d{3}-\d{3}-\d{4}$/, /^\d{10}$/];
            if (!phoneRegex[0].test(field.input.value) && !phoneRegex[1].test(field.input.value)) {
                invalidField(field);
                field.feedback.innerHTML = "You must enter a valid phone number. Please try again.";
            } else {
                validField(field);
            }
        }
    
        function validateMessage(field) {
            if (field.input.value.length > 400) {
                invalidField(field);
                field.feedback.innerHTML = "You must enter a message with less than 400 characters. Please try again.";
            } else if (field.input.value.length > 0) {
                validField(field);
            } else {
                resetField(field);
            }
        }
    
        return formFields;
    }
    formValidation();
});
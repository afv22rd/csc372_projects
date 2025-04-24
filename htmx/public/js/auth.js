/**
 * Authentication Modal Functionality
 */
$(document).ready(function() {
    // Initialize the auth modal by loading the login form when the page loads
    $("#auth-form-container").load("api/auth_forms.php?form=login");
    
    // Initialize the delete account modal by loading the delete account form when clicked
    $("a.text-error").on("click", function() {
        $("#delete-account-container").load("api/auth_forms.php?form=delete_account");
    });
});
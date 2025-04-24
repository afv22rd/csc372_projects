<?php
// Process the request type (login or signup)
$form_type = isset($_GET['form']) ? $_GET['form'] : 'login';

// Return the appropriate form HTML
header('Content-Type: text/html');

if ($form_type == 'login') {
    ?>
<!-- Login Form -->
<h3 class="font-bold text-lg mb-4">Login to Your Account</h3>
<div id="login-form-alerts"></div>
<form hx-post="api/login.php" hx-target="#login-form-alerts" class="space-y-4">
    <!-- Email -->
    <div class="form-control w-full">
        <label for="login-email" class="label">
            <span class="label-text">Email</span>
        </label>
        <input type="email" id="login-email" name="email" class="input input-bordered w-full" 
              placeholder="Enter your email address" required>
    </div>
    
    <!-- Password -->
    <div class="form-control w-full">
        <label for="login-password" class="label">
            <span class="label-text">Password</span>
        </label>
        <input type="password" id="login-password" name="password" class="input input-bordered w-full" 
              placeholder="Enter your password" required>
    </div>
    
    <!-- Remember Me -->
    <div class="form-control">
        <label class="cursor-pointer label justify-start gap-2">
            <input type="checkbox" name="remember" class="checkbox checkbox-primary" />
            <span class="label-text">Remember me</span>
        </label>
    </div>
    
    <!-- Submit Button -->
    <div class="form-control mt-6">
        <button type="submit" class="w-full btn btn-primary">Login</button>
    </div>
    
    <!-- Forgot Password Link -->
    <div class="form-control mt-2">
        <button class="w-full btn btn-neutral" 
           hx-get="api/auth_forms.php?form=forgot_password" 
           hx-target="#auth-form-container"
           hx-swap="innerHTML">
            Forgot password?
        </button>
    </div>
    
    <!-- Sign Up Link -->
    <div class="text-center mt-4">
        <p>Don't have an account? 
            <a class="text-primary hover:underline cursor-pointer" 
               hx-get="api/auth_forms.php?form=signup" 
               hx-target="#auth-form-container"
               hx-swap="innerHTML">
                Create one for free
            </a>
        </p>
    </div>
</form>
<?php } elseif ($form_type == 'forgot_password') { ?>
<!-- Forgot Password Form -->
<h3 class="font-bold text-lg mb-4">Reset Your Password</h3>
<div id="forgot-password-alerts"></div>
<form hx-post="api/reset_password.php" hx-target="#forgot-password-alerts" class="space-y-4">
    <!-- Email -->
    <div class="form-control w-full">
        <label for="reset-email" class="label">
            <span class="label-text">Email</span>
        </label>
        <input type="email" id="reset-email" name="email" class="input input-bordered w-full" 
              placeholder="Enter your email address" required>
    </div>
    
    <!-- New Password -->
    <div class="form-control w-full">
        <label for="reset-password" class="label">
            <span class="label-text">New Password</span>
        </label>
        <input type="password" id="reset-password" name="password" class="input input-bordered w-full" 
              placeholder="Enter your new password" required minlength="6">
    </div>
    
    <!-- Confirm New Password -->
    <div class="form-control w-full">
        <label for="reset-confirm-password" class="label">
            <span class="label-text">Confirm New Password</span>
        </label>
        <input type="password" id="reset-confirm-password" name="confirm_password" class="input input-bordered w-full" 
              placeholder="Confirm your new password" required minlength="6">
    </div>
    
    <!-- Submit Button -->
    <div class="form-control mt-6">
        <button type="submit" class="w-full btn btn-primary">Reset Password</button>
    </div>
    
    <!-- Back to Login Link -->
    <div class="text-center mt-4">
        <a class="text-primary hover:underline cursor-pointer" 
           hx-get="api/auth_forms.php?form=login" 
           hx-target="#auth-form-container"
           hx-swap="innerHTML">
            Back to login
        </a>
    </div>
</form>
<?php } elseif ($form_type == 'delete_account') { ?>
<!-- Delete Account Form -->
<h3 class="font-bold text-lg mb-4">Delete Your Account</h3>
<div id="delete-account-alerts"></div>
<div id="delete-warning" class="alert alert-warning mb-6">
    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
    <div>
        <h3 class="font-bold">Warning!</h3>
        <p>This action cannot be reversed! All your data will be permanently deleted.</p>
    </div>
</div>
<form id="delete-account-form" hx-post="/csc372_projects/htmx/api/delete_account.php" hx-target="#delete-account-alerts" class="space-y-4">
    <!-- Email -->
    <div class="form-control w-full">
        <label for="delete-email" class="label">
            <span class="label-text">Email</span>
        </label>
        <input type="email" id="delete-email" name="email" class="input input-bordered w-full" 
              placeholder="Enter your email address" required>
    </div>
    
    <!-- Password -->
    <div class="form-control w-full">
        <label for="delete-password" class="label">
            <span class="label-text">Password</span>
        </label>
        <input type="password" id="delete-password" name="password" class="input input-bordered w-full" 
              placeholder="Enter your password" required>
    </div>
    
    <!-- Delete Button -->
    <div class="form-control mt-6">
        <button type="submit" class="w-full btn btn-error" id="delete-account-button" 
                hx-trigger="click" hx-post="/csc372_projects/htmx/api/delete_account.php" 
                hx-target="#delete-account-alerts">Delete Account</button>
    </div>
    
    <!-- Cancel Button -->
    <div class="form-control mt-2">
        <button type="button" class="w-full btn" onclick="document.getElementById('delete-account-modal').close()">Cancel</button>
    </div>
</form>
<script>
// Prevent default form submission and ensure HTMX handles it
document.getElementById('delete-account-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent traditional form submission
    // HTMX will handle the submission via the hx-post attribute
});
</script>
<?php } else { ?>
<!-- Sign Up Form -->
<h3 class="font-bold text-lg mb-4">Create a New Account</h3>
<div id="signup-form-alerts"></div>
<form hx-post="api/signup.php" hx-target="#signup-form-alerts" class="space-y-4">
    <!-- Name -->
    <div class="form-control w-full">
        <label for="signup-name" class="label">
            <span class="label-text">Full Name</span>
        </label>
        <input type="text" id="signup-name" name="name" class="input input-bordered w-full" 
              placeholder="Enter your full name" required>
    </div>
    
    <!-- Email -->
    <div class="form-control w-full">
        <label for="signup-email" class="label">
            <span class="label-text">Email</span>
        </label>
        <input type="email" id="signup-email" name="email" class="input input-bordered w-full" 
              placeholder="Enter your email address" required>
    </div>
    
    <!-- Phone Number -->
    <div class="form-control w-full">
        <label for="signup-phone" class="label">
            <span class="label-text">Phone Number</span>
        </label>
        <input type="tel" id="signup-phone" name="phone" class="input input-bordered w-full" 
              placeholder="Enter your phone number" required>
    </div>
    
    <!-- Password -->
    <div class="form-control w-full">
        <label for="signup-password" class="label">
            <span class="label-text">Password</span>
        </label>
        <input type="password" id="signup-password" name="password" class="input input-bordered w-full" 
              placeholder="Create a password (min 6 characters)" required minlength="6">
    </div>
    
    <!-- Confirm Password -->
    <div class="form-control w-full">
        <label for="confirm-password" class="label">
            <span class="label-text">Confirm Password</span>
        </label>
        <input type="password" id="confirm-password" name="confirm_password" class="input input-bordered w-full" 
              placeholder="Confirm your password" required minlength="6">
    </div>
    
    <!-- Submit Button -->
    <div class="form-control mt-6">
        <button type="submit" class="btn btn-primary w-full">Create Account</button>
    </div>
    
    <!-- Login Link -->
    <div class="text-center mt-4">
        <p>Already have an account? 
            <a class="text-primary hover:underline cursor-pointer" 
               hx-get="api/auth_forms.php?form=login" 
               hx-target="#auth-form-container"
               hx-swap="innerHTML">
                Login
            </a>
        </p>
    </div>
</form>
<?php } ?>
<?php
// Start session at the beginning of the file
session_start();

// Include validation functions
require_once 'validate.php';

// Define valid appointment times
$valid_times = [
    '09:00', '10:00', '11:00', '12:00', '13:00',
    '14:00', '15:00', '16:00', '17:00',
];

// Get and sanitize form data
$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$appointment_date = isset($_POST['appointment_date']) ? trim($_POST['appointment_date']) : '';
$appointment_time = isset($_POST['appointment_time']) ? trim($_POST['appointment_time']) : '';
$budget = isset($_POST['budget']) ? trim($_POST['budget']) : '';

// Initialize errors array
$errors = [];

// Validate first name
if (!is_text($first_name, 2, 20)) {
    $errors['first_name'] = 'First name must be between 2 and 20 characters.';
}

// Validate last name
if (!is_text($last_name, 2, 20)) {
    $errors['last_name'] = 'Last name must be between 2 and 20 characters.';
}

// Validate phone
if (!is_valid_phone($phone)) {
    $errors['phone'] = 'Please enter a valid phone number (10-15 digits).';
}

// Validate email
if (!is_valid_email($email)) {
    $errors['email'] = 'Please enter a valid email address.';
}

// Validate date
if (!is_valid_date($appointment_date)) {
    $errors['appointment_date'] = 'Please select a date that is in the future.';
}

// Validate time
if (!is_valid_option($appointment_time, $valid_times)) {
    $errors['appointment_time'] = 'Please select a valid appointment time.';
}

// Validate budget
if (!is_number($budget, 1000, 100000)) {
    $errors['budget'] = 'Budget must be between $1,000 and $100,000.';
}

// If there are errors, return them along with the user input
if (count($errors) > 0) {
    header('Content-Type: text/html');

    // Display error message
    echo '<div class="alert alert-error shadow-lg mb-4">';
    echo '<div>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
    echo '<div>';
    echo '<h3 class="font-bold">Please correct the following errors:</h3>';
    echo '<ul class="mt-1 list-disc list-inside">';

    foreach ($errors as $field => $message) {
        echo '<li>'.htmlspecialchars($message).'</li>';
    }

    echo '</ul>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    // Return the form with preserved values
    ?>
    <script>
        // Restore the previously entered values
        document.getElementById('first_name').value = <?php echo json_encode(htmlspecialchars($first_name)); ?>;
        document.getElementById('last_name').value = <?php echo json_encode(htmlspecialchars($last_name)); ?>;
        document.getElementById('phone').value = <?php echo json_encode(htmlspecialchars($phone)); ?>;
        document.getElementById('email').value = <?php echo json_encode(htmlspecialchars($email)); ?>;
        document.getElementById('appointment_date').value = <?php echo json_encode(htmlspecialchars($appointment_date)); ?>;
        
        // For select elements we need to find the option and select it
        const timeSelect = document.getElementById('appointment_time');
        const timeValue = <?php echo json_encode(htmlspecialchars($appointment_time)); ?>;
        for (let i = 0; i < timeSelect.options.length; i++) {
            if (timeSelect.options[i].value === timeValue) {
                timeSelect.options[i].selected = true;
                break;
            }
        }
        
        document.getElementById('budget').value = <?php echo json_encode(htmlspecialchars($budget)); ?>;
        
        // Highlight fields with errors
        <?php foreach ($errors as $field => $message) { ?>
            document.getElementById('<?php echo $field; ?>').classList.add('input-error');
        <?php } ?>
    </script>
    <?php
    exit;
}

// If the validation passes, set cookie with user's first name (expires in 7 days)
setcookie("user_name", htmlspecialchars($first_name), time() + (86400 * 7), "/");

// Store appointment data in session
$_SESSION['appointment'] = [
    'first_name' => htmlspecialchars($first_name),
    'last_name' => htmlspecialchars($last_name),
    'phone' => htmlspecialchars($phone),
    'email' => htmlspecialchars($email),
    'appointment_date' => htmlspecialchars($appointment_date),
    'appointment_time' => htmlspecialchars($appointment_time),
    'budget' => htmlspecialchars($budget),
    'timestamp' => time()
];

// Format the date for display (YYYY-MM-DD to MM/DD/YYYY)
$formatted_date = date('m/d/Y', strtotime($appointment_date));

// Format the time for display (24h to 12h format)
$hour = intval(substr($appointment_time, 0, 2));
$minutes = substr($appointment_time, 3, 2);
$ampm = $hour >= 12 ? 'PM' : 'AM';
$hour12 = $hour % 12;
if ($hour12 == 0) $hour12 = 12;
$formatted_time = $hour12 . ':' . $minutes . ' ' . $ampm;

// Format the budget as currency
$formatted_budget = '$' . number_format($budget, 2);

// Return confirmation content that completely replaces the form
header('Content-Type: text/html');
?>
<!-- Success Alert -->
<div class="alert alert-success shadow-lg mb-6">
    <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span>Your appointment has been scheduled successfully!</span>
    </div>
</div>

<!-- Confirmation Content -->
<div class="space-y-4">
    <h4 class="font-bold text-lg">Appointment Details</h4>
    
    <div class="bg-base-200 p-4 rounded-lg">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Date:</strong> <?php echo $formatted_date; ?></p>
        <p><strong>Time:</strong> <?php echo $formatted_time; ?></p>
        <p><strong>Budget:</strong> <?php echo $formatted_budget; ?></p>
    </div>
    
    <p class="text-sm">A confirmation email has been sent to your email address. We'll contact you shortly to confirm your appointment.</p>
    
    <!-- Modal Actions - Only close button now -->
    <div class="modal-action">
        <button type="button" class="btn btn-primary" onclick="window.location.href='index.php'">Done</button>
    </div>
</div>
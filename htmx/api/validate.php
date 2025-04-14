<?php
/**
 * Form validation helper functions.
 */

/**
 * Validate that a text input is within a specific length range.
 *
 * @param string $text The text to validate
 * @param int    $min  The minimum allowed length
 * @param int    $max  The maximum allowed length
 *
 * @return bool Whether the text is valid
 */
function is_text($text, $min = 2, $max = 20)
{
    $length = strlen(trim($text));

    return $length >= $min && $length <= $max;
}

/**
 * Validate that an email is properly formatted.
 *
 * @param string $email The email to validate
 *
 * @return bool Whether the email is valid
 */
function is_valid_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate that a phone number contains only digits and optional formatting
 * and is within a reasonable length range.
 *
 * @param string $phone The phone number to validate
 *
 * @return bool Whether the phone number is valid
 */
function is_valid_phone($phone)
{
    // Remove all non-digit characters
    $digits_only = preg_replace('/[^0-9]/', '', $phone);

    // Check that there are between 10 and 15 digits
    return strlen($digits_only) >= 10 && strlen($digits_only) <= 15;
}

/**
 * Validate that a date is in the future.
 *
 * @param string $date The date to validate (in YYYY-MM-DD format)
 *
 * @return bool Whether the date is valid
 */
function is_valid_date($date)
{
    // Verify format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return false;
    }

    // Convert to timestamp
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return false;
    }

    // Get current date (without time)
    $today = strtotime(date('Y-m-d'));

    // Check if date is today or in the future
    return $timestamp >= $today;
}

/**
 * Validate that a value is one of the given options.
 *
 * @param mixed $value   The value to validate
 * @param array $options The array of allowed options
 *
 * @return bool Whether the value is valid
 */
function is_valid_option($value, $options)
{
    return in_array($value, $options);
}

/**
 * Validate that a number is within a specific range.
 *
 * @param mixed     $number The number to validate
 * @param int|float $min    The minimum allowed value
 * @param int|float $max    The maximum allowed value
 *
 * @return bool Whether the number is valid
 */
function is_number($number, $min = null, $max = null)
{
    if (!is_numeric($number)) {
        return false;
    }

    if ($min !== null && $number < $min) {
        return false;
    }

    if ($max !== null && $number > $max) {
        return false;
    }

    return true;
}

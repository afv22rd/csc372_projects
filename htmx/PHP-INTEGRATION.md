# PHP Integration for Vehicle Website

This document explains how to set up and use the PHP integration for the vehicle website. The integration allows you to use PHP for vehicle data management while keeping your Node.js/HTMX frontend.

## Overview

The integration consists of:

1. A PHP `Vehicle` class that represents vehicle data
2. A PHP API endpoint that serves vehicle data as JSON
3. A Node.js proxy to communicate with the PHP API
4. A Handlebars template to display vehicle data from the PHP API

## Setup Instructions

### 1. PHP Files

Place the following PHP files on your cPanel hosting:

- `vehicle.php`: Contains the Vehicle class definition
- `api.php`: Provides the API endpoint for vehicle data
- `vehicles.json`: Contains the vehicle data (copy from your Node.js project)

### 2. Update CORS Headers

In `api.php`, update the CORS headers to allow requests from your Render domain:

```php
header('Access-Control-Allow-Origin: https://your-app-name.onrender.com');
```

Replace `your-app-name.onrender.com` with your actual Render domain.

### 3. Update Node.js Proxy

In `server.js`, update the PHP API URL in the proxy routes:

```javascript
const response = await axios.get(`https://your-php-domain.com/api.php?action=search&term=${encodeURIComponent(searchTerm)}`);
```

Replace `your-php-domain.com` with your actual PHP domain.

### 4. Update JavaScript in Handlebars Template

In `views/php-vehicles.handlebars`, update the PHP API URL in the JavaScript:

```javascript
fetch('https://your-php-domain.com/api.php?action=all')
```

Replace `your-php-domain.com` with your actual PHP domain.

## Using the Integration

### Accessing the PHP Vehicles Page

Navigate to `/php-vehicles` in your browser to see the vehicles loaded from the PHP API.

### Searching Vehicles

Use the search form to search for vehicles by make, model, or year.

### Filtering Vehicles

Use the filter form to filter vehicles by various criteria such as make, body type, year range, and price range.

## Future MySQL Integration

When you're ready to integrate MySQL:

1. Update the `loadVehiclesFromJson()` function in `api.php` to load data from MySQL instead of JSON
2. Add database connection code at the top of `api.php`
3. Create a MySQL database and table for vehicles
4. Update the Vehicle class to work with database records

## Troubleshooting

### CORS Issues

If you encounter CORS issues:

1. Make sure the CORS headers in `api.php` match your Render domain exactly
2. Check that your PHP server is configured to allow CORS requests
3. Verify that the Node.js proxy is correctly forwarding requests to the PHP API

### API Connection Issues

If the API connection fails:

1. Check that the PHP API URL is correct in both `server.js` and `php-vehicles.handlebars`
2. Verify that your PHP server is running and accessible
3. Check for any PHP errors in your server logs

## Conclusion

This integration provides a foundation for using PHP with your Node.js/HTMX application. You can extend it to include more features such as user authentication, favorites, and MySQL integration. 
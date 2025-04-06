# PHP Backend for Vehicle Website

This directory contains the PHP backend for the vehicle website. It includes:

1. `vehicle.php`: A Vehicle class that represents a vehicle with all its properties and methods.
2. `api.php`: An API endpoint that creates and manages Vehicle objects.
3. `test.php`: A test script to verify that the PHP backend is working correctly.

## Architecture

- **Frontend**: Node.js/HTMX application hosted on Render (https://brawaautoimport.onrender.com)
- **Backend**: PHP API hosted on cPanel (your-cpanel-domain.com)

## Testing the PHP Backend

### Local Testing

To test the PHP backend locally, you can use the PHP built-in web server:

1. Navigate to the project root directory:
   ```
   cd /path/to/your/project
   ```

2. Start the PHP built-in web server:
   ```
   php -S localhost:8000
   ```

3. Open your browser and navigate to:
   ```
   http://localhost:8000/api/test.php
   ```

4. You should see the output of the test script, which verifies that the Vehicle class and API endpoints are working correctly.

### Testing the API Endpoints

To test the API endpoints, you can use a web browser or a tool like curl:

1. Get all vehicles:
   ```
   http://localhost:8000/api/api.php?action=all
   ```

2. Search for vehicles:
   ```
   http://localhost:8000/api/api.php?action=search&term=Toyota
   ```

3. Filter vehicles:
   ```
   http://localhost:8000/api/api.php?action=filter&make=Toyota
   ```

### Testing with the Node.js Frontend

To test the PHP backend with the Node.js frontend:

1. Start the Node.js server:
   ```
   node server.js
   ```

2. Open your browser and navigate to:
   ```
   http://localhost:3000/php-vehicles
   ```

3. You should see the vehicles loaded from the PHP API.

## Deployment to cPanel

To deploy the PHP backend to a cPanel hosting environment:

1. Log in to your cPanel account.
2. Navigate to the File Manager.
3. Upload the `api` directory to your desired location (typically in the `public_html` directory or a subdirectory).
4. Update the CORS headers in `api.php` to allow requests from your Render domain:
   ```php
   header('Access-Control-Allow-Origin: https://brawaautoimport.onrender.com');
   ```
5. Update the API URLs in `server.js` and `php-vehicles.handlebars` to point to your cPanel-hosted PHP API:
   ```javascript
   // In server.js
   const response = await axios.get(`https://your-cpanel-domain.com/api/api.php?action=search&term=${encodeURIComponent(searchTerm)}`);
   
   // In php-vehicles.handlebars
   fetch('https://your-cpanel-domain.com/api/api.php?action=all')
   ```

## Troubleshooting

If you encounter issues with the PHP backend:

1. Check the PHP error logs in cPanel for any errors.
2. Verify that the CORS headers in `api.php` match your Render domain exactly.
3. Make sure that the API URLs in `server.js` and `php-vehicles.handlebars` are correct.
4. Test the API endpoints directly using a web browser or curl to verify that they're working correctly.
5. If you're having issues with file permissions, make sure the PHP files have the correct permissions (typically 644 for files and 755 for directories). 
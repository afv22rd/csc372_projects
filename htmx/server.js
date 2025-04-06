// Import required modules
import express from 'express';                // Framework for building web applications
import path from 'path';                      // Module for working with file and directory paths
import { fileURLToPath } from 'url';          // Converts file URL to a file path
import fs from 'fs';                          // File system module for reading and writing files
import axios from 'axios';                    // HTTP client for making requests to external APIs
import { engine } from 'express-handlebars';  // Template engine for generating HTML

// Create an Express application
const app = express();

// Convert the current module's URL to a file path and get the directory name
// This allows us to use __dirname in ES modules (which normally don't have it)
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Configure Handlebars as the view template engine
app.engine('handlebars', engine({
  defaultLayout: 'main',                      // Set the default layout template
  layoutsDir: path.join(__dirname, 'views/layouts'), // Specify the directory for layout templates
  helpers: {
    // Add a helper function to compare two values for equality
    // This can be used in handlebars templates like {{#if (eq var1 var2)}}
    eq: function(v1, v2) {
      return v1 === v2;
    }
  }
}));
app.set('view engine', 'handlebars');         // Set handlebars as the template engine
app.set('views', path.join(__dirname, 'views')); // Specify the directory for view templates

// Serve static files from the 'public' directory
// This allows CSS, JavaScript, images, etc. to be accessed directly
app.use(express.static('public'));

// Add middleware to parse form data in requests
// extended: true allows for rich objects and arrays to be encoded
app.use(express.urlencoded({ extended: true }));

// Route handler for the home page
app.get('/', (req, res) => {
    // Render the 'home' template with the provided title and active page
    res.render('home', {title: "Brawa AutoImport | Buy, Sell & Finance Vehicles Online | Used Cars for Sale in Dominican Republic", active: 'home'});
});

// Route handler for the search cars page
app.get('/search-cars', (req, res) => {
    // Render the 'search-cars' template with title and active page marker
    res.render('search-cars', {title: "Search Cars | Brawa AutoImport | Buy, Sell & Finance Vehicles Online | Used Cars for Sale in Dominican Republic", active: 'search-cars'});
});

// Route handler to determine the user's location based on coordinates
app.get('/location', async (req, res) => {
    try {
        console.log('Location request:', req.query);
        // Extract latitude and longitude from the query parameters
        const { latitude, longitude } = req.query;
        if (latitude && longitude) {
            // If coordinates are provided, use OpenStreetMap to get location info
            // Nominatim is OSM's geocoding service that converts coordinates to addresses
            const response = await axios.get(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=10`);

            // Check if the response contains address data
            if (response.data && response.data.address) {
                let province = response.data.address.state;
                let city = response.data.address.city;
                if (city === undefined) {
                    city = response.data.address.town;
                }
                // Return the formatted location as "City, Province"
                return res.send(`${city}, ${province}`);
            }
        }
        
        // If coordinates weren't provided or location couldn't be determined
        res.send('Update location');
    } catch (error) {
        // Log any errors that occurred during the process
        console.error('Error fetching location:', error);
        // Return a fallback location
        res.send('Santo Domingo');
    }
});

// Route to serve the HTML content for popular searches
app.get('/popular-searches', (req, res) => {
    // Send the pre-defined HTML file for popular searches
    res.sendFile(path.join(__dirname, 'public', 'data', 'popular-searches.html'));
});

// Function to load vehicle data from the PHP API
const loadVehiclesData = async () => {
    try {
        // Make a request to the PHP API to get all vehicles
        const response = await axios.get('https://afriasv.rhody.dev/csc372_projects/htmx/api/api.php?action=all');
        // Return the vehicles from the response
        return response.data.vehicles || [];
    } catch (err) {
        // Log any errors that occur during API request
        console.error('Error loading vehicles data from PHP API:', err);
        // Return an empty array if there's an error
        return [];
    }
};

// Route to load and display vehicle cards for the search results
app.get('/load-vehicles', async(req, res) => {
    try {
        // Load the vehicle data from the PHP API
        const vehicles = await loadVehiclesData();

        // For each vehicle, generate an HTML card with all its details
        // Each card includes:
        // - Image carousel with navigation
        // - Favorite button
        // - Vehicle details (year, make, model)
        // - Specifications (fuel type, mileage)
        // - Price information
        // - Estimated payment information
        const vehicleCards = vehicles.map(vehicle => /*html*/`
            <div class="card bg-base-100 shadow-sm w-full max-w-sm">
                <!-- Image section -->
                <figure class="relative">
                    <!-- Carousel -->
                    <div class="carousel w-full rounded-t-lg h-48 md:h-56 lg:h-60 overflow-hidden relative">
                        ${vehicle.images.map((image, index) => `
                            <div id="slide-${vehicle.id}-${index}" class="carousel-item relative w-full">
                                <img src="${image}" class="w-full h-full object-cover" alt="${vehicle.make} ${vehicle.model} Image ${index+1}" />
                                <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                                    <a href="#slide-${vehicle.id}-${index === 0 ? vehicle.images.length - 1 : index - 1}" 
                                    class="btn btn-circle btn-sm opacity-85 hover:opacity-100" 
                                    onclick="event.preventDefault(); document.getElementById('slide-${vehicle.id}-${index === 0 ? vehicle.images.length - 1 : index - 1}').scrollIntoView({behavior: 'smooth', block: 'nearest', inline: 'center'})">❮</a>
                                    <a href="#slide-${vehicle.id}-${index === vehicle.images.length - 1 ? 0 : index + 1}" 
                                    class="btn btn-circle btn-sm opacity-85 hover:opacity-100"
                                    onclick="event.preventDefault(); document.getElementById('slide-${vehicle.id}-${index === vehicle.images.length - 1 ? 0 : index + 1}').scrollIntoView({behavior: 'smooth', block: 'nearest', inline: 'center'})">❯</a>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    <!-- End of Carousel -->
        
                    <!-- Favorite Heart Icon - Uses HTMX to send a POST request when clicked -->
                    <button class="absolute top-2 right-2 btn btn-circle btn-sm bg-base-100 bg-opacity-70" hx-post="/toggle-favorite" hx-vals='{"vehicleId": ${vehicle.id}}' hx-swap="none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <!-- End of Favorite Heart Icon -->
                </figure>
        
                <!-- Card body with vehicle information -->
                <div class="card-body p-4">
                    <!-- Year Make Model - Primary vehicle identifier -->
                    <h2 class="card-title text-lg font-bold">${vehicle.year} ${vehicle.make} ${vehicle.model}</h2>
                    
                    <!-- Spec line - Additional vehicle specifications -->
                    <div class="flex items-center text-sm text-gray-500">
                        <span>${vehicle.fuel_type}</span>
                        <span class="mx-2">•</span>
                        <span>${vehicle.mileage.toLocaleString()} miles</span>
                    </div>
                    
                    <!-- Price - Vehicle cost with formatting for thousands separator -->
                    <div class="mt-2">
                        <span class="text-2xl font-bold">$${vehicle.price.toLocaleString()}</span>
                    </div>
                    
                    <!-- Payment info - Estimated monthly payment and down payment -->
                    <div class="mt-2 text-sm text-gray-600">
                        <div class="flex items-center">
                            <span>Est. $${Math.round(vehicle.price / 60)} /mo</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 cursor-help" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>$${Math.round(vehicle.price * 0.1).toLocaleString()} initial cash down</div>
                    </div>
                </div>
            </div>
        `).join('');
        
        // Send the generated HTML for all vehicle cards
        res.send(vehicleCards);
    } catch (error) {
        // Log and return an error message if there's a problem
        console.error('Error loading vehicle data:', error);
        res.status(500).send('<div class="alert alert-error">Error loading vehicles</div>');
    }
});

// Route to serve the HTML template for search results
app.get('/search-results', (req, res) => {
    // Send the pre-defined search results template
    res.sendFile(path.join(__dirname, 'public', 'data', 'search-results.html'));
});

// Route to handle search functionality
app.post('/search', async (req, res) => {
    try {
        // Get the search term from the request body and convert to lowercase
        const searchTerm = req.body.search?.toLowerCase() || '';

        let responseHTML = '';

        // If search field is empty, show popular searches instead of results
        if (!searchTerm) {
            // Read the popular searches HTML file
            responseHTML = fs.readFileSync(path.join(__dirname, 'public', 'data', 'popular-searches.html'),'utf8');
        } else {
            // Make a request to the PHP API to search for vehicles
            const response = await axios.get(`https://afriasv.rhody.dev/csc372_projects/htmx/api/api.php?action=search&term=${encodeURIComponent(searchTerm)}`);
            const searchResults = response.data.vehicles || [];

            // Read the template for individual search results
            const searchResultsTemplate = fs.readFileSync(path.join(__dirname, 'public', 'data', 'search-results.html'),'utf8');
            
            // For each matching vehicle, create an entry using the template
            // Replace placeholders in the template with actual vehicle data
            responseHTML = searchResults.map(vehicle => {
                return searchResultsTemplate
                    .replace('{{make}}', vehicle.make)
                    .replace('{{model}}', vehicle.model)
                    .replace('{{year}}', vehicle.year);
            }).join('');

            // If no vehicles match the search term, show a message
            if (!responseHTML) {
                responseHTML = '<div class="p-2 text-gray-500">No vehicles found</div>';
            }
        }
        
        // Send the final HTML response
        res.send(responseHTML);
    } catch (error) {
        console.error('Error searching vehicles:', error);
        res.status(500).send('<div class="alert alert-error">Error searching vehicles</div>');
    }
});

// Route to handle requests for popular vehicle styles
app.get('/popular-styles', (req, res) => {
    try {
        // Read the XML file that contains popular vehicle styles data
        const xmlData = fs.readFileSync(path.join(__dirname, 'public', 'data', 'popular-styles.xml'), 'utf8');
        
        // Use regular expressions to extract all vehicle elements from the XML
        // This finds all text between <vehicle> and </vehicle> tags
        const vehicleMatches = xmlData.match(/<vehicle>[\s\S]*?<\/vehicle>/g);
        
        // If no vehicle elements were found, return an error
        if (!vehicleMatches) {
            return res.status(404).send('<div class="col-span-full text-center">Vehicle styles not found</div>');
        }
        
        // Transform each vehicle XML element into an HTML button
        const buttonsHtml = vehicleMatches.map(vehicleXml => {
            // Extract the vehicle type (like "SUV", "Sedan", etc.)
            const typeMatch = vehicleXml.match(/<type>(.*?)<\/type>/);
            const type = typeMatch ? typeMatch[1] : 'Unknown';
            
            // Extract the alt text for image accessibility
            const altMatch = vehicleXml.match(/<alt>(.*?)<\/alt>/);
            const alt = altMatch ? altMatch[1] : '';
            
            // Check if this vehicle should use an SVG icon instead of an image
            const isSvgMatch = vehicleXml.match(/<isSvg>(.*?)<\/isSvg>/);
            const isSvg = isSvgMatch && isSvgMatch[1] === 'true';
            
            // Create different HTML depending on whether it uses SVG or image
            if (isSvg) {
                // For "Shop All" option, use an arrow SVG icon
                return `
                <button class="border border-base-900 rounded-lg hover:bg-base-200 transition-colors w-full h-40 flex flex-col items-center justify-center gap-2 p-4">
                    <span class="md:text-lg sm:text-md font-medium">${type}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-24 w-auto">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"></path>
                    </svg>
                </button>`;
            } else {
                // For regular vehicle types, extract and use the image URL
                const imageMatch = vehicleXml.match(/<image>(.*?)<\/image>/);
                const image = imageMatch ? imageMatch[1] : '';
                
                // Return a button with the vehicle image
                return `
                <button class="border border-base-900 rounded-lg hover:bg-base-200 transition-colors w-full h-40 flex flex-col items-center justify-center gap-2 p-4">
                    <span class="md:text-lg sm:text-md font-medium">${type}</span>
                    <img src="${image}" alt="${alt}" class="h-24 w-auto object-contain">
                </button>`;
            }
        }).join(''); // Combine all button HTML strings
        
        // Send the completed HTML
        res.send(buttonsHtml);
        
    } catch (err) {
        // Log any errors that occur
        console.error('Error processing popular styles:', err);
        // Send an error message
        res.status(500).send('<div class="col-span-full text-center">Error loading vehicle styles</div>');
    }
});

// Function to load favorites data from a JSON file
const loadFavorites = () => {
    try {
        // Read the favorites JSON file
        const data = fs.readFileSync(path.join(__dirname, 'public', 'data', 'favorites.json'), 'utf8');
        // Parse the JSON string into a JavaScript object
        return JSON.parse(data);
    } catch (err) {
        // Log any errors that occur
        console.error('Error loading favorites:', err);
        // Return an empty favorites object if there's an error
        return { favorites: {} };
    }
}

// Route to handle requests for favorites data
app.get('/favorites', (req, res) => {
    // Load the favorites data
    const favoritesData = loadFavorites();
    const favorites = favoritesData.favorites || {};
    
    // Count the total number of favorite vehicles across all categories
    let totalCount = 0;
    for (const category in favorites) {
        totalCount += favorites[category].length;
    }
    
    // Check what type of content is requested (count or full list)
    const contentType = req.query.content;
    
    if (contentType === 'count') {
        // If only the count is requested, return just the number
        return res.send(`${totalCount}`);
    } else {
        // Otherwise, generate HTML for the full favorites list
        let html = `
                  <span class="text-lg font-bold fav-items">${totalCount} Items</span>
                  <span class="text-info">
                    <ul class="fav-list">`;
        
        // Add each category (like "SUVs", "Sedans") and its vehicles
        for (const category in favorites) {
            const vehicles = favorites[category];
            html += `<li class="font-semibold">${category}: ${vehicles.length}</li>`;
            html += `<ul class="ml-4">`;
            
            // Add each individual vehicle in this category
            vehicles.forEach(vehicle => {
                html += `<li>${vehicle.make} ${vehicle.model}</li>`;
            });
            
            html += `</ul>`;
        }
        
        html += `    </ul>
                   </span>`;
        
        // Return the complete HTML
        return res.send(html);
    }
});

// Load car make and model data from JSON file
const carsDataPath = path.join(__dirname, 'public', 'data', 'makes_models.json');
let carsData;
try {
    // Read and parse the JSON file
    const rawData = fs.readFileSync(carsDataPath, 'utf8');
    carsData = JSON.parse(rawData);
} catch (error) {
    // Log any errors and use an empty object as fallback
    console.error('Error loading cars data:', error);
    carsData = { makes: {} };
}
  
// Route to load all car makes for the filter sidebar
app.get('/load-car-makes', (req, res) => {
    // Extract the makes object from the loaded data
    const { makes } = carsData;

    let makesHtml = '';

    // Generate HTML for each car make as a checkbox
    Object.keys(makes).forEach(make => {
        // Create an ID for the models container by converting the make name to kebab-case
        const makeId = make.toLowerCase().replace(/\s+/g, '-');
        
        // Create a checkbox for each make with HTMX attributes
        // When clicked, it will trigger a POST to /toggle-make
        makesHtml += `
        <div class="make-item">
            <label class="cursor-pointer flex items-center gap-2">
            <input type="checkbox" class="checkbox checkbox-sm" 
                   name="checked"
                   hx-trigger="click" 
                   hx-post="/toggle-make" 
                   hx-vals='{"make": "${make}"}' 
                   hx-include="this"
                   hx-target="#${makeId}-models" 
                   hx-swap="innerHTML" />
            <span class="text-sm">${make}</span>
            </label>
            <div id="${makeId}-models" class="pl-6 mt-1 space-y-1"></div>
        </div>
        `;
    });

    // Send the HTML for all makes
    res.send(makesHtml);
});

// Route to handle toggling a car make and loading its models
app.post('/toggle-make', express.urlencoded({ extended: true }), (req, res) => {
    // Extract the make name and checked status from the request
    const { make, checked } = req.body;
    
    // Log the request for debugging
    console.log('Toggle make request:', req.body);
    
    // Determine if the checkbox is checked
    // HTML checkboxes send 'on' when checked, and the parameter is missing when unchecked
    const isChecked = req.body.checked === 'on';
    
    if (isChecked) {
      // If checked, load all models for this make
      const models = carsData.makes[make]?.models || [];
      console.log(`Models for ${make}:`, models);
      
      // Generate HTML for each model as a checkbox
      let modelsHtml = '';
      models.forEach(model => {
        modelsHtml += `
          <label class="cursor-pointer flex items-center gap-2">
            <input type="checkbox" class="checkbox checkbox-sm" 
                   name="model"
                   hx-trigger="click" 
                   hx-post="/update-filter" 
                   hx-vals='{"filter": "model", "value": "${model}", "make": "${make}"}' 
                   hx-include="this" />
            <span class="text-sm">${model}</span>
          </label>
        `;
      });
      
      // Send the HTML for all models of this make
      res.send(modelsHtml);
    } else {
      // If unchecked, clear the models section
      res.send('');
    }
});

// Add middleware to parse JSON request bodies
app.use(express.json());

// Test route that intentionally triggers a server error
// Used for testing the 500 error page
app.get('/500', (req, res, next) => {
    // Throw an error to trigger the error handler
    throw new Error('Test error for 500 page');
});

// Error handler middleware for internal server errors (500)
app.use((err, req, res, next) => {
    // Log the error stack trace to the console
    console.error(err.stack);
    // Render the 500 error page
    res.status(500).render('500', { title: "Server Error | Brawa AutoImport | Buy, Sell & Finance Vehicles Online | Used Cars for Sale in Dominican Republic" });
});

// Middleware to handle 404 errors (routes that don't match any defined routes)
app.use((req, res) => {
    // Render the 404 error page
    res.status(404).render('404', { title: "Page Not Found | Brawa AutoImport | Buy, Sell & Finance Vehicles Online | Used Cars for Sale in Dominican Republic" });
});

// Route handler for the PHP vehicles page
app.get('/php-vehicles', (req, res) => {
    // Render the 'php-vehicles' template with title and active page marker
    res.render('php-vehicles', {title: "PHP Vehicles | Brawa AutoImport | Buy, Sell & Finance Vehicles Online | Used Cars for Sale in Dominican Republic", active: 'php-vehicles'});
});

// Route to proxy requests to the PHP API
// This route is used by the Render-hosted frontend to communicate with the PHP API
app.get('/php-api-search', async (req, res) => {
    try {
        const searchTerm = req.query.term || '';
        
        // Make a request to the PHP API
        const response = await axios.get(`https://afriasv.rhody.dev/csc372_projects/htmx/api/api.php?action=search&term=${encodeURIComponent(searchTerm)}`);
        
        // Return the response from the PHP API
        res.json(response.data);
    } catch (error) {
        console.error('Error searching vehicles via PHP API:', error);
        res.status(500).json({
            status: 'error',
            message: 'Error searching vehicles'
        });
    }
});

// Route to proxy filter requests to the PHP API
// This route is used by the Render-hosted frontend to communicate with the PHP API
app.get('/php-api-filter', async (req, res) => {
    try {
        // Extract filter parameters from the request
        const { make, model, min_year, max_year, min_price, max_price, body_type } = req.query;
        
        // Build the query string for the PHP API
        let queryString = 'action=filter';
        
        if (make) queryString += `&make=${encodeURIComponent(make)}`;
        if (model) queryString += `&model=${encodeURIComponent(model)}`;
        if (min_year) queryString += `&min_year=${encodeURIComponent(min_year)}`;
        if (max_year) queryString += `&max_year=${encodeURIComponent(max_year)}`;
        if (min_price) queryString += `&min_price=${encodeURIComponent(min_price)}`;
        if (max_price) queryString += `&max_price=${encodeURIComponent(max_price)}`;
        if (body_type) queryString += `&body_type=${encodeURIComponent(body_type)}`;
        
        // Make a request to the PHP API
        const response = await axios.get(`https://afriasv.rhody.dev/csc372_projects/htmx/api/api.php?${queryString}`);
        
        // Return the response from the PHP API
        res.json(response.data);
    } catch (error) {
        console.error('Error filtering vehicles via PHP API:', error);
        res.status(500).json({
            status: 'error',
            message: 'Error filtering vehicles'
        });
    }
});

// Start the server on port 3000
app.listen(3000, () => {
    console.log(`Server is running on http://localhost:3000`);
});
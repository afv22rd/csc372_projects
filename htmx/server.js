import express from 'express';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';
const app = express();
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Set public static folder
app.use(express.static('public'));

// Parse URL-encoded bodies (as sent by HTML forms)
app.use(express.urlencoded({ extended: true }));

// Set index path
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'buy-sell-trade-finance-vehicles-online.html'));
})

// Load search cars page
app.get('/search-cars.html', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'search-cars.html'));
});

// GET request for popular searches HTML
app.get('/popular-searches', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'data', 'popular-searches.html'));
});

// Load vehicles data from JSON file
const loadVehiclesData = () => {
    try {
        const data = fs.readFileSync(path.join(__dirname, 'public', 'data', 'vehicles.json'), 'utf8');
        return JSON.parse(data);
    } catch (err) {
        console.error('Error loading vehicles data:', err);
        return [];
    }
};

// GET request for search results HTML
app.get('/search-results', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'data', 'search-results.html'));
});

// POST request for search bar
app.post('/search', (req, res) => {
    const vehicles = loadVehiclesData();
    const searchTerm = req.body.search?.toLowerCase() || '';

    let responseHTML = '';

    // If the input is empty, show popular searches
    if (!searchTerm) {
        responseHTML = fs.readFileSync(path.join(__dirname, 'public', 'data', 'popular-searches.html'),'utf8');
    } else {
        // Search for vehicles matching input
        const searchResults = vehicles.filter(vehicle => {
            return vehicle.make.toLowerCase().includes(searchTerm) ||
                   vehicle.model.toLowerCase().includes(searchTerm) ||
                   vehicle.year.toString().includes(searchTerm);
        });

        // Generate HTML for search results
        const searchResultsTemplate = fs.readFileSync(path.join(__dirname, 'public', 'data', 'search-results.html'),'utf8');
        responseHTML = searchResults.map(vehicle => {
            // Replace placeholders with vehicle data
            return searchResultsTemplate.replace('{{make}}', vehicle.make).replace('{{model}}', vehicle.model).replace('{{year}}', vehicle.year);
        }).join('');

        // If no vehicles found, show message
        if (!responseHTML) {
            responseHTML = '<div class="p-2 text-gray-500">No vehicles found</div>';
        }
    }
    res.send(responseHTML);
});

// Define a route to handle GET requests to '/popular-styles' endpoint
app.get('/popular-styles', (req, res) => {
    try {
        // Read the XML file containing popular vehicle styles from the specified path
        const xmlData = fs.readFileSync(path.join(__dirname, 'public', 'data', 'popular-styles.xml'), 'utf8');
        
        // Use regex to extract all vehicle elements from the XML data
        // The [\s\S]*? pattern matches any character (including newlines) non-greedily between vehicle tags
        const vehicleMatches = xmlData.match(/<vehicle>[\s\S]*?<\/vehicle>/g);
        
        // If no vehicle elements were found in the XML, return a 404 error with an error message
        if (!vehicleMatches) {
            return res.status(404).send('<div class="col-span-full text-center">Vehicle styles not found</div>');
        }
        
        // Map each vehicle XML element to an HTML button
        const buttonsHtml = vehicleMatches.map(vehicleXml => {
            // Extract the vehicle type using regex
            // The (.*?) captures the content between the type tags
            const typeMatch = vehicleXml.match(/<type>(.*?)<\/type>/);
            // Use the matched type or default to 'Unknown' if not found
            const type = typeMatch ? typeMatch[1] : 'Unknown';
            
            // Extract the alt text for image accessibility
            const altMatch = vehicleXml.match(/<alt>(.*?)<\/alt>/);
            const alt = altMatch ? altMatch[1] : '';
            
            // Check if this vehicle should use an SVG (for "Shop All" button)
            const isSvgMatch = vehicleXml.match(/<isSvg>(.*?)<\/isSvg>/);
            const isSvg = isSvgMatch && isSvgMatch[1] === 'true';
            
            // Create different button HTML based on whether it uses an SVG or an image
            if (isSvg) {
                // Return a button with an SVG arrow icon for the "Shop All" option
                return `
                <button class="border border-base-900 rounded-lg hover:bg-base-200 transition-colors w-full h-40 flex flex-col items-center justify-center gap-2 p-4">
                    <span class="text-lg font-medium">${type}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-24 w-auto">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"></path>
                    </svg>
                </button>`;
            } else {
                // For regular vehicle types, extract the image URL
                const imageMatch = vehicleXml.match(/<image>(.*?)<\/image>/);
                const image = imageMatch ? imageMatch[1] : '';
                
                // Return a button with an image representing the vehicle type
                return `
                <button class="border border-base-900 rounded-lg hover:bg-base-200 transition-colors w-full h-40 flex flex-col items-center justify-center gap-2 p-4">
                    <span class="text-lg font-medium">${type}</span>
                    <img src="${image}" alt="${alt}" class="h-24 w-auto object-contain">
                </button>`;
            }
        }).join(''); // Join all button HTML strings together
        
        // Send the completed HTML for all buttons to the client
        res.send(buttonsHtml);
        
    } catch (err) {
        // Log any errors that occur during processing
        console.error('Error processing popular styles:', err);
        // Send a 500 error with a user-friendly message
        res.status(500).send('<div class="col-span-full text-center">Error loading vehicle styles</div>');
    }
});

const loadFavorites = () => {
    try {
        const data = fs.readFileSync(path.join(__dirname, 'public', 'data', 'favorites.json'), 'utf8');
        return JSON.parse(data);
    } catch (err) {
        console.error('Error loading favorites:', err);
        return { favorites: {} };
    }
}

// GET request for favorites from JSON file
app.get('/favorites', (req, res) => {
    const favoritesData = loadFavorites();
    const favorites = favoritesData.favorites || {};
    
    // Count total favorites across all categories
    let totalCount = 0;
    for (const category in favorites) {
        totalCount += favorites[category].length;
    }
    
    // Check the content parameter to determine what to return
    const contentType = req.query.content;
    
    if (contentType === 'count') {
        // Just send the count as plain text with the word "Items"
        return res.send(`${totalCount}`);
    } else {
        // Generate full HTML for favorites
        let html = `
                  <span class="text-lg font-bold fav-items">${totalCount} Items</span>
                  <span class="text-info">
                    <ul class="fav-list">`;
        
        // Add each category and its vehicles
        for (const category in favorites) {
            const vehicles = favorites[category];
            html += `<li class="font-semibold">${category}: ${vehicles.length}</li>`;
            html += `<ul class="ml-4">`;
            
            // Add each vehicle in this category
            vehicles.forEach(vehicle => {
                html += `<li>${vehicle.make} ${vehicle.model}</li>`;
            });
            
            html += `</ul>`;
        }
        
        html += `    </ul>
                   </span>`;
        
        return res.send(html);
    }
});


// Parse JSON bodies (as sent by API clients)
app.use(express.json());

app.listen(3000, () => {
    console.log(`Server is running on http://localhost:3000`);
});


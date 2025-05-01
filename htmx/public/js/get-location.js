$(document).ready(function() {
  // Function to fetch location from the server
  function fetchLocationFromServer() {
    $('#location-name').html('Getting location...'); // Show loading state

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        // Got precise coordinates
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        htmx.ajax('GET', 'api/location.php?latitude=' + lat + '&longitude=' + lon, {
          target: '#location-name',
          swap: 'innerHTML',
          // Save the successful response to localStorage
          beforeSwap: function(swapInfo) {
            if (swapInfo.xhr.status === 200 && swapInfo.serverResponse && swapInfo.serverResponse.trim() !== 'Update location') {
              localStorage.setItem('userLocationName', swapInfo.serverResponse);
            } else if (swapInfo.xhr.status !== 200) {
               // Clear cache on error? Or keep stale? For now, let's keep stale.
               console.error("Failed to fetch location with coordinates.");
            }
            return true;
          }
        });
      }, function(error) {
        // Geolocation failed (denied permission, etc.)
        console.error('Geolocation error:', error);
        // Fall back to server-side IP lookup (if implemented) or default
        htmx.ajax('GET', 'api/location.php', {
          target: '#location-name',
          swap: 'innerHTML',
          // Save the successful response to localStorage
           beforeSwap: function(swapInfo) {
            if (swapInfo.xhr.status === 200 && swapInfo.serverResponse && swapInfo.serverResponse.trim() !== 'Update location') {
              localStorage.setItem('userLocationName', swapInfo.serverResponse);
            } else if (swapInfo.xhr.status !== 200) {
               console.error("Failed to fetch location without coordinates.");
            }
            return true;
          }
        });
      });
    } else {
      // Browser doesn't support geolocation
      console.log('Geolocation is not supported by this browser.');
      // Fall back to server-side IP lookup or default
      htmx.ajax('GET', 'api/location.php', {
        target: '#location-name',
        swap: 'innerHTML',
         // Save the successful response to localStorage
         beforeSwap: function(swapInfo) {
            if (swapInfo.xhr.status === 200 && swapInfo.serverResponse && swapInfo.serverResponse.trim() !== 'Update location') {
              localStorage.setItem('userLocationName', swapInfo.serverResponse);
            } else if (swapInfo.xhr.status !== 200) {
               console.error("Failed to fetch location (no geolocation support).");
            }
            return true;
          }
      });
    }
  }

  // --- Initialization on Page Load ---
  const cachedLocation = localStorage.getItem('userLocationName');

  if (cachedLocation && cachedLocation !== 'Update location') {
    // If a valid location is cached, display it immediately
    $('#location-name').html(cachedLocation);
    // Optional: You could uncomment the next line to silently refresh
    // the location in the background *after* showing the cached one.
    // fetchLocationFromServer();
  } else {
    // If no valid cache, fetch fresh location on load
    fetchLocationFromServer();
  }

  // --- Event Handler for Refresh Button ---
  $('#location-button').on('click', function(e) {
    e.preventDefault(); // Prevent default behavior
    // Always fetch fresh location when the button is clicked, bypassing cache check
    fetchLocationFromServer();
  });
});
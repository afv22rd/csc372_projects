$(document).ready(function() {
  // This function executes when the DOM is fully loaded
  
  // Always get fresh location data when the page loads
  getAndUpdateLocation();
  
  // Set up an event handler for when the user clicks the location refresh button
  $('#location-button').on('click', function(e) {
    // Prevent any default HTMX behaviors or form submissions
    e.preventDefault();
    
    // When the user explicitly requests a location update, get fresh coordinates
    getAndUpdateLocation();
  });
  
  // Core function that handles obtaining user location and updating the interface
  function getAndUpdateLocation() {
    // Clear existing loading state if any
    $('#location-name').html('Getting location...');
    
    if (navigator.geolocation) {
      // Browser supports geolocation API - attempt to get precise coordinates
      navigator.geolocation.getCurrentPosition(function(position) {
        // Success callback - user allowed access to their precise location
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        
        // Make an AJAX request to our server including precise coordinates
        htmx.ajax('GET', 'api/location.php?latitude=' + lat + '&longitude=' + lon, {
          target: '#location-name',  // Element to update with the response
          swap: 'innerHTML',         // Replace the inner HTML of the target
          // This runs before updating the UI with the response
          beforeSwap: function(swapInfo) {
            if (swapInfo.xhr.status === 200) {
              // Still store in localStorage for the current session
              localStorage.setItem('userLocationName', swapInfo.serverResponse);
            }
            return true; // Proceed with updating the UI
          }
        });
      }, function(error) {
        // Error callback - user denied permission or other geolocation error
        console.error('Geolocation error:', error);
        
        // Fall back to IP-based location detection without coordinates
        htmx.ajax('GET', 'api/location.php', {
          target: '#location-name',
          swap: 'innerHTML',
          beforeSwap: function(swapInfo) {
            if (swapInfo.xhr.status === 200) {
              localStorage.setItem('userLocationName', swapInfo.serverResponse);
            }
            return true;
          }
        });
      });
    } else {
      // Browser does not support geolocation API
      // Use IP-based location detection as fallback
      htmx.ajax('GET', 'api/location.php', {
        target: '#location-name',
        swap: 'innerHTML',
        beforeSwap: function(swapInfo) {
          if (swapInfo.xhr.status === 200) {
            localStorage.setItem('userLocationName', swapInfo.serverResponse);
          }
          return true;
        }
      });
    }
  }
});
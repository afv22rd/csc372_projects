$(document).ready(function() {
  // This function executes when the DOM is fully loaded
  
  // Check if we have a location stored in cookies or localStorage
  function getStoredLocation() {
    // Try to get location from cookies first
    const getCookie = (name) => {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(';').shift();
      return null;
    };
    
    // Check for cookie first, fall back to localStorage
    return getCookie('user_location') || localStorage.getItem('userLocationName');
  }
  
  const storedLocation = getStoredLocation();
  
  if (storedLocation) {
    // If a location exists, update the UI element with this stored value
    $('#location-name').html(storedLocation);
  } else {
    // For first-time visitors with no stored location, try to get their location automatically
    getAndUpdateLocation();
  }
  
  // Set up an event handler for when the user clicks the location refresh button
  $('#location-button').on('click', function(e) {
    // Prevent any default HTMX behaviors or form submissions
    e.preventDefault();
    
    // When the user explicitly requests a location update, get fresh coordinates
    // regardless of whether we already have a stored location
    getAndUpdateLocation();
  });
  
  // Core function that handles obtaining user location and updating the interface
  function getAndUpdateLocation() {
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
        // If request was successful, save the location name to local storage
        // (Cookie already set on the server side)
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
        // Still save the fallback location to avoid repeated requests
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
        // Store even this fallback location for future use
        localStorage.setItem('userLocationName', swapInfo.serverResponse);
      }
      return true;
      }
    });
    }
  }
});
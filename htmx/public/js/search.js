$(document).ready(function() {
    // Handle clicks on document
    $(document).on('click', function(event) {
      const $searchSuggestions = $('#search-suggestions');
      const $searchInput = $('input[name="search"]');
      
      // If suggestions are visible and click is outside both suggestions and input
      if (!$searchSuggestions.hasClass('hidden') && 
          !$searchSuggestions.is(event.target) && 
          $searchSuggestions.has(event.target).length === 0 && 
          !$searchInput.is(event.target) && 
          $searchInput.has(event.target).length === 0) {
        
        // Hide suggestions
        $searchSuggestions.addClass('hidden');
        console.log('Hiding search suggestions');
      }
    });
  });
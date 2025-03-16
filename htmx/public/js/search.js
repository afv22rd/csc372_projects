$(document).ready(function() {
  // Add event listener for search input focus
  $('input[name="search"]').on('click focus', function() {
      const $searchSuggestions = $('#search-suggestions');
      
      // Only animate if it's currently hidden
      if ($searchSuggestions.hasClass('hidden')) {
          // Remove hidden class
          $searchSuggestions.removeClass('hidden');
          
          // Apply animation - fade in with slide down
          $searchSuggestions.hide().slideDown(200).fadeIn(200);
          
          console.log('Showing search suggestions');
      }
  });

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
      
      // Hide suggestions with animation
      $searchSuggestions.fadeOut(200, function() {
        $(this).addClass('hidden').css('display', '');
        console.log('Hiding search suggestions');
      });
    }
  });
});
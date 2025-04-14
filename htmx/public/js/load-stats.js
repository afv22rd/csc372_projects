$(document).ready(function() {
    // Load stats using AJAX
    $.ajax({
      url: 'public/data/stats.html',
      type: 'GET',
      success: function(data) {
        $('#stats-container').html(data);
      },
      error: function(xhr, status, error) {
        console.error('Error loading stats:', error);
        $('#stats-container').html('<div class="stat"><div class="stat-title">Error loading stats</div><div class="stat-desc">Please refresh the page</div></div>');
      }
    });
  });
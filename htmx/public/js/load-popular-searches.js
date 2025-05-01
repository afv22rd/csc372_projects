document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('popular-searches-container');
    if (!container) {
        console.error('Popular searches container not found.');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'public/data/popular-searches.html', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Add a heading before inserting the content
            container.innerHTML = '<h2 class="text-3xl font-bold text-left pb-8">Popular Searches</h2>' + xhr.responseText;
        } else {
            console.error('Error loading popular searches:', xhr.statusText);
            container.innerHTML = '<h2 class="text-3xl font-bold text-left pb-8">Popular Searches</h2><p class="text-error">Could not load popular searches.</p>';
        }
    };

    xhr.onerror = function() {
        console.error('Network error loading popular searches.');
        container.innerHTML = '<h2 class="text-3xl font-bold text-left pb-8">Popular Searches</h2><p class="text-error">Network error loading popular searches.</p>';
    };

    xhr.send();
});

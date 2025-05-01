document.addEventListener('DOMContentLoaded', function() {
    loadPopularStyles();
    loadFavorites();

    // Add listener for popular searches on search input focus
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('focus', loadPopularSearches);
    }
});

// 1. Load XML using XMLHttpRequest
function loadPopularStyles() {
    const container = document.getElementById('popular-styles-grid');
    if (!container) return;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'public/data/popular-styles.xml', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const xmlDoc = xhr.responseXML;
            if (!xmlDoc) {
                console.error("Failed to parse XML");
                container.innerHTML = '<p class="text-error col-span-full">Error loading popular styles (invalid XML).</p>';
                return;
            }
            const styles = xmlDoc.getElementsByTagName('style');
            let htmlContent = '';
            for (let i = 0; i < styles.length; i++) {
                try {
                    const name = styles[i].getElementsByTagName('name')[0].textContent;
                    const image = styles[i].getElementsByTagName('image')[0].textContent;
                    const link = styles[i].getElementsByTagName('link')[0].textContent;
                    htmlContent += `
                        <a href="${link}" class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow duration-200 ease-in-out">
                            <figure class="px-4 pt-4">
                                <img src="${image}" alt="${name}" class="rounded-xl h-24 object-contain">
                            </figure>
                            <div class="card-body items-center text-center p-4">
                                <h2 class="card-title text-sm">${name}</h2>
                            </div>
                        </a>`;
                } catch (e) {
                    console.error("Error processing XML style element:", e);
                }
            }
            container.innerHTML = htmlContent;
        } else {
            console.error('Error loading popular styles:', xhr.statusText);
            container.innerHTML = '<p class="text-error col-span-full">Error loading popular styles.</p>';
        }
    };
    xhr.onerror = function() {
        console.error('Network error loading popular styles.');
        container.innerHTML = '<p class="text-error col-span-full">Network error loading popular styles.</p>';
    };
    xhr.send();
}

// 2. Load JSON using XMLHttpRequest
function loadFavorites() {
    const mobileContainer = document.getElementById('favorites-mobile');
    const desktopContainer = document.getElementById('favorites-desktop');
    const countSpans = document.querySelectorAll('.indicator-item'); // Select all count badges

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'public/data/favorites.json', true);
    xhr.responseType = 'json'; // Automatically parse JSON

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const favorites = xhr.response; // Already parsed
            if (!favorites || !Array.isArray(favorites.items)) {
                 console.error("Invalid JSON structure received");
                 if(mobileContainer) mobileContainer.innerHTML = '<span class="text-sm text-error">Error loading favorites.</span>';
                 if(desktopContainer) desktopContainer.innerHTML = '<span class="text-sm text-error">Error loading favorites.</span>';
                 countSpans.forEach(span => span.textContent = '!');
                 return;
            }

            let totalItems = favorites.items.length;
            let totalPrice = favorites.items.reduce((sum, item) => sum + item.price, 0);
            let itemsHtml = '';

            if (totalItems > 0) {
                favorites.items.forEach(item => {
                    itemsHtml += `<div class="text-sm">${item.name} - $${item.price.toLocaleString()}</div>`;
                });
            } else {
                itemsHtml = '<span class="text-sm">Your favorites list is empty.</span>';
            }

            const fullHtml = `
                ${itemsHtml}
                <div class="divider my-1"></div>
                <span class="font-bold text-info text-sm">Subtotal: $${totalPrice.toLocaleString()}</span>
            `;

            if (mobileContainer) mobileContainer.innerHTML = fullHtml;
            if (desktopContainer) desktopContainer.innerHTML = fullHtml;
            countSpans.forEach(span => span.textContent = totalItems); // Update all count badges

        } else {
            console.error('Error loading favorites:', xhr.statusText);
            if(mobileContainer) mobileContainer.innerHTML = '<span class="text-sm text-error">Error loading favorites.</span>';
            if(desktopContainer) desktopContainer.innerHTML = '<span class="text-sm text-error">Error loading favorites.</span>';
            countSpans.forEach(span => span.textContent = '!');
        }
    };
    xhr.onerror = function() {
        console.error('Network error loading favorites.');
        if(mobileContainer) mobileContainer.innerHTML = '<span class="text-sm text-error">Network error.</span>';
        if(desktopContainer) desktopContainer.innerHTML = '<span class="text-sm text-error">Network error.</span>';
        countSpans.forEach(span => span.textContent = '!');
    };
    xhr.send();
}


// 3. Load HTML using XMLHttpRequest (for popular searches)
function loadPopularSearches() {
    const container = document.getElementById('search-suggestions');
    if (!container) return;

    // Avoid reloading if already loaded or currently loading
    if (container.dataset.loading === 'true' || container.dataset.loaded === 'true') {
        container.classList.remove('hidden'); // Ensure it's visible
        return;
    }

    container.dataset.loading = 'true'; // Mark as loading
    container.innerHTML = '<div class="p-4 text-center"><span class="loading loading-dots loading-md"></span></div>'; // Show loading indicator
    container.classList.remove('hidden');


    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'public/data/popular-searches.html', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            container.innerHTML = xhr.responseText;
            container.dataset.loaded = 'true'; // Mark as loaded
        } else {
            console.error('Error loading popular searches:', xhr.statusText);
            container.innerHTML = '<div class="p-2 text-error">Error loading suggestions.</div>';
            container.dataset.loaded = 'false'; // Mark as not loaded
        }
        delete container.dataset.loading; // Remove loading flag
    };
    xhr.onerror = function() {
        console.error('Network error loading popular searches.');
        container.innerHTML = '<div class="p-2 text-error">Network error loading suggestions.</div>';
        delete container.dataset.loading; // Remove loading flag
        container.dataset.loaded = 'false'; // Mark as not loaded
    };
    xhr.send();

    // Add click away listener if not already present
    if (!container.hasAttribute('hx-on:click-away')) {
         container.setAttribute('hx-on:click-away', "$(this).addClass('hidden');");
         // If using vanilla JS for click away:
         document.addEventListener('click', function(event) {
            if (!container.contains(event.target) && event.target !== document.querySelector('input[name="search"]')) {
                container.classList.add('hidden');
            }
         }, { once: true }); // Use once to auto-remove listener after first click away
    }
}

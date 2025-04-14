<?php
// No need for a separate layout inclusion since index.php already handles that
?>
<!-- Search cars section -->
<div class="w-full bg-white min-h-screen flex flex-col">
    <!-- Main content layout with drawer -->
    <div class="drawer drawer-end xl:drawer-open flex-grow flex h-screen overflow-hidden">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        
        <!-- Main content area -->
        <div class="drawer-content flex flex-col h-full overflow-y-auto">
            <!-- Search bar at the top -->
            <div class="form-control w-[90%] mx-auto sticky top-0 bg-white z-40 pt-6 pb-4">
                <div class="flex w-full relative">
                    <div class="relative flex-grow">
                    <input 
                        type="text"
                        name="search" 
                        placeholder="Search Make, Model or Keyword"
                        class="input input-bordered text-black placeholder:text-gray-500 h-[6vh] w-full pr-10"
                        hx-post="/search"
                        hx-trigger="click, input changed delay:200ms, search"
                        hx-target="#search-suggestions"
                        hx-swap="innerHTML"
                        hx-on::after-request="if ($(this).val().trim() !== '' || event.detail.successful) {
                        $('#search-suggestions').removeClass('hidden'); 
                        } else {
                        $('#search-suggestions').addClass('hidden');
                        }"
                    >
                    </div>

                    <button class="btn btn-square h-[6vh] w-[6vh] ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    </button>
                    
                    <!-- Mobile filter toggle -->
                    <label for="my-drawer-2" class="btn btn-primary drawer-button ml-2 xl:hidden h-[6vh]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                    </label>
                </div>

                <!-- Search suggestions container -->
                <div id="search-suggestions" 
                    class="absolute z-50 w-full mt-1 bg-white shadow-lg rounded-md overflow-auto hidden max-h-[300px] top-full"
                    hx-on:click-away="$(this).addClass('hidden'); console.log('click-away triggered');"
                    data-hx-logging="true"
                    >
                </div> 
                <!-- End of search suggestions container -->
            </div>
            <!-- End search bar -->


            <!--Scrollable results area-->
            <div class="flex-grow overflow-hidden flex flex-col">
                <!-- Inventory results section -->
                <div class="w-[90%] mx-auto mt-2 mb-16 overflow-y-auto pb-8" id="inventory-results">
                    <!-- Location and sort buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center w-full mb-7 gap-3">
                        <button class="btn btn-ghost w-full sm:w-auto justify-start" id="location-button" 
                        hx-get="api/location" 
                        hx-trigger="load" 
                        hx-target="#location-name"
                        hx-indicator=".loading-indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <span id="location-name">Update location</span>
                            <!-- Loading indicator -->
                            <div class="htmx-indicator loading-indicator col-span-full flex justify-center px-2">
                                <span class="loading loading-spinner loading-lg"></span>
                            </div>
                        </button>
                        <button class="btn btn-ghost w-full sm:w-auto justify-start sm:justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M12 17.25h8.25" />
                            </svg>
                            Sort by Suggested
                        </button>
                    </div>
                    <!-- End of location and sort buttons -->
                    <!-- Empty state message -->
                    <div class="hidden text-center py-10 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <p class="text-xl font-medium">No cars found</p>
                        <p class="mt-2">Try adjusting your search or filters to find what you're looking for.</p>
                    </div>
                    <!-- End of empty state message -->

                    <!-- Inventory cards -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" 
                        id="vehicle-cards"
                        hx-get="api/load-vehicles" 
                        hx-trigger="load"
                        hx-indicator=".loading-indicator">
                        <!-- Loading indicator -->
                        <div class="loading-indicator col-span-full flex justify-center py-8">
                            <span class="loading loading-spinner loading-lg"></span>
                        </div>
                    </div>
                    <!-- End of inventory cards -->
                </div>
                <!-- End of inventory results -->
            </div>
            <!-- End of scrollable results area -->
        </div>
        <!-- End of main content area -->
    
        <!-- Sidebar area -->
        <div class="drawer-side z-50 left-0">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay z-0"></label>
            <div class="bg-base-100 text-base-content min-h-full min-w-80 p-6 px-2">
            <!-- Scrollable content area -->
            <div class="h-full overflow-y-auto p-4 pb-20">
                <!-- Header with title and close button -->
                <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Filter Options</h3>
                <label for="my-drawer-2" class="btn btn-sm btn-circle lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </label>
                </div>
                <!-- End of header -->
            
                <!-- Active filters summary - only shown when filters are applied -->
                <div id="active-filters" class="collapse collapse-arrow join-item border-base-300 border mb-3 hidden" hx-swap-oob="true">
                <input type="checkbox" class="peer" checked /> 
                <div class="collapse-title font-medium flex items-center">
                    <span>Filters</span>
                    <span class="badge badge-primary ml-2">0</span>
                </div>
                <div class="collapse-content">
                    <div class="flex flex-wrap gap-2" id="active-filter-tags">
                    <!-- Filter tags will be inserted here dynamically -->
                    </div>
                </div>
                </div>
                
                <!-- Filter options -->
                <div class="join join-vertical bg-base-100">
                <!-- Payment & Price -->
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="checkbox" class="peer" /> 
                    <div class="collapse-title font-medium">Payment & Price</div>
                    <div class="collapse-content">
                    <!-- Price Range -->
                    <div class="mb-3">
                        <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Price Range</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="relative w-full">
                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-500 z-50">$</span>
                            <input 
                                type="number" 
                                min="0" 
                                placeholder="Min" 
                                class="input input-bordered w-full input-sm pl-6 p-5" 
                                hx-trigger="change, blur" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "priceMin", "value": "~this.value"}' />
                            </div>
                            <span class="text-gray-500">-</span>
                            <div class="relative w-full">
                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-500 z-50">$</span>
                            <input 
                                type="number" 
                                min="0" 
                                placeholder="Max" 
                                class="input input-bordered w-full input-sm pl-6 p-5" 
                                hx-trigger="change, blur" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "priceMax", "value": "~this.value"}' />
                            </div>
                        </div>
                        </label>
                    </div>
                    
                    <!-- Monthly Payment -->
                    <div class="mb-3">
                        <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Max Monthly Payment</span>
                        </div>
                        <input type="number" placeholder="Max monthly payment" class="input input-bordered w-full input-sm p-5" 
                                hx-trigger="change" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "monthly", "value": "~this.value"}' />
                        </label>
                    </div>
                    
                    <!-- Down Payment -->
                    <div class="mb-2">
                        <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Cash Down Payment</span>
                        </div>
                        <input type="number" placeholder="Down payment amount" class="input input-bordered w-full input-sm p-5" 
                                hx-trigger="change" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "down", "value": "~this.value"}' />
                        </label>
                    </div>
                    </div>
                </div>

                <!-- Make & Model -->
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="checkbox" class="peer" /> 
                    <div class="collapse-title font-medium">Make & Model</div>
                    <div class="collapse-content">
                    <div class="space-y-2" id="car-makes-container"
                        hx-get="api/load-car-makes" 
                        hx-trigger="load"
                        hx-swap="innerHTML">
                        <!-- Car makes will be loaded here -->
                        <div class="loading flex justify-center py-4">
                        <span class="loading loading-spinner loading-md"></span>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Body Type -->
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="checkbox" class="peer" /> 
                    <div class="collapse-title font-medium">Body Type</div>
                    <div class="collapse-content">
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "body", "value": "Sedan", "checked": "~this.checked"}' />
                        <span class="text-sm">Sedan</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "body", "value": "SUV", "checked": "~this.checked"}' />
                        <span class="text-sm">SUV</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "body", "value": "Truck", "checked": "~this.checked"}' />
                        <span class="text-sm">Truck</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "body", "value": "Coupe", "checked": "~this.checked"}' />
                        <span class="text-sm">Coupe</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "body", "value": "Convertible", "checked": "~this.checked"}' />
                        <span class="text-sm">Convertible</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "body", "value": "Wagon", "checked": "~this.checked"}' />
                        <span class="text-sm">Wagon</span>
                        </label>
                    </div>
                    </div>
                </div>

                <!-- Year & Mileage -->
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="checkbox" class="peer" /> 
                    <div class="collapse-title font-medium">Year & Mileage</div>
                    <div class="collapse-content">
                    <!-- Year Range -->
                    <div class="mb-3">
                        <div class="flex gap-2">
                        <select class="select select-bordered select-sm w-full flex items-center justify-center h-10" 
                                hx-trigger="change" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "yearMin", "value": "~this.value"}'>
                            <option disabled selected>Min Year</option>
                            <option value="2010">2010</option>
                            <option value="2015">2015</option>
                            <option value="2020">2020</option>
                        </select>
                        <select class="select select-bordered select-sm w-full flex items-center justify-center h-10" 
                                hx-trigger="change" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "yearMax", "value": "~this.value"}'>
                            <option disabled selected>Max Year</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                        </select>
                        </div>
                    </div>

                    <!-- Mileage Range -->
                    <div class="mb-2">
                        <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Max Mileage</span>
                        </div>
                        <select class="select select-bordered select-sm w-full flex items-center justify-center h-10" 
                                hx-trigger="change" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "mileage", "value": "~this.value"}'>
                            <option disabled selected>Select mileage</option>
                            <option value="10000">Under 10,000 miles</option>
                            <option value="30000">Under 30,000 miles</option>
                            <option value="50000">Under 50,000 miles</option>
                            <option value="100000">Under 100,000 miles</option>
                        </select>
                        </label>
                    </div>
                    </div>
                </div>

                <!-- Fuel Type -->
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="checkbox" class="peer" /> 
                    <div class="collapse-title font-medium">Fuel Type</div>
                    <div class="collapse-content">
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "fuel", "value": "Gasoline", "checked": "~this.checked"}' />
                        <span class="text-sm">Gasoline</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "fuel", "value": "Diesel", "checked": "~this.checked"}' />
                        <span class="text-sm">Diesel</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "fuel", "value": "Hybrid", "checked": "~this.checked"}' />
                        <span class="text-sm">Hybrid</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "fuel", "value": "Electric", "checked": "~this.checked"}' />
                        <span class="text-sm">Electric</span>
                        </label>
                    </div>
                    </div>
                </div>

                <!-- Color -->
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="checkbox" class="peer" /> 
                    <div class="collapse-title font-medium">Color</div>
                    <div class="collapse-content">
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "color", "value": "Black", "checked": "~this.checked"}' />
                        <span class="text-sm">Black</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "color", "value": "White", "checked": "~this.checked"}' />
                        <span class="text-sm">White</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "color", "value": "Silver", "checked": "~this.checked"}' />
                        <span class="text-sm">Silver</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "color", "value": "Red", "checked": "~this.checked"}' />
                        <span class="text-sm">Red</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "color", "value": "Blue", "checked": "~this.checked"}' />
                        <span class="text-sm">Blue</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "color", "value": "Grey", "checked": "~this.checked"}' />
                        <span class="text-sm">Grey</span>
                        </label>
                    </div>
                    </div>
                </div>

                <!-- Seating Capacity -->
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="checkbox" class="peer" /> 
                    <div class="collapse-title font-medium">Seating Capacity</div>
                    <div class="collapse-content">
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "seats", "value": "2", "checked": "~this.checked"}' />
                        <span class="text-sm">2</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "seats", "value": "4", "checked": "~this.checked"}' />
                        <span class="text-sm">4</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "seats", "value": "5", "checked": "~this.checked"}' />
                        <span class="text-sm">5</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "seats", "value": "6", "checked": "~this.checked"}' />
                        <span class="text-sm">6</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "seats", "value": "7+", "checked": "~this.checked"}' />
                        <span class="text-sm">7+</span>
                        </label>
                    </div>
                    </div>
                </div>

                <!-- Drivetrains -->
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="checkbox" class="peer" /> 
                    <div class="collapse-title font-medium">Drivetrains</div>
                    <div class="collapse-content">
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "drivetrain", "value": "FWD", "checked": "~this.checked"}' />
                        <span class="text-sm">FWD</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "drivetrain", "value": "RWD", "checked": "~this.checked"}' />
                        <span class="text-sm">RWD</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "drivetrain", "value": "AWD", "checked": "~this.checked"}' />
                        <span class="text-sm">AWD</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "drivetrain", "value": "4WD", "checked": "~this.checked"}' />
                        <span class="text-sm">4WD</span>
                        </label>
                    </div>
                    </div>
                </div>

                <!-- Transmissions -->
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="checkbox" class="peer" /> 
                    <div class="collapse-title font-medium">Transmissions</div>
                    <div class="collapse-content">
                    <div class="space-y-2">
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "transmission", "value": "Automatic", "checked": "~this.checked"}' />
                        <span class="text-sm">Automatic</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "transmission", "value": "Manual", "checked": "~this.checked"}' />
                        <span class="text-sm">Manual</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "transmission", "value": "CVT", "checked": "~this.checked"}' />
                        <span class="text-sm">CVT</span>
                        </label>
                    </div>
                    </div>
                </div>

                <!-- Cylinders -->
                <div class="collapse collapse-arrow join-item border-base-300 border">
                    <input type="checkbox" class="peer" /> 
                    <div class="collapse-title font-medium">Cylinders</div>
                    <div class="collapse-content">
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "cylinders", "value": "3", "checked": "~this.checked"}' />
                        <span class="text-sm">3</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "cylinders", "value": "4", "checked": "~this.checked"}' />
                        <span class="text-sm">4</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "cylinders", "value": "6", "checked": "~this.checked"}' />
                        <span class="text-sm">6</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "cylinders", "value": "8", "checked": "~this.checked"}' />
                        <span class="text-sm">8</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "cylinders", "value": "10+", "checked": "~this.checked"}' />
                        <span class="text-sm">10+</span>
                        </label>
                        <label class="cursor-pointer flex items-center gap-2">
                        <input type="checkbox" class="checkbox checkbox-sm" 
                                hx-trigger="click" 
                                hx-post="/update-filter" 
                                hx-vals='{"filter": "cylinders", "value": "Electric", "checked": "~this.checked"}' />
                        <span class="text-sm">Electric</span>
                        </label>
                    </div>
                    </div>
                </div>
                </div>
                <!-- End of filter options -->
            </div>
            <!-- End of scrollable area -->
            </div>
        </div>
        <!-- End of sidebar area -->
    </div>
    <!-- End of drawer layout -->
</div>
<!-- End of search cars section -->
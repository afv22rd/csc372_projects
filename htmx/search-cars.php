<?php
// Start the session at the beginning of the file
session_start();

$title = 'Used Cars for Sale | Buy, Trade & Finance Cars in the Dominican Republic | Brawa AutoImport';
?>
<!DOCTYPE html>
<html lang="en" data-theme="caramellatte" class="bg-white">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="htmx-config" content='{"selfRequestsOnly":false}'>
    <meta name="author" content="Abel Frias">
    <meta name="email" content="afriasv@uri.edu">
    <meta name="description" content="Buy, sell, and finance vehicles online in the Dominican Republic. Discover our wide selection of cars, easy financing options, and hassle-free car imports at the best rates.">
    <meta name="keywords" content="buy car Dominican Republic, sell car Dominican Republic, finance car Dominican Republic, car imports, online car dealership">
    <title><?php echo $title; ?></title>
    <base href="/csc372_projects/htmx/" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.8/daisyui.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.8/themes.css" rel="stylesheet" />
    <link rel="stylesheet" href="public/css/index-style.css" />
  </head>
  <body hx-boost="true">
    <div class="mx-auto bg-base-100">
      <!-- Header -->
      <header>
        <nav>
          <div class="navbar bg-base-100 shadow-sm flex-col lg:flex-row p-6 gap-4">
            <!-- Mobile Nav -->
            <div class="w-full flex justify-between items-center lg:hidden">
              <div class="flex-none">
                <a href="index.php" class="py-2">
                  <img src="public/images/Brawa_logo_hor.svg" alt="Brawa Logo" class="w-auto h-10">
                </a>
              </div>
              <div class="flex-none">
                <div class="flex gap-2">
                  <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                      <div class="indicator">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                        <span class="badge badge-sm indicator-item" hx-get="api/favorites.php?content=count" hx-trigger="load"></span>
                      </div>
                    </div>
                    <div tabindex="0" class="card card-compact dropdown-content bg-base-100 z-50 mt-3 w-52 shadow">
                      <div class="card-body" hx-get="api/favorites.php" hx-trigger="load" hx-target="#favorites-mobile">
                        <div id="favorites-mobile"></div>
                        <div class="card-actions">
                          <button class="btn btn-primary btn-block">View cart</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Profile dropdown (shown when signed in) -->
                  <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) { ?>
                  <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                      <div class="w-10 rounded-full bg-primary text-white flex items-center justify-center">
                        <span class="text-lg font-bold"><?php echo substr(htmlspecialchars($_SESSION['user_name']), 0, 1); ?></span>
                      </div>
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-50 mt-3 w-52 p-2 shadow">
                      <li><a class="justify-between">Profile<span class="badge">New</span></a></li>
                      <li><a>Settings</a></li>
                      <li><a class="text-error" 
                            onclick="document.getElementById('delete-account-modal').showModal()"
                            hx-get="api/auth_forms.php?form=delete_account" 
                            hx-target="#delete-account-container" 
                            hx-trigger="click">Delete Account</a></li>
                      <li><a href="api/logout.php" hx-boost="false">Logout</a></li>
                    </ul>
                  </div>
                  <?php } else { ?>
                  <!-- Login button for unauthenticated users (mobile) -->
                  <button class="btn btn-primary ml-2 lg:hidden" 
                    hx-get="api/auth_forms.php?form=login" 
                    hx-target="#auth-form-container" 
                    hx-trigger="click" 
                    onclick="document.getElementById('login-modal').showModal()">Login</button>
                  <?php } ?>
                </div>
              </div>
            </div>

            <!-- Desktop Nav -->
            <div class="hidden lg:flex flex-none">
              <a href="index.php" class="py-2">
                <img src="public/images/Brawa_logo_hor.svg" alt="Brawa Logo" class="w-auto h-10">
              </a>
            </div>

            <div class="hidden lg:flex navbar-start">
              <ul class="menu menu-horizontal px-1 flex-1 gap-2">
                <li><a href="search-cars.php" class="font-semibold bg-base-300">Search Cars</a></li>
                <li><a href="sell_trade.php" class="font-semibold">Sell/Trade</a></li>
                <li><a href="" class="font-semibold">Financing</a></li>
              </ul>
            </div>

            <div class="hidden lg:flex navbar-end">
              <div class="flex gap-2">
                <div class="dropdown dropdown-end">
                  <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <div class="indicator">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                      </svg>
                      <span class="badge badge-sm indicator-item" hx-get="api/favorites.php?content=count" hx-trigger="load"></span>
                    </div>
                  </div>
                  <div tabindex="0" class="card card-compact dropdown-content bg-base-100 z-50 mt-3 w-52 shadow">
                    <div class="card-body" hx-get="api/favorites.php" hx-trigger="load" hx-target="#favorites-desktop">
                      <div id="favorites-desktop"></div>
                      <div class="card-actions">
                        <button class="btn btn-primary btn-block">View cart</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Profile dropdown (shown when signed in) -->
                <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) { ?>
                <div class="dropdown dropdown-end">
                  <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full bg-primary text-white flex items-center justify-center">
                      <span class="text-lg font-bold"><?php echo substr(htmlspecialchars($_SESSION['user_name']), 0, 1); ?></span>
                    </div>
                  </div>
                  <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-50 mt-3 w-52 p-2 shadow">
                    <li><a class="justify-between">Profile<span class="badge">New</span></a></li>
                    <li><a>Settings</a></li>
                    <li><a class="text-error" 
                          onclick="document.getElementById('delete-account-modal').showModal()"
                          hx-get="api/auth_forms.php?form=delete_account" 
                          hx-target="#delete-account-container" 
                          hx-trigger="click">Delete Account</a></li>
                    <li><a href="api/logout.php" hx-boost="false">Logout</a></li>
                  </ul>
                </div>
                <?php } else { ?>
                <!-- Login button for unauthenticated users -->
                <button class="btn btn-primary ml-2" 
                  hx-get="api/auth_forms.php?form=login" 
                  hx-target="#auth-form-container" 
                  hx-trigger="click"
                  onclick="document.getElementById('login-modal').showModal()">Login</button>
                <?php } ?>
              </div>
            </div>

            <!-- Mobile Bottom Nav -->
            <div class="w-full lg:hidden flex justify-center">
              <div class="flex gap-8 py-2 w-full justify-around">
                <a href="search-cars.php" class="text-sm text-primary font-semibold hover:text-primary p-2 rounded-lg">Search Cars</a>
                <a href="sell_trade.php" class="text-sm font-semibold hover:text-primary p-2 rounded-lg">Sell/Trade</a>
                <a href="" class="text-sm font-semibold hover:text-primary p-2 rounded-lg">Financing</a>
              </div>
            </div>
          </div>
        </nav>
      </header>

      <!-- Body Content -->
      <div class="body-content" id="body-content">
        <!-- Search cars section -->
        <div class="w-full bg-white min-h-screen flex flex-col">
            <!-- Main content area -->
            <div class="flex flex-col h-full">
                <!-- Search bar at the top -->
                <div class="form-control w-[90%] mx-auto sticky top-0 bg-white z-40 pt-6 pb-4">
                    <div class="flex w-full relative">
                        <div class="relative flex-grow">
                            <input 
                                type="text"
                                name="search" 
                                placeholder="Search Make, Model or Keyword"
                                class="input input-bordered text-black placeholder:text-gray-500 h-[6vh] w-full pr-10"
                                hx-post="api/search.php"
                                hx-trigger="click, input changed delay:200ms"
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
                            hx-get="api/location.php" 
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
                            hx-get="api/load-vehicles.php" 
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
        </div>
        <!-- End of search cars section -->
      </div>

        <!-- Footer -->
        <footer class="footer lg:footer-horizontal bg-neutral text-neutral-content p-10">
            <!-- Company information section -->
            <aside>
                <a href="" class="py-2">
                <img src="public/images/Brawa_logo_hor.jpg" alt="Brawa Logo" class="w-auto h-18 rounded-lg">
                </a>
                <p>
                Brawa AutoImport SRL
                <br />
                Copyright © 2025 Brawa | Abel Frias. All Rights Reserved.
                </p>
            </aside>
            <!-- Footer navigation sections organized by category -->
            <!-- Financing links section -->
            <nav>
                <h6 class="footer-title">Financing</h6>
                <a class="link link-hover">Get Pre-Qualified</a>
                <a class="link link-hover">Banks & Finance Companies</a>
                <a class="link link-hover">Payment Calculator</a>
            </nav>
            <!-- Sell/Trade links section -->
            <nav>
                <h6 class="footer-title">Sell/Trade</h6>
                <a class="link link-hover">Get an Offer</a>
            </nav>
            <!-- About & Support links section -->
            <nav>
                <h6 class="footer-title">About & Support</h6>
                <a class="link link-hover">About Brawa</a>
                <a class="link link-hover">Customer Reviews</a>
                <a class="link link-hover">Buying from Brawa</a>
                <a class="link link-hover">Finance with Brawa</a>
                <a class="link link-hover">Selling or Trading In</a>
            </nav>
            <!-- Social media links section -->
            <nav>
                <h6 class="footer-title">Social</h6>
                <div class="grid grid-flow-col gap-4">
                <!-- Instagram logo SVG -->
                <a>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve" width="24" height="24" class="fill-current">
                    <g>
                        <path d="M12,2.162c3.204,0,3.584,0.012,4.849,0.07c1.308,0.06,2.655,0.358,3.608,1.311c0.962,0.962,1.251,2.296,1.311,3.608   c0.058,1.265,0.07,1.645,0.07,4.849c0,3.204-0.012,3.584-0.07,4.849c-0.059,1.301-0.364,2.661-1.311,3.608   c-0.962,0.962-2.295,1.251-3.608,1.311c-1.265,0.058-1.645,0.07-4.849,0.07s-3.584-0.012-4.849-0.07   c-1.291-0.059-2.669-0.371-3.608-1.311c-0.957-0.957-1.251-2.304-1.311-3.608c-0.058-1.265-0.07-1.645-0.07-4.849   c0-3.204,0.012-3.584,0.07-4.849c0.059-1.296,0.367-2.664,1.311-3.608c0.96-0.96,2.299-1.251,3.608-1.311   C8.416,2.174,8.796,2.162,12,2.162 M12,0C8.741,0,8.332,0.014,7.052,0.072C5.197,0.157,3.355,0.673,2.014,2.014   C0.668,3.36,0.157,5.198,0.072,7.052C0.014,8.332,0,8.741,0,12c0,3.259,0.014,3.668,0.072,4.948c0.085,1.853,0.603,3.7,1.942,5.038   c1.345,1.345,3.186,1.857,5.038,1.942C8.332,23.986,8.741,24,12,24c3.259,0,3.668-0.014,4.948-0.072   c1.854-0.085,3.698-0.602,5.038-1.942c1.347-1.347,1.857-3.184,1.942-5.038C23.986,15.668,24,15.259,24,12   c0-3.259-0.014-3.668-0.072-4.948c-0.085-1.855-0.602-3.698-1.942-5.038c-1.343-1.343-3.189-1.858-5.038-1.942   C15.668,0.014,15.259,0,12,0z"/>
                        <path d="M12,5.838c-3.403,0-6.162,2.759-6.162,6.162c0,3.403,2.759,6.162,6.162,6.162s6.162-2.759,6.162-6.162   C18.162,8.597,15.403,5.838,12,5.838z M12,16c-2.209,0-4-1.791-4-4s1.791-4,4-4s4,1.791,4,4S14.209,16,12,16z"/>
                        <circle cx="18.406" cy="5.594" r="1.44"/>
                    </g>
                    </svg>
                </a>
                <!-- Facebook logo SVG -->
                <a>
                    <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    class="fill-current">
                    <path
                        d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path>
                    </svg>
                </a>
                </div>
            </nav>
        </footer>
    </div>

    <!-- Login Modal -->
    <dialog id="login-modal" class="modal">
      <div class="modal-box">
        <form method="dialog">
          <button class="bg-base-300 btn btn-md btn-circle btn-ghost absolute right-5 top-4">✕</button>
        </form>
        <div id="auth-form-container">
          <!-- Form content will be loaded by HTMX -->
        </div>
      </div>
    </dialog>

    <!-- Delete Account Modal -->
    <dialog id="delete-account-modal" class="modal">
      <div class="modal-box">
        <form method="dialog">
          <button class="bg-base-300 btn btn-md btn-circle btn-ghost absolute right-5 top-4">✕</button>
        </form>
        <div id="delete-account-container">
          <!-- Form content will be loaded by HTMX -->
        </div>
      </div>
    </dialog>

    <!-- Scripts -->
    <script src="https://unpkg.com/htmx.org@2.0.4/dist/htmx.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="public/js/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="public/js/search.js"></script>
    <script src="public/js/load-stats.js"></script>
    <script src="public/js/get-location.js"></script>
    <script src="public/js/auth.js"></script>
  </body>
</html>

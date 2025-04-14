<?php
$title = 'Brawa AutoImport SRL | Buy, Sell & Finance Cars in the Dominican Republic';
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
                  <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                      <div class="w-10 rounded-full">
                        <img alt="User avatar" src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp">
                      </div>
                    </div>
                    <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-50 mt-3 w-52 p-2 shadow">
                      <li><a class="justify-between">Profile<span class="badge">New</span></a></li>
                      <li><a>Settings</a></li>
                      <li><a>Logout</a></li>
                    </ul>
                  </div>
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
                <li><a href="search-cars.php" class="font-semibold">Search Cars</a></li>
                <li><a href="views/sell-trade" class="font-semibold">Sell/Trade</a></li>
                <li><a href="views/financing" class="font-semibold">Financing</a></li>
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

                <div class="dropdown dropdown-end">
                  <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full">
                      <img alt="User avatar" src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp">
                    </div>
                  </div>
                  <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-50 mt-3 w-52 p-2 shadow">
                    <li><a class="justify-between">Profile<span class="badge">New</span></a></li>
                    <li><a>Settings</a></li>
                    <li><a>Logout</a></li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Mobile Bottom Nav -->
            <div class="w-full lg:hidden flex justify-center">
              <div class="flex gap-8 py-2 w-full justify-around">
                <a href="/search-cars" class="text-sm font-semibold hover:text-primary p-2 rounded-lg">Search Cars</a>
                <a href="/sell-trade" class="text-sm font-semibold hover:text-primary p-2 rounded-lg">Sell/Trade</a>
                <a href="/financing" class="text-sm font-semibold hover:text-primary p-2 rounded-lg">Financing</a>
              </div>
            </div>
          </div>
        </nav>
      </header>

      <!-- Body Content -->
      <div class="body-content" id="body-content">
        <!-- Main hero section -->
        <div>
            <div class="hero min-h-[40vh] md:min-h-[50vh] bg-cover bg-center" style="background-image: url(public/images/hero_placeholder.jpg);">
                <div class="hero-overlay bg-opacity-60"></div>
                <div class="hero-content text-neutral-content text-center">
                    <div class="w-full">
                    <!-- Heading -->
                    <h1 class="mb-5 text-3xl md:text-4xl lg:text-5xl font-bold w-full">Your Next Car is Just a Click Away!</h1>
                    <h3 class="mb-5 text-xl font-bold">
                        Get the best deals and financing options on all models.
                    </h3>

                    <!-- Search bar -->
                    <div class="form-control w-full relative">
                        <div class="flex w-full relative">
                            <div class="relative flex-grow">
                                <input
                                    type="text"
                                    name="search"
                                    placeholder="Search Make, Model or Keyword"
                                    class="input input-bordered text-black placeholder:text-gray-500 h-[6vh] w-full pr-10"
                                    hx-get="api/search.php"
                                    hx-trigger="focus delay:100ms, input changed delay:250ms"
                                    hx-target="#search-suggestions"
                                    hx-swap="innerHTML"
                                    hx-indicator="#search-spinner"
                                    autocomplete="off">
                                <span class="htmx-indicator absolute right-2 top-1/2 transform -translate-y-1/2" id="search-spinner">
                                    <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </div>

                            <button type="button" class="btn btn-square h-[6vh] w-[6vh] ml-2" onclick="document.querySelector('input[name=search]').dispatchEvent(new Event('input'))">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>

                        <div id="search-suggestions"
                            class="absolute z-10 w-full mt-1 bg-white shadow-lg rounded-md overflow-auto hidden max-h-[300px] top-full">
                            </div>
                        </div>
                    <!-- End search bar -->
                </div>
            </div>
        </div>
        <!-- End main hero -->

        <!-- Stats section -->
        <div class="container mx-auto py-10 p-4 mt-4 max-w-[1700px]">
            <div id="stats-container" class="stats stats-vertical lg:stats-horizontal shadow w-full bg-base-200">
            <!-- Add HTML using AJAX and jQuery -->
            </div>
        </div>
        <!--  End of stats section -->

        <!-- Popular vehicle styles -->
        <div id="popular-styles-container" class="container mx-auto p-4">
            <h2 class="text-3xl font-bold text-left pb-8">Popular Vehicle Styles</h2>
            <div id="popular-styles-grid" class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6" hx-get="api/popular-styles.php" hx-trigger="load" hx-swap="innerHTML">
            <!--Load from XML-->
            </div>
        </div>
        <!-- End of popular vehicle styles -->

        <!-- 2 Cards section (finance and sell/trade) -->
        <div class="container mx-auto p-4 py-10 max-w-[1700px]">
            <div class="flex flex-col items-center justify-center gap-10 lg:gap-24 md:w-full md:flex-row md:items-stretch">
            <!-- Finance Card -->
            <div class="card bg-base-200 w-full shadow-sm flex flex-col">
                <figure class="p-8 pb-0 flex-shrink-0">
                <img src="public/images/finance.jpeg" alt="Finance a car" class="w-full h-[220px] sm:h-[250px] md:h-[280px] object-cover rounded-xl">
                </figure>
                <div class="card-body flex flex-col flex-grow">
                <h2 class="card-title text-3xl">Finance with Brawa</h2>
                <p class="flex-grow">Get flexible financing through trusted banks and finance companies to drive your dream car today.</p>
                <div class="card-actions">
                    <button class="btn btn-primary mt-2 w-full">Get Pre-Qualified</button>
                </div>
                </div>
            </div>
            <!-- End finance card -->
            
            <!-- Sell/Trade Card -->
            <div class="card bg-base-200 w-full shadow-sm flex flex-col">
                <figure class="p-8 pb-0 flex-shrink-0">
                <img src="public/images/sell_trade.jpg" alt="Sell/Trade" class="w-full h-[220px] sm:h-[250px] md:h-[280px] object-cover rounded-xl">
                </figure>
                <div class="card-body flex flex-col flex-grow">
                <h2 class="card-title text-3xl">Sell or Trade Your Car</h2>
                <p class="flex-grow">Get a real offer in just a couple minutes.</p>
                <div class="card-actions">
                    <button class="btn btn-primary mt-2 w-full">Get My Offer</button>
                </div>
                </div>
            </div>
            <!-- End sell/trade card -->
            </div>
        </div>
        <!-- End of 2 Cards section -->

        <!-- Affordable cards section -->
        <div class="w-full bg-base-300">
            <div class="container mx-auto p-4 mt-6 py-10">
            <h2 class="text-3xl font-bold text-left pb-8">Affordable Cars Available</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Under 10K button -->
                <button class="btn bg-base-100 rounded-lg w-full h-40 flex flex-col items-center justify-center gap-2 p-4">
                <span class="text-lg font-medium">Cars Under $10,000</span>
                <svg id="svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400"><g id="svgg"><path id="path0" d="M175.391 12.205 C 78.183 17.537,5.628 105.450,19.155 201.510 C 31.294 287.707,110.169 351.902,195.990 345.433 C 206.693 344.626,226.227 341.407,228.338 340.103 C 228.747 339.850,221.791 332.345,212.879 323.426 L 196.677 307.210 188.768 307.609 C 89.606 312.614,22.325 199.641,73.556 114.153 C 138.329 6.068,301.443 43.715,312.651 169.336 L 313.575 179.688 332.683 179.688 L 351.792 179.688 351.259 170.778 C 345.748 78.600,266.564 7.203,175.391 12.205 M175.119 79.934 C 174.623 80.532,174.175 85.349,174.047 91.451 L 173.828 101.940 167.438 104.006 C 141.412 112.420,128.611 136.033,137.422 159.375 C 142.114 171.807,154.060 180.809,178.906 190.636 C 204.667 200.825,211.470 207.890,206.355 219.147 C 199.607 234.002,174.459 233.817,154.688 218.766 C 147.344 213.176,146.522 213.338,139.885 221.680 C 132.324 231.183,131.424 232.953,132.822 235.566 C 135.791 241.112,154.632 251.863,166.180 254.599 C 174.788 256.639,174.219 255.776,174.219 266.779 C 174.219 279.964,173.221 278.906,185.655 278.906 C 197.971 278.906,196.888 279.997,197.116 267.368 C 197.315 256.384,196.771 257.298,204.692 254.641 C 228.315 246.714,241.025 225.086,235.609 202.028 C 231.892 186.204,221.985 178.234,190.407 165.660 C 168.317 156.865,161.732 150.595,164.218 140.724 C 168.142 125.137,193.320 123.031,213.234 136.624 C 218.634 140.310,220.413 140.921,222.051 139.652 C 223.896 138.223,232.801 121.782,232.807 119.793 C 232.823 114.763,215.950 105.358,202.344 102.813 L 197.266 101.863 197.047 91.413 C 196.771 78.250,197.374 78.906,185.547 78.906 C 178.585 78.906,175.740 79.187,175.119 79.934 M258.051 197.198 L 255.859 199.083 255.641 247.752 C 255.474 285.008,255.199 296.563,254.469 297.027 C 253.945 297.360,248.049 297.638,241.367 297.644 C 220.161 297.665,218.644 294.501,264.045 344.935 C 277.590 359.981,291.647 375.714,295.284 379.896 C 304.348 390.320,304.360 390.321,311.761 381.776 C 314.431 378.694,329.005 362.637,344.148 346.094 C 391.636 294.215,390.032 297.656,366.722 297.656 L 353.086 297.656 352.324 295.652 C 351.860 294.432,351.563 275.507,351.563 247.252 C 351.562 214.912,351.300 200.278,350.695 198.951 C 349.060 195.362,348.418 195.313,303.132 195.313 L 260.243 195.313 258.051 197.198 " fill="currentColor" fill-rule="evenodd"></path></g></svg>
                </button>
                <!-- End cars under 10k-->

                <!-- Great deals button -->
                <button class="btn bg-base-100 rounded-lg w-full h-40 flex flex-col items-center justify-center gap-2 p-4">
                <span class="text-lg font-medium">Great Deals</span>
                <svg id="svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400" version="1.1"><g id="svgg"><path id="path0" d="M217.188 8.497 C 185.605 13.079,159.063 17.237,158.203 17.737 C 153.250 20.618,7.192 168.589,4.902 173.047 C -2.387 187.230,-1.652 205.360,6.723 217.969 C 10.124 223.089,132.121 345.577,138.048 349.823 L 142.113 352.734 142.150 358.707 C 142.261 376.409,154.101 392.169,171.830 398.213 L 176.953 399.960 271.094 399.960 L 365.234 399.960 370.358 398.213 C 384.099 393.528,394.285 383.176,398.466 369.644 C 399.959 364.811,400.000 361.804,400.000 256.185 L 400.000 147.690 378.748 119.353 C 367.059 103.767,357.528 90.488,357.567 89.844 C 357.607 89.199,357.904 85.455,358.228 81.523 C 358.976 72.441,359.157 72.739,343.424 57.067 L 331.303 44.994 344.499 31.606 C 355.450 20.496,357.788 17.723,358.241 15.309 C 359.883 6.554,355.181 0.000,347.257 0.000 C 341.179 -0.000,340.249 0.653,326.181 14.791 L 313.690 27.344 301.181 14.951 C 294.301 8.135,287.803 1.983,286.740 1.279 C 283.499 -0.867,277.269 -0.221,217.188 8.497 M286.146 34.974 L 296.098 44.947 288.312 52.704 L 280.527 60.460 275.565 55.696 C 263.924 44.517,248.449 50.463,252.294 64.637 C 253.486 69.033,289.845 105.281,294.002 106.219 C 308.589 109.509,314.070 94.312,302.519 82.607 L 298.076 78.105 305.882 70.299 L 313.688 62.493 323.784 72.612 L 333.880 82.730 326.380 135.244 L 318.879 187.758 247.135 259.583 C 170.670 336.134,172.664 334.327,164.623 334.358 C 156.499 334.389,158.551 336.217,90.739 268.524 C 22.708 200.612,24.253 202.341,24.253 194.141 C 24.253 185.262,21.403 188.441,98.671 111.125 L 169.923 39.827 220.704 32.496 C 248.633 28.464,272.544 25.128,273.839 25.083 C 275.859 25.012,277.610 26.419,286.146 34.974 M198.047 89.031 C 193.236 91.034,193.288 90.896,164.009 178.568 C 133.458 270.048,134.126 267.630,137.943 272.895 C 142.014 278.510,150.635 279.482,155.838 274.914 C 159.235 271.931,214.844 106.764,214.844 99.658 C 214.844 91.279,206.034 85.705,198.047 89.031 M376.387 259.194 L 376.172 361.328 373.848 365.524 C 371.504 369.756,369.074 372.038,364.063 374.712 C 361.438 376.113,357.707 376.172,271.094 376.172 L 180.859 376.172 176.953 374.101 C 172.381 371.677,170.325 369.589,168.057 365.070 C 165.401 359.777,165.729 358.888,170.547 358.322 C 176.076 357.672,182.972 355.232,187.828 352.208 C 192.726 349.157,339.360 203.017,340.923 199.628 C 341.522 198.329,344.171 181.973,346.808 163.281 C 349.446 144.590,351.801 128.315,352.043 127.116 C 352.448 125.106,353.429 126.196,364.542 140.997 L 376.602 157.059 376.387 259.194 M98.252 149.124 C 68.364 159.005,65.213 200.621,93.301 214.500 C 118.348 226.876,145.738 208.747,144.396 180.681 C 143.309 157.935,119.940 141.955,98.252 149.124 M231.250 149.127 C 192.987 161.474,201.294 217.982,241.406 218.214 C 273.188 218.397,289.064 181.645,267.269 158.344 C 258.514 148.985,243.554 145.156,231.250 149.127 M114.656 173.433 C 120.112 176.760,121.875 183.616,118.625 188.874 C 111.416 200.538,93.089 190.491,99.360 178.313 C 102.451 172.310,109.254 170.140,114.656 173.433 M247.736 173.648 C 252.922 177.113,254.538 183.630,251.367 188.289 C 246.145 195.963,238.058 196.246,232.608 188.947 C 227.549 182.170,233.090 171.875,241.797 171.875 C 243.911 171.875,246.027 172.507,247.736 173.648 " fill="currentColor" fill-rule="evenodd"></path></g></svg>
                </button>
                <!-- End great deals -->

                <!-- Gas efficient button -->
                <button class="btn bg-base-100 rounded-lg w-full h-40 flex flex-col items-center justify-center gap-2 p-4">
                <span class="text-lg font-medium">Gas Efficient</span>
                <svg id="svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400" viewBox="0, 0, 400,400" version="1.1"><g id="svgg"><path id="path0" d="M57.163 2.034 C 53.080 4.069,50.683 6.586,47.944 11.719 C 46.534 14.361,46.477 20.067,46.280 180.273 L 46.076 346.094 38.468 346.111 C 21.854 346.148,19.522 349.538,19.548 373.608 C 19.568 391.477,20.204 394.238,25.104 397.734 L 27.734 399.609 172.488 399.811 C 331.055 400.032,319.301 400.364,323.785 395.529 C 326.816 392.259,327.517 387.196,327.220 370.703 C 326.835 349.315,324.890 346.729,308.789 346.209 L 300.000 345.925 300.000 276.478 L 300.000 207.031 302.369 207.031 C 307.688 207.031,314.623 211.470,317.390 216.647 C 319.119 219.881,319.145 220.401,319.531 258.594 L 319.922 297.266 322.346 302.381 C 333.757 326.462,366.243 326.462,377.654 302.381 L 380.078 297.266 380.289 199.219 C 380.533 86.092,381.065 94.591,373.225 86.387 C 367.478 80.373,323.696 53.125,319.780 53.125 C 316.037 53.125,313.277 56.019,313.293 59.925 C 313.310 64.080,313.643 64.430,323.562 70.679 L 332.422 76.262 332.813 97.701 C 333.247 121.560,333.417 122.362,339.189 127.885 C 343.877 132.371,347.722 133.557,357.631 133.577 L 366.435 133.594 366.225 213.867 L 366.016 294.141 364.265 297.416 C 358.140 308.874,341.860 308.874,335.735 297.416 C 334.006 294.181,333.980 293.662,333.594 255.469 L 333.203 216.797 330.708 211.531 C 325.547 200.635,314.135 193.013,302.930 192.978 L 300.000 192.969 300.000 103.881 C 300.000 5.404,300.372 12.391,294.802 6.214 C 288.716 -0.536,299.925 0.037,173.396 0.017 L 61.244 -0.000 57.163 2.034 M284.129 15.761 L 285.938 17.460 285.938 181.777 L 285.938 346.094 173.047 346.094 L 60.156 346.094 60.156 182.774 C 60.156 9.717,60.034 15.964,63.460 14.585 C 64.006 14.366,113.473 14.158,173.387 14.124 L 282.321 14.063 284.129 15.761 M80.078 28.016 C 72.652 32.603,72.730 32.061,72.695 79.688 C 72.663 123.080,72.787 124.741,76.361 128.988 C 80.446 133.844,75.181 133.594,173.444 133.594 C 274.333 133.594,266.889 134.024,271.223 127.947 L 273.047 125.391 273.260 80.416 L 273.474 35.441 271.679 32.778 C 270.692 31.314,268.698 29.316,267.249 28.339 L 264.613 26.563 173.517 26.565 C 86.196 26.568,82.325 26.628,80.078 28.016 M259.375 80.078 L 259.375 119.531 173.047 119.531 L 86.719 119.531 86.719 80.078 L 86.719 40.625 173.047 40.625 L 259.375 40.625 259.375 80.078 M362.312 95.606 C 365.609 98.676,366.397 101.741,366.402 111.523 L 366.406 119.531 358.924 119.531 C 351.696 119.531,351.365 119.454,349.159 117.248 L 346.875 114.964 346.875 100.077 L 346.875 85.189 353.320 89.211 C 356.865 91.424,360.912 94.301,362.312 95.606 M223.047 180.827 C 200.920 184.315,181.591 194.629,165.593 211.487 L 158.139 219.341 155.246 217.020 C 139.388 204.298,115.210 197.373,95.667 199.954 C 86.479 201.167,85.646 201.790,83.307 209.193 C 70.503 249.727,91.968 295.117,128.516 304.788 C 140.536 307.969,195.889 307.607,209.135 304.260 C 243.045 295.695,266.674 260.762,266.710 219.141 C 266.725 202.044,263.148 185.997,258.594 182.729 C 254.587 179.855,235.648 178.841,223.047 180.827 M248.922 194.755 C 253.349 199.182,253.949 225.838,249.962 240.997 C 239.706 279.998,213.426 296.314,167.921 291.934 C 159.095 291.085,159.485 291.530,158.521 281.203 C 154.765 240.980,174.328 210.884,212.529 198.117 C 223.962 194.297,246.391 192.224,248.922 194.755 M225.664 214.618 C 202.758 233.029,172.693 269.594,174.509 276.833 C 176.076 283.077,183.774 284.057,187.713 278.516 C 204.218 255.297,217.917 239.560,230.624 229.223 C 243.008 219.148,244.798 215.821,240.270 211.293 C 236.718 207.741,233.233 208.534,225.664 214.618 M112.981 214.125 C 125.119 215.813,139.007 221.950,147.501 229.380 L 151.382 232.776 149.573 237.759 C 148.578 240.500,147.318 244.902,146.773 247.543 C 145.551 253.458,146.054 253.531,139.043 246.424 C 122.797 229.954,116.434 226.414,111.659 231.190 C 107.818 235.031,108.412 237.795,114.189 242.961 C 136.463 262.879,143.355 271.085,144.049 278.516 C 144.330 281.523,144.769 286.044,145.023 288.562 L 145.486 293.139 141.298 292.606 C 117.836 289.620,102.467 275.272,96.078 250.391 C 92.590 236.808,93.704 214.645,97.915 213.824 C 101.230 213.178,107.005 213.293,112.981 214.125 M313.281 373.047 L 313.281 385.938 173.438 385.938 L 33.594 385.938 33.594 373.047 L 33.594 360.156 173.438 360.156 L 313.281 360.156 313.281 373.047 " fill="currentColor" fill-rule="evenodd"></path></g></svg>
                </button>
                <!-- End gas efficient -->
            </div>
            </div>
        </div>
        <!-- End of affordables section -->

        <!-- Contact us and location section -->
        <div class="w-full bg-base-100">
            <div class="container mx-auto p-4 my-6 py-10">
            <h2 class="text-3xl font-bold text-left pb-8">Contact Us</h2>
            <div class="flex flex-col-reverse items-center justify-between gap-10 lg:gap-24 md:flex-row md:items-start">
                <!-- Map -->
                <div class="w-full md:w-3/5">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3783.7932728912733!2d-69.81940639999999!3d18.493020899999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8eaf87e791a264f5%3A0x99052b78f5bec6!2sBRAWA%20AUTOIMPORT%20SRL!5e0!3m2!1sen!2sus!4v1740862164015!5m2!1sen!2sus" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade" 
                    class="rounded-lg shadow-sm w-full">
                </iframe>
                </div>
                <!-- End map -->
                
                <!-- Contact info and hours -->
                <div class="w-full md:w-2/5 space-y-8 bg-base-200 p-4 rounded-lg shadow-sm">
                <!-- Hours of operation -->
                <div>
                    <h3 class="text-xl font-semibold mb-4 text-left">HOURS OF OPERATION</h3>
                    <div class="overflow-x-auto">
                    <table class="table w-full">
                        <tbody>
                        <tr>
                            <td class="text-left font-medium">Monday - Friday</td>
                            <td class="text-right">9 AM - 6 PM</td>
                        </tr>
                        <tr>
                            <td class="text-left font-medium">Saturday</td>
                            <td class="text-right">9 AM - 2 PM</td>
                        </tr>
                        <tr>
                            <td class="text-left font-medium">Sunday</td>
                            <td class="text-right">CLOSED</td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <!-- End of hours of operation -->
                
                <!-- Contact information -->
                <div>
                    <h3 class="text-xl font-semibold mb-4 text-left">CONTACT</h3>
                    <div class="overflow-x-auto">
                    <table class="table w-full">
                        <tbody>
                        <tr>
                            <td class="text-left font-medium">Phone Number</td>
                            <td class="text-right"><a href="tel:5555555555">555-555-5555</a></td>
                        </tr>
                        <tr>
                            <td class="text-left font-medium">Email</td>
                            <td class="text-right"><a href="mailto:afriasv@uri.edu">afriasv@uri.edu</a></td>
                        </tr>
                        <tr>
                            <td class="text-left font-medium">Address</td>
                            <td class="text-right"><a href="https://maps.app.goo.gl/qnGkghtU2V3UMwfXA" target="_blank">55 Test St, Santo Domingo, DR</a></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                            <button class="btn btn-primary mt-2 w-full" data-toggle="modal" data-target="#appointment-modal">Make an Appointment
                            </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <!-- End of contact information -->
                </div>
                <!-- End of contact info and hours -->
            </div>
            </div>
        </div>
        <!-- End of contact us and location section -->
      </div>

      <!-- Appointment Modal -->
      <div class="modal" id="appointment-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog w-11/12 max-w-2xl mx-auto">
          <div class="modal-content bg-white rounded-lg shadow-xl">
            <div class="modal-header p-6 border-b">
              <h3 class="modal-title text-xl font-bold">Schedule an Appointment</h3>
            </div>
            <div class="modal-body p-6">
              <form id="appointment-form" action="api/appointments.php" method="POST" class="space-y-4">
                <!-- Name inputs -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="form-control">
                    <input type="text" name="firstName" placeholder="First Name" class="input input-bordered" required>
                  </div>
                  <div class="form-control">
                    <input type="text" name="lastName" placeholder="Last Name" class="input input-bordered" required>
                  </div>
                </div>
                
                <!-- Contact info -->
                <div class="form-control">
                  <input type="email" name="email" placeholder="Email Address" class="input input-bordered" required>
                </div>
                <div class="form-control">
                  <input type="tel" name="phone" placeholder="Phone Number" class="input input-bordered" required>
                </div>
                
                <!-- Date and Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="form-control">
                    <input type="date" name="appointmentDate" class="input input-bordered" required
                           min="<?php echo date('Y-m-d'); ?>">
                  </div>
                  <div class="form-control">
                    <select name="appointmentTime" class="select select-bordered w-full" required>
                      <option value="">Select Time</option>
                      <?php
                      $start = strtotime('9:00');
$end = strtotime('17:00');
for ($i = $start; $i <= $end; $i += 1800) {
    echo '<option value="'.date('H:i', $i).'">'.date('g:i A', $i).'</option>';
}
?>
                    </select>
                  </div>
                </div>

                <div class="modal-action">
                  <button type="submit" class="btn btn-primary">Schedule Appointment</button>
                  <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Appointment Success Modal -->
      <div class="modal" id="success-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog w-11/12 max-w-lg mx-auto">
          <div class="modal-content bg-white rounded-lg shadow-xl">
            <div class="modal-header p-6 border-b">
              <h3 class="modal-title text-xl font-bold">Appointment Scheduled!</h3>
            </div>
            <div class="modal-body p-6">
              <p>Your appointment has been successfully scheduled. We'll send you a confirmation email with all the details.</p>
            </div>
            <div class="modal-footer p-6 border-t">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
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
              Copyright © 2025 Brawa. All Rights Reserved.
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

    <!-- Scripts -->
    <script src="https://unpkg.com/htmx.org@2.0.4/dist/htmx.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="public/js/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="public/js/search.js"></script>
    <script src="public/js/load-stats.js"></script>
    <script src="public/js/get-location.js"></script>
    <script src="public/js/appointments.js"></script>
  </body>
</html>

<?php

// Start the session at the beginning of the file
session_start();

$title = 'Sell or Trade Your Car Online Today | Brawa AutoImport';
?>
<!DOCTYPE html>
<html lang="en" data-theme="caramellatte" class="bg-white">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="htmx-config" content='{"selfRequestsOnly":false}'>
    <meta name="author" content="Abel Frias">
    <meta name="email" content="afriasv@uri.edu">
    <meta name="description" content="Sell or trade your vehicle online in the Dominican Republic. Get an instant offer, create a listing, or let us handle the sale for you.">
    <meta name="keywords" content="sell car Dominican Republic, trade car Dominican Republic, instant car offer, online car selling">
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
                <li><a href="search-cars.php" class="font-semibold">Search Cars</a></li>
                <li><a href="sell_trade.php" class="font-semibold bg-base-300">Sell/Trade</a></li>
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
                <a href="search-cars.php" class="text-sm font-semibold hover:text-primary p-2 rounded-lg">Search Cars</a>
                <a href="sell_trade.php" class="text-sm font-semibold hover:text-primary p-2 rounded-lg text-primary">Sell/Trade</a>
                <a href="views/financing" class="text-sm font-semibold hover:text-primary p-2 rounded-lg">Financing</a>
              </div>
            </div>
          </div>
        </nav>
      </header>

      <!-- Body Content -->
      <div class="body-content" id="body-content">
        <!-- Hero Section -->
        <div class="bg-base-200 px-16 py-20 xl:px-60 xl:py-35">
          <div class="container mx-auto max-w-[1700px] grid grid-cols-1 md:grid-cols-5 gap-8 items-center">
            <!-- Left Side: Text Content -->
            <div class="md:col-span-3">
              <h1 class="text-4xl lg:text-5xl font-bold mb-4">Sell or Trade Your Car.<span class="text-neutral"> On Your Terms.</span></h1>
              <h3 class="text-xl lg:text-2xl font-semibold">Turn your car into cash or trade it for something new- fast, easy, and completely online.</h3>
            </div>

            <!-- Right Side: Call to Action -->
            <div class="md:col-span-2 bg-base-100 p-8 rounded-lg shadow-lg text-center">
              <h2 class="text-2xl font-semibold mb-4">Start Here</h2>
              <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="form-control">
                  <select class="select select-bordered w-full">
                    <option disabled selected>Select Make</option>
                    <option>Toyota</option>
                    <option>Honda</option>
                    <option>Ford</option>
                    <option>Chevrolet</option>
                    <option>BMW</option>
                    <option>Mercedes-Benz</option>
                    <option>Audi</option>
                    <option>Hyundai</option>
                    <option>Kia</option>
                  </select>
                </div>
                <div class="form-control">
                  <select class="select select-bordered w-full">
                    <option disabled selected>Select Model</option>
                    <option>Select a make first</option>
                  </select>
                </div>
              </div>
              <button class="btn btn-primary btn-lg w-full" 
                      hx-get="sell_trade_form.php" 
                      hx-target="#body-content" 
                      hx-swap="outerHTML">
                Get Started Now
              </button>
              <p class="text-xs mt-4 text-base-content/60">It only takes a few minutes.</p>
            </div>
          </div>
        </div>

        <!-- How It Works Section (Tabbed) -->
        <div class="w-full py-16 bg-base-300">
          <div class="container mx-auto px-4">
            <h2 class="text-3xl lg:text-4xl font-bold text-start mb-12">How It Works</h2>
            <div class="bg-base-100 rounded-4xl shadow p-6 lg:p-10">
              <!-- Tab Navigation -->
              <div class="flex justify-center mb-8 p-4 overflow-x-auto">
            <div class="inline-flex rounded-full shadow-md bg-base-100 p-2" id="howitworks-tabs">
              <button type="button" class="howitworks-tab px-6 py-2 rounded-full transition-all duration-200 bg-primary text-white hover:bg-neutral hover:text-neutral-content" data-tab="sell-to-us">Sell to Us</button>
              <button type="button" class="howitworks-tab px-6 py-2 rounded-full transition-all duration-200 bg-base-100 text-base-content hover:bg-neutral hover:text-neutral-content" data-tab="sell-online">Sell 100% Online</button>
              <button type="button" class="howitworks-tab px-6 py-2 rounded-full transition-all duration-200 bg-base-100 text-base-content hover:bg-neutral hover:text-neutral-content" data-tab="trade">Trade</button>
            </div>
              </div>
              <!-- Tab Content -->
              <div class="relative overflow-hidden">
            <div class="howitworks-panel" id="tab-sell-to-us">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Step 1 -->
                <div class="bg-base-100 border border-base-300 rounded-lg shadow p-6 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-primary mx-auto mb-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V19.5a2.25 2.25 0 0 0 2.25 2.25h.75m0-3.75h3.75M9 15h3.75M9 12h3.75M15 12h3.75m-3.75 3H18" />
                  </svg>
                  <h3 class="text-xl font-bold mb-2">Complete the Form</h3>
                  <p class="text-base-content/80">Fill out our quick online form with details about your vehicle including make, model, year, and condition.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-base-100 border border-base-300 rounded-lg shadow p-6 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-primary mx-auto mb-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                  </svg>
                  <h3 class="text-xl font-bold mb-2">Get Your Offer</h3>
                  <p class="text-base-content/80">Receive a competitive cash offer from our team within 24 hours based on current market values.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-base-100 border border-base-300 rounded-lg shadow p-6 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-primary mx-auto mb-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                  </svg>
                  <h3 class="text-xl font-bold mb-2">Schedule Pickup</h3>
                  <p class="text-base-content/80">Accept the offer and schedule a convenient time for us to inspect and pick up your vehicle.</p>
                </div>
              </div>
            </div>
            <div class="howitworks-panel hidden" id="tab-sell-online">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Step 1 -->
                <div class="bg-base-100 border border-base-300 rounded-lg shadow p-6 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-primary mx-auto mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                  </svg>
                  <h3 class="text-xl font-bold mb-2">Create Your Listing</h3>
                  <p class="text-base-content/80">Easily create your vehicle listing with photos and details. Set your price and preferences.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-base-100 border border-base-300 rounded-lg shadow p-6 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-primary mx-auto mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 8.25V6a2.25 2.25 0 0 0-2.25-2.25h-4.5A2.25 2.25 0 0 0 7.5 6v12a2.25 2.25 0 0 0 2.25 2.25h4.5A2.25 2.25 0 0 0 16.5 18v-2.25" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12h6m0 0-3-3m3 3-3 3" />
                  </svg>
                  <h3 class="text-xl font-bold mb-2">Receive Offers</h3>
                  <p class="text-base-content/80">Get offers from interested buyers directly through our platform. Review and negotiate as you wish.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-base-100 border border-base-300 rounded-lg shadow p-6 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-primary mx-auto mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m-7 8.25V19.5A2.25 2.25 0 0 0 8.25 21.75h7.5A2.25 2.25 0 0 0 18 19.5v-1.25" />
                  </svg>
                  <h3 class="text-xl font-bold mb-2">Close Sell</h3>
                  <p class="text-base-content/80">Accept an offer and complete the sale securely online. We help with paperwork and payment.</p>
                </div>
              </div>
            </div>
            <div class="howitworks-panel hidden" id="tab-trade">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Step 1 -->
                <div class="bg-base-100 border border-base-300 rounded-lg shadow p-6 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-primary mx-auto mb-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z" />
                  </svg>
                  <h3 class="text-xl font-bold mb-2">Calculate Trade Value</h3>
                  <p class="text-base-content/80">Use our online tool to calculate your current vehicle's trade-in value based on market conditions.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-base-100 border border-base-300 rounded-lg shadow p-6 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-primary mx-auto mb-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                  </svg>
                  <h3 class="text-xl font-bold mb-2">Browse Our Inventory</h3>
                  <p class="text-base-content/80">Explore our extensive selection of vehicles to find your next car, applying your trade-in value.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-base-100 border border-base-300 rounded-lg shadow p-6 text-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-primary mx-auto mb-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                  </svg>
                  <h3 class="text-xl font-bold mb-2">Complete Your Trade</h3>
                  <p class="text-base-content/80">Finalize the trade in one seamless transaction, with all paperwork handled by our dedicated team.</p>
                </div>
              </div>
              <div class="mt-6 text-center">
                <p class="text-base-content/70 text-sm">You can also trade with other users who have their listings available for trade.</p>
              </div>
            </div>
              </div>
            </div>
          </div>
        </div>

      <!-- Login modal -->
      <dialog id="login-modal" class="modal">
        <div class="modal-box">
          <form method="dialog">
             <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
          </form>
          <div id="auth-form-container">
             <!-- Login/Signup form loaded via HTMX -->
             <span class="loading loading-spinner loading-lg"></span>
          </div>
        </div>
         <form method="dialog" class="modal-backdrop">
           <button>close</button>
         </form>
      </dialog>

      <!-- Delete Account Modal (Copied from index.php) -->
      <dialog id="delete-account-modal" class="modal">
        <div class="modal-box">
          <form method="dialog">
             <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
          </form>
          <div id="delete-account-container">
             <!-- Delete confirmation loaded via HTMX -->
             <span class="loading loading-spinner loading-lg"></span>
          </div>
        </div>
         <form method="dialog" class="modal-backdrop">
           <button>close</button>
         </form>
      </dialog>

      <!-- Footer (Copied from index.php) -->
      <footer class="footer lg:footer-horizontal bg-neutral text-neutral-content p-10">
          <!-- Company information section -->
          <aside>
              <a href="index.php" class="py-2">
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
              <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.012 3.584-.07 4.85c-.148 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.85s.012-3.584.07-4.85c.148-3.227 1.664-4.771 4.919-4.919C8.416 2.175 8.796 2.163 12 2.163m0-1.081c-3.264 0-3.66.014-4.944.072C3.049 1.26 1.26 3.049 1.182 7.056c-.058 1.284-.073 1.68-.073 4.944s.015 3.66.073 4.944c.079 4.007 1.868 5.895 5.874 5.973 1.284.058 1.68.073 4.944.073s3.66-.015 4.944-.073c4.006-.078 5.795-1.966 5.873-5.973.058-1.284.073-1.68.073-4.944s-.015-3.66-.073-4.944C22.74 3.049 20.95 1.26 16.944 1.182 15.66.124 15.264 1.082 12 1.082zM12 6.865c-2.833 0-5.135 2.302-5.135 5.135s2.302 5.135 5.135 5.135 5.135-2.302 5.135-5.135S14.833 6.865 12 6.865zm0 8.518c-1.866 0-3.383-1.517-3.383-3.383s1.517-3.383 3.383-3.383 3.383 1.517 3.383 3.383-1.517 3.383-3.383 3.383zm5.438-8.864c-.74 0-1.34.6-1.34 1.34s.6 1.34 1.34 1.34 1.34-.6 1.34-1.34-.6-1.34-1.34-1.34z"></path></svg></a>
              <!-- Facebook logo SVG -->
              <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path></svg></a>
              </div>
          </nav>
      </footer>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/htmx.org@2.0.4/dist/htmx.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="public/js/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="public/js/how-it-works-tabs.js"></script>
    <script>
      // Basic modal handling for HTMX loaded content
      document.body.addEventListener('htmx:afterRequest', function(evt) {
        // Handle Sell/Trade modal
        if (evt.detail.target.id === 'modal-content' && !evt.detail.failed) {
          const modal = document.getElementById('sell-trade-modal');
          if (modal) {
            modal.showModal();
          }
        }
        // Handle auth modal if triggered from this page
        if (evt.detail.target.id === 'auth-form-container' && !evt.detail.failed) {
          const modal = document.getElementById('login-modal');
          if (modal) {
            modal.showModal();
          }
        }
         // Handle delete account modal if triggered from this page
        if (evt.detail.target.id === 'delete-account-container' && !evt.detail.failed) {
          const modal = document.getElementById('delete-account-modal');
          if (modal) {
            modal.showModal();
          }
        }
      });

       // Clear modal content when closed
       const sellTradeModal = document.getElementById('sell-trade-modal');
       if (sellTradeModal) {
         sellTradeModal.addEventListener('close', () => {
           const modalContent = document.getElementById('modal-content');
           if (modalContent) {
             modalContent.innerHTML = '<span class="loading loading-spinner loading-lg"></span>'; // Reset to loading state
           }
         });
       }
       const loginModal = document.getElementById('login-modal');
        if (loginModal) {
         loginModal.addEventListener('close', () => {
           const authContainer = document.getElementById('auth-form-container');
           if (authContainer) {
             authContainer.innerHTML = '<span class="loading loading-spinner loading-lg"></span>'; // Reset
           }
         });
       }
       const deleteModal = document.getElementById('delete-account-modal');
        if (deleteModal) {
         deleteModal.addEventListener('close', () => {
           const deleteContainer = document.getElementById('delete-account-container');
           if (deleteContainer) {
             deleteContainer.innerHTML = '<span class="loading loading-spinner loading-lg"></span>'; // Reset
           }
         });
       }

    </script>
    <!-- Include other JS files if needed -->
     <script src="public/js/auth.js"></script>

  </body>
</html>

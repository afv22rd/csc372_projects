<?php
// Start the session at the beginning of the file
session_start();
require_once __DIR__.'/database/database_connection.php';

// Get vehicle ID from query string
$vehicleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Validate vehicle ID
if ($vehicleId <= 0) {
    header('Location: search-cars.php');
    exit;
}

// Fetch vehicle data from database
try {
    $sql = 'SELECT * FROM vehicles WHERE vehicle_id = :vehicle_id';
    $statement = pdo($pdo, $sql, ['vehicle_id' => $vehicleId]);
    $vehicle = $statement->fetch();

    // Check if vehicle exists
    if (!$vehicle) {
        header('Location: search-cars.php');
        exit;
    }

    // Parse JSON data
    $images = json_decode($vehicle['images'], true) ?: [];
    $features = json_decode($vehicle['features'], true) ?: [];

    // Format values for display
    $price = number_format($vehicle['price'], 0, '.', ',');
    $monthlyPayment = round($vehicle['price'] / 60);
    $downPayment = round($vehicle['price'] * 0.1);
    $monthlyPaymentFormatted = number_format($monthlyPayment, 0, '.', ',');
    $downPaymentFormatted = number_format($downPayment, 0, '.', ',');
    $mileageFormatted = number_format($vehicle['mileage'], 0, '.', ',');

    // Set page title
    $title = htmlspecialchars("{$vehicle['year']} {$vehicle['make']} {$vehicle['model']} | Brawa AutoImport");
} catch (PDOException $e) {
    // Log error and redirect on database error
    error_log('Database Error: '.$e->getMessage());
    header('Location: search-cars.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="caramellatte" class="bg-base-300">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="htmx-config" content='{"selfRequestsOnly":false}'>
    <meta name="author" content="Abel Frias">
    <meta name="email" content="afriasv@uri.edu">
    <meta name="description" content="<?php echo htmlspecialchars("{$vehicle['year']} {$vehicle['make']} {$vehicle['model']} for sale at Brawa AutoImport. {$vehicle['mileage']} miles, {$vehicle['fuel_type']}, {$vehicle['color']}."); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars("{$vehicle['make']}, {$vehicle['model']}, used car Dominican Republic, buy car Dominican Republic"); ?>">
    <title><?php echo $title; ?></title>
    <base href="/csc372_projects/htmx/" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.8/daisyui.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.8/themes.css" rel="stylesheet" />
    <link rel="stylesheet" href="public/css/index-style.css" />
  </head>
  <body hx-boost="true" class="bg-base-300">
    <div class="mx-auto">
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
                <li><a href="" class="font-semibold">Sell/Trade</a></li>
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
                <a href="search-cars.php" class="text-sm font-semibold hover:text-primary p-2 rounded-lg">Search Cars</a>
                <a href="" class="text-sm font-semibold hover:text-primary p-2 rounded-lg">Sell/Trade</a>
                <a href="" class="text-sm font-semibold hover:text-primary p-2 rounded-lg">Financing</a>
              </div>
            </div>
          </div>
        </nav>
      </header>

      <!-- Body Content -->
      <div class="body-content" id="body-content">
        <!-- Vehicle Detail Section -->
        <div class="w-full bg-base-200 min-h-screen flex flex-col py-8">
            <div class="w-[95%] max-w-7xl mx-auto">
                <!-- Back button -->
                <div class="mb-8">
                    <a href="search-cars.php" class="btn btn-primary btn-sm gap-2 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Back to search results
                    </a>
                </div>
                
                <!-- Vehicle title -->
                <h1 class="text-3xl font-bold mb-6"><?php echo htmlspecialchars("{$vehicle['year']} {$vehicle['make']} {$vehicle['model']}"); ?></h1>
                
                <!-- Main content area with image gallery and details -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">
                    <!-- Image gallery - 70% width on lg screens -->
                    <div class="lg:col-span-8 carousel w-full rounded-xl overflow-hidden shadow-lg h-[300px] sm:h-[400px] md:h-[500px] bg-white">
                        <?php foreach ($images as $index => $image) { ?>
                            <div id="gallery-slide-<?php echo $index; ?>" class="carousel-item relative w-full">
                                <img src="<?php echo htmlspecialchars($image); ?>" 
                                     class="w-full h-full object-cover" 
                                     alt="<?php echo htmlspecialchars("{$vehicle['year']} {$vehicle['make']} {$vehicle['model']} Image ".($index + 1)); ?>" />
                                <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                                    <a href="#gallery-slide-<?php echo ($index === 0) ? count($images) - 1 : $index - 1; ?>" 
                                       class="btn btn-circle"
                                       onclick="event.preventDefault(); document.getElementById('gallery-slide-<?php echo ($index === 0) ? count($images) - 1 : $index - 1; ?>').scrollIntoView({behavior: 'smooth', block: 'nearest', inline: 'center'})">❮</a>
                                    <a href="#gallery-slide-<?php echo ($index === count($images) - 1) ? 0 : $index + 1; ?>" 
                                       class="btn btn-circle"
                                       onclick="event.preventDefault(); document.getElementById('gallery-slide-<?php echo ($index === count($images) - 1) ? 0 : $index + 1; ?>').scrollIntoView({behavior: 'smooth', block: 'nearest', inline: 'center'})">❯</a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <!-- Vehicle details - 30% width on lg screens -->
                    <div class="lg:col-span-4 flex flex-col gap-6">
                        <!-- Price section -->
                        <div class="card bg-white shadow-md">
                            <div class="card-body">
                                <h2 class="text-3xl font-bold">$<?php echo $price; ?></h2>
                                <div class="flex flex-wrap gap-4 mt-2">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span>Est. $<?php echo $monthlyPaymentFormatted; ?> /mo</span>
                                        <div class="tooltip" data-tip="Estimated payment with 10% down for 60 months">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 cursor-help" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-600">$<?php echo $downPaymentFormatted; ?> down payment</span>
                                </div>
                                <div class="card-actions mt-4">
                                    <button class="btn btn-primary w-full shadow-sm">Contact about this vehicle</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Specifications -->
                        <div class="card bg-white shadow-md">
                            <div class="card-body px-5 py-[13px]">
                                <h3 class="text-xl font-bold mb-4">Vehicle Specifications</h3>
                                <div class="grid grid-cols-1 gap-3">
                                    <div class="flex justify-between items-center py-1 border-b border-base-200">
                                        <span class="text-sm text-gray-500">Make</span>
                                        <span class="font-medium"><?php echo htmlspecialchars($vehicle['make']); ?></span>
                                    </div>
                                    <div class="flex justify-between items-center py-1 border-b border-base-200">
                                        <span class="text-sm text-gray-500">Model</span>
                                        <span class="font-medium"><?php echo htmlspecialchars($vehicle['model']); ?></span>
                                    </div>
                                    <div class="flex justify-between items-center py-1 border-b border-base-200">
                                        <span class="text-sm text-gray-500">Year</span>
                                        <span class="font-medium"><?php echo htmlspecialchars($vehicle['year']); ?></span>
                                    </div>
                                    <div class="flex justify-between items-center py-1 border-b border-base-200">
                                        <span class="text-sm text-gray-500">Body Type</span>
                                        <span class="font-medium"><?php echo htmlspecialchars($vehicle['body_type']); ?></span>
                                    </div>
                                    <div class="flex justify-between items-center py-1 border-b border-base-200">
                                        <span class="text-sm text-gray-500">Mileage</span>
                                        <span class="font-medium"><?php echo $mileageFormatted; ?> miles</span>
                                    </div>
                                    <div class="flex justify-between items-center py-1 border-b border-base-200">
                                        <span class="text-sm text-gray-500">Condition</span>
                                        <span class="font-medium"><?php echo htmlspecialchars($vehicle['condition'] ?? 'N/A'); ?></span>
                                    </div>
                                    <div class="flex justify-between items-center py-1 border-b border-base-200">
                                        <span class="text-sm text-gray-500">Location</span>
                                        <span class="font-medium"><?php echo htmlspecialchars($vehicle['location'] ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Description and features -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <!-- Description -->
                    <div class="card bg-white shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-bold mb-4">Description</h3>
                            <p class="whitespace-pre-line"><?php echo htmlspecialchars($vehicle['description']); ?></p>
                        </div>
                    </div>
                    
                    <!-- Features -->
                    <div class="card bg-white shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-bold mb-4">Features</h3>
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <?php foreach ($features as $feature) { ?>
                                <li class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span><?php echo htmlspecialchars($feature); ?></span>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Full specifications section -->
                <div class="mb-10">
                    <div class="card bg-white shadow-md">
                        <div class="card-body">
                            <h3 class="text-xl font-bold mb-4">All Specifications</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Make</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($vehicle['make']); ?></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Model</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($vehicle['model']); ?></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Year</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($vehicle['year']); ?></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Body Type</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($vehicle['body_type']); ?></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Mileage</span>
                                    <span class="font-medium"><?php echo $mileageFormatted; ?> miles</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Fuel Type</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($vehicle['fuel_type']); ?></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Color</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($vehicle['color']); ?></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Seating</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($vehicle['seating']); ?> seats</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Drivetrain</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($vehicle['drivetrain']); ?></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Transmission</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($vehicle['transmission']); ?></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Cylinders</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($vehicle['cylinders']); ?></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Condition</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($vehicle['condition'] ?? 'N/A'); ?></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">Location</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($vehicle['location'] ?? 'N/A'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Similar vehicles section -->
                <div class="mb-10">
                    <h3 class="text-2xl font-bold mb-6">Similar Vehicles</h3>
                    <?php
                    // Get similar vehicles (same make or model)
                    try {
                        $sql = "SELECT * FROM vehicles 
                                WHERE (make = :make OR model = :model) 
                                AND vehicle_id != :vehicle_id
                                AND status = 'active'
                                LIMIT 4";
                        $similar = pdo($pdo, $sql, [
                            'make' => $vehicle['make'],
                            'model' => $vehicle['model'],
                            'vehicle_id' => $vehicle['vehicle_id'],
                        ])->fetchAll();

                        if (!empty($similar)) {
                            echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">';

                            foreach ($similar as $similarVehicle) {
                                $similarImages = json_decode($similarVehicle['images'], true) ?: [];
                                $similarPrice = number_format($similarVehicle['price'], 0, '.', ',');

                                echo "
                                <div class='card bg-white shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer' onclick=\"window.location='vehicle-detail.php?id={$similarVehicle['vehicle_id']}';\">
                                    <figure>";

                                if (!empty($similarImages)) {
                                    echo "<img src='".htmlspecialchars($similarImages[0])."' class='w-full h-40 object-cover' 
                                           alt='".htmlspecialchars("{$similarVehicle['year']} {$similarVehicle['make']} {$similarVehicle['model']}")."'>";
                                }

                                echo "
                                    </figure>
                                    <div class='card-body p-4'>
                                        <h4 class='card-title text-sm'>".htmlspecialchars("{$similarVehicle['year']} {$similarVehicle['make']} {$similarVehicle['model']}")."</h4>
                                        <div class='font-bold text-primary'>$".$similarPrice.'</div>
                                    </div>
                                </div>';
                            }

                            echo '</div>';
                        } else {
                            echo '<p class="text-gray-500">No similar vehicles found</p>';
                        }
                    } catch (Exception $e) {
                        // If error fetching similar vehicles, just hide this section
                        echo '<p class="text-gray-500">No similar vehicles found</p>';
                    }
?>
                </div>
            </div>
        </div>
        <!-- End of vehicle detail section -->
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
        <!-- Footer navigation -->
        <nav>
            <h6 class="footer-title">Financing</h6>
            <a class="link link-hover">Get Pre-Qualified</a>
            <a class="link link-hover">Banks & Finance Companies</a>
            <a class="link link-hover">Payment Calculator</a>
        </nav>
        <nav>
            <h6 class="footer-title">Sell/Trade</h6>
            <a class="link link-hover">Get an Offer</a>
        </nav>
        <nav>
            <h6 class="footer-title">About & Support</h6>
            <a class="link link-hover">About Brawa</a>
            <a class="link link-hover">Customer Reviews</a>
            <a class="link link-hover">Buying from Brawa</a>
            <a class="link link-hover">Finance with Brawa</a>
            <a class="link link-hover">Selling or Trading In</a>
        </nav>
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
<?php

require_once __DIR__.'/../database/database_connection.php';

try {
    // SQL query to get all vehicles with status 'available'
    $sql = "SELECT * FROM vehicles WHERE status = 'available'";
    $statement = pdo($pdo, $sql);
    $vehicles = $statement->fetchAll();

    // Check if vehicles were found
    if (empty($vehicles)) {
        echo '<div class="alert alert-warning">No vehicles found</div>';
        exit;
    }

    $vehicleCards = '';

    foreach ($vehicles as $vehicle) {
        // Parse images from JSON format
        $images = json_decode($vehicle['images'], true) ?: [];
        $features = json_decode($vehicle['features'], true) ?: [];

        // Generate carousel HTML with navigation buttons
        $imagesHtml = '';
        foreach ($images as $index => $image) {
            $prevIndex = $index === 0 ? count($images) - 1 : $index - 1;
            $nextIndex = $index === count($images) - 1 ? 0 : $index + 1;

            $imagesHtml .= "
                <div id='slide-{$vehicle['vehicle_id']}-{$index}' class='carousel-item relative w-full'>
                    <img src='{$image}' class='w-full h-full object-cover' alt='".htmlspecialchars("{$vehicle['make']} {$vehicle['model']} Image ".($index + 1))."' />
                    <div class='absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between'>
                        <a href='#slide-{$vehicle['vehicle_id']}-{$prevIndex}' 
                        class='btn btn-circle btn-sm opacity-85 hover:opacity-100' 
                        onclick='event.preventDefault(); event.stopPropagation(); document.getElementById(\"slide-{$vehicle['vehicle_id']}-{$prevIndex}\").scrollIntoView({behavior: \"smooth\", block: \"nearest\", inline: \"center\"})'>❮</a>
                        <a href='#slide-{$vehicle['vehicle_id']}-{$nextIndex}' 
                        class='btn btn-circle btn-sm opacity-85 hover:opacity-100'
                        onclick='event.preventDefault(); event.stopPropagation(); document.getElementById(\"slide-{$vehicle['vehicle_id']}-{$nextIndex}\").scrollIntoView({behavior: \"smooth\", block: \"nearest\", inline: \"center\"})'>❯</a>
                    </div>
                </div>";
        }

        // Format price and calculate estimated payments
        $price = number_format($vehicle['price'], 0, '.', ',');
        $monthlyPayment = round($vehicle['price'] / 60);
        $downPayment = round($vehicle['price'] * 0.1);
        $downPaymentFormatted = number_format($downPayment, 0, '.', ',');
        $mileageFormatted = number_format($vehicle['mileage'], 0, '.', ',');

        $vehicleCards .= "
            <div class='card bg-base-100 shadow-sm w-full max-w-sm cursor-pointer' onclick=\"window.location='vehicle-detail.php?id={$vehicle['vehicle_id']}';\">
                <figure class='relative'>
                    <div class='carousel w-full rounded-t-lg h-48 md:h-56 lg:h-60 overflow-hidden relative'>
                        {$imagesHtml}
                    </div>
                    <!-- Favorite Heart Icon -->
                    <button class='absolute top-2 right-2 btn btn-circle btn-sm bg-base-100 bg-opacity-70' 
                            hx-post='/toggle-favorite' 
                            hx-vals='{\"vehicleId\": {$vehicle['vehicle_id']}}' 
                            hx-swap='none'
                            onclick='event.stopPropagation();'>
                        <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z' />
                        </svg>
                    </button>
                </figure>
                
                <div class='card-body p-4'>
                    <h2 class='card-title text-lg font-bold'>".htmlspecialchars("{$vehicle['year']} {$vehicle['make']} {$vehicle['model']}")."</h2>
                    <div class='flex items-center text-sm text-gray-500'>
                        <span>".htmlspecialchars($vehicle['fuel_type'])."</span>
                        <span class='mx-2'>•</span>
                        <span>{$mileageFormatted} miles</span>
                    </div>
                    <div class='mt-2'>
                        <span class='text-2xl font-bold'>\${$price}</span>
                    </div>
                    <div class='mt-2 text-sm text-gray-600'>
                        <div class='flex items-center'>
                            <span>Est. \${$monthlyPayment} /mo</span>
                            <svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4 ml-1 cursor-help' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' />
                            </svg>
                        </div>
                        <div>\${$downPaymentFormatted} initial cash down</div>
                    </div>
                </div>
            </div>";
    }

    echo $vehicleCards;
} catch (PDOException $e) {
    // Log the error (in a production environment)
    error_log('Database Error: '.$e->getMessage());
    echo '<div class="alert alert-error">Error loading vehicles: Database error</div>';
} catch (Exception $e) {
    // Log the error (in a production environment)
    error_log('General Error: '.$e->getMessage());
    echo '<div class="alert alert-error">Error loading vehicles</div>';
}

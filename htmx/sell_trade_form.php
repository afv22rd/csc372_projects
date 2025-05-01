<?php
// sell_trade_form.php: Vehicle listing form
session_start();
$title = 'Create Your Vehicle Listing | Brawa AutoImport';
?>
<!DOCTYPE html>
<html lang="en" data-theme="caramellatte" class="bg-white">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $title; ?></title>
  <base href="/csc372_projects/htmx/" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.8/daisyui.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.8/themes.css" rel="stylesheet" />
  <link rel="stylesheet" href="public/css/index-style.css" />
  <script src="https://unpkg.com/htmx.org@2.0.4/dist/htmx.js" crossorigin="anonymous"></script>
  <script src="public/js/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-base-300 min-h-screen">
  <div class="mx-auto bg-base-200 min-h-screen">
    <main class="container mx-auto max-w-7xl py-10 px-8">
      <h1 class="text-3xl font-bold mb-8 text-center">Create Your Vehicle Listing</h1>
      <div class="flex flex-col md:flex-row gap-6 relative">
        <!-- Form Column (Left) - Make this scrollable if needed -->
        <div class="w-full md:w-2/5 p-4 bg-white rounded-lg shadow md:max-h-[calc(100vh-10rem)] md:overflow-y-auto">
          <form id="listing-form"
                class="flex flex-col gap-4"
                hx-post="api/submit_listing.php"
                hx-target="#submission-result"
                hx-swap="innerHTML"
                hx-indicator="#submission-loading"
                hx-encoding="multipart/form-data"
                hx-on::before-request="document.getElementById('submission-modal').showModal(); document.getElementById('submission-result').innerHTML = '';"
                hx-on::after-request="if(event.detail.successful) { document.getElementById('submission-loading').classList.add('hidden'); } else { document.getElementById('submission-loading').classList.add('hidden'); /* Optionally show error state */ }"
                >
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="label font-semibold">Make</label>
                <input type="text" name="make" id="make" class="input input-bordered w-full" required />
              </div>
              <div>
                <label class="label font-semibold">Model</label>
                <input type="text" name="model" id="model" class="input input-bordered w-full" required />
              </div>
              <div>
                <label class="label font-semibold">Year</label>
                <input type="number" name="year" id="year" min="1900" max="<?php echo date('Y') + 1; ?>" class="input input-bordered w-full" required />
              </div>
              <div>
                <label class="label font-semibold">Price (USD)</label>
                <input type="number" name="price" id="price" min="0" step="0.01" class="input input-bordered w-full" required />
              </div>
              <div>
                <label class="label font-semibold">Location</label>
                <input type="text" name="location" id="location" class="input input-bordered w-full" placeholder="e.g. Santo Domingo" />
              </div>
              <div>
                <label class="label font-semibold">Mileage</label>
                <input type="number" name="mileage" id="mileage" min="0" class="input input-bordered w-full" />
              </div>
            </div>

            <div class="md:col-span-2">
              <label class="label font-semibold">Images (up to 30)</label>
              <input type="file" name="images[]" id="images" class="file-input file-input-bordered w-full" accept="image/*" multiple />
              <span class="text-xs text-base-content/60">Select images for preview. Max 30 on submission.</span>
            </div>

            <div>
              <label class="label font-semibold">Description</label>
              <textarea name="description" id="description" class="textarea textarea-bordered w-full" rows="4" required></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
               <div>
                <label class="label font-semibold">Body Type</label>
                <input type="text" name="body_type" id="body_type" class="input input-bordered w-full" />
              </div>
              <div>
                <label class="label font-semibold">Fuel Type</label>
                <input type="text" name="fuel_type" id="fuel_type" class="input input-bordered w-full" />
              </div>
              <div>
                <label class="label font-semibold">Color</label>
                <input type="text" name="color" id="color" class="input input-bordered w-full" />
              </div>
              <div>
                <label class="label font-semibold">Seating</label>
                <input type="number" name="seating" id="seating" min="1" class="input input-bordered w-full" />
              </div>
              <div>
                <label class="label font-semibold">Drivetrain</label>
                <input type="text" name="drivetrain" id="drivetrain" class="input input-bordered w-full" />
              </div>
               <div>
                <label class="label font-semibold">Transmission</label>
                <select name="transmission" id="transmission" class="select select-bordered w-full">
                  <option value="" disabled selected>Select Transmission</option>
                  <option value="Automatic">Automatic</option>
                  <option value="Manual">Manual</option>
                  <option value="CVT">CVT</option>
                  <option value="Semi-Automatic">Semi-Automatic</option>
                </select>
              </div>
              <div>
                <label class="label font-semibold">Cylinders</label>
                <input type="number" name="cylinders" id="cylinders" min="1" class="input input-bordered w-full" />
              </div>
              <div>
                <label class="label font-semibold">Condition</label>
                 <select name="condition" id="condition" class="select select-bordered w-full">
                  <option value="" disabled selected>Select Condition</option>
                  <option value="New">New</option>
                  <option value="Used - Excellent">Used - Excellent</option>
                  <option value="Used - Good">Used - Good</option>
                  <option value="Used - Fair">Used - Fair</option>
                  <option value="For Parts">For Parts</option>
                </select>
              </div>
            </div>

             <div class="md:col-span-2">
              <label class="label font-semibold">Features (comma separated)</label>
              <input type="text" name="features" id="features" class="input input-bordered w-full" placeholder="e.g. Sunroof, Bluetooth, Backup Camera" />
            </div>

            <button type="submit" class="btn btn-primary btn-lg mt-6">Submit Listing</button>
          </form>
        </div>

        <!-- Preview Column (Right) - Make this sticky -->
        <div class="w-full md:w-3/5">
          <div id="listing-preview" class="bg-base-100 rounded-lg shadow p-6 space-y-4 md:sticky md:top-10">
            <!-- Preview content will be loaded here by HTMX -->
            <h2 class="text-2xl font-bold" id="preview-title-placeholder">Your Listing Preview</h2>
            <!-- Adjust aspect ratio and add explicit height for better image containment -->
            <div id="image-carousel-placeholder" class="bg-base-200 rounded overflow-hidden h-96 max-h-96">
                <!-- Images will be placed here with appropriate containment styling -->
                 <div class="flex overflow-x-auto space-x-2 p-2 h-full items-center justify-center"> <!-- Inner div for scrolling and padding -->
                    <span class="text-base-content/60">Images will appear here</span>
                    <!-- Note: Images added by JS use strict height/width constraints with object-contain to ensure proper scaling -->
                 </div>
            </div>
            <p class="text-xl font-semibold" id="preview-price-placeholder">Price: $ TBD</p>
            <p class="text-sm text-base-content/80" id="preview-location-placeholder">Location: TBD</p>
            <div class="divider"></div>
            <h3 class="font-semibold">Description</h3>
            <p class="text-base-content/90" id="preview-description-placeholder">Details about the vehicle will show up here as you type...</p>
            <div class="divider"></div>
            <h3 class="font-semibold">Details</h3>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <p id="preview-transmission-placeholder">Transmission: TBD</p>
                <p id="preview-condition-placeholder">Condition: TBD</p>
                <p id="preview-mileage-placeholder">Mileage: TBD</p>
                <p id="preview-body_type-placeholder">Body Type: TBD</p>
                <p id="preview-fuel_type-placeholder">Fuel: TBD</p>
                <p id="preview-color-placeholder">Color: TBD</p>
                <p id="preview-seating-placeholder">Seating: TBD</p>
                <p id="preview-drivetrain-placeholder">Drivetrain: TBD</p>
                <p id="preview-cylinders-placeholder">Cylinders: TBD</p>
            </div>
             <div class="divider"></div>
             <h3 class="font-semibold">Features</h3>
             <p class="text-sm text-base-content/80" id="preview-features-placeholder">Features will be listed here...</p>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Submission Status Modal -->
  <dialog id="submission-modal" class="modal">
    <div class="modal-box">
      <h3 class="font-bold text-lg mb-4">Submitting Listing...</h3>
      <!-- Loading Indicator -->
      <div id="submission-loading" class="text-center py-4">
        <span class="loading loading-spinner loading-lg"></span>
        <p>Please wait while we create your listing.</p>
      </div>
      <!-- Result Area -->
      <div id="submission-result">
        <!-- Submission status (success/error) will be loaded here by HTMX -->
      </div>
      <div class="modal-action">
         <form method="dialog">
            <!-- Close button appears initially, might be replaced by Done/View Listing button on success -->
            <button class="btn" id="submission-close-button">Close</button>
         </form>
      </div>
    </div>
     <form method="dialog" class="modal-backdrop">
        <button>close</button>
     </form>
  </dialog>

  <script src="public/js/preview-listing.js"></script>
  <script>
    // Optional: Add more sophisticated JS handling if needed,
    // e.g., listening for custom HX-Trigger events from the server.
    // For now, the basic HTMX attributes handle showing the modal and loading the result.
  </script>
</body>
</html>

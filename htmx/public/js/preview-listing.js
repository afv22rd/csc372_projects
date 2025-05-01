$(document).ready(function () {
  // Image preview handler
  $('#images').on('change', function (event) {
    const previewContainer = $('#image-preview-container');
    const previewPlaceholderForm = previewContainer.find('span'); // Get the placeholder text for form
    const previewTargetContainer = $('#listing-preview #image-carousel-placeholder'); // Target container for the main preview
    const previewTargetInner = previewTargetContainer.find('> div'); // The inner scrollable div
    const previewPlaceholderMain = previewTargetInner.find('span'); // Placeholder in main preview

    previewContainer.empty(); // Clear previous previews in the form area
    previewTargetInner.empty(); // Clear previous previews in the main preview inner div

    const files = event.target.files;
    if (files.length > 0) {
      previewPlaceholderForm.hide(); // Hide placeholder if files are selected
      previewTargetInner.removeClass('justify-center'); // Only remove justify-center, keep items-center for vertical alignment

      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function (e) {
          // Add to form preview container (small thumbs)
          const imgForm = $('<img>').attr('src', e.target.result).addClass('h-20 w-auto object-cover rounded flex-shrink-0');
          previewContainer.append(imgForm);

          // Create a wrapper div to manage each image's dimensions
          const imgWrapper = $('<div>').addClass('flex flex-shrink-0 items-center justify-center h-full px-1');
          
          // Add to main preview carousel with explicit height constraints
          const imgPreview = $('<img>')
            .attr('src', e.target.result)
            .addClass('h-auto max-h-[calc(24rem-1rem)] w-auto max-w-full object-contain rounded') // Explicit max height and width with object-contain
            .css({
              'display': 'block',
              'max-height': '95%'  // Additional safety constraint
            });

          // Append the image to wrapper, then wrapper to container
          imgWrapper.append(imgPreview);
          previewTargetInner.append(imgWrapper);
        }
        reader.readAsDataURL(file);
      }
    } else {
      // Show placeholder if no files are selected
      previewContainer.append('<span class="text-xs text-base-content/60 self-center">Image previews appear here</span>');
      previewTargetInner.addClass('justify-center'); // Add justify-center back for the placeholder text
      previewTargetInner.append('<span class="text-base-content/60">Images will appear here</span>');
    }
  });

  // Text/Select Field Preview Handler
  const form = $('#listing-form');
  const preview = $('#listing-preview');

  // Function to update a single preview field
  function updatePreviewField(inputId, previewId, prefix = '', suffix = '', defaultValue = 'TBD') {
    const value = form.find(`#${inputId}`).val();
    const displayValue = value ? `${prefix}${value}${suffix}` : defaultValue;
    preview.find(`#${previewId}`).text(displayValue);
  }

  // Map input IDs to preview placeholder IDs and optional formatting
  const previewMappings = {
    'make': { previewId: 'preview-title-placeholder', transform: (make, model, year) => `${year || 'Year'} ${make || 'Make'} ${model || 'Model'}` },
    'model': { previewId: 'preview-title-placeholder', transform: (make, model, year) => `${year || 'Year'} ${make || 'Make'} ${model || 'Model'}` },
    'year': { previewId: 'preview-title-placeholder', transform: (make, model, year) => `${year || 'Year'} ${make || 'Make'} ${model || 'Model'}` },
    'price': { previewId: 'preview-price-placeholder', prefix: 'Price: $ ', defaultValue: 'Price: $ TBD' },
    'location': { previewId: 'preview-location-placeholder', prefix: 'Location: ', defaultValue: 'Location: TBD' },
    'description': { previewId: 'preview-description-placeholder', defaultValue: 'Details about the vehicle will show up here as you type...' },
    'transmission': { previewId: 'preview-transmission-placeholder', prefix: 'Transmission: ', defaultValue: 'Transmission: TBD' },
    'condition': { previewId: 'preview-condition-placeholder', prefix: 'Condition: ', defaultValue: 'Condition: TBD' },
    'mileage': { previewId: 'preview-mileage-placeholder', prefix: 'Mileage: ', suffix: ' miles', defaultValue: 'Mileage: TBD' },
    'body_type': { previewId: 'preview-body_type-placeholder', prefix: 'Body Type: ', defaultValue: 'Body Type: TBD' },
    'fuel_type': { previewId: 'preview-fuel_type-placeholder', prefix: 'Fuel: ', defaultValue: 'Fuel: TBD' },
    'color': { previewId: 'preview-color-placeholder', prefix: 'Color: ', defaultValue: 'Color: TBD' },
    'seating': { previewId: 'preview-seating-placeholder', prefix: 'Seating: ', defaultValue: 'Seating: TBD' },
    'drivetrain': { previewId: 'preview-drivetrain-placeholder', prefix: 'Drivetrain: ', defaultValue: 'Drivetrain: TBD' },
    'cylinders': { previewId: 'preview-cylinders-placeholder', prefix: 'Cylinders: ', defaultValue: 'Cylinders: TBD' },
    'features': { previewId: 'preview-features-placeholder', prefix: 'Features: ', defaultValue: 'Features will be listed here...' }
  };

  // Attach event listeners to form fields
  form.find('input, textarea, select').on('input change', function () {
    const inputId = $(this).attr('id');
    const mapping = previewMappings[inputId];

    if (mapping) {
      if (mapping.transform) {
        // Special handling for combined fields like title
        const make = form.find('#make').val();
        const model = form.find('#model').val();
        const year = form.find('#year').val();
        preview.find(`#${mapping.previewId}`).text(mapping.transform(make, model, year));
      } else {
        updatePreviewField(inputId, mapping.previewId, mapping.prefix, mapping.suffix, mapping.defaultValue);
      }
    }
  });
});
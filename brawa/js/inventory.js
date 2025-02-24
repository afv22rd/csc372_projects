$(document).ready(function () {
    // Vehicle class (unchanged)
    class Vehicle {
        constructor(make, model, trim, year, mileage, price, color, features, isAvailable, images, imageAlt, review) {
            this.make = make;
            this.model = model;
            this.trim = trim;
            this.year = year;
            this.mileage = mileage;
            this.price = price;
            this.color = color;
            this.features = features;
            this.isAvailable = isAvailable;
            this.images = images;
            this.imageAlt = imageAlt;
            this.review = review;
        }

        // Method to get vehicle heading
        getHeading() {
            return `${this.year} ${this.make} ${this.model}`;
        }

        // Method to get vehicle subheading
        getSubheading() {
            return `${this.trim} | ${this.mileage} Miles`;
        }

        // Method to format price
        getFormattedPrice() {
            return `$${this.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
        }

        // Method to calculate price with tax
        getPriceWithTax(taxRate) {
            return this.price * (1 + taxRate);
        }

        // Calculate monthly payment
        getMonthlyPayment(months, interestRate, taxRate) {
            return (this.getPriceWithTax(taxRate) * (1 + interestRate)) / months;
        }
    }

    // Vehicle inventory data (unchanged)
    const vehicles = [
        new Vehicle("Mercedes-Benz", "E-Class", "E350 4MATIC", "2014", "60k", "20000", "White", ["Leather seats", "Sunroof", "Navigation"], true, ["./images/2014_e350.jpeg", "./images/2014_e350_2.jpg", "./images/2014_e350_3.jpg"], "2014 Mercedes-Benz E-Class", "https://www.youtube.com/embed/OUBVZ9ZasGs?si=IhNcYJaVOfICI3FO"),
        new Vehicle("Honda", "Accord", "Touring V6", "2017", "90k", "15000", "Black", ["Leather seats", "Sunroof", "Navigation"], true, ["./images/2017_accord.jpg", "./images/2017_accord_2.jpg", "./images/2017_accord_3.jpg"], "2017 Honda Accord Touring V6", "https://www.youtube.com/embed/gcVsZE1_ynw?si=qPdgnZ2QfwIHp1hK"),
        new Vehicle("Toyota", "Camry", "XSE V6", "2019", "110k", "15000", "Blue", ["Leather seats", "Sunroof", "Navigation"], true, ["./images/2019_camry.jpg", "./images/2019_camry_2.jpg", "./images/2019_camry_3.jpg"], "2019 Toyota Camry XSE V6", "https://www.youtube.com/embed/jT5biRzLgzw?si=gFzwbwvQ5t9bY853"),
        new Vehicle("Honda", "Accord", "2.0T Sport", "2020", "90k", "23000", "Gray", ["Apple CarPlay", "Sunroof", "Lane Assist"], true, ["./images/2020_accord.jpg", "./images/2020_accord_2.jpg", "./images/2020_accord_3.jpg"], "2020 Honda Accord 2.0T Sport", "https://www.youtube.com/embed/TmihZPj0Vlg?si=NHFJ7XVbafEEnWIZ"),
        new Vehicle("Toyota", "Camry", "TRD V6", "2020", "50k", "25000", "Red", ["Apple CarPlay", "Sunroof", "Lane Assist"], false, "./images/2020_camry.jpg", "2020 Toyota Camry TRD V6", "https://www.youtube.com/embed/1J9QJ9QJ1Zo?si=J9J9J9J9J9J9J9J9"),
    ];

    // Cache jQuery selections
    const $inventoryContainer = $('.row-cols-1');
    const $filterPrice = $('.filter-dropdown');

    // Function to create vehicle card (unchanged)
    function createVehicleCard(vehicle) {
        let originalIndex = vehicles.indexOf(vehicle);
        return `
            <div class="col">
                <a href="vehicle-page.html?vehicleId=${originalIndex}" class="text-decoration-none">
                    <div class="card shadow-sm">
                        <div class="car-img inventory-${originalIndex + 1}">
                            <svg class="bd-placeholder-img card-img-top" alt="${vehicle.imageAlt}" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"></svg>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${vehicle.getHeading()}</h5>
                            <p>${vehicle.getSubheading()}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">${vehicle.getFormattedPrice()}</h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        `;
    }

    // Initial display of vehicles
    vehicles.forEach((vehicle, index) => {
        if (vehicle.isAvailable) {
            $inventoryContainer.append(createVehicleCard(vehicle));
            $(`.inventory-${index + 1}`).css('background-image', `url("${vehicle.images[0]}")`);
        }
    });

    // Add hover effects using jQuery
    $('.card').on('mouseenter', function () {
        $(this).css({ transform: 'translateY(-5px)', transition: 'transform 0.3s ease' });
    }).on('mouseleave', function () {
        $(this).css({ transform: 'translateY(0)' });
    });

    // Filter by price using jQuery
    $filterPrice.on('change', function () {
        const selectedPrice = $(this).val();
        let filteredVehicles;

        // Determine the filter criteria
        if (selectedPrice === 'All') {
            filteredVehicles = vehicles.filter(vehicle => vehicle.isAvailable);
        } else {
            switch (selectedPrice) {
                case '10000':
                    filteredVehicles = vehicles.filter(vehicle => vehicle.isAvailable && vehicle.price < 10000);
                    break;
                case '15999':
                    filteredVehicles = vehicles.filter(vehicle => vehicle.isAvailable && vehicle.price >= 10000 && vehicle.price <= 15999);
                    break;
                case '19999':
                    filteredVehicles = vehicles.filter(vehicle => vehicle.isAvailable && vehicle.price >= 16000 && vehicle.price <= 19999);
                    break;
                case '24999':
                    filteredVehicles = vehicles.filter(vehicle => vehicle.isAvailable && vehicle.price >= 20000 && vehicle.price <= 24999);
                    break;
                default:
                    filteredVehicles = [];
                    break;
            }
        }

        // Clear the inventory container and add filtered vehicles
        $inventoryContainer.empty();
        filteredVehicles.forEach((vehicle, index) => {
            $inventoryContainer.append(createVehicleCard(vehicle));
            $(`.inventory-${vehicles.indexOf(vehicle) + 1}`).css('background-image', `url("${vehicle.images[0]}")`);
        });

        // Reapply hover effects to new cards
        $('.card').on('mouseenter', function () {
            $(this).css({ transform: 'translateY(-5px)', transition: 'transform 0.3s ease' });
        }).on('mouseleave', function () {
            $(this).css({ transform: 'translateY(0)' });
        });
    });
});
document.addEventListener('DOMContentLoaded', () => {
    // Vehicle class
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

    // Vehicle inventory data
    const vehicles = [
        new Vehicle("Mercedes-Benz", "E-Class", "E350 4MATIC", "2014", "60k", "20000", "White", ["Leather seats", "Sunroof", "Navigation"], true, ["./images/2014_e350.jpeg", "./images/2014_e350_2.jpg", "./images/2014_e350_3.jpg"], "2014 Mercedes-Benz E-Class", "https://www.youtube.com/embed/OUBVZ9ZasGs?si=IhNcYJaVOfICI3FO"),
        new Vehicle("Honda", "Accord", "Touring V6", "2017", "90k", "15000", "Black", ["Leather seats", "Sunroof", "Navigation"], true, ["./images/2017_accord.jpg", "./images/2017_accord_2.jpg", "./images/2017_accord_3.jpg"], "2017 Honda Accord Touring V6", "https://www.youtube.com/embed/gcVsZE1_ynw?si=qPdgnZ2QfwIHp1hK"),
        new Vehicle("Toyota", "Camry", "XSE V6", "2019", "110k", "15000", "Blue", ["Leather seats", "Sunroof", "Navigation"], true, ["./images/2019_camry.jpg", "./images/2019_camry_2.jpg", "./images/2019_camry_3.jpg"], "2019 Toyota Camry XSE V6", "https://www.youtube.com/embed/jT5biRzLgzw?si=gFzwbwvQ5t9bY853"),
        new Vehicle("Honda", "Accord", "2.0T Sport", "2020", "90k", "23000", "Gray", ["Apple CarPlay", "Sunroof", "Lane Assist"], true, ["./images/2020_accord.jpg", "./images/2020_accord_2.jpg", "./images/2020_accord_3.jpg"], "2020 Honda Accord 2.0T Sport", "https://www.youtube.com/embed/TmihZPj0Vlg?si=NHFJ7XVbafEEnWIZ"),
        new Vehicle("Toyota", "Camry", "TRD V6", "2020", "50k", "25000", "Red", ["Apple CarPlay", "Sunroof", "Lane Assist"], false, "./images/2020_camry.jpg", "2020 Toyota Camry TRD V6", "https://www.youtube.com/embed/1J9QJ9QJ1Zo?si=J9J9J9J9J9J9J9J9"),
    ];

    // Select the inventory container
    const inventoryContainer = document.querySelector('.row-cols-1');
    
    // Function to create vehicle card
    function createVehicleCard(vehicle) {
        // Get index from original vehicles array
        let originalIndex;
        for (let i = 0; i < vehicles.length; i++) {
            if (vehicles[i] === vehicle) {
                originalIndex = i;
                break;
            }
        }
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
    for (let i = 0; i < vehicles.length; i++) {
        if (!vehicles[i].isAvailable) continue;
        inventoryContainer.innerHTML += createVehicleCard(vehicles[i]);
    }

    // Set initial vehicle images
    for (let i = 0; i < vehicles.length; i++) {
        if (!vehicles[i].isAvailable) continue;
        const vehicleImage = document.querySelector(`.inventory-${i + 1}`);
        if (vehicleImage) {
            vehicleImage.style.backgroundImage = `url("${vehicles[i].images[0]}")`;
        }
    }

    // Add hover effects
    const cards = document.querySelectorAll('.card');
    for (let i = 0; i < cards.length; i++) {
        cards[i].addEventListener('mouseenter', () => {
            cards[i].style.transform = 'translateY(-5px)';
            cards[i].style.transition = 'transform 0.3s ease';
        });
        
        cards[i].addEventListener('mouseleave', () => {
            cards[i].style.transform = 'translateY(0)';
        });
    }

    // Filter by price
    const filterPrice = document.querySelector('.filter-dropdown');
    filterPrice.addEventListener('change', () => {
        const selectedPrice = filterPrice.value;
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
    
        // Clear the inventory container
        inventoryContainer.innerHTML = '';
        // Add only filtered vehicles to the inventory container
        for (let i = 0; i < filteredVehicles.length; i++) {
            inventoryContainer.innerHTML += createVehicleCard(filteredVehicles[i]);
        }

        // Reset cover images and hover
        for (let i = 0; i < filteredVehicles.length; i++) {
            const originalIndex = vehicles.indexOf(filteredVehicles[i]);
            const vehicleImage = document.querySelector(`.inventory-${originalIndex + 1}`);
            if (vehicleImage) {
                vehicleImage.style.backgroundImage = `url("${filteredVehicles[i].images[0]}")`;
            }
        }

        // Reapply hover effects to new cards
        const newCards = document.querySelectorAll('.card');
        for (let i = 0; i < newCards.length; i++) {
            newCards[i].addEventListener('mouseenter', () => {
                newCards[i].style.transform = 'translateY(-5px)';
                newCards[i].style.transition = 'transform 0.3s ease';
            });
            
            newCards[i].addEventListener('mouseleave', () => {
                newCards[i].style.transform = 'translateY(0)';
            });
        }
    });
});
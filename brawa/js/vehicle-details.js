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

     // Load URL parameters
     const URLParameters = new URLSearchParams(window.location.search);
     // Get current vehicle index / ID
     const currentVehicleIndex = URLParameters.get('vehicleId');

    // Get current vehicle
    const currentVehicle = vehicles[currentVehicleIndex];
    // Select HTML elements
    const vehicleHeadingElement = document.querySelector('.vehicle-heading');
    const vehicleSubheadingElement = document.querySelector('.vehicle-subheading');
    const vehiclePriceElement = document.querySelectorAll('.vehicle-price');
    const breadcrumbElement = document.querySelector('.breadcrumb');

    // Display breadcrumb
    const currentMake = currentVehicle.make;
    breadcrumbElement.innerHTML += `
        <li class="breadcrumb-item active" aria-current="page">${currentMake}</li>
    `;

    // Display vehicle details
    vehicleHeadingElement.innerHTML = currentVehicle.getHeading();
    vehicleSubheadingElement.innerHTML = currentVehicle.getSubheading();
    vehiclePriceElement.forEach(element => {
        element.innerHTML = currentVehicle.getFormattedPrice();
    });

    // Display vehicle images
    const carouselInnerElement = document.querySelector('.carousel-inner');
    const carouselIndicatorsElement = document.querySelector('.carousel-indicators');
    for (let i = 0; i <= currentVehicle.images.length; i++){
        const image = currentVehicle.images[i];
        const active = i === 0 ? 'active' : '';
        // Add carousel review video
        if (i === currentVehicle.images.length){
            carouselInnerElement.innerHTML += `
                <div class="carousel-item h-100 ${active}">
                    <iframe class="bd-placeholder-img card-img-top" width="100%" height="100%" src="${currentVehicle.review}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            `;
            // Add carousel indicators
            carouselIndicatorsElement.innerHTML += `
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="${i}" class="${active}" aria-label="Slide ${i + 1}" ${active ? 'aria-current="true"' : ''}></button>
            `;
        }
        else {
            // Add carousel images
            carouselInnerElement.innerHTML += `
            <div class="carousel-item h-100 ${active}">
                <img src="${image}" class="d-block w-100 h-100 object-fit-cover" alt="${currentVehicle.imageAlt}">
            </div>
        `;
            // Add carousel indicators
            carouselIndicatorsElement.innerHTML += `
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="${i}" class="${active}" aria-label="Slide ${i + 1}" ${active ? 'aria-current="true"' : ''}></button>
            `;
        }
    }
});
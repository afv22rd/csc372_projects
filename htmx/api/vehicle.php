<?php
/**
 * Vehicle Class
 * 
 * This class represents a vehicle with all its properties and methods.
 * It supports the data structure from vehicles.json and can be used with a Node.js/HTMX application.
 */

class Vehicle {
    // Private properties with type declarations
    private int $id;
    private string $make;
    private string $model;
    private int $year;
    private float $price;
    private array $images;
    private string $description;
    private array $features;
    private string $body_type;
    private int $mileage;
    private string $fuel_type;
    private string $color;
    private int $seating;
    private string $drivetrain;
    private string $transmission;
    private int $cylinders;

    /**
     * Constructor for the Vehicle class
     * 
     * @param int $id Vehicle ID
     * @param string $make Vehicle make
     * @param string $model Vehicle model
     * @param int $year Vehicle year
     * @param float $price Vehicle price
     * @param array $images Array of image URLs
     * @param string $description Vehicle description
     * @param array $features Array of vehicle features
     * @param string $body_type Vehicle body type
     * @param int $mileage Vehicle mileage
     * @param string $fuel_type Vehicle fuel type
     * @param string $color Vehicle color
     * @param int $seating Number of seats
     * @param string $drivetrain Vehicle drivetrain
     * @param string $transmission Vehicle transmission
     * @param int $cylinders Number of cylinders
     */
    public function __construct(
        int $id,
        string $make,
        string $model,
        int $year,
        float $price,
        array $images,
        string $description,
        array $features,
        string $body_type,
        int $mileage,
        string $fuel_type,
        string $color,
        int $seating,
        string $drivetrain,
        string $transmission,
        int $cylinders
    ) {
        $this->id = $id;
        $this->make = $make;
        $this->model = $model;
        $this->year = $year;
        $this->price = $price;
        $this->images = $images;
        $this->description = $description;
        $this->features = $features;
        $this->body_type = $body_type;
        $this->mileage = $mileage;
        $this->fuel_type = $fuel_type;
        $this->color = $color;
        $this->seating = $seating;
        $this->drivetrain = $drivetrain;
        $this->transmission = $transmission;
        $this->cylinders = $cylinders;
    }

    // Getter methods
    public function getId(): int {
        return $this->id;
    }

    public function getMake(): string {
        return $this->make;
    }

    public function getModel(): string {
        return $this->model;
    }

    public function getYear(): int {
        return $this->year;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getImages(): array {
        return $this->images;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getFeatures(): array {
        return $this->features;
    }

    public function getBodyType(): string {
        return $this->body_type;
    }

    public function getMileage(): int {
        return $this->mileage;
    }

    public function getFuelType(): string {
        return $this->fuel_type;
    }

    public function getColor(): string {
        return $this->color;
    }

    public function getSeating(): int {
        return $this->seating;
    }

    public function getDrivetrain(): string {
        return $this->drivetrain;
    }

    public function getTransmission(): string {
        return $this->transmission;
    }

    public function getCylinders(): int {
        return $this->cylinders;
    }

    // Setter methods
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setMake(string $make): void {
        $this->make = $make;
    }

    public function setModel(string $model): void {
        $this->model = $model;
    }

    public function setYear(int $year): void {
        $this->year = $year;
    }

    public function setPrice(float $price): void {
        $this->price = $price;
    }

    public function setImages(array $images): void {
        $this->images = $images;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setFeatures(array $features): void {
        $this->features = $features;
    }

    public function setBodyType(string $body_type): void {
        $this->body_type = $body_type;
    }

    public function setMileage(int $mileage): void {
        $this->mileage = $mileage;
    }

    public function setFuelType(string $fuel_type): void {
        $this->fuel_type = $fuel_type;
    }

    public function setColor(string $color): void {
        $this->color = $color;
    }

    public function setSeating(int $seating): void {
        $this->seating = $seating;
    }

    public function setDrivetrain(string $drivetrain): void {
        $this->drivetrain = $drivetrain;
    }

    public function setTransmission(string $transmission): void {
        $this->transmission = $transmission;
    }

    public function setCylinders(int $cylinders): void {
        $this->cylinders = $cylinders;
    }

    /**
     * Utility method to convert the vehicle to an array
     * 
     * @return array Array representation of the vehicle
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            'price' => $this->price,
            'images' => $this->images,
            'description' => $this->description,
            'features' => $this->features,
            'body_type' => $this->body_type,
            'mileage' => $this->mileage,
            'fuel_type' => $this->fuel_type,
            'color' => $this->color,
            'seating' => $this->seating,
            'drivetrain' => $this->drivetrain,
            'transmission' => $this->transmission,
            'cylinders' => $this->cylinders
        ];
    }

    /**
     * Utility method to convert the vehicle to JSON
     * 
     * @return string JSON representation of the vehicle
     */
    public function toJson(): string {
        return json_encode($this->toArray());
    }

    /**
     * Utility method to calculate estimated monthly payment
     * 
     * @param int $months Number of months for the loan (default: 60)
     * @param float $downPaymentPercentage Down payment percentage (default: 10%)
     * @return array Array with monthly payment and down payment
     */
    public function calculatePayments(int $months = 60, float $downPaymentPercentage = 0.10): array {
        $downPayment = $this->price * $downPaymentPercentage;
        $loanAmount = $this->price - $downPayment;
        $monthlyPayment = $loanAmount / $months;
        
        return [
            'down_payment' => $downPayment,
            'monthly_payment' => $monthlyPayment
        ];
    }

    /**
     * Utility method to check if the vehicle is a good value based on price and mileage
     * 
     * @return bool True if the vehicle is a good value, false otherwise
     */
    public function isGoodValue(): bool {
        // Simple algorithm: if price per mile is less than $0.50, it's a good value
        $pricePerMile = $this->price / $this->mileage;
        return $pricePerMile < 0.50;
    }
} 
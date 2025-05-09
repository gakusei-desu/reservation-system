<?php
require_once('./config/db.php');
require_once('./config/session.php');
require_once('./lib/pdo_db.php');
require_once('./models/Reservations.php');
require_once('./models/Customers.php');

// Filter and sanitize post array
$POST = filter_var_array($_POST, FILTER_SANITIZE_SPECIAL_CHARS);

$firstName = $POST['firstName'];
$lastName = $POST['lastName'];
$email = $POST['guestEmail'];
$reservedDate = $POST['rsDate'];
$reservedTime = $POST['rsTime'];
$partySize = $POST['rsPartySize'];
$occasion = $POST['rsOccasion'];

// Instantiate Reservation
$reservations = new Reservations();

// Validate
$errors = [];

if (empty($firstName)) {
    $errors['firstName'] = "Please enter your first name.";
}

if (empty($lastName)) {
    $errors['lastName'] = "Please enter your last name.";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['guestEmail'] = "Please enter a valid email address";
}

if (empty($reservedDate)) {
    $errors['rsDate'] = "Please select a reservation date.";
}

if (empty($reservedTime)) {
    $errors['rsTime'] = "Please select a reservation time.";
}

if (empty($partySize) || $partySize == "Party Size") {
    $errors['rsPartySize'] = "Please select your party size.";
}

if (empty($occasion) || $occasion == "Select an occasion") {
    $errors['rsOccasion'] = "Please select an occasion.";
}

// Check for duplicate reservations before proceeding
$duplicateReservation = $reservations->checkForDuplications($email, $reservedDate, $reservedTime);

if ($duplicateReservation == true) {
    $errors['duplicateReservations'] = "You have already made a reservation for this time and date.";
}

// if there are any validation errors, redirect back to the form
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("location: index.php");
    exit();
}

// If there are no validation errors, then generate the confirmation token and proceed.
$confirmationToken = $reservations->generateConfirmationToken();

// Reservation Data
$reservationData = [
    'firstName' => $firstName,
    'lastName' => $lastName,
    'email' => $email,
    'reservedDate' => $reservedDate,
    'reservedTime' => $reservedTime,
    'partySize' => $partySize,
    'occasion' => $occasion,
    'confirmation_token' => $confirmationToken
];

// Add reservation to db
$reservations->addReservation($reservationData);

// Instantiate Customers
$customers = new Customers();

$duplicateCustomer = $customers->checkForCustomerDuplications($firstName, $lastName, $email);

if ($duplicateCustomer == false) {
    // Customer Data
    $customerData = [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email
    ];

    // Add customer to db
    $customers->addCustomer($customerData);
}

// redirect when successful
header("location: success.php");
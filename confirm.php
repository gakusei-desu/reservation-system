<?php
    require_once('./config/db.php');
    require_once('./config/session.php');
    require_once('./lib/pdo_db.php');
    require_once('./models/Reservations.php');

    // Retrieve and sanitize the confirmation token from URL parameter
    $confirmationToken = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validate the confirmation token and get the reservation
    $reservations = new Reservations();
    $reservation = $reservations->getReservationByConfirmationToken($confirmationToken);

    if ($reservation->confirmation_token != $confirmationToken) {
        $confirmationResult = "The confirmation token is not valid";
        $reservationDetails = null;
    } else {
        $reservationId = $reservation->id;

        // Check if the reservation is already confirmed
        if ($reservation->confirmed == 0) {
            $reservations->confirmReservation($reservationId);
            $confirmationResult = "Reservation confirmed!";

            // Send reservation details email
            $email = $reservation->email;
            $subject = "Reservation Confirmation";
            $message = "Your reservation has been confirmed. Here are the details:\n\n";
            $message .= "Name: " . htmlspecialchars($reservation->firstName, ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($reservation->lastName, ENT_QUOTES, 'UTF-8') . "\n";
            $message .= "Reserved Date: " . htmlspecialchars($reservation->reservedDate, ENT_QUOTES, 'UTF-8') . "\n";
            $message .= "Reserved Time: " . htmlspecialchars($reservation->reservedTime, ENT_QUOTES, 'UTF-8') . "\n";

            $headers = "From: hello@atypicaltinkerer.dev";
            mail($email, $subject, $message, $headers);
        } else {
            $confirmationResult = "Reservation already confirmed!";
        }

        $reservationDetails = $reservation;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Reservation Confirmation</title>
</head>
<body>
    <div class="container mt-4">
        <h2 class="title"><?php echo htmlspecialchars($confirmationResult, ENT_QUOTES, 'UTF-8'); ?></h2>
        <div class="reservation-details">
            <?php if (empty($reservationDetails)) { ?>
                <p>Your reservation was not confirmed.</p>
            <?php } else { ?>
                <h3>Reservation Details</h3>
                <p>Name: 
                    <?php echo htmlspecialchars($reservationDetails->firstName, ENT_QUOTES, 'UTF-8'); ?> 
                    <?php echo htmlspecialchars($reservationDetails->lastName, ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <p>
                    Reserved Date: <?php echo htmlspecialchars($reservationDetails->reservedDate, ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <p>
                    Reserved Time: <?php echo htmlspecialchars($reservationDetails->reservedTime, ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <p>We've sent a copy of these details to the email address you provided.</p>
            <?php } ?>
        </div>
        <p><a href="index.php" class="btn btn-light mt-2">Return to the homepage</a></p>
    </div>
</body>
</html>

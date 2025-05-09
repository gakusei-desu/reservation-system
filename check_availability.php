<?php
    // Proceed with your includes and business logic
    require_once('./config/db.php');
    require_once('./config/session.php');
    require_once('./lib/pdo_db.php');
    require_once('./models/Reservations.php');

    // Always return JSON and prevent malformed output
    header('Content-Type: application/json');
    
    // Optional: Clear any accidental whitespace/output
    ob_clean();

    // 🔍 Add this to log incoming POST data for debugging
    error_log('POST DATA: ' . print_r($_POST, true));
    
    // Check if selectedDate is passed via POST
    $selectedDate = $_POST['selectedDate'] ?? null;
    
    if (!$selectedDate) {
        http_response_code(400); // Bad Request
        echo json_encode([
            'error' => 'Missing selectedDate',
            'selectedDate' => null,
            'reservedTimes' => []
        ]);
        exit;
    }
    
    // Instantiate reservation model
    $reservation = new Reservations();
    
    // Get reserved times for selected date
    $reservedTimes = $reservation->getReservedTimes($selectedDate);
    
    // Respond with the reserved times
    echo json_encode([
        'selectedDate' => $selectedDate,
        'reservedTimes' => $reservedTimes
    ]);
    exit;
    

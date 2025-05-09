<?php
    require_once('./config/db.php');
    require_once('./config/session.php');
    require_once('./lib/pdo_db.php');
    require_once('./models/Reservations.php');

    $reservation = new Reservations();

    $reservations = $reservation->getReservations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>View Reservations</title>
</head>
<body>
    <div class="container mt-4">
        <div class="btn-group" role="group">
            <a href="customers.php" class="btn btn-secondary">Customers</a>
            <a href="reservations.php" class="btn btn-primary">Reservations</a>
        </div>
        <hr>
        <h2>Reservations</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Reservation Date</th>
                    <th>Reservation Time</th>
                    <th>Party Size</th>
                    <th>Occasion</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($r->firstName); ?> <?php echo htmlspecialchars($r->lastName); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($r->email); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($r->reservedDate); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($r->reservedTime); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($r->partySize); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($r->occasion); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <p><a href="index.php">Back to Reservation Page</a></p>
    </div>
</body>
</html>
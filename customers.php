<?php
    require_once('./config/db.php');
    require_once('./config/session.php');
    require_once('./lib/pdo_db.php');
    require_once('./models/Customers.php');

    // Instantiate Customer
    $customer = new Customers();

    $customers = $customer->getCustomers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>View Customers</title>
</head>
<body>
    <div class="container mt-4">
        <div class="btn-group" role="group">
            <a href="customers.php" class="btn btn-primary">Customers</a>
            <a href="reservations.php" class="btn btn-secondary">Reservations</a>
        </div>
        <hr>
        <h2>Customers</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c->firstName); ?></td>
                        <td><?php echo htmlspecialchars($c->lastName); ?></td>
                        <td><?php echo htmlspecialchars($c->email); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <p><a href="index.php">Back To The Reservation Page</a></p>
    </div>
</body>
</html>
<?php
    require_once('./config/session.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">
    <title>Make a Reservation</title>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-MGT74Y9GFR"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-MGT74Y9GFR');
    </script>
</head>
<body>
    <div class="container col-xl-10 col-xxl-8 px-4 py-5">
        <div class="row align-items-center g-lg-5 py-5">
            <div class="col-md-10 col-lg-7 mx-auto text-center text-lg-start">
                <h1 class="display-3 fw-bold lh-1 text-body-emphasis text-center mb-4">Book a reservation</h1>
            </div>
        </div>
        <div class="row align-items-center g-lg-5 py-5">
            <div class="col-md-10 mx-auto col-lg-5">
                <?php
                    if (isset($_SESSION['errors'])) {
                        foreach ($_SESSION['errors'] as $error) {
                ?>
                    <div id="error-message" class="alert alert-danger bg-danger text-light" role="alert">
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php 
                        }

                        unset($_SESSION['errors']);
                    }
                ?>
                <form class="p-4 p-md-5 border rounded-0 bg-body-tertiary" action="./reserve.php" method="post" class="needs-validation" id="reservationForm" novalidate>
                    <div class="row g-sm-2 g-lg-2">
                        <div class="col-sm-12 col-md-6 form-floating mb-3">
                            <input type="text" name="firstName" id="firstName" class="form-control" required>
                            <label for="firstName">First Name</label>
                            <div class="invalid-feedback">Please enter your first name.</div>
                        </div>
                        <div class="col-sm-12 col-md-6 form-floating mb-3">
                            <input type="text" name="lastName" id="lastName" class="form-control" required>
                            <label for="lastName">Last Name</label>
                            <div class="invalid-feedback">Please enter your last name.</div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" name="guestEmail" id="guestEmail" class="form-control" required>
                        <label for="email">Email Address</label>
                        <div class="invalid-feedback">Please enter a valid email address</div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" name="rsDate" id="rsDate" placeholder="Reservation Date">
                        <label for="rsDate">Choose a date</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select name="rsTime" id="rsTime" class="form-select">
                            <option selected>Select a date</option>
                        </select>
                        <label for="rsTime">Available Times</label>
                    </div>
                    <div class="row g-2">
                        <div class="form-floating mb-3">
                            <select class="form-select" name="rsPartySize" id="rsPartySize">
                                <option selected></option>
                                <option value="1">One person</option>
                                <option value="2">Two people</option>
                                <option value="3">Three people</option>
                                <option value="4">Four people</option>
                                <option value="5">Five people</option>
                                <option value="6">Six people</option>
                                <option value="7">Seven people</option>
                                <option value="8">Eight people</option>
                                <option value="9">Nine people</option>
                                <option value="10">Ten people</option>
                            </select>
                            <label for="rsPartySize">How many guests?</label>
                            <div class="invalid-feedback">Please select your party size</div>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" name="rsOccasion" id="rsOccasion">
                                <option selected></option>
                                <option value="Birthday">Birthday</option>
                                <option value="Anniversary">Anniversary</option>
                                <option value="Date Night">Date Night</option>
                                <option value="Business">Business</option>
                                <option value="Celebration">Celebration</option>
                                <option value="Other">Other</option>
                            </select>
                            <label for="rsOccasion">What's the occasion?</label>
                            <div class="invalid-feedback">Please select an occasion</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center">
                            <button type="submit" class="btn btn-primary">Book Reservation</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="./js/scripts.js"></script>
</body>
</html>
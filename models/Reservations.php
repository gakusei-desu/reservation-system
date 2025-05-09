<?php
require_once('./config/session.php');

class Reservations {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function generateConfirmationToken () {
        $token = bin2hex(random_bytes(32));
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);

        return $hashedToken;
    }

    public function addReservation ($data) {
        $this->db->query('INSERT INTO reservations (firstName, lastName, email, reservedDate, reservedTime, partySize, occasion, confirmation_token, confirmed) VALUES (:firstName, :lastName, :email, :reservedDate, :reservedTime, :partySize, :occasion, :confirmationToken, 0)');

        $this->db->bind(':firstName', $data['firstName']);
        $this->db->bind(':lastName', $data['lastName']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':reservedDate', $data['reservedDate']);
        $this->db->bind(':reservedTime', $data['reservedTime']);
        $this->db->bind(':partySize', $data['partySize']);
        $this->db->bind(':occasion', $data['occasion']);
        $this->db->bind(':confirmationToken', $data['confirmation_token']);

        // Execute
        if ($this->db->execute()) {
            // Send confirmation email
            $this->sendConfirmationEmail($data['email'], $data['confirmation_token']);
            return true;
        } else {
            return false;
        }
    }

    public function getReservations () {
        $this->db->query('SELECT * FROM reservations WHERE confirmed = 1 ORDER BY created_at DESC');

        $results = $this->db->resultset();

        return $results;
    }

    public function updateReservation ($id, $data) {
        $this->db->query('UPDATE reservations SET firstName = :firstName, lastName = :lastName, email = :email, reservedDate = :reservedDate, reservedTime = :reservedTime, partySize = :partySize, occasion = :occasion WHERE id = :id');

        $this->db->bind(':id', $id);
        $this->db->bind(':firstName', $data['firstName']);
        $this->db->bind(':lastName', $data['lastName']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':reservedDate', $data['reservedDate']);
        $this->db->bind(':reservedTime', $data['reservedTime']);
        $this->db->bind(':partySize', $data['partySize']);
        $this->db->bind(':occasion', $data['occasion']);

        // execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getReservationByConfirmationToken ($confirmationToken) {
        $this->db->query('SELECT * FROM reservations WHERE confirmation_token = :confirmationToken');
        $this->db->bind(':confirmationToken', $confirmationToken);

        $reservation = $this->db->single();

        return $reservation;
    }

    public function confirmReservation ($reservationId) {
        $this->db->query('UPDATE reservations SET confirmed = 1 WHERE id = :id');
        $this->db->bind(':id', $reservationId);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteReservation ($id) {
        $this->db->query('DELETE FROM reservations WHERE id = :id');
        $this->db->bind(':id', $id);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteExpiredReservations ($timeLimit) {
        $this->db->query('DELETE FROM reservations WHERE confirmed = 0 AND created_at < :timeLimit');
        $this->db->bind(':timeLimit', date('Y-m-d H:i:s', $timeLimit));

        // Execute
        $this->db->execute();

        // Get the number of deleted rows
        $deletedCount = $this->db->rowCount();

        if ($deletedCount > 0) {
            $adminEmail = 'restaurant.admin@website.com';
            $subject = 'Expired Reservations Deleted';
            $message = "The cron job has deleted " . $deletedCount . " expired reservations(s)";
            $this->sendEmail($adminEmail, $subject, $message);
        }

        return $deletedCount;
    }

    public function getReservedTimes($selectedDate) {
        $this->db->query('SELECT reservedTime FROM reservations WHERE reservedDate = :selectedDate');
        $this->db->bind(':selectedDate', $selectedDate);

        $reservedTimes = $this->db->resultset();
        $times = array();

        foreach ($reservedTimes as $reservedTime) {
            $time = date('H:i', strtotime($reservedTime->reservedTime));
            $times[] = $time;
        }

        return $times;
    }

    public function checkForDuplications($email, $reservedDate, $reservedTime) {
        $this->db->query('SELECT email, reservedDate, reservedTime FROM reservations WHERE email = :email AND reservedDate = :reservedDate AND reservedTime = :reservedTime');
        $this->db->bind(':email', $email);
        $this->db->bind(':reservedDate', $reservedDate);
        $this->db->bind(':reservedTime', $reservedTime);

        $this->db->execute();
        $rows = $this->db->rowCount();

        $resultCheck;
        if ($rows > 0) {
            $resultCheck = true;
        } else {
            $resultCheck = false;
        }

        return $resultCheck;
    }

    private function sendConfirmationEmail($email, $confirmationToken) {
        $to = $email;
        $subject = "Confirm Your Reservation";
    
        // Set the headers to send an HTML email
        $headers = "From: hello@atypicaltinkerer.dev\r\n";
        $headers .= "Reply-To: hello@atypicaltinkerer.dev\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
        // Format the email message as HTML
        $message = "<html><body>";
        $message .= "<p>Hello,</p>";
        $message .= "<p>Please click on the following link to confirm your reservation:</p>";
        $message .= "<p><a href='https://atypicaltinkerer.dev/staging/reservations/confirm.php?token=" . $confirmationToken . "'>Click here to confirm your reservation.</a></p>";
        $message .= "<p>If you did not make this reservation, you can ignore this email.</p>";
        $message .= "</body></html>";
    
        // Send the email
        mail($to, $subject, $message, $headers);
    }



    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM reservations WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}

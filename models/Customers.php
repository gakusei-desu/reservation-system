<?php
require_once('./config/session.php');

class Customers {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addCustomer ($data) {
        $this->db->query('INSERT INTO customers (firstName, lastName, email) VALUES (:firstName, :lastName, :email)');

        $this->db->bind(':firstName', $data['firstName']);
        $this->db->bind(':lastName', $data['lastName']);
        $this->db->bind(':email', $data['email']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getCustomers() {
        $this->db->query('SELECT * FROM customers ORDER BY created_at DESC');

        $results = $this->db->resultset();

        return $results;
    }

    public function checkForCustomerDuplications($firstName, $lastName, $email) {
        $this->db->query('SELECT firstname, lastName, email FROM customers WHERE firstName = :firstName AND lastName = :lastName AND email = :email');
        $this->db->bind(':firstName', $firstName);
        $this->db->bind(':lastName', $lastName);
        $this->db->bind(':email', $email);

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
}
<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function register($data) {
        try {
            // Validate required fields
            $requiredFields = ['email', 'password', 'name', 'cpf', 'birth_date'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Field {$field} is required");
                }
            }

            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }

            // Validate CPF format (basic format check)
            if (!preg_match('/^\d{11}$/', $data['cpf'])) {
                throw new Exception("CPF must be 11 digits");
            }

            // Check if email already exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$data['email']]);
            if ($stmt->fetch()) {
                throw new Exception("Email already registered");
            }

            // Check if CPF already exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE cpf = ?");
            $stmt->execute([$data['cpf']]);
            if ($stmt->fetch()) {
                throw new Exception("CPF already registered");
            }

            $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("
                INSERT INTO users (name, email, password, cpf, birth_date) 
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $data['name'],
                $data['email'],
                $hashed,
                $data['cpf'],
                $data['birth_date']
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            throw new Exception("Registration failed");
        }
    }

    public function login($email, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                unset($user['password']); // Don't include password in session
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    public function resetPassword($cpf, $birthDate, $newPassword) {
        try {
            // First verify the user exists with given CPF and birth date
            $stmt = $this->db->prepare("SELECT id FROM users WHERE cpf = ? AND birth_date = ?");
            $stmt->execute([$cpf, $birthDate]);
            $user = $stmt->fetch();

            if (!$user) {
                return false;
            }

            // Update the password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
            return $updateStmt->execute([$hashedPassword, $user['id']]);

        } catch (PDOException $e) {
            error_log("Password reset failed: " . $e->getMessage());
            return false;
        }
    }
}
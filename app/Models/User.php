<?php

namespace App\Models;

use App\Core\Model;

/**
 * User Model
 */
class User extends Model {
    
    // Find user by ID
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    // Find user by username
    public function findByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        return $this->db->fetch($sql, ['username' => $username]);
    }

    // Find user by email
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        return $this->db->fetch($sql, ['email' => $email]);
    }

    // Get all users
    public function getAll() {
        $sql = "SELECT * FROM users ORDER BY id DESC";
        return $this->db->fetchAll($sql);
    }

    // Create a new user
    public function create($data) {
        $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
        
        $params = [
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role'     => $data['role'] ?? 'editor'
        ];

        return $this->db->query($sql, $params);
    }

    // Update user details
    public function update($id, $data) {
        // If password is changed, we update password as well
        if (!empty($data['password'])) {
            $sql = "UPDATE users SET username = :username, email = :email, password = :password, role = :role WHERE id = :id";
            $params = [
                'id'       => $id,
                'username' => $data['username'],
                'email'    => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'role'     => $data['role']
            ];
        } else {
            $sql = "UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id";
            $params = [
                'id'       => $id,
                'username' => $data['username'],
                'email'    => $data['email'],
                'role'     => $data['role']
            ];
        }

        return $this->db->query($sql, $params);
    }

    // Delete a user
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }

    // Authenticate user login
    public function authenticate($username, $password) {
        $user = $this->findByUsername($username);
        if (!$user) {
            $user = $this->findByEmail($username); // Allow logging in with email too
        }

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}

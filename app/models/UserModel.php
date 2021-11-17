<?php

namespace App\Model;

use App\Config\Middleware;
use PDO;

class UserModel extends Middleware
{
    // * Declare your properties here: 
    private $user_id;
    private $email;
    private $password;

    // * Define setters and getters for your properties:
    function setUserId($user_id) { $this->user_id = $user_id; }
    function getUserId() { return $this->user_id; }

    function setEmail($email) { $this->email = $email; }
    function getEmail() { return $this->email; }

    function setPassword($password) { $this->password = $password; }
    function getPassword() { return $this->password; }

    // * Define your methods below
    // User creation
    public function create($table)
    {

        $sql = "SELECT email FROM $table";
        $stmt = $this->db_conn->prepare($sql);
        $stmt->execute();
        $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (!in_array($this->email, $emails)) {
            $sql = "INSERT INTO $table (user_id, email, password)
            VALUES (:user_id, :email, :password)";
            $stmt = $this->db_conn->prepare($sql);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Get All Users
    public function readAll($table)
    {
        $sql = "SELECT user_id, email, user_created_at FROM $table";
        $stmt = $this->db_conn->prepare($sql);
        if ($stmt->execute()) {
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($users)) {
                for ($i = 0; $i < count($users); $i++) {
                    // Unset password
                    unset($users[$i]['password']);
                    // Format date for all this->resul to a readable format
                    $users[$i]['user_created_at'] = (date("M d Y, H:m A", strtotime($users[$i]['user_created_at'])));
                }
                return $users;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Get User
    public function readUnique($table)
    {
        $sql = "SELECT user_id, email, user_created_at FROM $table WHERE (user_id = :user_id)";
        $stmt = $this->db_conn->prepare($sql);
        $stmt->bindParam(':user_id', $this->user_id);
        if ($stmt->execute()) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (is_array($user)) {
                // Unset password
                unset($user['password']);
                // Format the date to a better readable format
                $user['user_created_at'] = (date("M d Y, H:m A", strtotime($user['user_created_at'])));
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Update User
    public function update($table)
    {
        $sql = "SELECT user_id FROM $table WHERE user_id = :user_id";
        $stmt = $this->db_conn->prepare($sql);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        $user_id = $stmt->fetch();

        if (!empty($user_id)) {
            $sql = "UPDATE $table SET email = :email, password = :password, user_updated_at = CURRENT_TIMESTAMP
        WHERE user_id = :user_id";
            $stmt = $this->db_conn->prepare($sql);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Delete user account
    public function delete($table)
    {
        $sql = "SELECT user_id FROM $table WHERE user_id = :user_id";
        $stmt = $this->db_conn->prepare($sql);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        $user_id = $stmt->fetch();

        if (!empty($user_id)) {
            $sql = "DELETE FROM $table WHERE user_id = :user_id";
            $stmt = $this->db_conn->prepare($sql);
            $stmt->bindParam(':user_id', $this->user_id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

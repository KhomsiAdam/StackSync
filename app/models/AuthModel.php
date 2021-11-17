<?php

namespace App\Model;

use App\Config\Middleware;
use PDO;

class AuthModel extends Middleware
{

    /** SQL SELECT method for verifying if user credentials are correct to sign in and allow token generation
     * @param string $email user email
     * @param string $email_col ENV variable of user email column in database
     * @param string $password user password
     * @param string $password_col ENV variable of user password column in database
     * @param string $table ENV variable of table name of users in database
     */
    public function sqlVerifyUser($email, $email_col, $password, $password_col, $table)
    {
        $sql = "SELECT * FROM $table WHERE $email_col = :email";
        $stmt = $this->db_conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($user)) {
            if (password_verify($password, $user[$password_col])) {
                unset($user['password']);
                return $user;
            }
        } else {
            return false;
        }
    }
}

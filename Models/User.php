<?php 

namespace Models ;
use PDO;
use Exception;
class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $created_at;
    private $updated_at;
    public function __construct($username = null, $email = null, $password = null) {
        $this->username = $username;
        $this->email = $email;
        if ($password) {
            $this->setPassword($password);
        }
    }

    public function getId() {
        return $this->id;
    }
    public function getUsername() {
        return $this->username;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getCreatedAt() {
        return $this->created_at;
    }
    public function getUpdatedAt() {
        return $this->updated_at;
    }

    public function setName($name) {
        $this->username = $name;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function logout() {
        unset($_SESSION['user_id']);
    }
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    public function startSession($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_email'] = $user['email'];
    }


    public function findByEmail($db , $email) {
        try {
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Error finding user: " . $e->getMessage();
        }
    }

    public function findById($db, $id) {
        try {
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                $this->id = $data['id'];
                $this->setName( $data['username']);
                $this->email = $data['email'];
                $this->password = $data['password_hash'];
                
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error finding user: " . $e->getMessage());
            return false;
        }
    }
    public function login($db , $email, $password) {
        try {
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password_hash'])) {
                return $user;
            }
            return false;
        } catch (Exception $e) {
            echo "Error logging in user: " . $e->getMessage();
        }
    }
    public function register($db , $user) {
        try {
            $stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$user->getUsername(), $user->getEmail(), $user->getPassword()]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            echo "Error registering user: " . $e->getMessage();
        }
       
    }
    public function update($db , $user) {
        try {
            $stmt = $db->prepare("UPDATE users SET username = ?, email = ?, password_hash = ? WHERE id = ?");
            $stmt->execute([$user->getUsername(), $user->getEmail(), $user->getPassword(), $user->getId()]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            echo "Error updating user: " . $e->getMessage();
        }

    }
    public function delete($db , $user) {
        try {
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user->getId()]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            echo "Error deleting user: " . $e->getMessage();
        }
    }

    public function __toArray() {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

}
<?php
namespace Controllers ;

use Models\User;
class UserController { 
    
    public function login($db) {
        $message = "";
        // 1. Pre-check: If already logged in, go home
        if (isset($_SESSION['user_id'])) {
            header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=home');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = ['type'=> 'error','message'=>"Format d'email invalide"];
                header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=login');
                exit;
            }

            require_once 'Models/User.php';
            $userModel = new User();
            
            if($userModel->login($db ?? null, $email, $password)) {
                // Authentification réussie
                $user = $userModel->login($db ?? null, $email, $password);
                $userModel->startSession($user);
                $message = ['type'=> 'success','message'=>"Connexion réussie !"];
                header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=home');
                exit;
            } else {
                $message = ['type'=> 'error','message'=>"Email ou mot de passe incorrect"];
                header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=login');
                exit;
            }
    
        }

        require_once 'views/user/login.php';
    }
    
    public function register($db) {

        // 1. Pre-check: If already logged in, go home
        if (isset($_SESSION['user_id'])) {
            header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=home');
            exit;
        }
        // Si formulaire soumis

        if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])&& $_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = ['type'=> 'error','message'=>"Format d'email invalide"];
                header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=register');
                exit;
            }

            require_once 'Models/User.php';
            $userModel = new User($name, $email, $password);
            
            if($userModel->register($db, $userModel)) {
                $message = ['type'=> 'success','message'=> 'Inscription réussie ! Vous pouvez maintenant vous connecter.'];
                header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=login');
                exit;
            } else {
                $message = ['type'=> 'error','message'=>"Erreur lors de l'inscription"];
                header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=register');
                exit;
            }
        }
        
        // Afficher le formulaire   
        require_once 'views/user/register.php';
    }

    public function logout() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=home');
            exit;
        }
        session_start();
        session_unset();
        session_destroy();
        $message = ['type'=> 'success','message'=>"Déconnexion réussie !"];
        header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=home');
        exit;
    }

    public function profil($db) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=home');
            exit;
        }
        $user = new User();
        $user->findById($db, $_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $user->setName($name);
                $user->setEmail($email);

                if (!empty($password)) {
                    $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
                }

                if ($user->update($db, $user)) {
                    $_SESSION['user_username'] = $name;
                    $_SESSION['user_email'] = $email;
                    $message = ['type'=> 'success','message'=> 'Profile updated successfully!'];
                    header('Location: index.php?page=profil&'.http_build_query($message));
                } else {
                    $message = ['type'=> 'error','message'=> 'Failed to update profile.'];
                    header('Location: index.php?page=profil&'.http_build_query($message));
                }
            } else {
                $message = ['type'=> 'success','message'=> 'Please provide a valid name and email.'];
                header('Location: index.php?page=profil&'.http_build_query($message));
            }
        }
        require_once 'Views/user/profile.php';
    }


}
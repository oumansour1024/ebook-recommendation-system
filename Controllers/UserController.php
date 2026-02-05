<?php
namespace Controllers ;

use Models\User;
class UserController { 
    public function login($db) {
        $message = "";

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = ['type'=> 'error','message'=>"Format d'email invalide"];
                header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=login&'.http_build_query($message));
                exit;
            }


            
            require_once 'Models/User.php';
            $userModel = new User();
            
            if($userModel->login($db ?? null, $email, $password)) {
                // Authentification réussie
                $user = $userModel->login($db ?? null, $email, $password);
                $userModel->startSession($user);
                $message = ['type'=> 'success','message'=>"Connexion réussie !"];
                header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=home&'.http_build_query($message));
                exit;
            } else {
                $message = ['type'=> 'error','message'=>"Email ou mot de passe incorrect"];
                header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=login&'.http_build_query($message));
                exit;
            }
    
        }
        
        // Afficher le formulaire

        require_once 'views/user/login.php';
    }
    
    public function register($db) {
        // Si formulaire soumis
        if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])&& $_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = ['type'=> 'error','message'=>"Format d'email invalide"];
                header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=register&'.http_build_query($message));
                exit;
            }

            require_once 'Models/User.php';
            $userModel = new User($name, $email, $password);
            
            if($userModel->register($db, $userModel)) {
                $message = ['type'=> 'success','message'=> 'Inscription réussie ! Vous pouvez maintenant vous connecter.'];
                header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=login&'.http_build_query($message));
                exit;
            } else {
                $message = ['type'=> 'error','message'=>"Erreur lors de l'inscription"];
                header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=register&'.http_build_query($message));
                exit;
            }
        }
        
        // Afficher le formulaire   
        require_once 'views/user/register.php';
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        $message = ['type'=> 'success','message'=>"Déconnexion réussie !"];
        header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/index.php?page=home&'.http_build_query($message));
        exit;
    }
}
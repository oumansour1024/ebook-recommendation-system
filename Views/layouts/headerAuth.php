<?php if (isset($_SESSION['user_id'])): ?>
    <header class="header">
        <nav class="navbar mr-2 ml-2">
            <div class="logo">
                <a href="index.php?page=home"><img class="logo-img" src="public/assets/images/logos/logo.1.0.0.png"></a>
            </div>
            
            <div class="profil-container" onclick=showProfil()>

                <div class="profil-data">
                    <span class="username"><?php echo htmlspecialchars($_SESSION['user_username'] ?? 'Utilisateur'); ?></span>
                    <span class="email"><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'email@exemple.com'); ?></span>
                </div>
                
                <div class="profil-img-wrapper">
                    
                    <a href="index.php?page=profil" class="profil-link">
                        <img src="<?php echo $_SESSION['user_pp'] ?? './public/assets/images/content/default-avatar.1.0.0.png'; ?>" alt="Profil" class="profil-img">
                        <label for="upload-photo" class="add-icon">+</label>
                    </a>
                </div>
                
                <div class="dropdown-menu">
                    <a href="index.php?page=profil">Mon Compte</a>
                    <hr>
                    <a href="index.php?page=logout" class="logout">Déconnexion</a>
                </div>
            </div>
        </nav>
    </header>
<?php endif; ?>
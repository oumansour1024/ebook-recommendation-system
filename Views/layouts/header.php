    <!-- Header -->
    <header class="header pl-2 pr-3">
        <nav class="navbar">
            <div class="logo">
                <a href="index.php?page=home"><img class="logo-img" src="public/assets/images/logos/logo.1.0.0.png"></a>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php?page=home" class="active">Accueil</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                     <li><a href="index.php?page=logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="index.php?page=login">Connexion</a></li>
                    <li><a href="index.php?page=register">Inscription</a></li>
                <?php endif; ?>
            </ul>
            <button class="hamburger" aria-label="Menu mobile">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>
    </header>
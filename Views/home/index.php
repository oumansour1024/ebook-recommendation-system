<div> 
    <h1>Bienvenue sur la page d'accueil</h1>
    <p>Ceci est le contenu de la page d'accueil.</p>
    <?php if (isset($_SESSION['user_username'])): ?>
        <p>Bonjour, <?php 
            echo htmlspecialchars($_SESSION['user_username']);
            echo htmlspecialchars($_SESSION['user_email']);
        ?> ! <a href="index.php?page=logout">Déconnexion</a></p>
    <?php else: ?>
        <p>Vous n'êtes pas connecté. <a href="index.php?page=login">Connectez-vous ici</a>.</p>
    <?php endif; ?>
</div>
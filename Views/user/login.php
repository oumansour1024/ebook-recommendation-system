<div class="container d-flex justify-content-center align-items-center h-100">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-3">
            <h1 class="text-center mb-3">Connexion</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=login" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label ">Email :</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Enter your email" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Mots de passe :</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary  w-100 mt-3 mb-2" >Se connecter</button>
            </form>
            <hr class="my-4">
            <p class="text-center text-muted">Pas de compte ? <a href="index.php?page=register" class="text-decoration-none">Inscrivez-vous</a></p>
        </div>
    </div>
</div>

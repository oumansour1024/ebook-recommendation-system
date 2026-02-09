<div class="container d-flex justify-content-center align-items-center h-100">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-3">
            <h1 class="text-center mb-3">Inscription</h1>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']."?page=register";?>">
                <div class="form-group">
                    <label for="fullname" class="form-label">Nom complet</label>
                    <input type="text" id="fullname" name="name" class="form-input" placeholder="Nom d'utilisateur"
                        required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Entrez votre email"
                        required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-input" required
                        placeholder="Entrez votre Mot de passe">
                </div>
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" required
                        placeholder="Confirmez votre mot de passe">
                    <i class="fa fa-exclamation-circle" style="font-size:14px; color: red; display: none;"
                        id="passwordMatchIcon"></i>
                    <span id="passwordMatchError" style="color: red;"></span>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100 mt-3 mb-2">S'inscrire</button>
            </form>
            <div class="text-center text-muted">
                Vous avez déjà un compte? <a href="index.php?page=login">Connectez-vous ici</a>
            </div>
        </div>
    </div>
</div>
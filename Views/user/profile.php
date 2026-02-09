<div class="profile-page">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar-section">
                <img src="<?php echo $_SESSION['user_pp'] ?? './public/assets/images/content/default-avatar.1.0.0.png'; ?>" alt="Avatar" id="preview-img">
                <label for="profile-upload" class="edit-btn">
                    <label for="profile-upload" class="add-icon">+</label>
                </label>
                <input type="file" id="profile-upload" hidden>
            </div>
            <h2><?php echo htmlspecialchars($_SESSION['user_username']); ?></h2>
        </div>

        <form class="profile-form" action="<?php echo $_SERVER['PHP_SELF']."?page=profil"?>" method='post'>
            <div class="input-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['user_username']); ?>">
            </div>
            <div class="input-group">
                <label>Adresse Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>">
            </div>
            <div class="input-group">
                <label>Nouveau mot de passe</label>
                <input type="password" name="passsword" placeholder="Laissez vide pour ne pas changer">
            </div>
            <button type="submit" class="save-btn">Enregistrer les modifications</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('profile-upload').addEventListener('change', function(e) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('preview-img').src = reader.result;
    }
    reader.readAsDataURL(e.target.files[0]);
});
</script>

<?php
// session.php must be required before including this file
?>
<header>
    <h1>Portfolio de la classe</h1>
    <div>
        <a href="index.php" class="headerButton">Accueil</a>
        <a href="eleves.php" class="headerButton">Élèves</a>
        <a href="esicad.php" class="headerButton">Conformité et Normes</a>
        <a href="contact.php" class="headerButton">Contact</a>
        <?php if (isLoggedIn()): ?>
            <?php if (userRole() === 'etudiant'): ?>
                <a href="mes-notes.php" class="headerButton">Mes Notes</a>
            <?php endif; ?>
            <?php if (userRole() === 'prof' || userRole() === 'admin'): ?>
                <a href="notes-classe.php" class="headerButton">Notes classe</a>
            <?php endif; ?>
            <?php if (userRole() === 'admin'): ?>
                <a href="admin.php" class="headerButton">Admin</a>
            <?php endif; ?>
            <a href="change-password.php" class="headerButton">Mot de passe</a>
            <a href="logout.php" class="headerButton">Déconnexion</a>
        <?php else: ?>
            <a href="login.php" class="headerButton">Connexion</a>
        <?php endif; ?>
    </div>
</header>

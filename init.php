<?php
require 'php.php';

$errors = [];

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS Utilisateur (
      id INT PRIMARY KEY AUTO_INCREMENT,
      nom VARCHAR(100) NOT NULL,
      prenom VARCHAR(100) NOT NULL,
      mail VARCHAR(255) UNIQUE NOT NULL,
      password_hash VARCHAR(255) NOT NULL,
      role ENUM('admin', 'prof', 'etudiant') NOT NULL DEFAULT 'etudiant',
      must_change_password TINYINT(1) NOT NULL DEFAULT 1,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    $col = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'Utilisateur' AND COLUMN_NAME = 'must_change_password'")->fetchColumn();
    if (!$col) {
        $pdo->exec("ALTER TABLE Utilisateur ADD COLUMN must_change_password TINYINT(1) NOT NULL DEFAULT 1");
    }
    echo "✅ Table Utilisateur OK<br>";
} catch (\PDOException $e) {
    $errors[] = "Utilisateur: " . $e->getMessage();
    echo "❌ Table Utilisateur : " . $e->getMessage() . "<br>";
}

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS Notes (
      id INT PRIMARY KEY AUTO_INCREMENT,
      etudiant_id INT NOT NULL,
      matiere VARCHAR(100) NOT NULL,
      note DECIMAL(4,2) NOT NULL,
      appreciation TEXT,
      date_saisie DATE NULL DEFAULT NULL,
      FOREIGN KEY (etudiant_id) REFERENCES Utilisateur(id) ON DELETE CASCADE
    )");
    echo "✅ Table Notes OK<br>";
} catch (\PDOException $e) {
    $errors[] = "Notes: " . $e->getMessage();
    echo "❌ Table Notes : " . $e->getMessage() . "<br>";
}

try {
    $pdo->exec("INSERT IGNORE INTO Utilisateur (nom, prenom, mail, password_hash, role) VALUES
    ('Dupont', 'Jean', 'jean.dupont@esicad.fr', SHA2('motdepasse123', 256), 'etudiant'),
    ('Martin', 'Sophie', 'sophie.martin@esicad.fr', SHA2('motdepasse123', 256), 'prof'),
    ('Admin', 'Root', 'admin@esicad.fr', SHA2('admin123', 256), 'admin'),
    ('ADAM', 'Dawit', 'dawit.adam@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant'),
    ('ALBOUY', 'Kevin', 'kevin.albouy@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant'),
    ('ATLAN', 'Illan', 'illan.atlan@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant'),
    ('BIKIE BOUBENGA', 'Elysee', 'elysee.bikieboubenga@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant'),
    ('DEVAUCHELLE', 'Elina', 'elina.devauchelle@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant'),
    ('FRUCHON', 'Romain', 'romain.fruchon@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant'),
    ('GABRIELLE', 'Baptiste', 'baptiste.gabrielle@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant'),
    ('GRECH', 'Matteo', 'matteo.grech@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant'),
    ('HUSTACHE', 'Jordan', 'jordan.hustache@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant'),
    ('IMBERT', 'Alexandre', 'alexandre.imbert@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant'),
    ('LACHEVRE', 'Corran', 'corran.lachevre@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant'),
    ('SOULIER', 'Rémi', 'remi.soulier@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant'),
    ('LAPORTE', 'Enzo', 'enzo.laporte@my-digital-school.org', SHA2('motdepasse123', 256), 'etudiant')");
    // Admin never needs to change password
    $pdo->exec("UPDATE Utilisateur SET must_change_password = 0 WHERE mail = 'admin@esicad.fr'");
    echo "✅ Utilisateurs insérés OK<br>";
} catch (\PDOException $e) {
    $errors[] = "Insert: " . $e->getMessage();
    echo "❌ Insert utilisateurs : " . $e->getMessage() . "<br>";
}

if (empty($errors)) {
    echo "<br><strong>Base de données initialisée avec succès !</strong>";
} else {
    echo "<br><strong>Terminé avec des erreurs.</strong>";
}

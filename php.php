<?php
$host    = getenv('MYSQLHOST');
$db      = getenv('MYSQLDATABASE');
$user    = getenv('MYSQLUSER');
$pass    = getenv('MYSQLPASSWORD');
$port    = getenv('MYSQLPORT') ?: '3306';
$charset = 'utf8mb4';

$dsn = "mysql:host=mysql.railway.internal;port=3306;dbname=$db;charset=$charset";$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}

// Init schema if tables don't exist
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
    $pdo->exec("CREATE TABLE IF NOT EXISTS Notes (
      id INT PRIMARY KEY AUTO_INCREMENT,
      etudiant_id INT NOT NULL,
      matiere VARCHAR(100) NOT NULL,
      note DECIMAL(4,2) NOT NULL,
      appreciation TEXT,
      date_saisie DATE NULL DEFAULT NULL,
      FOREIGN KEY (etudiant_id) REFERENCES Utilisateur(id) ON DELETE CASCADE
    )");
} catch (\PDOException $e) {
    // Tables may already exist — continue
}

// Add column to existing tables; fails silently if already present
try {
    $pdo->exec("ALTER TABLE Utilisateur ADD COLUMN must_change_password TINYINT(1) NOT NULL DEFAULT 1");
} catch (\PDOException $e) {
    // Duplicate column — already exists, ignore
}

// Seed default users
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
} catch (\PDOException $e) {
    // Seed failed — continue
}

// Admin never needs to change password
try {
    $pdo->exec("UPDATE Utilisateur SET must_change_password = 0 WHERE mail = 'admin@esicad.fr'");
} catch (\PDOException $e) {
    // continue
}
?>

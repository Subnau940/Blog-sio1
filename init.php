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
    try { $pdo->exec("ALTER TABLE Notes ADD UNIQUE KEY uq_etudiant_matiere (etudiant_id, matiere)"); } catch (\PDOException $e) {}
    echo "✅ Table Notes OK<br>";
} catch (\PDOException $e) {
    $errors[] = "Notes: " . $e->getMessage();
    echo "❌ Table Notes : " . $e->getMessage() . "<br>";
}

try {
    $pdo->exec("INSERT IGNORE INTO Utilisateur (nom, prenom, mail, password_hash, role, must_change_password) VALUES
    ('Martin',         'Sophie',    'sophie.martin@esicad.fr',                    SHA2('motdepasse123', 256), 'prof',     0),
    ('Admin',          'Root',      'admin@esicad.fr',                            SHA2('admin123',      256), 'admin',    0),
    ('ADAM',           'Dawit',     'dawit.adam@my-digital-school.org',           SHA2('changeme', 256),     'etudiant', 1),
    ('ALBOUY',         'Kevin',     'kevin.albouy@my-digital-school.org',         SHA2('changeme', 256),     'etudiant', 1),
    ('ATLAN',          'Illan',     'illan.atlan@my-digital-school.org',          SHA2('changeme', 256),     'etudiant', 1),
    ('BIKIE BOUBENGA', 'Elysee',    'elysee.bikieboubenga@my-digital-school.org', SHA2('changeme', 256),     'etudiant', 1),
    ('DEVAUCHELLE',    'Elina',     'elina.devauchelle@my-digital-school.org',    SHA2('changeme', 256),     'etudiant', 1),
    ('FRUCHON',        'Romain',    'romain.fruchon@my-digital-school.org',       SHA2('changeme', 256),     'etudiant', 1),
    ('GABRIELLE',      'Baptiste',  'baptiste.gabrielle@my-digital-school.org',   SHA2('changeme', 256),     'etudiant', 1),
    ('GRECH',          'Matteo',    'matteo.grech@my-digital-school.org',         SHA2('changeme', 256),     'etudiant', 1),
    ('HUSTACHE',       'Jordan',    'jordan.hustache@my-digital-school.org',      SHA2('changeme', 256),     'etudiant', 1),
    ('IMBERT',         'Alexandre', 'alexandre.imbert@my-digital-school.org',     SHA2('changeme', 256),     'etudiant', 1),
    ('LACHEVRE',       'Corran',    'corran.lachevre@my-digital-school.org',      SHA2('changeme', 256),     'etudiant', 1),
    ('SOULIER',        'Rémi',      'remi.soulier@my-digital-school.org',         SHA2('changeme', 256),     'etudiant', 1),
    ('LAPORTE',        'Enzo',      'enzo.laporte@my-digital-school.org',         SHA2('changeme', 256),     'etudiant', 1)");
    $pdo->exec("UPDATE Utilisateur SET must_change_password = 0 WHERE mail IN ('admin@esicad.fr', 'sophie.martin@esicad.fr')");
    echo "✅ Utilisateurs insérés OK<br>";
} catch (\PDOException $e) {
    $errors[] = "Insert users: " . $e->getMessage();
    echo "❌ Insert utilisateurs : " . $e->getMessage() . "<br>";
}

try {
    $notesData = [
        'dawit.adam@my-digital-school.org'           => ['Contrôle 1'=>16, 'Contrôle 2'=>18, 'TP'=>15, 'Projet'=>17],
        'kevin.albouy@my-digital-school.org'         => ['Contrôle 1'=>14, 'Contrôle 2'=>13, 'TP'=>12, 'Projet'=>15],
        'illan.atlan@my-digital-school.org'          => ['Contrôle 1'=>17, 'Contrôle 2'=>16, 'TP'=>18, 'Projet'=>19],
        'elysee.bikieboubenga@my-digital-school.org' => ['Contrôle 1'=>15, 'Contrôle 2'=>14, 'TP'=>13, 'Projet'=>16],
        'elina.devauchelle@my-digital-school.org'    => ['Contrôle 1'=>18, 'Contrôle 2'=>17, 'TP'=>16, 'Projet'=>19],
        'romain.fruchon@my-digital-school.org'       => ['Contrôle 1'=>12, 'Contrôle 2'=>13, 'TP'=>14, 'Projet'=>15],
        'baptiste.gabrielle@my-digital-school.org'   => ['Contrôle 1'=>16, 'Contrôle 2'=>15, 'TP'=>17, 'Projet'=>18],
        'matteo.grech@my-digital-school.org'         => ['Contrôle 1'=>19, 'Contrôle 2'=>18, 'TP'=>17, 'Projet'=>20],
        'jordan.hustache@my-digital-school.org'      => ['Contrôle 1'=>13, 'Contrôle 2'=>12, 'TP'=>14, 'Projet'=>15],
        'alexandre.imbert@my-digital-school.org'     => ['Contrôle 1'=>17, 'Contrôle 2'=>16, 'TP'=>15, 'Projet'=>18],
        'corran.lachevre@my-digital-school.org'      => ['Contrôle 1'=>14, 'Contrôle 2'=>15, 'TP'=>16, 'Projet'=>13],
        'remi.soulier@my-digital-school.org'         => ['Contrôle 1'=>16, 'Contrôle 2'=>17, 'TP'=>15, 'Projet'=>18],
        'enzo.laporte@my-digital-school.org'         => ['Contrôle 1'=>18, 'Contrôle 2'=>19, 'TP'=>17, 'Projet'=>20],
    ];
    $stmtNote = $pdo->prepare(
        'INSERT IGNORE INTO Notes (etudiant_id, matiere, note)
         SELECT u.id, ?, ? FROM Utilisateur u WHERE u.mail = ?'
    );
    foreach ($notesData as $mail => $matieres) {
        foreach ($matieres as $matiere => $note) {
            $stmtNote->execute([$matiere, $note, $mail]);
        }
    }
    echo "✅ Notes insérées OK<br>";
} catch (\PDOException $e) {
    $errors[] = "Insert notes: " . $e->getMessage();
    echo "❌ Insert notes : " . $e->getMessage() . "<br>";
}

if (empty($errors)) {
    echo "<br><strong>Base de données initialisée avec succès !</strong>";
} else {
    echo "<br><strong>Terminé avec des erreurs.</strong>";
}

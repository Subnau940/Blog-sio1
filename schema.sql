CREATE TABLE IF NOT EXISTS Utilisateur (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  mail VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin', 'prof', 'etudiant') NOT NULL DEFAULT 'etudiant',
  must_change_password TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO Utilisateur (nom, prenom, mail, password_hash, role) VALUES
('Dupont', 'Jean', 'jean.dupont@esicad.fr', SHA2('motdepasse123', 256), 'etudiant'),
('Martin', 'Sophie', 'sophie.martin@esicad.fr', SHA2('motdepasse123', 256), 'prof'),
('Admin', 'Root', 'admin@esicad.fr', SHA2('admin123', 256), 'admin');

CREATE TABLE IF NOT EXISTS Notes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  matiere VARCHAR(100) NOT NULL,
  note DECIMAL(4,2) NOT NULL,
  appreciation TEXT,
  date_saisie DATE DEFAULT (CURRENT_DATE),
  FOREIGN KEY (etudiant_id) REFERENCES Utilisateur(id) ON DELETE CASCADE
);

INSERT IGNORE INTO Notes (etudiant_id, matiere, note, appreciation) VALUES
(1, 'Développement Web', 15.5, 'Bon travail, bonne compréhension des concepts.'),
(1, 'Réseaux', 12.0, 'Des efforts à fournir sur la partie configuration.'),
(1, 'Base de données', 17.0, 'Excellent, très bonne maîtrise de SQL.');

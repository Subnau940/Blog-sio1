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

INSERT IGNORE INTO Utilisateur (nom, prenom, mail, password_hash, role, must_change_password) VALUES
('Martin', 'Sophie', 'sophie.martin@esicad.fr', SHA2('motdepasse123', 256), 'prof', 0),
('Admin', 'Root', 'admin@esicad.fr', SHA2('admin123', 256), 'admin', 0),
('ADAM', 'Dawit', 'dawit.adam@esicad.fr', SHA2('changeme', 256), 'etudiant', 1),
('ALBOUY', 'Kevin', 'kevin.albouy@esicad.fr', SHA2('changeme', 256), 'etudiant', 1),
('ATLAN', 'Illan', 'illan.atlan@esicad.fr', SHA2('changeme', 256), 'etudiant', 1),
('BIKIE BOUBENGA', 'Elysee', 'elysee.bikie-boubenga@esicad.fr', SHA2('changeme', 256), 'etudiant', 1),
('DEVAUCHELLE', 'Elina', 'elina.devauchelle@esicad.fr', SHA2('changeme', 256), 'etudiant', 1),
('FRUCHON', 'Romain', 'romain.fruchon@esicad.fr', SHA2('changeme', 256), 'etudiant', 1),
('GABRIELLE', 'Baptiste', 'baptiste.gabrielle@esicad.fr', SHA2('changeme', 256), 'etudiant', 1),
('GRECH', 'Matteo', 'matteo.grech@esicad.fr', SHA2('changeme', 256), 'etudiant', 1),
('HUSTACHE', 'Jordan', 'jordan.hustache@esicad.fr', SHA2('changeme', 256), 'etudiant', 1),
('IMBERT', 'Alexandre', 'alexandre.imbert@esicad.fr', SHA2('changeme', 256), 'etudiant', 1),
('LACHEVRE', 'Corran', 'corran.lachevre@esicad.fr', SHA2('changeme', 256), 'etudiant', 1),
('SOULIER', 'Remi', 'remi.soulier@esicad.fr', SHA2('changeme', 256), 'etudiant', 1),
('LAPORTE', 'Enzo', 'enzo.laporte@esicad.fr', SHA2('changeme', 256), 'etudiant', 1);

CREATE TABLE IF NOT EXISTS Notes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  matiere VARCHAR(100) NOT NULL,
  note DECIMAL(4,2) NOT NULL,
  appreciation TEXT,
  date_saisie DATE DEFAULT (CURRENT_DATE),
  FOREIGN KEY (etudiant_id) REFERENCES Utilisateur(id) ON DELETE CASCADE,
  UNIQUE KEY uq_etudiant_matiere (etudiant_id, matiere)
);

-- ADAM Dawit
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 16 FROM Utilisateur WHERE mail = 'dawit.adam@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 18 FROM Utilisateur WHERE mail = 'dawit.adam@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 15 FROM Utilisateur WHERE mail = 'dawit.adam@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 17 FROM Utilisateur WHERE mail = 'dawit.adam@esicad.fr';

-- ALBOUY Kevin
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 14 FROM Utilisateur WHERE mail = 'kevin.albouy@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 13 FROM Utilisateur WHERE mail = 'kevin.albouy@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 12 FROM Utilisateur WHERE mail = 'kevin.albouy@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 15 FROM Utilisateur WHERE mail = 'kevin.albouy@esicad.fr';

-- ATLAN Illan
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 17 FROM Utilisateur WHERE mail = 'illan.atlan@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 16 FROM Utilisateur WHERE mail = 'illan.atlan@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 18 FROM Utilisateur WHERE mail = 'illan.atlan@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 19 FROM Utilisateur WHERE mail = 'illan.atlan@esicad.fr';

-- BIKIE BOUBENGA Elysee
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 15 FROM Utilisateur WHERE mail = 'elysee.bikie-boubenga@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 14 FROM Utilisateur WHERE mail = 'elysee.bikie-boubenga@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 13 FROM Utilisateur WHERE mail = 'elysee.bikie-boubenga@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 16 FROM Utilisateur WHERE mail = 'elysee.bikie-boubenga@esicad.fr';

-- DEVAUCHELLE Elina
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 18 FROM Utilisateur WHERE mail = 'elina.devauchelle@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 17 FROM Utilisateur WHERE mail = 'elina.devauchelle@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 16 FROM Utilisateur WHERE mail = 'elina.devauchelle@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 19 FROM Utilisateur WHERE mail = 'elina.devauchelle@esicad.fr';

-- FRUCHON Romain
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 12 FROM Utilisateur WHERE mail = 'romain.fruchon@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 13 FROM Utilisateur WHERE mail = 'romain.fruchon@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 14 FROM Utilisateur WHERE mail = 'romain.fruchon@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 15 FROM Utilisateur WHERE mail = 'romain.fruchon@esicad.fr';

-- GABRIELLE Baptiste
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 16 FROM Utilisateur WHERE mail = 'baptiste.gabrielle@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 15 FROM Utilisateur WHERE mail = 'baptiste.gabrielle@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 17 FROM Utilisateur WHERE mail = 'baptiste.gabrielle@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 18 FROM Utilisateur WHERE mail = 'baptiste.gabrielle@esicad.fr';

-- GRECH Matteo
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 19 FROM Utilisateur WHERE mail = 'matteo.grech@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 18 FROM Utilisateur WHERE mail = 'matteo.grech@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 17 FROM Utilisateur WHERE mail = 'matteo.grech@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 20 FROM Utilisateur WHERE mail = 'matteo.grech@esicad.fr';

-- HUSTACHE Jordan
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 13 FROM Utilisateur WHERE mail = 'jordan.hustache@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 12 FROM Utilisateur WHERE mail = 'jordan.hustache@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 14 FROM Utilisateur WHERE mail = 'jordan.hustache@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 15 FROM Utilisateur WHERE mail = 'jordan.hustache@esicad.fr';

-- IMBERT Alexandre
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 17 FROM Utilisateur WHERE mail = 'alexandre.imbert@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 16 FROM Utilisateur WHERE mail = 'alexandre.imbert@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 15 FROM Utilisateur WHERE mail = 'alexandre.imbert@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 18 FROM Utilisateur WHERE mail = 'alexandre.imbert@esicad.fr';

-- LACHEVRE Corran
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 14 FROM Utilisateur WHERE mail = 'corran.lachevre@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 15 FROM Utilisateur WHERE mail = 'corran.lachevre@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 16 FROM Utilisateur WHERE mail = 'corran.lachevre@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 13 FROM Utilisateur WHERE mail = 'corran.lachevre@esicad.fr';

-- SOULIER Remi
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 16 FROM Utilisateur WHERE mail = 'remi.soulier@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 17 FROM Utilisateur WHERE mail = 'remi.soulier@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 15 FROM Utilisateur WHERE mail = 'remi.soulier@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 18 FROM Utilisateur WHERE mail = 'remi.soulier@esicad.fr';

-- LAPORTE Enzo
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 1', 18 FROM Utilisateur WHERE mail = 'enzo.laporte@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Contrôle 2', 19 FROM Utilisateur WHERE mail = 'enzo.laporte@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'TP', 17 FROM Utilisateur WHERE mail = 'enzo.laporte@esicad.fr';
INSERT IGNORE INTO Notes (etudiant_id, matiere, note) SELECT id, 'Projet', 20 FROM Utilisateur WHERE mail = 'enzo.laporte@esicad.fr';

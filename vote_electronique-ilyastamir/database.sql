-- ============================================
-- Base de données : vote_electronique_db
-- Système de Vote Électronique
-- ============================================

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS vote_electronique_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE vote_electronique_db;

-- ============================================
-- Table : candidats
-- ============================================
CREATE TABLE IF NOT EXISTS candidats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    photo VARCHAR(255) NOT NULL DEFAULT 'images/default.png',
    programme TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table : votes
-- ============================================
CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_etudiant VARCHAR(20) NOT NULL,
    id_candidat INT NOT NULL,
    date_vote DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_etudiant (id_etudiant),
    FOREIGN KEY (id_candidat) REFERENCES candidats(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Insertion des candidats de test
-- ============================================
INSERT INTO candidats (nom, photo, programme) VALUES
(
    'Ahmed BENALI',
    'images/candidat1.png',
    'Mon programme pour la promotion :\n- Amélioration de la communication entre étudiants et administration.\n- Organisation d\'ateliers de renforcement en programmation.\n- Mise en place d\'un système de tutorat entre étudiants.'
),
(
    'Sara MOUSSAOUI',
    'images/candidat2.png',
    'Mes engagements en tant que déléguée :\n- Représentation effective des étudiants auprès de la direction.\n- Création d\'un espace de partage de ressources pédagogiques.\n- Organisation d\'événements culturels et sportifs.'
),
(
    'Youssef EL AMRANI',
    'images/candidat3.png',
    'Mon plan d\'action pour l\'année :\n- Modernisation des infrastructures informatiques de l\'établissement.\n- Mise en place de partenariats avec des entreprises pour les stages.\n- Création d\'un club de développement web.'
);
-- =============================================================
-- Touche Pas au Klaxon — Script de création de la base de données
-- =============================================================

CREATE DATABASE IF NOT EXISTS klaxon
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE klaxon;

-- -------------------------------------------------------------
-- Table : agences
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS agences (
    id_agence   INT          NOT NULL AUTO_INCREMENT,
    nom         VARCHAR(100) NOT NULL,
    CONSTRAINT pk_agences PRIMARY KEY (id_agence)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table : users
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id_user     INT          NOT NULL AUTO_INCREMENT,
    nom         VARCHAR(100) NOT NULL,
    prenom      VARCHAR(100) NOT NULL,
    telephone   VARCHAR(15)  NOT NULL,
    email       VARCHAR(150) NOT NULL,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    CONSTRAINT pk_users    PRIMARY KEY (id_user),
    CONSTRAINT uq_email    UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table : trajets
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS trajets (
    id_trajet           INT      NOT NULL AUTO_INCREMENT,
    gdh_depart          DATETIME NOT NULL,
    gdh_arrivee         DATETIME NOT NULL,
    places_total        INT      NOT NULL,
    places_dispo        INT      NOT NULL,
    id_agence_depart    INT      NOT NULL,
    id_agence_arrivee   INT      NOT NULL,
    id_user             INT      NOT NULL,
    CONSTRAINT pk_trajets           PRIMARY KEY (id_trajet),
    CONSTRAINT fk_trajet_depart     FOREIGN KEY (id_agence_depart)
        REFERENCES agences(id_agence) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_trajet_arrivee    FOREIGN KEY (id_agence_arrivee)
        REFERENCES agences(id_agence) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_trajet_user       FOREIGN KEY (id_user)
        REFERENCES users(id_user) ON UPDATE CASCADE ON DELETE RESTRICT,
    -- NOTE MySQL 8 : les colonnes FK ne peuvent pas être dans un CHECK comparatif
    -- La règle depart <> arrivee est validée côté PHP (TrajetController)
    CONSTRAINT chk_dates            CHECK (gdh_arrivee > gdh_depart),
    CONSTRAINT chk_places_dispo     CHECK (places_dispo >= 0 AND places_dispo <= places_total)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

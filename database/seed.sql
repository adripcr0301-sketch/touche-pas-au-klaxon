-- =============================================================
-- Touche Pas au Klaxon — Jeu d'essais
-- =============================================================
-- Mots de passe :
--   Admin  : admin@klaxon.fr   / Admin1234!
--   Users  : (email du user)   / User1234!
-- =============================================================

USE klaxon;

-- -------------------------------------------------------------
-- Agences (12 villes — données fournies en annexe)
-- -------------------------------------------------------------
INSERT INTO agences (nom) VALUES
    ('Paris'),
    ('Lyon'),
    ('Marseille'),
    ('Toulouse'),
    ('Nice'),
    ('Nantes'),
    ('Strasbourg'),
    ('Montpellier'),
    ('Bordeaux'),
    ('Lille'),
    ('Rennes'),
    ('Reims');

-- -------------------------------------------------------------
-- Users (20 employés extraits du système RH + 1 administrateur)
-- Hash bcrypt de 'User1234!'  : $2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa
-- Hash bcrypt de 'Admin1234!' : $2b$10$lKuGm0QlHd/bhnNsRNRJ.uHJfNEZzAHibgTtAajhKq6teEjSzEkxG
-- -------------------------------------------------------------
INSERT INTO users (nom, prenom, telephone, email, password, role) VALUES
    ('Martin',    'Alexandre', '0612345678', 'alexandre.martin@email.fr',  '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Dubois',    'Sophie',    '0698765432', 'sophie.dubois@email.fr',     '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Bernard',   'Julien',    '0622446688', 'julien.bernard@email.fr',    '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Moreau',    'Camille',   '0611223344', 'camille.moreau@email.fr',    '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Lefèvre',   'Lucie',     '0777889900', 'lucie.lefevre@email.fr',     '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Leroy',     'Thomas',    '0655443322', 'thomas.leroy@email.fr',      '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Roux',      'Chloé',     '0633221199', 'chloe.roux@email.fr',        '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Petit',     'Maxime',    '0766778899', 'maxime.petit@email.fr',      '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Garnier',   'Laura',     '0688776655', 'laura.garnier@email.fr',     '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Dupuis',    'Antoine',   '0744556677', 'antoine.dupuis@email.fr',    '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Lefebvre',  'Emma',      '0699887766', 'emma.lefebvre@email.fr',     '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Fontaine',  'Louis',     '0655667788', 'louis.fontaine@email.fr',    '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Chevalier', 'Clara',     '0788990011', 'clara.chevalier@email.fr',   '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Robin',     'Nicolas',   '0644332211', 'nicolas.robin@email.fr',     '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Gauthier',  'Marine',    '0677889922', 'marine.gauthier@email.fr',   '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Fournier',  'Pierre',    '0722334455', 'pierre.fournier@email.fr',   '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Girard',    'Sarah',     '0688665544', 'sarah.girard@email.fr',      '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Lambert',   'Hugo',      '0611223366', 'hugo.lambert@email.fr',      '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Masson',    'Julie',     '0733445566', 'julie.masson@email.fr',      '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    ('Henry',     'Arthur',    '0666554433', 'arthur.henry@email.fr',      '$2b$10$YLa5.jvWVJwsV60Wd1sPuuzHnkGk9wBS7OpOz9kPxZpV707WnCpBa', 'user'),
    -- Compte administrateur
    ('Admin',     'Système',   '0100000000', 'admin@klaxon.fr',            '$2b$10$lKuGm0QlHd/bhnNsRNRJ.uHJfNEZzAHibgTtAajhKq6teEjSzEkxG', 'admin');

-- -------------------------------------------------------------
-- Trajets
-- Agences : Paris=1 Lyon=2 Marseille=3 Toulouse=4 Nice=5
--           Nantes=6 Strasbourg=7 Montpellier=8 Bordeaux=9
--           Lille=10 Rennes=11 Reims=12
--
-- Cas de test couverts :
--   - Trajets avec places dispo (visibles visiteur)
--   - Trajet complet (places_dispo=0, non visible visiteur)
--   - Trajet passé (non visible visiteur)
-- -------------------------------------------------------------
-- Conversion en heures pour éviter INTERVAL x DAY + INTERVAL y HOUR (non supporté MySQL)
-- départ  | arrivée  | total_h = jours*24 + heures_trajet
INSERT INTO trajets (gdh_depart, gdh_arrivee, places_total, places_dispo, id_agence_depart, id_agence_arrivee, id_user) VALUES
    -- Trajets futurs avec places disponibles (visibles)
    (DATE_ADD(NOW(), INTERVAL 24  HOUR), DATE_ADD(NOW(), INTERVAL 27  HOUR), 4, 3, 1,  2,  1),  -- Paris → Lyon       (Alexandre)  +1j / +1j3h
    (DATE_ADD(NOW(), INTERVAL 48  HOUR), DATE_ADD(NOW(), INTERVAL 52  HOUR), 3, 2, 2,  3,  2),  -- Lyon → Marseille   (Sophie)     +2j / +2j4h
    (DATE_ADD(NOW(), INTERVAL 72  HOUR), DATE_ADD(NOW(), INTERVAL 78  HOUR), 5, 4, 1,  9,  3),  -- Paris → Bordeaux   (Julien)     +3j / +3j6h
    (DATE_ADD(NOW(), INTERVAL 120 HOUR), DATE_ADD(NOW(), INTERVAL 125 HOUR), 3, 1, 7,  1,  4),  -- Strasbourg → Paris (Camille)    +5j / +5j5h
    (DATE_ADD(NOW(), INTERVAL 168 HOUR), DATE_ADD(NOW(), INTERVAL 172 HOUR), 6, 5, 6,  1,  5),  -- Nantes → Paris     (Lucie)      +7j / +7j4h
    (DATE_ADD(NOW(), INTERVAL 240 HOUR), DATE_ADD(NOW(), INTERVAL 242 HOUR), 4, 3, 9,  4,  6),  -- Bordeaux → Toulouse(Thomas)    +10j / +10j2h
    (DATE_ADD(NOW(), INTERVAL 336 HOUR), DATE_ADD(NOW(), INTERVAL 339 HOUR), 3, 2, 10, 2,  7),  -- Lille → Lyon       (Chloé)     +14j / +14j3h
    (DATE_ADD(NOW(), INTERVAL 480 HOUR), DATE_ADD(NOW(), INTERVAL 482 HOUR), 4, 4, 5,  3,  8),  -- Nice → Marseille   (Maxime)    +20j / +20j2h
    (DATE_ADD(NOW(), INTERVAL 720 HOUR), DATE_ADD(NOW(), INTERVAL 727 HOUR), 5, 2, 5,  1,  9),  -- Nice → Paris       (Laura)     +30j / +30j7h
    (DATE_ADD(NOW(), INTERVAL 1080 HOUR),DATE_ADD(NOW(), INTERVAL 1083 HOUR),3, 3, 12, 2,  10), -- Reims → Lyon       (Antoine)   +45j / +45j3h
    -- Trajet complet (places_dispo=0 — NON visible en page d'accueil)
    (DATE_ADD(NOW(), INTERVAL 96  HOUR), DATE_ADD(NOW(), INTERVAL 98  HOUR), 2, 0, 11, 6,  11), -- Rennes → Nantes complet (Emma)  +4j / +4j2h
    -- Trajet passé (NON visible en page d'accueil) — départ -48h, arrivée -45h
    (DATE_SUB(NOW(), INTERVAL 48  HOUR), DATE_SUB(NOW(), INTERVAL 45  HOUR), 4, 2, 3,  5,  12); -- Marseille → Nice passé (Louis)

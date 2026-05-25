# MLD — Touche Pas au Klaxon

## Règles de dérivation appliquées

- Association **(1,1) — (0,n)** → la clé étrangère migre dans l'entité côté **(1,1)**
- TRAJET reçoit donc les FK des deux associations AGENCE et celle de USER

---

## Modèle Logique des Données

```
agences (
    id_agence   INT          PK
    nom         VARCHAR(100) NOT NULL
)

users (
    id_user     INT          PK
    nom         VARCHAR(100) NOT NULL
    prenom      VARCHAR(100) NOT NULL
    telephone   VARCHAR(15)  NOT NULL
    email       VARCHAR(150) NOT NULL UNIQUE
    password    VARCHAR(255) NOT NULL
    role        ENUM('user','admin') NOT NULL DEFAULT 'user'
)

trajets (
    id_trajet           INT       PK
    gdh_depart          DATETIME  NOT NULL
    gdh_arrivee         DATETIME  NOT NULL
    places_total        INT       NOT NULL
    places_dispo        INT       NOT NULL
    #id_agence_depart   INT       FK → agences(id_agence)
    #id_agence_arrivee  INT       FK → agences(id_agence)
    #id_user            INT       FK → users(id_user)
)
```

---

## Contraintes d'intégrité fonctionnelle

| Règle | Description |
|-------|-------------|
| R1 | `id_agence_depart` ≠ `id_agence_arrivee` — un trajet ne peut pas avoir la même agence au départ et à l'arrivée |
| R2 | `gdh_arrivee` > `gdh_depart` — on ne peut pas arriver avant de partir |
| R3 | `places_dispo` ≤ `places_total` — les places disponibles ne peuvent pas dépasser le total |
| R4 | `places_dispo` ≥ 0 — pas de places négatives |
| R5 | `role` ∈ {'user', 'admin'} — seuls ces deux rôles sont autorisés |

# MCD — Touche Pas au Klaxon

## Entités

### AGENCE
| Attribut   | Type        | Contrainte  |
|------------|-------------|-------------|
| #id_agence | INT         | PK, AUTO_INC|
| nom        | VARCHAR(100)| NOT NULL    |

---

### USER
| Attribut   | Type         | Contrainte       |
|------------|--------------|------------------|
| #id_user   | INT          | PK, AUTO_INC     |
| nom        | VARCHAR(100) | NOT NULL         |
| prenom     | VARCHAR(100) | NOT NULL         |
| telephone  | VARCHAR(15)  | NOT NULL         |
| email      | VARCHAR(150) | NOT NULL, UNIQUE |
| password   | VARCHAR(255) | NOT NULL         |
| role       | ENUM         | 'user','admin'   |

---

### TRAJET
| Attribut      | Type     | Contrainte  |
|---------------|----------|-------------|
| #id_trajet    | INT      | PK, AUTO_INC|
| gdh_depart    | DATETIME | NOT NULL    |
| gdh_arrivee   | DATETIME | NOT NULL    |
| places_total  | INT      | NOT NULL    |
| places_dispo  | INT      | NOT NULL    |

---

## Relations

```
AGENCE ──(0,n)──[ DEPART_DE ]──(1,1)── TRAJET ──(1,1)──[ ARRIVE_A ]──(0,n)── AGENCE

                                           │
                                        (1,1)
                                           │
                                      [ PROPOSE ]
                                           │
                                        (0,n)
                                           │
                                          USER
```

### Détail des associations

| Association | Entité 1 | Card. 1 | Entité 2 | Card. 2 | Signification |
|-------------|----------|---------|----------|---------|---------------|
| DEPART_DE   | AGENCE   | (0,n)   | TRAJET   | (1,1)   | Un trajet part d'exactement 1 agence. Une agence peut être le départ de 0 à n trajets. |
| ARRIVE_A    | AGENCE   | (0,n)   | TRAJET   | (1,1)   | Un trajet arrive à exactement 1 agence. Une agence peut être la destination de 0 à n trajets. |
| PROPOSE     | USER     | (0,n)   | TRAJET   | (1,1)   | Un trajet est proposé par exactement 1 user. Un user peut proposer 0 à n trajets. |

---

> **Note livrable :** Ce fichier sert de base pour générer le MCD visuel (ex. avec [Looping](https://www.looping-mcd.fr/) ou draw.io).
> Le fichier image final (PNG/PDF) doit être ajouté dans ce dossier sous le nom `mcd.png`.

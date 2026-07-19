# Master Droit & Ingénierie Financière — Site + CMS

Refonte complète du site [droit-ingenieriefinanciere.fr](http://droit-ingenieriefinanciere.fr/),
le site du **Master 1 & 2 Droit et Ingénierie Financière (DIF)** de l'Université Jean Moulin Lyon 3.

Site en **PHP + contenu JSON** (aucune base de données), doté d'un **back-office privé** (`/admin`)
permettant à l'association de gérer le contenu sans toucher au code.

---

## Pourquoi cette refonte

Le site actuel tourne sous **WordPress 5.8.13** (version de 2021, non maintenue, faille de sécurité)
avec le thème gratuit *Sydney*. Contenu riche, mais présentation datée et surface d'attaque WordPress.

| | Site actuel | Refonte |
|---|---|---|
| Socle | WordPress 5.8.13 + thème Sydney | PHP 8 + JSON, **sans base de données** |
| Édition du contenu | WordPress (lourd) | Back-office `/admin` léger, protégé |
| Sécurité | Surface WordPress, plugins | Session + mot de passe hashé + CSRF |
| Identité visuelle | Marine + rouge terne, gabarit générique | Marine « nuit sur le Rhône » + accent laiton, sur-mesure |
| Parcours candidat | Pas de page Admissions | Page Admissions dédiée (procédure, FAQ) |
| Performance | Lourd | Léger : une CSS, un JS, pages statiques rendues côté serveur |
| Accessibilité | Limitée | Sémantique, focus visible, thème sombre, `prefers-reduced-motion` |

---

## Le système de design

**Concept** : le sujet vit à l'intersection du **droit** (tradition, gravité) et de la **finance**
(précision, chiffres), ancré à Lyon (le Rhône, le Palais de l'Université illuminé la nuit).

- **Couleurs** — encre marine `#0f1a33`, accent **laiton `#c2a15b`**, papier froid `#f5f6f8`.
  Thèmes clair **et** sombre (tokens CSS).
- **Typographie — la dualité comme signature** : *Fraunces* (serif, le droit) · *Inter* (sans, la clarté)
  · *IBM Plex Mono* pour **tous les chiffres** (la finance, comme une bande de cotation).
- **Layout** — rythme éditorial, grille 12 colonnes, filets or, motif récurrent *Droit / Finance*.

---

## Architecture

```
Pages publiques (PHP, rendues côté serveur — SEO friendly)
  index.php  le-master.php  formation.php  admissions.php
  reseau.php  actualites.php  contact.php

inc/                 Code partagé
  head.php           <head> + entête/nav (dédoublonné)
  footer.php         pied de page
  data.php           lecture/écriture JSON, helpers, registre des collections

content/             CONTENU ÉDITABLE (écrit par le back-office)
  site.json          accroche, chiffres-clés, parrain, coordonnées
  actualites.json    articles / actualités
  partenaires.json   cabinets & entreprises partenaires
  promotions.json    promotions
  temoignages.json   citations & témoignages (la 1re alimente la home)

admin/               BACK-OFFICE PRIVÉ (connexion requise)
  login.php  logout.php  index.php (tableau de bord)
  collection.php       liste + réordonnancement + suppression
  edit.php             ajout / modification d'un élément
  settings.php         réglages du site (site.json)
  bootstrap.php        session, authentification, CSRF, flash
  config.php           identifiants (À PERSONNALISER)
  layout.php  admin.css

assets/              css/dif.css · js/dif.js · img/ (photo hero, logo, favicon)
.htaccess            protections (index par défaut, blocage des includes)
```

Le contenu géré depuis `/admin` est écrit dans `content/*.json` (écriture atomique) et
**apparaît immédiatement** sur le site public, qui lit ces fichiers à chaque affichage.

---

## Le back-office `/admin`

Accessible **uniquement après connexion**. Il permet de gérer, sans toucher au code :

- **Actualités** — ajouter / modifier / supprimer / réordonner les articles (titre, date, catégorie, résumé, contenu) ;
- **Partenaires** — les cabinets et entreprises affichés sur *Le Réseau* et l'accueil ;
- **Promotions** — la liste des promotions ;
- **Citations & témoignages** — la première citation alimente le bloc de l'accueil ;
- **Réglages du site** — accroche de l'accueil, chiffres-clés, parrain, coordonnées.

**Sécurité** : session PHP durcie (cookie `HttpOnly`, `SameSite=Lax`, `Secure` en HTTPS),
mot de passe **hashé** (`password_hash`), **jeton CSRF** sur toutes les actions, throttling
à la connexion, déconnexion automatique après inactivité. Tout le contenu saisi est
échappé à l'affichage (protection anti-XSS).

### Identifiants par défaut — à changer avant la mise en ligne

> Mot de passe par défaut : **`DIF-admin-2026`**

1. Générez un nouveau hash :
   ```bash
   php -r 'echo password_hash("VOTRE_MOT_DE_PASSE", PASSWORD_DEFAULT), "\n";'
   ```
2. Collez-le dans `admin/config.php` → `ADMIN_PASSWORD_HASH`.
3. Servez le site en **HTTPS** (le cookie de session passe alors en `Secure`).

---

## Lancer le site en local

Nécessite **PHP 8+** :

```bash
php -S localhost:8000
# Site :   http://localhost:8000
# Admin :  http://localhost:8000/admin/   (mot de passe : DIF-admin-2026)
```

## Mise en production

Hébergement **PHP/Apache** (comme l'actuel hébergement WordPress). Copiez les fichiers,
assurez-vous que le dossier `content/` est **accessible en écriture** par le serveur web
(`chmod 775 content` ou propriété du user PHP), changez le mot de passe admin, activez HTTPS.

## Notes & pistes

- Textes fidèles au site actuel. Les **logos partenaires** sont en typographie (à remplacer par
  les logos officiels avec l'accord des cabinets).
- Le **formulaire de contact** est une maquette front-end ; à relier à la boîte de l'association
  ou à un service d'e-mail en production.
- Pistes : upload d'images pour les actualités, trombinoscopes des promotions, plaquette PDF,
  et — si vous préférez un hébergement 100 % statique — bascule vers un CMS git (Decap) au lieu du back-office PHP.

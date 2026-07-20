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
  reseau.php  actualites.php  article.php  promotion.php (trombinoscope)
  contact.php  404.php  sitemap.php  robots.txt

inc/                 Code partagé
  head.php           <head> + SEO (Open Graph, canonical) + entête/nav
  footer.php         pied de page
  data.php           JSON, slugs, upload d'images, helpers, collections

content/             CONTENU ÉDITABLE (écrit par le back-office)
  site.json          accroche, chiffres-clés, parrain, coordonnées
  actualites.json    articles (avec slug, statut publié/brouillon, image)
  partenaires.json   cabinets & entreprises partenaires (avec logo)
  promotions.json    promotions
  membres.json       étudiants (trombinoscope) : nom, promotion, niveau, photo
  temoignages.json   citations & témoignages (la 1re alimente la home)

admin/               BACK-OFFICE PRIVÉ (connexion requise)
  login.php  logout.php  index.php (tableau de bord)
  collection.php       liste + réordonnancement + suppression
  edit.php             ajout / modification (+ upload d'images)
  messages.php         boîte de réception (formulaire de contact)
  settings.php         réglages du site (site.json)
  password.php         changement du mot de passe
  bootstrap.php        session, authentification, CSRF, flash
  config.php           identifiants (À PERSONNALISER)
  layout.php  admin.css

assets/
  css/dif.css  js/dif.js  img/  uploads/ (images téléversées)
logs/                messages de contact (sauvegarde, dossier protégé)
.htaccess            index par défaut, page 404, blocage des includes
```

## Fonctionnalités

- **Articles** — chaque actualité a sa page dédiée (`article.php?slug=…`), avec image de
  couverture, méta SEO, partage (Open Graph) et navigation précédent / suivant.
- **Statut publié / brouillon** — un brouillon n'apparaît ni sur le site, ni dans le sitemap.
- **Images** — upload sécurisé (JPG/PNG/WEBP/GIF, 4 Mo max, nom aléatoire) pour les couvertures
  d'articles et les logos partenaires.
- **Formulaire de contact fonctionnel** — validation, anti-spam (honeypot), envoi par e-mail à
  l'association (`Reply-To` du visiteur) et sauvegarde de secours dans `logs/`.
- **SEO** — balises Open Graph / Twitter + canonical sur chaque page, `sitemap.php` dynamique
  (articles inclus), `robots.txt`, page **404** personnalisée.
- **Changement de mot de passe** depuis le back-office (écrit un hash dans `admin/auth.local.php`,
  fichier protégé et non versionné).
- **Boîte de réception** — les messages du formulaire de contact arrivent dans le back-office
  (`admin/messages.php`) : compteur de non-lus, marquer lu/non lu, répondre, supprimer. Stockés dans
  `logs/` (protégé), donc consultables même si l'e-mail n'est pas configuré.
- **Actualités paginées & filtrables** — filtre par catégorie et pagination côté serveur
  (9 par page), sans dépendre du JavaScript (bon pour le SEO).
- **Trombinoscope** — chaque promotion peut afficher ses étudiants (`promotion.php?slug=…`),
  regroupés par niveau (M1 / M2), avec photo, e-mail et LinkedIn ou pastille à initiale.
- **Assistant de promotion** (`admin/promo-builder.php`) — un formulaire unique pour créer une
  promotion complète : année, numéro (auto-incrémenté), photo de groupe M1+M2, puis sections M2 et
  M1 avec l'ajout des étudiants un par un (photo, prénom, nom, e-mail, LinkedIn). Chaque photo est
  téléversée en AJAX à la sélection (contourne la limite PHP de fichiers par envoi). À la création,
  la promotion devient automatiquement la **promotion actuelle** (accueil + réseau) et le numéro de
  promotion de l'accueil s'incrémente.

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

> **Guide pas à pas complet : [DEPLOIEMENT.md](DEPLOIEMENT.md)** (sauvegarde de l'ancien
> site, transfert, droits, mot de passe, HTTPS, bascule, liste de contrôle, SEO).

Hébergement **PHP/Apache** (comme l'actuel hébergement WordPress). Copiez les fichiers, puis :

- rendez **accessibles en écriture** par le serveur web les dossiers `content/`, `assets/uploads/`
  et `logs/` (`chmod 775`, ou propriété de l'utilisateur PHP) — édition du contenu, upload d'images
  et sauvegarde des messages ;
- pour le **changement de mot de passe** depuis l'interface, le dossier `admin/` doit être accessible
  en écriture ; sinon, modifiez `ADMIN_PASSWORD_HASH` dans `admin/config.php` à la main ;
- l'**envoi d'e-mails** du formulaire de contact utilise la fonction `mail()` de PHP (activée chez la
  plupart des hébergeurs) ; les messages sont de toute façon sauvegardés dans `logs/` ;
- **changez le mot de passe admin** et activez **HTTPS**.

## Notes & pistes

- Textes fidèles au site actuel. Les **logos partenaires** sont en typographie (à remplacer par
  les logos officiels avec l'accord des cabinets).
- Le **formulaire de contact** est une maquette front-end ; à relier à la boîte de l'association
  ou à un service d'e-mail en production.
- Pistes : upload d'images pour les actualités, trombinoscopes des promotions, plaquette PDF,
  et — si vous préférez un hébergement 100 % statique — bascule vers un CMS git (Decap) au lieu du back-office PHP.

# Guide de mise en ligne — droit-ingenieriefinanciere.fr

Ce guide décrit, étape par étape, le remplacement du site WordPress actuel par la refonte.
Comptez **30 à 45 minutes**, sans coupure visible si vous suivez l'ordre proposé.

## Prérequis

- Un hébergement **PHP 8.0+ avec Apache** (l'hébergement actuel du WordPress convient :
  même type d'offre mutualisée classique — OVH, o2switch, Ionos…).
- Les **accès FTP/SFTP** (ou le gestionnaire de fichiers de l'hébergeur) et l'accès au
  panneau de l'hébergeur (pour HTTPS).
- Aucune base de données n'est nécessaire.

---

## Étape 1 — Sauvegarder l'ancien site

Avant toute chose, depuis le gestionnaire de fichiers ou en FTP :

1. Téléchargez une copie complète du dossier du site WordPress (notamment
   `wp-content/uploads/`, qui contient l'historique des photos).
2. Exportez la base de données WordPress depuis phpMyAdmin (Exporter → SQL).
3. Conservez ces deux archives : elles permettent un retour arrière à tout moment.

## Étape 2 — Déposer la refonte

1. Récupérez le contenu du dépôt (bouton **Code → Download ZIP** sur GitHub, ou `git clone`).
2. **Option prudente (recommandée)** : déposez d'abord les fichiers dans un sous-dossier
   `nouveau/` du site pour tester en conditions réelles
   (`droit-ingenieriefinanciere.fr/nouveau/`), puis basculez à l'étape 6.
   **Option directe** : videz la racine du site (l'ancien WordPress, déjà sauvegardé)
   et déposez les fichiers de la refonte à la racine.
3. Vérifiez que les fichiers cachés `.htaccess` (racine, `inc/`, `admin/`, `content/`,
   `logs/`) ont bien été transférés — certains clients FTP les masquent.

## Étape 3 — Droits d'écriture

Le back-office écrit dans certains dossiers. Donnez les droits d'écriture au serveur web
(via le gestionnaire de fichiers : « permissions » → 755, ou 775 si nécessaire) sur :

| Dossier | Pourquoi |
|---|---|
| `content/` | l'édition du contenu (actualités, partenaires, réglages…) |
| `assets/uploads/` | les images téléversées depuis l'admin |
| `logs/` | la boîte de réception des messages du formulaire |
| `admin/` | le changement de mot de passe depuis l'interface *(facultatif)* |

> Chez la plupart des hébergeurs mutualisés, PHP tourne sous votre propre utilisateur :
> les droits par défaut suffisent alors et cette étape est transparente.

## Étape 4 — Changer le mot de passe admin (obligatoire)

Le mot de passe par défaut (`DIF-admin-2026`) est public — changez-le immédiatement :

1. Ouvrez `votre-site/admin/`, connectez-vous avec le mot de passe par défaut.
2. Menu **Mot de passe** → définissez un mot de passe fort (12+ caractères).
3. Si le dossier `admin/` n'est pas accessible en écriture, générez un hash à la main :
   ```bash
   php -r 'echo password_hash("VOTRE_MOT_DE_PASSE", PASSWORD_DEFAULT), "\n";'
   ```
   puis collez-le dans `admin/config.php` → `ADMIN_PASSWORD_HASH`.

## Étape 5 — Activer HTTPS

1. Dans le panneau de l'hébergeur, activez un certificat SSL (Let's Encrypt, gratuit
   chez la quasi-totalité des hébergeurs).
2. Ajoutez la redirection HTTP → HTTPS en tête du `.htaccess` racine :
   ```apache
   RewriteEngine On
   RewriteCond %{HTTPS} off
   RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```
3. En HTTPS, le cookie de session admin passe automatiquement en mode `Secure`.

## Étape 6 — Basculer (si vous avez testé dans `nouveau/`)

1. Renommez le dossier WordPress de la racine en `ancien-site/`.
2. Déplacez le contenu de `nouveau/` vers la racine.
3. Testez immédiatement la liste de contrôle ci-dessous.
4. Après quelques jours sans problème, supprimez `ancien-site/` (vous gardez l'archive
   de l'étape 1).

## Étape 7 — Liste de contrôle post-mise en ligne

- [ ] La page d'accueil s'affiche en HTTPS (cadenas dans le navigateur).
- [ ] Le menu, les onglets M1/M2 (Formation) et le carrousel des promotions fonctionnent.
- [ ] Une page promotion s'ouvre avec photos et trombinoscope défilant.
- [ ] `admin/` demande le **nouveau** mot de passe ; l'ancien est refusé.
- [ ] Ajoutez une actualité de test depuis l'admin → elle apparaît sur le site → supprimez-la.
- [ ] Envoyez un message depuis la page Contact → il apparaît dans **Messages** de l'admin
      (et par e-mail si `mail()` est actif chez l'hébergeur).
- [ ] `votre-site/sitemap.php` renvoie du XML ; `robots.txt` est accessible.
- [ ] Une URL inexistante affiche la page 404 personnalisée.

## Étape 8 — Référencement (dans la foulée)

1. Dans **Google Search Console** (propriété du domaine), soumettez le sitemap :
   `https://droit-ingenieriefinanciere.fr/sitemap.php`.
2. Les URL changent par rapport à WordPress (`/2026/03/05/...` → `article.php?slug=...`).
   Le trafic organique du site étant essentiellement sur la page d'accueil, des
   redirections fines ne sont pas indispensables ; la 404 personnalisée guide les
   visiteurs égarés. Si vous y tenez, des `Redirect 301` peuvent être ajoutés au
   `.htaccess` au cas par cas.

---

## Au quotidien

- **Publier une actualité** : `admin/` → Actualités → Ajouter (statut *Brouillon* pour
  préparer sans publier, image de couverture facultative).
- **Nouvelle promotion** : Promotions → Ajouter (année + photos de groupe), puis
  Trombinoscope → Ajouter chaque étudiant (nom, promotion, niveau, portrait).
- **Messages reçus** : l'onglet Messages centralise les demandes du formulaire, même si
  l'e-mail n'est pas configuré.
- **Dates d'admission** : Dates clés → mettez à jour les périodes chaque année.

## En cas de problème

| Symptôme | Cause probable | Solution |
|---|---|---|
| « Erreur lors de l'enregistrement » dans l'admin | `content/` non inscriptible | Étape 3 |
| L'upload d'image échoue | `assets/uploads/` non inscriptible | Étape 3 |
| Pas d'e-mail reçu du formulaire | `mail()` désactivé chez l'hébergeur | Les messages restent lisibles dans l'admin (Messages) |
| Page blanche | Version PHP < 8 | Sélectionnez PHP 8.x dans le panneau de l'hébergeur |
| Styles absents | `.htaccess` ou dossier `assets/` manquant | Revérifiez le transfert FTP (fichiers cachés) |

Retour arrière : remettez l'archive WordPress de l'étape 1 à la racine et réimportez la
base SQL — l'ancien site refonctionne à l'identique.

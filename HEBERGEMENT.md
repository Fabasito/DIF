# Héberger le site pour tester (admin inclus)

Le site est en **PHP + fichiers** : l'espace d'administration écrit sur le disque
(contenu JSON, images téléversées, messages). Le choix de l'hébergement dépend donc
d'un point clé : **le disque est-il inscriptible et persistant ?**

| Option | Admin fonctionne | Modifs conservées | Idéal pour |
|---|---|---|---|
| **A. Docker → Render / Railway** | ✅ | ✅ (avec disque) | **Tester à plusieurs, avec l'association** |
| **B. Vercel** | ✅ | ⚠️ éphémère | Aperçu rapide, cliquer/essayer |
| **C. Hébergement PHP classique** | ✅ | ✅ | **Production** (voir `DEPLOIEMENT.md`) |

> Résumé : pour **tout tester avec un admin qui garde les modifications**, prends
> l'**option A**. Vercel (option B) est parfait pour montrer le site, mais tout ce
> qui est ajouté depuis l'admin y est réinitialisé au prochain déploiement.

---

## Option A — Docker sur Render (recommandé pour un test complet)

Un `Dockerfile` est fourni : il fait tourner le site exactement comme en production
(Apache + PHP + `.htaccess`), avec un disque inscriptible.

### Render (gratuit)

1. Pousse le dépôt sur GitHub (déjà fait).
2. Sur [render.com](https://render.com) → **New +** → **Web Service** → connecte le dépôt.
3. Render détecte le `Dockerfile` automatiquement. Laisse les réglages par défaut,
   clique **Create Web Service**.
4. Au bout de quelques minutes tu obtiens une URL `https://xxx.onrender.com`.
   Le site **et** l'admin (`/admin`, mot de passe `DIF-admin-2026`) fonctionnent.

**Pour que les modifications survivent aux redéploiements** : dans Render, onglet
**Disks** → **Add Disk** (ex. 1 Go, chemin de montage `/data`), puis onglet
**Environment** → ajoute la variable `DIF_DATA_DIR=/data`. Le contenu éditable,
les images et les messages seront alors stockés sur ce disque persistant.

### Railway

Même principe : **New Project → Deploy from GitHub repo**. Railway détecte le
`Dockerfile`. Ajoute un **Volume** monté sur `/data` et la variable
`DIF_DATA_DIR=/data` pour la persistance.

### En local (Docker Desktop)

```bash
docker build -t dif .
docker run -p 8080:80 dif
# → http://localhost:8080   (admin : http://localhost:8080/admin)
```

---

## Option B — Vercel (aperçu rapide, données éphémères)

Fichiers fournis : `vercel.json` (runtime **`vercel-php@0.9.0`**) et `api/index.php`
(contrôleur frontal). Sur Vercel, le disque est en lecture seule ; le contenu
éditable, les images et les messages sont écrits dans `/tmp` (inscriptible mais
**éphémère**).

### Déploiement

1. Sur [vercel.com](https://vercel.com) → **Add New… → Project** → importe le dépôt GitHub.
2. Framework Preset : **Other**. Ne change rien d'autre, clique **Deploy**.
3. Tu obtiens une URL `https://xxx.vercel.app`. Le site et l'admin sont accessibles.

### Ce qu'il faut savoir

- **Les modifications de l'admin ne persistent pas** : elles vivent le temps d'une
  instance « chaude » et sont réinitialisées au prochain déploiement, au démarrage à
  froid ou lors d'une montée en charge. Parfait pour **essayer** chaque fonctionnalité,
  pas pour un contenu durable.
- **La connexion admin** repose sur une session : si Vercel bascule sur une autre
  instance entre deux clics, il faudra parfois se reconnecter.
- Le **changement de mot de passe** ne persiste pas (utilise le mot de passe par défaut).
- La sécurité est assurée par le contrôleur frontal (les fichiers `inc/` et
  `admin/config.php` ne sont jamais exposés).

> Ces limites ne viennent pas du site mais du modèle serverless de Vercel. Pour un
> test durable, préfère l'option A.

---

## Variable d'environnement `DIF_DATA_DIR`

Permet de choisir le dossier inscriptible (contenu, uploads, logs). S'il diffère du
dépôt, il est **amorcé automatiquement** au premier accès à partir du contenu livré.

- Non définie → le dépôt lui-même (hébergement classique).
- Définie (ex. `/data`) → ce dossier (disque persistant Docker/Render/Railway).
- Sur Vercel, `/tmp/dif-data` est utilisé automatiquement.

---

## Sécurité — à faire quel que soit l'hébergement

- Changer le mot de passe admin par défaut (`DIF-admin-2026`).
- Servir le site en **HTTPS** (automatique sur Render, Railway et Vercel).

Détails de la mise en production sur hébergement classique : **`DEPLOIEMENT.md`**.

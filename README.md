# Master Droit & Ingénierie Financière — Refonte du site

Refonte complète du site [droit-ingenieriefinanciere.fr](http://droit-ingenieriefinanciere.fr/),
le site du **Master 1 & 2 Droit et Ingénierie Financière (DIF)** de l'Université Jean Moulin Lyon 3.

Il s'agit d'une **proposition de refonte** : un site statique moderne, rapide et sans dépendance à
WordPress, construit en HTML / CSS / JavaScript vanilla. Aucune étape de build n'est nécessaire.

---

## Pourquoi une refonte

Le site actuel tourne sous **WordPress 5.8.13** (version de 2021, non maintenue, faille de sécurité)
avec le thème gratuit *Sydney*. Le contenu est riche et de qualité, mais la présentation est datée,
peu hiérarchisée et non pensée pour convertir des candidats.

| | Site actuel | Refonte proposée |
|---|---|---|
| Socle | WordPress 5.8.13 (obsolète) + thème Sydney | Site statique HTML/CSS/JS, zéro dépendance |
| Sécurité / maintenance | Surface d'attaque WordPress, mises à jour | Aucune base de données, hébergement statique |
| Identité visuelle | Bleu marine + rouge terne, gabarit générique | Marine « nuit sur le Rhône » + accent laiton, système sur-mesure |
| Parcours candidat | Page « Admissions » inexistante | Page Admissions dédiée (procédure, profils, FAQ) |
| Performance | Lourd (plugins, scripts) | Léger, une CSS + un JS, chargement rapide |
| Accessibilité | Limitée | Sémantique, focus visible, thème sombre, `prefers-reduced-motion` |
| Responsive | Thème daté | Mobile-first, menu plein écran, grilles fluides |

---

## Le système de design

**Concept** : le sujet vit à l'intersection du **droit** (tradition, gravité) et de la **finance**
(précision, chiffres), ancré à Lyon (le Rhône, le Palais de l'Université illuminé la nuit).

- **Couleurs** — encre marine `#0f1a33` (fond hero/footer), accent **laiton `#c2a15b`** (seul accent,
  très retenu), papier froid `#f5f6f8`. Thèmes clair **et** sombre gérés au niveau des tokens CSS.
- **Typographie — la dualité comme signature** :
  - *Fraunces* (serif éditoriale) → le droit, la gravité ;
  - *Inter* (sans) → la clarté ;
  - *IBM Plex Mono* → **tous les chiffres et labels**, comme la lecture d'un instrument financier.
- **Layout** — rythme éditorial, grille 12 colonnes, filets or, « ruban » de chiffres-clés façon
  cotation sous le hero, motif récurrent à deux colonnes *Droit / Finance*, révélations au scroll.

---

## Arborescence

```
index.html          Accueil — hero, double compétence, piliers, formation, débouchés, partenaires, actualités
le-master.html      Le Master — histoire, mot du directeur, valeurs, parrain
formation.html      La Formation — maquette M1/M2 (onglets), objectifs, débouchés
admissions.html     Admissions — profils, procédure en 4 étapes, FAQ  (NOUVEAU)
reseau.html         Le Réseau — partenaires, promotions, devenir partenaire
actualites.html     Actualités — grille d'articles, filtres par catégorie
contact.html        Contact — interlocuteurs, formulaire, partenariats
assets/
  css/dif.css       Design system complet (tokens, composants, thèmes)
  js/dif.js         Thème clair/sombre, menu mobile, reveal, compteurs, onglets, accordéon
  img/              Photo hero (berges du Rhône) et logo
```

## Aperçu en local

Aucune installation requise :

```bash
# ouvrir directement index.html, ou servir le dossier :
python3 -m http.server 8000
# puis http://localhost:8000
```

## Notes

- Les textes reprennent fidèlement le contenu du site actuel (mot du directeur, maquette,
  partenaires, débouchés). Les logos partenaires sont représentés en typographie ; ils pourront
  être remplacés par les logos officiels avec l'accord des cabinets.
- Le formulaire de contact est une maquette front-end ; à relier en production à la boîte de
  l'association ou à un service d'envoi d'e-mails.
- Prochaines pistes : intégration des vrais trombinoscopes, plaquette PDF, CMS léger (ex. Decap/Netlify
  CMS) pour que l'association publie les actualités sans toucher au code.

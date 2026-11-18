# CLAUDE.md — Thème Audace Magazine

Toujours répondre en français, avec les accents.

## Projet

Thème WordPress **autonome et sur mesure** pour audacemagazine.com, destiné à
remplacer Extra (Elegant Themes) et son thème enfant. Décision de
l'utilisateur du 2026-11-18 : un thème **orienté magazine professionnel**, au
niveau des codes des magazines internationaux, qui tient compte du **magazine
proposé en ligne** (le kiosque), à optimiser et à moderniser.

L'utilisateur dit manquer d'idées pour la direction du magazine : c'est à
nous de proposer (références de magazines, direction artistique, maquettes),
puis de le laisser trancher.

**État (2026-11-18) : projet créé, rien d'écrit.** Ni inventaire du site, ni
maquette, ni thème.

**Prochaine étape : l'inventaire du site** (voir « À faire en premier »),
puis des maquettes HTML statiques dans `design/`, puis le thème.

## Ce que l'on sait du site (relevé le 2026-11-18)

Sources : deux captures de l'administration fournies par l'utilisateur et une
lecture de l'accueil public. Tout le reste est à mesurer.

- Adresse : `https://www.audacemagazine.com` (titre : « Audace Magazine | une
  autre façon de s'informer »).
- WordPress 7.1.4, thème Extra avec un thème enfant (`extra-child-theme`).
- Menu public : Economie, Nos Articles, Politique, **Kiosque** (`/kiosque/`),
  Mon Compte (`/my-account/`).
- Extensions vues : WooCommerce 10.2.4, Indeed Ultimate Membership Pro 9.6,
  Flutterwave Payment Gateway for WooCommerce 2.4.1, Facebook for WooCommerce
  3.5.9, Audace Analytics 0.2.0, All in One SEO, Site Kit de Google,
  UpdraftPlus, LightStart (mode maintenance), Broken Link Checker (inactif).
- Hypothèse à vérifier : le magazine en ligne repose sur WooCommerce
  (numéros vendus comme produits), Ultimate Membership Pro (abonnés, accès
  restreint) et Flutterwave (paiement). Le thème devra habiller ces écrans
  (kiosque, fiche d'un numéro, panier, paiement, compte) sans casser le
  parcours d'achat.
- Signaux d'entretien vus dans l'administration, à signaler à l'utilisateur,
  hors périmètre du thème : 7 505 commentaires en attente, 17 047 « New
  Users » (inscriptions probablement indésirables), 15 mises à jour en
  attente, Site Kit à reconnecter (adresse du site changée).

## À faire en premier (inventaire)

- Contenu : nombre d'articles, catégories, auteurs, formats d'image, part des
  articles construits avec Divi (shortcodes `et_pb_*` à nettoyer à
  l'affichage, comme dans le thème radio).
- Kiosque : comment un numéro est publié (produit WooCommerce, PDF, liseuse),
  comment on le lit, ce qui est gratuit et ce qui est payant, les niveaux
  d'abonnement, les moyens de paiement réellement utilisés.
- Ce que fait le thème enfant d'Extra (fonctions à reprendre ou à abandonner).
- Qui publie et comment (éditeur WordPress, RadioManager, autre).
- Accès : existe-t-il un staging et un accès SSH ? Sinon, où tester.

## Exigences de l'utilisateur

- Magazine professionnel, aux codes des magazines internationaux.
- Le magazine en ligne (kiosque) est une fonction centrale : en tenir compte,
  l'optimiser, le moderniser.
- Thème personnalisé, versionné dans ce dépôt.

## Projets liés (à lire avant de concevoir)

| Projet | Emplacement | Rôle pour ce thème |
|---|---|---|
| Thème Radio Audace | `~/App/radio-audace-theme` | Le modèle à suivre : même démarche (inventaire, maquettes critiquées, thème), nettoyage du contenu Divi à l'affichage (`inc/contenu.php`), updater GitHub (`inc/miseajour.php`), `release.sh`, leçons de l'hébergement dans son `CLAUDE.md` |
| Plugin `audace-analytics` | `~/App/audace-analytics-wp` | Déjà installé sur le magazine (statistiques sans cookie) |
| Chantier « L'Onde » | `~/App/radioaudace-website` | Refonte précédente de la radio sur Extra ; runbooks et inventaire d'un site voisin |

Le magazine et la radio sont deux marques de la même maison : le thème du
magazine a sa propre identité, mais peut reprendre les mécanismes éprouvés
du thème radio.

## Environnements et règles

- **Prod** : `https://www.audacemagazine.com`. **Aucune écriture sans
  décision explicite de l'utilisateur.** Le site vend et encaisse : toute
  bascule de thème doit d'abord être essayée ailleurs, parcours d'achat
  compris.
- Staging : inconnu à ce jour.
- Lecture du site public : une requête avec un User-Agent de navigateur
  répond 200 ; rester sobre (pas de rafale), l'hébergeur de la radio bannit
  les rafales et celui du magazine n'est pas encore identifié.
- Secrets et identifiants : jamais dans ce dépôt ni dans les sorties.

## Dépôt et mises à jour

- Dépôt GitHub `lwilly3/audace-magazine-theme`, **privé** à sa création
  (2026-11-18).
- Leçon du thème radio : un site ne peut lire les releases que d'un dépôt
  **public** (dépôt privé = HTTP 404). Avant la première release avec
  updater, rendre ce dépôt public (décision de l'utilisateur) ; d'ici là,
  n'y mettre ni secret ni adresse personnelle, comme s'il l'était déjà.
- Publier une release crée une surface publique : c'est l'utilisateur qui
  lance la commande.

## Arborescence prévue

```text
audace-magazine/   # le thème (ce dossier deviendra le ZIP installable)
design/            # maquettes HTML statiques
docs/              # inventaire du site et documentation de conception
```

## Commits

Messages conventionnels en français. Auteur configuré localement avec
l'adresse noreply GitHub : ne pas la changer.

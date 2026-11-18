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

**État (2026-11-18) : inventaire fait** (`docs/INVENTAIRE.md`), depuis
l'extérieur et en lecture seule. Ni maquette, ni thème.

**Décision de l'utilisateur (2026-11-18)** : il n'y a à ce jour **aucun
achat ni aucun abonné**. Rien à migrer ni à préserver côté vente : le site
est à refaire « de la meilleure manière », avec une prise en main facile,
une ergonomie professionnelle de site magazine et une touche moderne.

Conséquences retenues (recommandations de Claude, à confirmer sur
maquettes) :

- Le site est d'abord un **site d'information** ; le kiosque est mis en
  avant partout, mais l'accueil doit rester crédible sans nouveau numéro.
- **Un seul compte et un seul circuit de vente**, dans WooCommerce.
  Ultimate Membership Pro n'a pas d'abonné : candidat au retrait (décision
  et désactivation par l'utilisateur, hors thème).
- Une page par numéro avec couverture, sommaire et aperçu ; liseuse en
  ligne si les PDF d'origine sont fournis.
- Défauts de contenu rattrapés à l'affichage, comme sur le thème radio.

Encore ouvert : rythme de parution des numéros, prix, staging chez OVH,
PDF et logo d'origine, rubriques définitives.

**Maquettes, troisième passe (2026-11-18)** dans `design/` : `index.html`
(sommaire, changements, règles, points à décider), `article.html`,
`accueil.html`, `kiosque.html` (tout en lecture libre) et
`kiosque-vente.html` (à la sortie d'un numéro). Historique : la première
passe (deux directions sur une grille de journal classique) a été jugée pas
assez moderne ; la deuxième (une direction, typographie grasse) cassait sur
tablette et tirait vers le « blog de start-up » ; la troisième répond à deux
analyses critiques, dont une par format d'écran.

Direction retenue par les maquettes (à valider par l'utilisateur) :

- Titres Bricolage Grotesque, **très gras pour les grands titres seulement**
  (listes en 600) ; texte Source Serif 4 ; interface Instrument Sans ; fond
  clair, noir et rouge en accents ; angles droits. Mode sombre
  (`data-theme`, suit l'appareil, `?theme=`).
- **Signature de magazine** : chapô et exergue en italique à empattements,
  lettrine rouge en début d'article, filet noir en tête de section.
- **Points de rupture** : téléphone d'abord ; à 720 px une colonne élargie ;
  deux colonnes et rubriques dans l'en-tête à partir de **1000 px**
  seulement. La taille des titres suit la largeur de leur colonne (requêtes
  de conteneur, unités `cqi`) et trois paliers de longueur (≤ 55, ≤ 90).
- En-tête sur une ligne, avec « Magazine » présent dès le téléphone ; il
  s'efface à la descente dans un article.
- **Radio Audace est référencée, pas embarquée** (décision de l'utilisateur
  du 2026-11-18) : un simple lien vers le site de la radio dans le pied et
  le menu, nouvel onglet. Pas de lecteur dans le magazine : le son se
  couperait à chaque changement de page, et ce sont deux marques.
- Vérifié par mesure et captures à **360, 768, 1024 et 1366 px** : aucun
  débordement. Toujours vérifier à ces quatre largeurs (la tablette était
  passée au travers avec deux largeurs seulement).

Règles que le thème devra appliquer seul (détail dans `design/index.html`) :
espace insécable avant « : ; ? ! » et dans les guillemets ; pas de coupure
aux traits d'union des titres ; capitales remises en casse normale ; chapô =
extrait rédigé de moins de 220 caractères environ, sinon rien ; exergue =
bloc Citation, facultatif ; plan tiré des intertitres à partir de trois ;
image jamais plus grande que le fichier ; une seule interruption (exergue,
publicité) tous les six à huit paragraphes, renvoi magazine et « Suivre »
en fin d'article ; aucun doublon sur l'accueil ; pas de date dans les listes
au-delà de trente jours ; « dernier numéro », jamais « numéro en cours » ;
bloc « Comment ça marche » seulement s'il y a un numéro en vente.

Pages ajoutées ensuite (même direction) : `rubrique.html` (un article en
tête, puis les articles rangés par mois, pagination), `numero.html` (page
d'un numéro avec **liseuse**, refaite après une critique : flèches, clavier,
glissement du doigt, **curseur de pages**, **zoom** en trois crans (bouton ou
double appui), **plein écran en recouvrement** (l'API du navigateur manque
sur iPhone), double page à partir de 1000 px, **sommaire cliquable** avec
numéros de page, **adresse par page** (`#page=34`) et reprise de lecture ;
**la page suit le doigt** (geste nettement horizontal seulement, bords de
l'écran exclus, seuil d'un quart de largeur ou geste rapide, résistance aux
extrémités, rien si la page est agrandie) ; transition de 280 ms :
**glissement latéral** en page simple, **pliure à la reliure** en double
page, fondu si l'appareil demande moins d'animations, saut direct depuis le
curseur et le sommaire ; gestes vérifiés par simulation, pas encore sur un
vrai téléphone ; **volume du magazine** en CSS simple, sans moteur 3D :
tranches de pages de chaque côté de la double page (14 px au plus), dont
l'épaisseur suit la lecture (lues à gauche, restantes à droite), creux de
reliure, dos rouge et épaisseur d'un seul côté quand le magazine est fermé
(couverture, dernière page), simple liseré de 4 px à droite en page simple,
rien quand la page est agrandie ; les états de la liseuse ont des classes
préfixées (`mag-debut`, `mag-fin`) car `.couverture` et `.fin` existent
déjà ailleurs dans la feuille de style ; **construction en double page :
un « livre » de deux moitiés** — la couverture est une double page à moitié
gauche vide, la dernière page une double page à moitié droite vide, le livre
fermé est décalé d'une demi-page pour rester centré ; tranches, dos et volet
sont des enfants du livre, donc **tout ce qui bouge bouge ensemble** (la
première version posait les tranches à côté des pages : elles se
détachaient à la couverture, à la dernière page et en butée) ; la pliure
s'applique partout, couverture comprise ; l'épaisseur change pendant la
transition ; en page simple le liseré fait partie de la planche qui glisse ;
seule la couverture est réelle, les pages intérieures sont figurées à
l'échelle d'une page imprimée, donc illisibles sans zoom sur téléphone),
`recherche.html` (articles et sommaires du magazine confondus, filtres par
rubrique). Mesurées aux quatre largeurs, sans débordement.

Liseuse, à faire dans le thème : des **images de pages préparées à l'avance**
à partir du PDF, chargées une à une, jamais le PDF entier (24 Mo sur mobile).
**À faire avant d'aller plus loin : essayer la liseuse avec un vrai PDF.**
Question ouverte pour la rédaction : reprendre les articles des numéros en
**texte sur le site**, rattachés à leur numéro (la vraie lecture sur
téléphone ; suppose d'avoir encore les textes d'origine).

**La commande et l'espace client ne sont pas maquettés, volontairement** :
ils le seront quand le prochain numéro aura une date, sur un staging, avec
un vrai paiement d'essai (voir « Vente : contraintes à respecter »).

**Propositions non décidées** : les six numéros en lecture libre jusqu'au
prochain (recommandation de la critique) ; « 3 000 FCFA » est un prix
d'exemple ; lecture en ligne ; chaîne WhatsApp et lettre ; emplacement
publicitaire si AdSense reste. Il manque le **logo vectoriel** (PNG peu
lisible en petit, recoloré par filtre en mode sombre) et les **PDF des
numéros**.

**Thème, version 0.1.1 (2026-11-18)** dans `audace-magazine/`, ZIP dans
`dist/` (construit par `./release.sh`, qui vérifie versions, journal et
syntaxe). L'utilisateur a demandé un premier thème à installer et activer
lui-même sur la production pour essai. Contenu :

- Partie éditoriale complète : `front-page.php` (accueil), `single.php`,
  `archive.php` (rubrique, étiquette, auteur, date), `index.php` (tous les
  articles), `search.php`, `page.php`, `404.php`.
- `inc/contenu.php` : nettoyage à l'affichage (Divi, capitales, `h1`,
  balises vides, signature répétée, insécables, plan, chapô).
- `inc/kiosque.php` et `woocommerce.php` : habillage de WooCommerce
  (boutique, fiche, panier, commande, compte) sans toucher au parcours ;
  tout disparaît proprement si WooCommerce est inactif.
- **Absents de la 0.1.0** : la liseuse (il faut les PDF), les commentaires
  (non affichés, décision ouverte). La mise à jour depuis GitHub est arrivée
  en 0.1.1.

Essayé dans un WordPress 7.1.4 local (SQLite, PHP 8.5, WooCommerce 11.3)
avec les 60 articles réels et six numéros : aucune erreur ni avertissement
PHP sur tous les types de page, aucun débordement à 360, 768, 1024 et
1366 px sur huit pages. **Non essayé** : avec Extra encore présent dans la
base, Ultimate Membership Pro, Flutterwave, All in One SEO, Site Kit, la
langue française de WordPress, ni un paiement.

Leçons de cet essai, à ne pas refaire :

- **Ne jamais nommer une classe comme une classe de `body` de WordPress**
  (`archive`, `page`, `single`, `search`, `home`, `blog`, `category`…) :
  `.archive` bridait toute la page à 860 px.
- Les images de WordPress portent `width` et `height` : toujours
  `height: auto` en CSS, et ne pas forcer `aspect-ratio: auto` (avec
  `sizes="auto"` et le chargement différé, l'image réserve alors 1500 px).
- WooCommerce impose des largeurs en pourcentage à `li.product` : dans une
  grille CSS, les neutraliser avec plus de spécificité.
- Python 3.9 du système refuse les longues lignes accentuées sans
  `# -*- coding: utf-8 -*-`.

**En production depuis le 2026-11-18** : l'utilisateur a activé le thème
0.1.1 sur audacemagazine.com. Relevé du site après activation : accueil,
article, rubrique, recherche, fiche d'un numéro, panier, compte et pages
d'Ultimate Membership Pro s'affichent correctement, en français. Défauts
trouvés sur le kiosque et corrigés en **0.1.2** :

- la page boutique était une mise en page Divi : WooCommerce affiche son
  contenu au-dessus de la grille **sans passer par `the_content`**, donc les
  shortcodes Divi sortaient en clair (filtres `woocommerce_short_description`
  et `woocommerce_format_content`) ;
- le réglage WooCommerce « afficher les catégories », dormant sous Extra,
  faisait montrer des catégories par année au lieu des numéros (filtre sur
  les options, côté public seulement) ;
- WordPress avait affecté tout seul l'ancien menu d'Extra à l'emplacement
  « Rubriques » : on n'en garde que les rubriques, et il en faut trois ;
- l'en-tête collant passait sous la barre d'administration.

Pour reproduire un défaut de la production, recréer son état dans le
WordPress local (contenu Divi de la page, options, menu) avant de corriger.

**0.1.3 : fiche de partage** (demande de l'utilisateur après un essai sur
WhatsApp). `inc/visuels.php` compose avec GD deux images de 1200 × 630,
rangées dans `wp-content/uploads/audace-magazine/` et refaites seules quand
leur contenu change (le nom du fichier porte une empreinte des données et de
la version du thème) : le **kiosque** (couvertures des cinq derniers numéros
en éventail) et l'**accueil** (logo, titre et image de la une, trois
articles suivants). Sans GD ou en cas d'échec, repli sur l'image fixe
`assets/img/partage.png`. La police de titre est embarquée en TTF pour
écrire dans l'image. Descriptions propres à l'accueil, au kiosque, aux
rubriques. Devise retenue : « L'Afrique autrement » (celle des couvertures),
à confirmer par l'utilisateur. WhatsApp garde les anciens aperçus en mémoire
plusieurs jours pour un lien déjà partagé.

**Prochaine étape : la release 0.1.3 par l'utilisateur, puis sa mise à
jour depuis WordPress** (la mise à jour par WordPress sur l'hébergement
n'est pas encore confirmée).
Ancienne étape, pour mémoire : le retour de l'utilisateur après installation
sur la production (voir la procédure donnée : prévisualisation en direct, puis
activation, retour arrière en réactivant « Extra Child Theme »), puis la
liseuse dès que les PDF sont fournis.

## Vente : contraintes à respecter (analyse du 2026-11-18)

Principe validé : achat sans compte préalable, un seul espace client
(`/mon-compte/` de WooCommerce), une seule connexion en français. À
construire seulement quand un numéro sera à vendre. Points durs :

- **PayPal n'encaisse pas en francs CFA** (à vérifier sur le staging) : prix
  en FCFA = Flutterwave seul (carte, Mobile Money), ou PayPal en euros.
- **Le courriel est un identifiant fragile** : la page de confirmation doit
  ouvrir le numéro tout de suite ; demander aussi le téléphone.
- **Mobile Money n'est pas instantané** : écran d'attente, déblocage
  automatique à la confirmation. Point le plus risqué, jamais éprouvé.
- **Un PDF vendu circule sur WhatsApp** : lecture en ligne seule, ou PDF
  marqué au nom de l'acheteur.
- Formulaire de commande réduit au nom, courriel, téléphone (pas d'adresse
  postale) ; connexion par lien envoyé par courriel plutôt que mot de passe.
- Abonnement futur : préférer un « pass » prépayé (produit WooCommerce
  ordinaire, accès jusqu'à une échéance), compatible Mobile Money.

## Alléger la production (plan proposé, décisions de l'utilisateur)

Rien de ceci n'est fait ; tout est une écriture en production, donc sur
décision explicite, sauvegarde faite, idéalement après essai sur staging.

1. **Cache de page** (premier gain : l'attente du serveur est de 3 à 7 s),
   en excluant panier, commande et compte.
2. **Retirer Ultimate Membership Pro** : vérifier d'abord dans
   l'administration qu'il n'y a aucun abonné payant (le « zéro abonné » vient
   de l'utilisateur) ; désactiver sans supprimer ; pointer « Mon Compte »
   vers `/mon-compte/` et rediriger `/member-login/`, `/sinscrire/`,
   `/niveaux-dadhesion/` ; supprimer après une ou deux semaines.
3. **Purger** les 17 047 comptes et les 7 505 commentaires en attente,
   fermer l'inscription libre (irréversible : sauvegarde avant).
4. Trancher les pisteurs (Google Analytics 167 Ko, pixel Facebook, AdSense).
5. Les 15 mises à jour ; adresse interne en `https://`.
6. WooCommerce : le désactiver tant qu'il n'y a rien à vendre, ou le
   cantonner au kiosque dans le thème. À décider.

Si le serveur reste lent après cela, la cause est l'offre OVH.

## Ce que l'on sait du site (inventaire du 2026-11-18)

Le détail et les chiffres sont dans `docs/INVENTAIRE.md`. L'essentiel :

- Adresse : `https://www.audacemagazine.com`. WordPress 7.1.4, PHP 8.4,
  Extra 4.27.9 avec le thème enfant `extra-child-theme`, dont le `style.css`
  ne contient aucune règle (son `functions.php` n'est pas lisible de
  l'extérieur).
- **Hébergeur : OVH** (comme la radio). Aucun cache de page : 3 à 7 s
  d'attente du serveur par page.
- Contenu : 60 articles (49 en 2026), 12 rubriques, 8 articles Divi
  seulement (13 %, tous d'avant février 2026). Défauts de saisie : titres en
  capitales ou trop longs, `h1` dans le corps, images à la une trop petites
  (16 sous 800 px), un doublon, un article sans titre.
- **Kiosque** : la page `/kiosque/` est la boutique WooCommerce. 6 numéros
  (N°001 à N°005 et un hors-série), produits simples téléchargeables à 80 €.
  Aucune liseuse, aucun aperçu. Dernier numéro : sept.-nov. 2025.
- **Deux circuits séparés** : achat au numéro par WooCommerce (PayPal,
  Flutterwave) et abonnement par Ultimate Membership Pro (Gratuit,
  Trimestriel 80 €, Annuel 310 €, PayPal seul). Deux pages de connexion :
  le menu « Mon Compte » mène à `/member-login/` (Ultimate Membership Pro),
  l'espace client WooCommerce est à `/mon-compte/` et n'est lié nulle part.
  Tous les articles sont en accès libre.
- Poids sur mobile : accueil ≈ 3,75 Mo et 78 requêtes, article ≈ 1,56 Mo.
- Pages absentes : à propos, contact, mentions légales, conditions de vente.
- Signaux d'entretien, hors périmètre du thème, à rappeler à
  l'utilisateur : 7 505 commentaires en attente, 17 047 « New Users »
  (inscription gratuite ouverte), 15 mises à jour en attente, adresse
  interne du site en `http://` (Site Kit à reconnecter). Deux points de
  sécurité sont notés hors dépôt, dans `docs/PRIVE.md` (non versionné).

## Ce qui reste à mesurer (accès manquants)

- Le `functions.php` du thème enfant et les réglages d'Extra.
- Le fichier livré à l'acheteur, la page de commande et le paiement : non
  parcourus, pour ne rien écrire en production.
- Ce que l'abonnement débloque, les ventes réelles, la fréquentation.
- Qui publie et comment.
- Il faut un **staging avec SSH** ; à défaut, des captures de
  l'administration. Liste complète dans `docs/INVENTAIRE.md`, section 4.

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
- Staging : aucun connu à ce jour (à demander à l'utilisateur).
- Lecture du site public : une requête avec un User-Agent de navigateur
  répond 200. L'hébergeur est OVH, comme la radio, qui bannit les rafales :
  rester sobre, une requête à la fois, espacées d'une à trois secondes.
  L'inventaire (environ 115 requêtes ainsi espacées) n'a provoqué aucun
  blocage.
- **Incident du 2026-11-18** : douze requêtes à trois secondes d'intervalle
  ont toutes reçu un 403 ; l'IP était déjà refusée et j'ai continué. Cette
  IP est aussi celle du navigateur de l'utilisateur. Règle : **toujours une
  seule requête d'abord, et s'arrêter net au premier 403** ; puis une page
  toutes les six secondes au moins. Le blocage s'est levé en quelques
  minutes.
- Ne pas remplir de panier ni envoyer de formulaire en production : le
  parcours d'achat se teste sur un staging.
- Secrets et identifiants : jamais dans ce dépôt ni dans les sorties.

## Dépôt et mises à jour

- Dépôt GitHub `lwilly3/audace-magazine-theme`, **public** depuis le
  2026-11-18 (décision de l'utilisateur), comme celui du thème radio : un
  site ne peut lire les releases que d'un dépôt public (privé = HTTP 404).
- **Tout ce qui est commité est public.** Ni secret, ni identifiant de
  connexion, ni adresse personnelle, ni faiblesse de sécurité du site : ces
  notes vont dans `docs/PRIVE.md`, ignoré par git.
- Mise à jour du thème : `audace-magazine/inc/miseajour.php`, repris du
  thème radio. Le site lit `github.com/<dépôt>/releases/latest` (la
  redirection donne le numéro de version), puis l'API en repli ; le paquet
  est l'asset `audace-magazine.zip` de la release. Réponse gardée 12 heures ;
  lien « Vérifier les mises à jour maintenant » dans Apparence › Thèmes.
- Publier une version : incrémenter la version dans `style.css` **et**
  `functions.php`, ajouter la section dans `CHANGELOG.md`, commiter, pousser
  sur `main`, puis `./release.sh` (`--essai` pour tout vérifier sans
  publier). **C'est l'utilisateur qui lance `./release.sh`** : publier une
  release crée une surface publique et propose la mise à jour au site.
- Le site doit d'abord recevoir à la main une version qui contient le module
  de mise à jour (0.1.1 ou plus) ; ensuite tout passe par WordPress.

## Arborescence prévue

```text
audace-magazine/   # le thème (0.1.0)
dist/              # ZIP installables, non versionnés (./release.sh)
design/            # maquettes HTML statiques
docs/              # INVENTAIRE.md (fait), puis documentation de conception
```

## Commits

Messages conventionnels en français. Auteur configuré localement avec
l'adresse noreply GitHub : ne pas la changer.

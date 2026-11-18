# INVENTAIRE.md — Inventaire du site audacemagazine.com

Relevé du **2026-11-18**, entièrement depuis l'extérieur et en lecture seule :
API REST publique de WordPress et de WooCommerce, pages publiques lues avec un
User-Agent de navigateur mobile, environ 115 requêtes espacées (1 à 3 s).
Aucune écriture sur le site : rien n'a été ajouté au panier, aucun formulaire
n'a été envoyé, aucun compte n'a été créé.

Ce document ne propose aucun design. Il décrit l'existant, ce qui n'a pas pu
être mesuré, et les décisions à prendre avant les maquettes.

## En bref

- **Petit fonds éditorial** : 60 articles, dont 49 publiés en 2026. Le
  contenu est propre côté technique (8 articles Divi seulement), les défauts
  sont surtout de saisie (titres, images trop petites, rubriques).
- **Le kiosque est une boutique WooCommerce de 6 numéros** vendus 80 € pièce,
  en téléchargement. Aucune liseuse, aucun extrait gratuit. Le dernier numéro
  (N°005) date de septembre-novembre 2025.
- **Deux circuits de vente coexistent sans se parler** : l'achat au numéro
  (WooCommerce, PayPal ou Flutterwave) et l'abonnement (Ultimate Membership
  Pro, PayPal seul). Chacun a sa propre page de connexion. Rien n'indique au
  visiteur ce que l'abonnement donne par rapport à l'achat d'un numéro.
- **Le site est lent côté serveur** : 3 à 7 s avant le premier octet sur
  chaque page, sans cache de page. L'accueil pèse environ 3,7 Mo sur mobile.
- **Hébergeur identifié : OVH**, comme la radio. Même prudence sur les rafales.

## 1. Contenu éditorial

### Volume et rythme

60 articles publiés, 130 médias, 56 étiquettes, 15 pages.

| Année | Articles |
|---|---|
| 2015 | 1 (article sans titre, id 221, sans doute un reste d'import) |
| 2021 | 4 |
| 2022 | 1 |
| 2023, 2024 | 0 |
| 2025 | 5 (d'octobre à décembre) |
| 2026 | 49 |

Rythme de 2026, très irrégulier : janvier 7, février 3, mars 10, avril 1,
mai 2, juin 12, juillet 1, août 9, septembre 1, octobre 3, novembre 0 à ce
jour. Dernier article : 22 octobre 2026. La publication se fait par paquets
(12 articles entre le 24 et le 30 juin).

Longueur : médiane 713 mots, de 134 à 3 468 mots. Ce sont des articles de
fond, pas des brèves.

### Rubriques

| Rubrique | Articles | Remarque |
|---|---|---|
| Economie | 28 | Au menu. Sans accent |
| Politique | 22 | Au menu |
| Société | 15 | Absente du menu |
| Diplomatie | 6 | |
| Culture | 5 | Contient des articles de football |
| Environnement | 5 | |
| Dernières Heure | 3 | Faute dans le nom |
| Santé | 3 | |
| Non classé | 3 | |
| Nos Articles | 2 | **Au menu**, alors qu'elle ne contient que 2 articles |
| Portrait | 2 | |
| Sport | 1 | Le sport est dispersé dans Culture et Politique |

47 articles ont une seule rubrique, 13 en ont deux ou trois. Les étiquettes
ne servent presque pas : 5 articles sur 60 en portent.

8 articles sont « épinglés » (sticky), ce qui fige une partie de l'accueil.

### Auteurs

| Signature publique | Articles |
|---|---|
| audace magazine (compte principal de la rédaction) | 39 |
| René Narcisse MOTTO | 14 |
| audace magazine (second compte de la rédaction) | 6 |
| richard tong | 1 |

Les trois quarts des articles sont signés du nom du magazine, par deux
comptes différents. Un seul journaliste est réellement identifié. Plusieurs
articles répètent une signature dans le corps du texte (« Audace Magazine |
Environnement et économie | 19 octobre 2026 »).

L'API publique liste 7 comptes. Hors périmètre du thème (voir section 5).

### Part du contenu Divi

**8 articles sur 60 (13 %)** sont construits avec Divi, tous publiés entre
2021 et janvier 2026. Depuis février 2026, tous les articles sont écrits dans
l'éditeur normal. Le nettoyage à l'affichage du thème radio
(`inc/contenu.php`) suffira, sur un volume faible.

En revanche, les **pages** du kiosque, du compte et de la fiche produit sont
des mises en page Divi (`et_pb_pagebuilder_layout`).

### Images à la une

- 55 articles sur 60 en ont une ; 5 n'en ont pas.
- Proportions : 3:2 (23), 16:9 (18), 4:3 (7), carré (3), panoramique (3),
  portrait (1). Aucune proportion dominante.
- Formats : JPEG 30, PNG 19, WebP 5, GIF 1. Les PNG sont des photos ou des
  captures, donc lourds sans raison.
- Largeur : médiane 1 071 px. **16 images font moins de 800 px de large**,
  32 moins de 1 200 px. La plus petite fait 200 × 200.
- D'après les noms de fichiers, une part visible des images de 2026 sont des
  images générées (« ChatGPT Image… ») ou des captures d'écran (« Capture
  d'écran… »).

### Extraits

39 articles ont un extrait rédigé, 21 n'ont que l'extrait automatique (le
début du texte coupé). Estimation par comparaison de l'extrait avec le début
de l'article.

### Défauts récurrents

| Défaut | Articles |
|---|---|
| Paragraphes vides ou espaces insécables en série | 30 |
| Longs passages entièrement en gras | 28 |
| Titre de plus de 110 caractères | 11 |
| Titre entièrement en capitales | 5 |
| `h1` dans le corps de l'article | 4 |
| Titre avec des astérisques de Markdown (`**…**`) | 2 |
| Titre en caractères Unicode « gras » (illisibles par les lecteurs d'écran et les moteurs) | 1 |
| Titre contenant une balise `<strong>` | 1 |
| Article sans titre | 1 |
| Article publié en double (id 772 et 774, même titre, même texte) | 1 paire |

Point positif : 46 articles ont de vrais intertitres (`h2`/`h3`).

### Commentaires

Le formulaire de commentaire est ouvert sur les articles. 672 commentaires
sont publiés, 7 505 en attente d'après l'administration. Leur nature (vrais
lecteurs ou indésirables) n'a pas été vérifiée.

## 2. Kiosque (le magazine en ligne)

### Comment un numéro est publié

Chaque numéro est un **produit WooCommerce simple, téléchargeable**. La page
`/kiosque/` (id 344) est la page boutique de WooCommerce.

| Numéro | Période | Prix | Adresse |
|---|---|---|---|
| N°005 – Présidentielle Cameroun 2025 | sept.-nov. 2025 | 80 € | `/produit/audace-magazine-n006-…` |
| N°004 – Comment la France perd l'Afrique | avril-mai 2024 | 80 € | `/produit/audace-magazine-n004-…` |
| N°003 – Femmes leaders africaines | novembre 2023 | 80 € | `/produit/audace-magazine-n003-…` |
| Hors-série – Spécial Gouvernement Cameroun | mai 2023 | 80 € | `/produit/audace-magazine-hors-serie/` |
| N°002 – Paul Biya, le bâtisseur | juillet-août 2022 | 80 € | `/produit/audace-magazine-n002-…` |
| N°001 – Tchad : Mahamat Idriss Déby | sept.-oct. 2021 | 80 € | `/produit/album/` |

Six numéros en cinq ans, un par an environ. **Aucun numéro depuis plus d'un
an**, alors que le site publie des articles chaque mois.

### Comment on le lit

- Le produit est marqué « téléchargeable » : après paiement, l'acheteur
  reçoit un lien de téléchargement, très probablement un PDF. **Non vérifié**,
  faute d'achat.
- **Aucune liseuse** : aucune extension de feuilletage n'apparaît dans les
  pages publiques.
- **Aucun aperçu** : ni extrait, ni pages feuilletables, ni sommaire visuel.
  On achète sur la seule couverture et un sommaire en texte.

### Gratuit et payant

- **Gratuit** : tous les articles du site. L'article testé est lisible en
  entier sans compte. Aucune restriction d'accès n'a été observée.
- **Payant** : les numéros (80 € pièce) et deux abonnements.

### Niveaux d'abonnement (Ultimate Membership Pro)

Page `/niveaux-dadhesion/`, absente du menu.

| Niveau | Prix affiché | Texte affiché |
|---|---|---|
| Gratuit | « S'inscrire maintenant! » | « Niveau gratuit permettant un accès limité à la plupart de nos contenus. » |
| Trimestriel | « Seulement 80 Euro » | « Abonnement pour 3 mois » |
| Annuel | « Seulement 310 Euro » | « Contenu de qualité! Il s'agit d'un paiement annuel. » |

Nulle part il n'est écrit ce que l'abonnement donne : accès aux numéros,
auxquels, sous quelle forme. L'abonnement de trois mois coûte le prix d'un
seul numéro.

### Moyens de paiement

| Circuit | Moyens proposés |
|---|---|
| Achat d'un numéro (WooCommerce) | PayPal, Flutterwave (`tbz_rave`) |
| Abonnement (Ultimate Membership Pro) | PayPal, PayPal Express Checkout |

Tout est affiché en euros. Les moyens réellement utilisés par les acheteurs
(et le nombre de ventes) ne sont visibles que dans l'administration.

### Parcours du visiteur

**Achat d'un numéro**

1. Menu → Kiosque : grille de six couvertures avec titre et prix.
2. Fiche du numéro : couverture, titre, prix, phrase de présentation, champ
   de quantité, bouton « Ajouter au panier », puis un long sommaire.
3. Panier (`/panier/`).
4. Validation de la commande (`/commander/`) : **non parcourue**, car il
   aurait fallu remplir un panier sur le site de production.
5. Paiement par PayPal ou Flutterwave, puis téléchargement.

**Abonnement**

1. Menu → Mon Compte → redirection vers « Member Login ».
2. Lien « S'inscrire » → page des niveaux d'adhésion.
3. Bouton « Inscription » d'un niveau → formulaire `/sinscrire/?lid=N`.
4. Paiement PayPal.

Aucun lien ne mène du kiosque vers l'abonnement, ni l'inverse.

### Ce qui est lent, confus ou daté

**Confus**

- **Deux comptes pour un seul site.** Le menu « Mon Compte » pointe sur
  `/my-account/`, qui redirige vers la connexion d'Ultimate Membership Pro
  (`/member-login/`). L'espace client de WooCommerce, celui où l'acheteur
  retrouve ses téléchargements, est à `/mon-compte/` et n'est lié nulle part.
- **Deux offres sans rapport affiché** : numéro à 80 €, trimestre à 80 €,
  année à 310 €. Le visiteur ne peut pas comparer.
- **La page des abonnements est introuvable** depuis le menu ou le kiosque.
- **Adresses incohérentes** : le N°005 a une adresse en « n006 », le N°001
  s'appelle `/produit/album/`.
- **Un champ de quantité** sur un magazine numérique (jusqu'à 9 999
  exemplaires).
- Le produit est téléchargeable mais pas marqué « virtuel » : la commande
  peut demander une adresse de livraison. À vérifier dans l'administration.
- Le niveau gratuit promet « un accès limité à la plupart de nos contenus »
  alors que tout est déjà en accès libre.

**Daté ou non fini**

- **Mélange d'anglais et de français** : « Member Login », « Username or
  Email », « Keep me signed in », « Accept our Terms&Conditions », « Select
  Payment », liste des pays en anglais.
- Le formulaire d'inscription demande dix champs, dont un avatar à
  téléverser et un code promo, avant tout paiement.
- La page des conditions s'appelle « Member TOS Page » et contient un texte
  sur les données personnelles, pas des conditions de vente.
- Des pages d'installation sont restées publiques : « Default Redirect
  Page », « Public Individual Page », « Page d'exemple », « Boutique » (en
  double du kiosque), deux « Politique de confidentialité ».
- Les couvertures, au format portrait, sont **recadrées en carrés de
  300 px** dans la grille : titres et visages coupés.
- La fiche ne dit ni le nombre de pages, ni le format du fichier, ni son
  poids, ni comment se passe la lecture après achat. Les rubriques du
  sommaire sont marquées par des émojis.
- Dans la colonne de droite de toutes les pages, la couverture du N°005 est
  chargée en taille réelle (516 Ko), en `http://`, avec le texte alternatif
  « audace magazine 001 ».

**Lent**

- 3 à 6 s d'attente du serveur à chaque étape (kiosque 4,6 s, fiche 4,3 s,
  connexion 5,9 s, commande 5,7 s).
- La page Kiosque charge 27 scripts et 14 feuilles de style, dont tous les
  scripts de la commande (pays, adresse, paiement, zoom, diaporama) alors
  qu'elle n'affiche qu'une grille.

## 3. Technique

### Hébergement et serveur

- **OVH** : adresse 145.239.37.162, serveurs de noms et courrier chez OVH.
  Apache, PHP 8.4, HTTP/2, compression gzip.
- **Aucun cache de page** détecté. Temps avant le premier octet : accueil
  3,7 s, article 7,3 s (avec une redirection), pages du parcours 3 à 6 s. La
  racine de l'API a mis 28 s à répondre.
- Fichiers statiques mis en cache 15 minutes seulement.
- L'adresse interne de WordPress est encore en `http://` (l'adresse publique
  est en `https://`). C'est l'origine probable de l'image en `http://` et de
  l'avertissement « Site Kit à reconnecter ».
- `http://audacemagazine.com` redirige d'abord vers `http://www…`, puis vers
  `https://` : deux redirections au lieu d'une.

### Thème

- Extra 4.27.9 et thème enfant `extra-child-theme` (auteur « AMG », version
  « 9 oct 2025 »).
- **La feuille de style du thème enfant ne contient aucune règle CSS**,
  seulement son en-tête (1,5 Ko). Elle est pourtant chargée deux fois par
  page.
- Son en-tête décrit son rôle : remplacer la mention de pied de page d'Extra
  et accueillir du CSS et des fonctions PHP. Le fichier `functions.php` n'est
  pas lisible de l'extérieur.
- Le pied de page vient du Theme Builder de Divi et ne contient qu'une
  adresse de courriel. Pas de mentions légales, pas de liens, pas de réseaux.
- Chaque page embarque environ 100 Ko de CSS en ligne généré par Extra.
- La balise `viewport` limite le zoom (`maximum-scale=1.0`).

### Extensions qui agissent sur l'affichage public

| Extension | Effet sur les pages publiques |
|---|---|
| WooCommerce 10.2.4 | Styles sur toutes les pages ; sur le kiosque et les fiches, une vingtaine de scripts |
| Ultimate Membership Pro | 2 feuilles de style, jQuery UI et un script **sur toutes les pages**, y compris les articles |
| Site Kit de Google | Google Analytics (`gtag`, 167 Ko) et balise AdSense |
| Facebook for WooCommerce | Pixel Facebook sur toutes les pages |
| Audace Analytics 0.2.0 | Script de mesure, léger |
| Extra | Google Fonts (Roboto en 12 variantes), appel à Google Maps **sans clé** sur l'accueil, lecteur audio/vidéo de WordPress |

**All in One SEO ne laisse aucune trace** : pas de description, pas de
données structurées sur l'accueil ni sur les articles, et le plan de site est
celui de WordPress par défaut. Elle semble inactive ou non réglée. Les fiches
produit ont des données structurées, fournies par WooCommerce.

LightStart (mode maintenance) n'est pas actif côté public.

### Pages existantes (15)

| Page | Adresse | Rôle |
|---|---|---|
| Kiosque | `/kiosque/` | Boutique WooCommerce (Divi) |
| Panier | `/panier/` | WooCommerce |
| Validation de la commande | `/commander/` | WooCommerce |
| Mon compte | `/mon-compte/` | Espace client WooCommerce, non lié |
| Boutique | `/boutique/` | Reste, en double du kiosque |
| Member Login | `/member-login/` | Connexion Ultimate Membership Pro |
| S'inscrire | `/sinscrire/` | Inscription (renvoie aux niveaux sans `?lid=`) |
| Niveaux d'adhésion | `/niveaux-dadhesion/` | Offres d'abonnement |
| Mot de passe oublié | `/mot-de-passe-oublie/` | Ultimate Membership Pro |
| Member TOS Page | `/member-tos-page/` | Conditions (texte sur les données) |
| Default Redirect Page | `/default-redirect-page/` | Reste d'installation |
| Public Individual Page | `/public-individual-page/` | Reste d'installation |
| Politique de confidentialité | `/politique-de-confidentialite/` et `-2/` | En double |
| Page d'exemple | `/page-d-exemple/` | Reste d'installation |

Il n'existe **ni page « À propos », ni contact, ni mentions légales, ni
conditions générales de vente, ni page de l'équipe**. L'accueil n'est pas une
page : c'est le flux des articles habillé par une mise en page d'Extra.

### Poids et vitesse sur mobile

Poids transféré (compressé), mesuré ressource par ressource.

| | Accueil | Article |
|---|---|---|
| Attente du serveur | 3,7 s | 7,3 s |
| HTML | 33 Ko | 38 Ko |
| CSS | 96 Ko (12 fichiers) | 92 Ko (10) |
| JavaScript | 488 Ko (22 fichiers) | 347 Ko (18) |
| Images | 3 022 Ko (39) | 968 Ko (8) |
| Polices | 114 Ko | 114 Ko |
| **Total** | **≈ 3,75 Mo, 78 requêtes** | **≈ 1,56 Mo, 41 requêtes** |

- Sur l'accueil, **aucune des 48 images n'est chargée en différé** et aucune
  n'a de variantes adaptées à l'écran (`srcset`). Un téléphone reçoit des
  images de 1 280 px de large.
- Les images les plus lourdes sont des PNG de photos (jusqu'à 380 Ko) et la
  couverture du N°005 de la colonne de droite (516 Ko, sur toutes les pages).
- L'accueil a trois titres `h1`, tous « Tous », et aucun titre de site.
- L'accueil n'a ni description, ni balises de partage, ni données
  structurées.

**Non mesuré** : les indicateurs de rendu (LCP, CLS, temps de blocage).
Seuls l'attente du serveur et le poids ont été relevés ; une mesure
Lighthouse reste à faire.

## 4. Ce qui manque, et les accès nécessaires

| Ce que je n'ai pas pu savoir | Accès qui le permet |
|---|---|
| Contenu de `functions.php` du thème enfant, CSS personnalisé d'Extra, réglages du Theme Builder | **SSH** ou SFTP en lecture, ou une archive du dossier `wp-content/themes/extra-child-theme` et un export des options du thème |
| Le fichier livré à l'acheteur (PDF ? poids ? nombre de pages ?), limites de téléchargement, produit virtuel ou non | Capture de l'onglet « Général » d'un produit dans l'administration, ou un accès administrateur en lecture |
| La page de commande et le paiement réels | **Un staging** (copie du site) où remplir un panier et payer en mode test ; à défaut, des captures de chaque étape faites par vous |
| Ce que l'abonnement débloque réellement, règles d'accès, nombre d'abonnés par niveau | Captures d'Ultimate Membership Pro : niveaux, règles d'accès, liste des membres |
| Ventes réelles : nombre de commandes, numéros vendus, moyen de paiement utilisé, pays des acheteurs | Captures de WooCommerce → Commandes et Statistiques, ou un export CSV des commandes |
| Brouillons, articles privés, révisions, comptes réels parmi les 17 047 inscrits | Accès administrateur en lecture, ou export WordPress (Outils → Exporter) |
| Liste complète des extensions actives et leurs réglages (cache, SEO, clés Flutterwave en mode test ou réel) | Capture de la page Extensions et de Santé du site |
| Fréquentation : pages vues, part du mobile, pays, part du kiosque | Accès à Audace Analytics et à Google Analytics |
| Qui publie et avec quel outil (éditeur WordPress, autre) | Votre réponse |
| Les PDF d'origine et les couvertures en haute définition des six numéros | Les fichiers eux-mêmes, pour les maquettes |
| Logo vectoriel, charte, polices de l'édition papier | Les fichiers eux-mêmes |
| Mesure de vitesse de rendu | Rien à fournir : je peux lancer Lighthouse sur accord |

Le plus utile, dans l'ordre : **un staging avec SSH** (il couvre presque tout
le tableau), puis les PDF des numéros, puis les chiffres de vente.

## 5. Signaux hors périmètre du thème

À traiter par vous ou sur décision séparée ; le thème n'y changera rien.

- Deux points de sécurité relevés de l'extérieur, communiqués à l'éditeur et
  gardés hors de ce dépôt public.
- 7 505 commentaires en attente, formulaire de commentaire ouvert.
- 15 mises à jour en attente.
- Adresse interne du site encore en `http://`.
- Aucun cache de page.
- Article en double (id 772 et 774), article sans titre (id 221).

## 6. Décisions à prendre avant les maquettes

1. **Le modèle de vente.** Achat au numéro, abonnement, ou les deux ? Si les
   deux, que donne l'abonnement (tous les numéros, les nouveaux seulement,
   des articles réservés) ? C'est la décision qui commande tout le kiosque.
2. **Un seul compte.** Garder Ultimate Membership Pro, ou tout ramener dans
   WooCommerce (compte, téléchargements, abonnement) ? Deux connexions ne
   peuvent pas être habillées proprement.
3. **Les prix et la monnaie.** 80 € le numéro et 80 € le trimestre : est-ce
   voulu ? Faut-il afficher des francs CFA, et pour quel public (Cameroun,
   diaspora, institutions) ?
4. **La lecture.** Rester sur un PDF à télécharger, ou lire en ligne
   (liseuse) ? Offrir un aperçu gratuit de quelques pages ?
5. **Le rythme du magazine.** Un nouveau numéro est-il prévu, et à quelle
   fréquence ? Le kiosque se conçoit différemment pour six numéros figés et
   pour une parution régulière.
6. **Le lien entre le site et le magazine.** Les articles du site restent-ils
   tous gratuits ? Les articles d'un numéro ont-ils vocation à paraître en
   ligne, en entier ou en extrait ?
7. **Les rubriques.** Liste définitive et ordre du menu : sort de « Nos
   Articles », « Dernières Heure », « Non classé » ; place de Société, Sport,
   Diplomatie.
8. **La signature.** Articles signés par des journalistes nommés ou par la
   rédaction ? Faut-il des pages d'auteur ?
9. **Les commentaires.** Les garder, les fermer, ou les remplacer ?
10. **Les pages manquantes.** À propos, contact, mentions légales,
    conditions de vente : qui fournit les textes ?
11. **Les pisteurs.** Garder Google Analytics, AdSense et le pixel Facebook,
    ou s'en tenir à Audace Analytics ? Cela décide du bandeau de
    consentement.
12. **L'environnement d'essai.** Créer un staging chez OVH avec accès SSH,
    avant toute maquette du parcours d'achat ?
13. **Le nettoyage du contenu.** Corriger les titres, doublons et images
    trop petites dans WordPress (écriture sur le site, à décider), ou
    seulement les rattraper à l'affichage ?

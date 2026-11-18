# Thème Audace Magazine

Thème WordPress sur mesure d'[Audace Magazine](https://www.audacemagazine.com) :
l'information et le magazine en ligne.

## Contenu du dépôt

| Dossier | Rôle |
|---|---|
| `audace-magazine/` | Le thème. C'est ce dossier qui est livré au site. |
| `design/` | Maquettes HTML statiques (ouvrir `design/index.html`). |
| `docs/` | Inventaire du site et documentation de conception. |
| `release.sh` | Vérifie le thème, construit le ZIP et publie la release. |

## Installer le thème

La première fois, à la main : télécharger `audace-magazine.zip` depuis la
[dernière release](https://github.com/lwilly3/audace-magazine-theme/releases/latest),
puis dans WordPress : Apparence › Thèmes › Ajouter › Téléverser.

Ensuite, les nouvelles versions apparaissent dans WordPress comme pour
n'importe quel thème : Apparence › Thèmes, ou Tableau de bord › Mises à jour.
Le lien « Vérifier les mises à jour maintenant » force la vérification.

## Publier une version

1. Incrémenter la version dans `audace-magazine/style.css` et dans
   `audace-magazine/functions.php` (les deux doivent être identiques).
2. Ajouter la section `## [X.Y.Z] - AAAA-MM-JJ` dans `CHANGELOG.md`.
3. Commiter et pousser sur `main`.
4. Lancer `./release.sh` (ou `./release.sh --essai` pour tout vérifier sans
   rien publier).

Prérequis : `gh` authentifié, `php` et `zip` disponibles.

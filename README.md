# L'application
Ce site a pour but de vendre et acheter des composants custom pour reactjs.

## Installer 

Clonez le répertoire puis l'ouvrir avec un éditeur de code.
Faire ``composer install`` pour installer les dépendances.

## Setup la base de données

- Tout d'abord il faut créer la base de données avec : 
``php bin/console doctrine:database:create``
- Puis faire : ``php bin/console d:s:u --force``
- Et ensuite faire ``php bin/console doctrine:fixture:load`` pour remplir la base de données

## Structure de la base de données 

La table Component est la table qui contiendra l'ensemble des mes composants custom et chaque composant peut contenir des variables.
C'est pour cela qu'il y a la table Variables.


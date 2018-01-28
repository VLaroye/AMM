# AMM
Site de l'association Art Mass et Mess (AMM). Association ayant pour objectif l'organisation d'évènements
culturels en Flandres.

## Stack technique
* Back :
    * PHP 7.1
    * Symfony 4
    * MySQL
* Front :
    * HTML/SCSS/JS
## PHP extensions
* XDebug

## Installation
### Cloner le projet
Aller dans le répertoire dans lequel vous souhaitez installer le projet.
Par exemple : `xampp/htdocs` :
    
 ```
 cd xampp/htdocs
 git clone METTRE ADRESSE GITHUB PROJET
 ```

### Installation des dépendances
Pour installer les dépendances, vous aurez besoin de <a href="https://getcomposer.org/">Composer</a> <br>
Dans le répertoire du projet :

````
composer install
````

### Base de donnée
   * Créer une base de donnée vide
   * Modifier la partie `Doctrine/Doctrine-Bundle` de votre fichier `.env` avec les informations de votre base de donnée
   * Depuis le répertoire du projet :
   ````
   php bin/console doctrine:schema:update --force
   ````

Le site est prêt !

### Accès au panneau d'administration
L'application utilise 2 types de comptes:
* `admin`. Comptes que l'on peut créer/supprimer depuis le panneau d'administration. Accès a toutes les fonctionnalités, en dehors de la gestion des utilisateurs.
* `super-admin`. Comptes que l'on ne peut créer que depuis `phpMyAdmin`. Accès à toutes les fonctionnalités, y compris la gestion des utilisateurs.

Pour démarrer, il faut donc créer un compte `super-admin`.
* Se connecter à `phpMyAdmin`
* Aller dans la table `user`
* Insérer une nouvelle ligne :
    * `id` : Laisser ce champ vide
    * `username` : Insérer le nom de compte qui servira pour la connexion
    * `password` : Le mot de passe qui servira à la connexion. Celui-ci doit être encodé avant d'être insérer dans la base de donnée. Pour cela, ouvrir le terminal dans le dossier du projet:
        ````
        php bin/console security:encode-password
        ````
        Entrer le mot de passe, puis appuyer sur Entrée. Le mot de passé encodé apparaît dans le terminal. Il ne reste plus qu'à Copier/Coller cette valeur dans le champ `password` de la base de donnée.
    * `role` : `ROLE_SUPER_ADMIN`
    * Valider. Le compte a été créé.
    * Vous pouvez à présent vous connecter avec ces identifiants, et accèder au panneau d'administration.
    
## Assets
La compilation des assets se fait avec `gulp` (lui même installé par `npm`)<br>
Pour démarrer :
````
cd assets
npm install
````

Pour modifier le CSS du projet. Depuis le dossier `assets`, lancer la commande `gulp`. A chaque sauvegarde d'un fichier scss modifié, `gulp` se chargera de lancer les tâches nécessaires à leur compilation.
# P8 : Todo List

[![SymfonyInsight](https://insight.symfony.com/projects/cf18ef52-2e6c-4aa4-ab4c-881b59ecfce1/big.svg)](https://insight.symfony.com/projects/cf18ef52-2e6c-4aa4-ab4c-881b59ecfce1)

## Projet 8 du parcours Openclassroms

### Contexte

Ce projet est réalisé dans le cadre de la formation de développeur d'application PHP/Symfony chez OpenClassrooms.

La mission est de reprendre le développement d'une application type "Todo List" utilisant des versions assez anciennes afin de corriger le code et implementer de nouvelles fonctionnalités.

Voici les différentes technologies utilisées dans ce projet :
- Symfony - Php - Html - Css - Javascript - Boostrap - PhpUnit

### Installation

Cloner le projet : 
```bash
gh repo clone https://github.com/boaicha/todo.git
```

Modifier les variables d'environnement "DATABASE_URL" dans .env et .env.test :
```bash
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=14&charset=utf8"
```

Installer les dépendances avec Composer : 
```bash
composer install
```

Créer la base de donnée : 
```bash
php bin/console doctrine:database:create --env=test
```

Créer les tables de la base de données

```bash
php bin/console doctrine:schema:update --force --env=test
```

Insérer un jeu de données via la commande :

```bash
php bin/console doctrine:fixtures:load --env=test
```

Lancer le projet : 

```bash
symfony server:start
```

Se connecter avec les identifiants suivant (ADMIN) : 

```bash
nom d\'utilisateur: jaja
mot de passe: password
```

Se connecter avec les identifiants suivant (USER) : 

```bash
nom d\'utilisateur: simon
mot de passe: password
```

Lancer la couverture de test : 

```bash
php vendor/bin/phpunit --coverage-html coverage
```
Vous trouverez les rapports de couvertures de tests dans le dossier "Coverage" à la racine du projet.

Et tout devrait fonctionner sans soucis !


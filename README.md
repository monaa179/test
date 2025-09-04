# API de Gestion de Plats

Une API RESTful pour gérer une carte de restaurant, développée avec Symfony et Doctrine.

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- PostgreSQL
- Symfony CLI

## Installation

1. Cloner le dépôt :
```bash
git clone git@github.com:monaa179/test.git
cd test
cd dish_project
```

2. Configurer la base de données :
```bash
composer install
   ```

3. Installer les dépendances :
```bash
# Créer la base de données
php bin/console doctrine:database:create
# Exécuter les migrations
php bin/console doctrine:migrations:migrate

   ```
4. Démarrer le serveur de développement:
```bash
symfony server:start

   ```
### Documentation de l'API - Postman
[API des Plats](https://mona-eb3f601e-2946837.postman.co/workspace/mona's-Workspace~ccd39125-a75f-443b-8793-3bd4cce7a5f2/request/48083061-7ca9aba9-2f02-45e5-9019-99cd0f464e8f?action=share&source=copy-link&creator=48083061... "Documentation complète")

<!-- db app
user mona
psql -U mona -d app -h 127.0.0.1 -p 5432

migration dish /Version20250904101509.php

documentation API avec Postman : https://mona-eb3f601e-2946837.postman.co/workspace/mona's-Workspace~ccd39125-a75f-443b-8793-3bd4cce7a5f2/request/48083061-7ca9aba9-2f02-45e5-9019-99cd0f464e8f?action=share&source=copy-link&creator=48083061 -->

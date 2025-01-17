# Installation

Temps passé : 2 jours à temps plein

## Prérequis

- [PHP 8 <=](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/download/)
- Wamp, Xamp, Mamp ou autre serveur local


- Cloner le projet et se rendre dans la branche `main`
- Installer les dépendances avec la commande `composer install`
- Générer les clés SSH avec la commande `php bin/console lexik:jwt:generate-keypair`
- modifier le fichier `.env` pour configurer la base de données dans la section `DATABASE_URL`
- Installer les fixtures avec la commande `php bin/console doctrine:fixtures:load`
- Lancer le serveur avec la commande `symfony server:start` ou `php -S localhost:8000 -t public` PORT 8000 obligatoire
- Se rendre sur la page `http://localhost:8000/api` pour voir la documentation de l'API

# Fonctionnalités

- Authentification JWT : `email: mink@mink.com`, `password: mink`
- CRUD sur les entités `Animmal`, `Race`, `Species`
- Gestion des images
- Gestion de la visibilité des champs via les groupes de sérialisation
- Tests unitaires : `php bin/phpunit`

# Dépendances

- [API Platform](https://api-platform.com/)
- [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle)
- [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle)
- [PHPUnit](https://phpunit.de/)

# Contact

- [Email](mailto:nicolas.castex.pro@gmail.com) : nicolas.castex.pro@gmail.com
- [Telephone]() : 06 78 23 64 60


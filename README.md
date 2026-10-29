# Gestion de Stock

Application web de gestion de stock développée avec **Laravel**.

## Fonctionnalités

- Authentification et rôles (administrateur, gestionnaire, magasinier)
- Produits, catégories et fournisseurs
- Mouvements de stock (entrées, sorties, ajustements, retours) avec historique
- Alertes de stock faible sur le tableau de bord
- Commandes d'achat : création, envoi, réception (génère les entrées de stock), annulation

## Prérequis

- PHP 8.3 ou plus, Composer
- Node.js 20 ou plus
- MySQL / MariaDB (par exemple via XAMPP)

## Installation

```bash
git clone https://github.com/idohouchristian76-lgtm/gestion-stock.git
cd gestion-stock
composer install
cp .env.example .env
php artisan key:generate
```

Créer la base de données :

```bash
mysql -u root -e "CREATE DATABASE gestion_stock CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Puis :

```bash
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

L'application est disponible sur http://127.0.0.1:8000.

## Comptes de démonstration

| E-mail | Rôle | Mot de passe |
|---|---|---|
| admin@stock.test | administrateur | password |
| gestionnaire@stock.test | gestionnaire | password |
| magasinier@stock.test | magasinier | password |

Ces comptes sont réservés au développement : changer les mots de passe avant tout déploiement.

## Licence

MIT

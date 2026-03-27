# 🔧 Service Finder Tunisia

Application web pour connecter les clients avec les prestataires de services locaux en Tunisie.

## 📋 Prérequis

- [XAMPP](https://www.apachefriends.org/) ou [WAMP](https://www.wampserver.com/) installé
- PHP 7.4+ avec MySQLi
- MySQL 5.7+

## 🚀 Installation (XAMPP)

### 1. Copier le projet
Copiez le dossier `service-finder-tunisia` dans :
```
C:\xampp\htdocs\service-finder-tunisia
```

### 2. Démarrer les services
Ouvrez **XAMPP Control Panel** et démarrez :
- ✅ Apache
- ✅ MySQL

### 3. Créer la base de données
1. Ouvrez **phpMyAdmin** : [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Cliquez sur l'onglet **Importer**
3. Choisissez le fichier `database.sql` du projet
4. Cliquez sur **Exécuter**

### 4. Accéder à l'application
Ouvrez votre navigateur : [http://localhost/service-finder-tunisia](http://localhost/service-finder-tunisia)

## 👤 Comptes par défaut

| Rôle  | Email                      | Mot de passe |
|-------|----------------------------|--------------|
| Admin | admin@servicefinder.tn     | admin123     |

## 📁 Structure du projet

```
service-finder-tunisia/
├── index.php              # Page d'accueil (recherche + liste)
├── login.php              # Connexion
├── register.php           # Inscription
├── dashboard.php          # Tableau de bord (selon rôle)
├── add_service.php        # Ajouter un service (prestataire)
├── edit_service.php       # Modifier un service (prestataire)
├── delete_service.php     # Supprimer un service
├── admin_users.php        # Gestion des utilisateurs (admin)
├── admin_services.php     # Gestion des services (admin)
├── logout.php             # Déconnexion
├── config.php             # Configuration base de données
├── database.sql           # Script SQL de création
├── assets/
│   ├── css/style.css      # Styles
│   └── js/main.js         # JavaScript
├── includes/
│   ├── header.php         # En-tête
│   ├── footer.php         # Pied de page
│   └── functions.php      # Fonctions utilitaires
└── uploads/               # Images uploadées
```

## ✨ Fonctionnalités

- 🔐 Inscription/Connexion avec hachage bcrypt
- 🔍 Recherche par ville et catégorie
- 📝 CRUD complet pour les services (prestataires)
- 👑 Panel admin pour gérer utilisateurs et services
- 📸 Upload d'images pour les services
- 📱 Design responsive (mobile-friendly)
- 🛡️ Protection SQL injection (requêtes préparées)

## ⚙️ Configuration

Si votre MySQL utilise un mot de passe, modifiez `config.php` :
```php
$db_user = 'root';
$db_pass = 'votre_mot_de_passe';
```

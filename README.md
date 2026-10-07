# Système de gestion des classes pour enseignants

## Description
Système arabe RTL pour la gestion des classes, enseignants, documents et messagerie interne.

## Stack
- HTML
- CSS
- JavaScript
- PHP
- MySQLi

## Installation
1. Créer la base de données `school_system`
2. Importer le fichier `database.sql`
3. Configurer `config/db.php`
4. Ouvrir `create_admin.php` pour créer le compte admin
5. Accéder à `login.php`

## Compte admin
- email: admin@school.com
- mot de passe: Admin@123

## Sécurité
- hash des mots de passe
- requêtes préparées MySQLi
- contrôle des rôles
- validation des fichiers
- protection CSRF
- htmlspecialchars

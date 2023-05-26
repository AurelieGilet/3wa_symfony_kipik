# 3wa_symfony_kipik

Projet Symfony réalisé durant ma formation à la 3W Academy

## Consignes

### Description
Le but de ce projet est de développer vos compétences avec Symfony 6.2 et Twig en créant une application web robuste en l'espace d'une semaine. 
Le projet consistera à construire une base de données avec un minimum de 5 tables, tout en offrant un back-office complet pour la gestion de ces données.

### Objectifs
Maîtriser l'utilisation du framework Symfony 6.2
Utiliser Twig pour la gestion des templates
Construire et gérer une base de données avec un minimum de 5 à 6 tables
Développer un back-office complet pour la gestion de l'application

### Instructions
#### Création de la base de données
Utilisez Doctrine, inclus dans Symfony, pour créer et gérer votre base de données.Assurez-vous d'avoir un minimum de 5 à 6 tables.

#### Création du back-office
Votre back-office doit permettre de gérer les données de chaque table. Pour chaque table, assurez-vous d'implémenter les fonctionnalités suivantes : lister tous les enregistrements, ajouter un nouvel enregistrement, modifier un enregistrement existant, et supprimer un enregistrement.

#### Livrables
À la fin de la semaine, veuillez soumettre le code source de votre projet sur github, ainsi qu'un bref rapport expliquant le fonctionnement de votre back-office et la structure de votre base de données.


## Choix du projet 

Site e-commerce

### Schéma relationnel

<img src="https://user-images.githubusercontent.com/75724762/241248612-1c2da8e2-b304-4850-b27b-8ce299127149.jpg" width=500)>

### Fonctionnalités

* Côté front-office :
  - Inscription d'un utilisateur
  - Accès de l'utilisateur à un espace personnel listant ses informations et les commandes passées
  - Catalogue de produits, filtrables par catégories
  - Ajout/retrait d'un produit à un panier d'achat
  - Passer une commande

* Côté back-office :
  - Accès à un espace administrateur pour les utilisateurs concernés
  - Gestion des utilisateurs
  - Gestion des commandes
  - Gestion des catégories de produits
  - Gestion des produits

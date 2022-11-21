# TrueBubble

## Concept
TrueBubble est un genre de Twitter/Reddit/Le Monde où chaque article est une bulle ayant une taille proportionnelle au nombre de likes. 
Les bulles sont aussi colorées en fonction de leur popularité/véracité. Les bulles sont cliquables et renvoient à la page de l'article.

Les utilisateurs peuvent :
+ Créer des posts et voter pour leurs posts préférés
+ Modifier leurs posts
+ Voter pour les posts des autres utilisateurs (WIP)

Les administrateurs peuvent :
+ Créer des posts
+ Supprimer/modifier des utilisateurs
+ Supprimer/modifier des votes
+ Supprimer/modifier des posts

## Utilisation
Après avoir installé toutes les dépendances et Webpack :
+ `npm run watch` pour lancer Webpack
+ `symfony server:start` pour lancer le serveur Symfony
+ `symfony console doctrine:database:create` pour créer la base de données
+ `symfony console doctrine:migrations:migrate` pour créer les tables
+ `symfony console doctrine:fixtures:load` pour charger les fixtures (cela peut prendre un peu de temps)

Une fois que le site et ready :
+ Vous pouvez créer un compte en cliquant sur le bouton "Sign up" ou vous connecter :
    + En tant qu'admin :
        + email : `admin@gmail.com`
        + password : `azerty`

## Workflow
Arborescence des branches :
+ main
+ dev
    + feature/...
      + Users
      + Posts
      + etc...

## WIP
+ Feature incomplète : les likes ne fonctionne plus, il faut revoir le système de vote
+ Ajouter des notifications (toast messages)
+ 
## TODO
+ Ajouter des commentaires
+ Ajouter des tags
+ Ajouter des mentions
+ Ajouter des hashtags
+ Ajouter une option de recherche
+ Ajouter une option de filtre
+ Ajouter des catégories
+ Permettre aux utilisateurs de supprimer leurs posts
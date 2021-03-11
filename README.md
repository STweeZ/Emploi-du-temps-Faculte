# Projet de programmation web - Création d'un emploi du temps dynamique

## Introduction

Ce projet de programmation web consiste en la réalisation d'un emploi du temps tel que celui que nous pouvons retrouver sur l'ent de la Faculté des Sciences de Jean Perrin.

## Les choix techniques

Ce projet a nécessité l'utilisation de divers langages de programmation.

Je me suis servi en grande partie de PHP. Ce dernier m'a été utile pour tout ce qui est gestion de la base de données et gestion des utilisateurs connectés. Le premier usage de PHP a été pour la création de la base de données. Il m'a permit de la créer, de la mettre en forme et de lui injecter des valeurs. Vous pouvez retrouver le schéma de cette dernière ainsi que son explication dans la section "La base de données".
PHP m'a été utile pour la connexion des utilisateurs. Il m'a permit de vérifier, vis-à-vis de la base de données, si la personne voulant se connecter rentre des informations valides. J'ai alors pu me servir des fonctions password_hash() et de password_verify() pour respectivement chiffrer les mots de passe et pour vérifer que le mot de passe est valide.
PHP m'a finalement été utile lors de toute interaction avec la base de données. Je m'en suis servi pour les fonctionnalités d'ajout d'utilisateur, d'ajout de salle, d'ajout de matière, d'ajout de créneau, de suppression de créneau et pour l'affichage d'une promotion, d'une salle ou d'un enseignant en particulier. Son utilisation a ensuite été requise lors de l'affichage de l'emploi du temps. C'est-à-dire qu'il m'a fallu récupérer dans la base de données les créneaux de la semaine concernée.

Je me suis ensuite servi de JavaScript pour l'affichage de l'emploi du temps. Cet emploi du temps a été réalisé par mes soins, from scratch. Ce dernier se présente sous la forme d'un tableau HTML présentant des cellules. JavaScript m'a permit d'utiliser un ensemble de fonctions que j'ai développées afin de construire le semainier petit à petit. Ces fonctions ont pour nom selectAWeek(), printSelectedWeek(), printSelectedDay(), constructEdt() qui permet de construire la base du semainier ou encore finishEdt() qui permet de remplir le tableau avec les valeurs récupérées dans la base de données. Dans ces fonctions, j'ai utilisé des méthodes propre à JavaScript telles que createElement() ou encore appendChild() afin de pouvoir construire les cellules du semainier et de leur appliquer un style afin de les différencier : tout simplement afin de pouvoir les personnaliser.

Je me suis bien évidemment servi de HTML et de CSS pour mettre en forme les éléments et leur appliquer un style propre. J'ai choisi des couleurs sobres qui n'agressent pas les yeux de l'utilisateur. J'ai agencer les éléments d'une bonne manière afin que l'utilisateur s'y retrouve et ne soit pas perdu. L'application web est de ce fait compréhensible.

Pour finir, je me suis servi de BootStrap. Ce dernier m'a été utile afin de rendre l'application web graphiquement meilleure, plus belle. J'ai utilisé les styles qu'il fournit tels que pour les tableaux, pour la barre de navigation ou encore pour les boutons.

La base de données a été crées à partir de php et plus particulièrement avec PDO. C'est une base de données SQLite3.

## Les difficultés rencontrées

La principale difficulté que j'ai rencontré concerne l'utilisation de PHP et de JavaScript ensemble. Je n'arrivais pas à comprendre comment je pouvais transmettre les données recueillies par PHP dans la base de données directement à JavaScript. Résoudre ce problème m'a demandé de réaliser de multiples recherches et de réfléchir à de nombreuses reprises. J'ai finalement trouver comme solution de réaliser uniquement des fichiers PHP et d'insérer une balise script dans le fichier concerné par l'affichage du semainier. A partir de ce script je pouvais donc faire appel à PHP pour ressortir les créneaux sous forme d'un tableau que je pouvais alors utiliser dans mon code JavaScript.

La seconde difficulté a été l'affichage du semainier. J'avais du mal à trouver la bonne façon de faire pour afficher l'emploi du temps sur l'application web. Deux choix s'offrait à moi :

* L'affichage sous la forme d'un tableau HTML, avec des cellules représentant des créneaux de 15 minutes.
* L'affichage sous la forme d'une image SVG. Dans ce cas-ci je dois construire l'emploi du temps par le biais de coordonnées.

J'ai finalement opté pour l'affichage sous la forme d'un tableau HTML car je me sentais plus à l'aise de réaliser un élément de cette façon du fait que je n'ai jamais utilisé de manière concrète le format SVG.

## L'architecture choisie

J'ai voulu mon application web sobre et intuitive.
La page d'accueil de l'application consiste en un formulaire de connexion. Seul les utilisateurs enregistrés dans la base de données peuvent se connecter. Qu'ils soient un utilisateur, un enseignant ou un administrateur, chacun peut se connecter de la même manière.

Une fois connecté, l'utilisateur est redirigé vers la page principale de l'application.
Sur cette page principale doit figurer les éléments essentiels de l'application web. L'utilisateur peut choisir, par le biais d'une interface présente à gauche, la semaine qu'il souhaite voir affichée. Le semainier s'affichage directement à droite de cette section. La possibilité de changer de semaine est toujours présente afin de facilier l'utilisation de l'application.

En fonction du role de l'utilisateur, enseignant ou administrateur, ce dernier va pouvoir accéder à certaines fonctionnalités propre à son rôle. L'enseignant voit apparaître, sur la page principale, un bouton. Ce bouton permet à l'enseignant d'insérer un nouveau créneau. Il a aussi la possibilité de modifier directement un créneau ou alors de le supprimer. Il a juste a effectuer un clic gauche sur ce dernier afin d'être rediriger vers la page de gestion de créneau.
L'administrateur,lui, voit apparaître, sur la page principale, quatres boutons. Ces quatres boutons représentent chacuns une fonctionnalité différente. Le premier permet à l'administrateur de créer un nouvel étudiant et de lui assigner une promotion en particulier. Le second bouton, lui, permet de créer un nouvel enseignant. Le troisième bouton permet de créer une nouvelle salle et de lui préciser une capacité d'accueil. Puis, le quatrième et dernier bouton lui permet de créer une nouvelle matière.

Sur l'ensemble des pages de cette application nous pouvons voir une barre de navigation présente sur le bord supérieur de l'écran. A partir de cette barre il va être possible pour l'enseignant ainsi que pour l'administrateur connecté de sélectionner un affichage spécifique. Comme demandé dans le sujet, il peut choisir d'afficher l'emploi du temps d'une promotion, d'un enseignant ou alors d'une salle en particulier. L'étudiant quant à lui n'a pas le choix, il a seulement accès à son propre emploi du temps.
Sur cette même barre de navigation nous trouvons le nom ainsi que le prénom de l'utilisateur connecté, mais aussi le moyen pour lui de se déconnecter.
De cette manière la barre de navigation présente le minimum d'informations afin de ne pas surcharger la page. Mais cette dernière présente tout ce dont nous avons besoin et tout ce dont il est nécessaire d'avoir.

## La base de données

![Database schema](/database.png)

Vous pouvez voir ci-dessus le schéma de la base de données utilisées. Je vais vous expliquer cette dernière.

1. Table 'Role'. Dans cette table nous trouvons un champ 'intitule' de type VARCHAR représentant le nom du rôle. Il existe 3 rôles, 'Etudiant', 'Enseignant', 'Administratif'. Ce sont 3 rôles nécessaires à l'élaboration de l'application web.
2. Table 'Utilisateur'. Dans cette table nous trouvons le champ 'idRole' de type INTEGER faisant référence à un rôle parmis les 3 rôles existants de la table 'Role'. Ces utilisateurs présentent un champ 'nom' de type VARCHAR, 'prenom' de type VARCHAR, mail' de type VARCHAR que 'mot_de_passe' de type VARCHAR. Leur mots de passe sont chiffrés. Nous avons ensuite un champ 'idPromotion' de type INTEGER, c'est une clé étrangère de la table 'Promotion' et cette relation correspond au fait qu'un étudiant se voit assigné une promotion en particulier.
3. Table 'Filiere'. Elle présente un champ 'intitule' de type VARCHAR et représente une filière/un département. Il en existe 4, 'Informatique', 'Mathematiques', 'SVT' et 'Chimie'.
4. Table 'Promotion'. Nous y trouvons le champ 'idFiliere' de type INTEGER car une promotion fait forcément partie intégrante d'une filière. Il y a ensuite le champ 'intitule' de type VARCHAR représenant le nom de la promotion tel que 'Licence 1' ou encore 'Master 2'.
5. Table 'Type', avec un champ 'intitule' de type VARCHAR, qui représente le type de cours qu'il est possible d'avoir. C'est-à-dire de type 'CM', 'TD' ou alors 'TP'.
6. Table 'Matiere', avec un champ 'intitule' de type VARCHAR, qui représente une matière. Il en existe de multiples telles que 'C', 'Python', 'Programmation web', etc.
7. Table 'Salle', avec un champ 'intitule' de type VARCHAR, qui représente une salle. De la même façon que la table 'Matiere', il en existe de multiples. Nous pouvons citer la salle 'S25', 'E15' ou alors 'G311'. Cette table présente un champ de type 'capacite' de type INTEGER et représentant la capacité d'accueil de la salle en question.
8. Table 'Creneau'. C'est la table principale de la base de données, la table au centre de toutes les autres. Cette table va représenter les créneaux de cours. Nous y trouvons le champ 'date' de type DATETIME et qui représentent la date du créneau, le champ 'debut' et le champ 'fin' tout deux de types DATETIME et représentant respectivement les heures de début et de fin du créneau. Nous trouvons ensuite le champ 'idType' de type INTEGER faisant référence à un élément de la table 'Type'. Le champ 'idSalle' de type INTEGER faisant référence à un élément de la table 'Salle'. Le champ 'idMatiere' de type INTEGER faisant référence à un élément de la table 'Matiere'. Le champ 'idEnseignant' de type INTEGER faisant référence à un élément de la table 'Utilisateur'. Puis, le champ 'idPromotion' de type INTEGER faisant référence à un élément de la table 'Promotion'.

## Conclusion

Pour conclure, j'ai trouvé ce projet intéressant. Il m'a permis de développer mes compétences et connaissances dans divers langages de programmation et surtout au niveau du PHP. Durant les anciens projets que j'ai pu réaliser, j'ai essentiellement pu travailler sur du HTML, CSS et JavaScript mais jamais réellement sur du PHP. Grâce à ce projet et à la matière j'ai actuellement pu prendre goût à ce langage de programmation.
Je trouve que le projet réalisé présente toutes les fonctionnalités qu'il était demandé de réaliser. J'ai pu réaliser une application fonctionnelle, intuitive, sobre et appréciable.
Si j'avais plus de temps pour continuer à développer ce projet je rajouterais de nouvelles fonctionnalités. Je pourrais par exemple dans ce cas rajouter une fonctionnalité qui permetterait d'insérer un créneau sur plusieurs semaines.

Ce projet a été réalisé par Grégoire Delacroix, Licence 3 Informatique à la Faculté des Sciences de Jean Perrin.

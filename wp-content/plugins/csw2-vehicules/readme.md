# Plugin WordPress : CSW2_vehicules

## Description
Ce plugin wordpress, développé par Sacha Pignot dans le cadre du cours "Création de sites wb 2", permet à des utilisateurs dont le rôle est autorisé (via le back office) de publier des annonces puis de les modifier ou supprimer. Ces anonces sont publiquement listée sur une page dédiée.

## Liste des fichiers
- csw2-vehicules.php 
    - fichier principale qui inclut tout les autres fichiers
- csw2-vehicules-install-activation.php
    - fichier contenant le script d'activation et d'installation du plugin
- csw2-vehicules-uninstall-deactivation.php
    - fichier contenant le script de désactivation et de désinstallation du plugin
- csw2-vehicules-page-add.php
    - fichier contenant le script qui affiche le formulaire d'annonce de nouveaux véhicules et qui insert ses données dans la BDD
- csw2-vehicules-page-delete.php
    - fichier contenant le script qui affiche la page de confirmation de suppression et qui supprime les données de la BDD
- csw2-vehicules-page-update.php
    - fichier contenant le script qui affiche le formulaire de modification d'un véhicule et qui modifie les données associées dans la BDD
- csw2-vehicules-page-list.php
    - fichier contenant le script qui affiche la liste de tout les vehicules en fonction du rôle de l'utilisateur et de si il est propriétaire ou pas des annonces
- csw2-vehicules-page-single.php
    - fichier contenant le script qui affiche la page d'un véhicule en particulier

## 

<?php

/**
 * @package CSW2_vehicules
 * @version 0.1
 */
/*
Plugin Name: CSW2_vehicules
Description: Ceci est un plugin permettant à des utilisteurs de gérer la vente de leurs véhicules d'occasion. 
Author: Sacha Pignot
Version: 0.1
Author URI: https://www.linkedin.com/in/sacha-pignot-75b645160/
*/

// Section pour la gestion des réglages dans l'administration
require_once("csw2-vehicules-settings.php");

// Section pour l'installation et l'activation de l'extension
register_activation_hook(__FILE__, 'csw2_vehicules_activate');
require_once("csw2-vehicules-install-activate.php");

// Section pour la désinstallation et la désactivation de l'extension
require_once("csw2-vehicules-uninstall-deactivate.php");
register_deactivation_hook(__FILE__, 'csw2_vehicules_deactivate');
register_uninstall_hook(__FILE__, 'csw2_vehicules_uninstall');

// Section pour la page de création d'annonce de véhicule
require_once("csw2-vehicules-page-form.php");

// Section pour le traitement de la page de liste des véhicules
require_once("csw2-vehicules-page-list.php");

// Section pour le traitement de la page d'affichage d'un véhicule
require_once("csw2-vehicules-page-single.php");

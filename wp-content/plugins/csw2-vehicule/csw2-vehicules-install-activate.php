<?php

/**
 * Traitements à l'activation de l'extension
 *
 * @param none
 * @return none
 */
function csw2_vehicules_activate()
{
    csw2_vehicules_check_version();
    csw2_vehicules_create_table();
    csw2_vehicules_default_settings();
    csw2_vehicules_create_pages();
}

/**
 * Vérification de la version WP
 *
 * @param none
 * @return none
 */
function csw2_vehicules_check_version()
{
    global $wp_version;
    if (version_compare($wp_version, '5.0', '<')) {
        wp_die('Cette extension requiert WordPress version 5.0 ou plus.');
    }
}

/**
 * Création de la table vehicules
 *
 * @param none
 * @return none
 */
function csw2_vehicules_create_table()
{
    global $wpdb;

    $sql = "CREATE TABLE $wpdb->prefix" . "vehicules (
        vehicule_id INT NOT NULL AUTO_INCREMENT,
        vehicule_marque VARCHAR(255) NOT NULL,
        vehicule_model VARCHAR(255) NOT NULL,
        vehicule_couleur VARCHAR(255) NOT NULL,
        vehicule_annee_circulation YEAR NOT NULL,
        vehicule_kilometrage INT NOT NULL,
        vehicule_prix INT NOT NULL,
        vehicule_date_enregistrement DATE NOT NULL,
        vehicule_propietaire_id INT NOT NULL,
        PRIMARY KEY (vehicule_id))
      ENGINE = InnoDB " . $wpdb->get_charset_collate();

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


/**
 * Inilialisation de l'option csw2_vehicules_settings, qui regroupe un tableau de réglages pour l'affichage des rubriques sur la page de liste
 *
 * @param none
 * @return none
 */
function csw2_vehicules_default_settings()
{
    add_option(
        'csw2_vehicules_settings',
        array(
            "duree_affichage"  => '15',
            "roles_permis" => 'administrator',
            "visibilite_annonce" => true
        )
    );
}

/**
 *Création des pages de l'extension
 *
 * @param none 
 * @return none
 */
function csw2_vehicules_create_pages()
{
    // Formulaire pour annoncer un nouveau véhicule
    $csw2_vehicules_page = array(
        'post_title'     => "Annoncer un véhicule",
        'post_name'      => "annoncer-vehicule",
        'post_content'   => "[annoncer_vehicule]",
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'comment_status' => 'closed',
        'ping_status'    => 'closed',
        'meta_input'     => array('csw2_vehicules' => 'form')
    );
    wp_insert_post($csw2_vehicules_page);

    // Liste de tous les véhicules annoncés
    $csw2_vehicules_page = array(
        'post_title'     => "Vehicules d'occasion à vendre",
        'post_name'      => "vehicules",
        'post_content'   => "[csw2_vehicules_list]",
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'comment_status' => 'closed',
        'ping_status'    => 'closed',
        'meta_input'     => array('csw2_vehicules' => 'list')
    );
    wp_insert_post($csw2_vehicules_page);

    // Infos sur un véhicule en particulier
    $csw2_vehicules_page = array(
        'post_title'     => "Vehicule",
        'post_name'      => "vehicule",
        'post_content'   => "[csw2_vehicules_single]",
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'comment_status' => 'closed',
        'ping_status'    => 'closed',
        'meta_input'     => array('csw2_vehicules' => 'single')
    );
    wp_insert_post($csw2_vehicules_page);
}
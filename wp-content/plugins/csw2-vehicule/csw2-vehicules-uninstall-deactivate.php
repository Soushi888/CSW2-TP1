<?php
/**
 * Traitements à la désactivation de l'extension
 *
 * @param none
 * @return none
 */
function csw2_vehicules_deactivate()
{
	csw2_vehicules_delete_pages();
}

/**
 * Suppression des pages de l'extension, exceptés les posts des images
 *
 * @param none
 * @return none
 */
function csw2_vehicules_delete_pages()
{
	global $wpdb;
	$postmetas = $wpdb->get_results(
		"SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules'"
	);
	$force_delete = true;
	foreach ($postmetas as $postmeta) {
		// suppression lorsque la métadonnée csw2_vehicules n'est pas celle d'un post image
		if ($postmeta->meta_value !== 'img') {
			wp_delete_post($postmeta->post_id, $force_delete);
		}
	}
}

/* Section pour la désinstallation de l'extension
 * ==============================================
 */


/**
 * Traitements à la désinstallation de l'extension
 *
 * @param none
 * @return none
 */
function csw2_vehicules_uninstall()
{
	csw2_vehicules_drop_table();
	csw2_vehicules_delete_settings();
	csw2_vehicules_delete_images();
}

/**
 * Suppression de la table vehicules
 *
 * @param none
 * @return none
 */
function csw2_vehicules_drop_table()
{
	global $wpdb;
	$sql = "DROP TABLE $wpdb->prefix" . "vehicules";
	$wpdb->query($sql);
}

/**
 * Suppression de l'option csw2_vehicules_settings
 *
 * @param none
 * @return none
 */
function csw2_vehicules_delete_settings()
{
	delete_option('csw2_vehicules_settings');
}

/**
 * Suppression des images (posts et fichiers) de l'extension
 *
 * @param none
 * @return none
 */
function csw2_vehicules_delete_images()
{
	global $wpdb;
	$postmetas = $wpdb->get_results(
		"SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules'"
	);
	// booléen pour indiquer à la fonction wp_delete_post, la suppression des fichiers
	// et la suppression des informations dans les tables posts et postmeta
	$force_delete = true;
	foreach ($postmetas as $postmeta) {
		if ($postmeta->meta_value === 'img') {
			wp_delete_post($postmeta->post_id, $force_delete);
		}
	}
}
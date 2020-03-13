<?php
/* Section pour le traitement de la page d'affichage d'un véhicule
 * =============================================================== 
 */

/**
 * Création de la page d'affichage d'un véhicule
 *
 * @param none
 * @return echo html single vehicule code
 */
function csw2_vehicules_html_single_code()
{

	/* Affichage d'un lien vers la page de liste des véhicules
	   ------------------------------------------------------ */
	global $wpdb;
	$postmeta = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules' AND meta_value = 'delete'");
	$delete_permalink = get_permalink($postmeta->post_id);

	$postmeta = $wpdb->get_row(
		"SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules' AND meta_value = 'list'"
	);
	$list_permalink = get_permalink($postmeta->post_id);
	
	$postmeta = $wpdb->get_row(
		"SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules' AND meta_value = 'update'"
	);
	$update_permalink = get_permalink($postmeta->post_id);
?>
	<section style="margin: 0 auto; width: 80%; max-width: 100%; padding: 0">
		<a style="display: inline-block; margin-bottom: 30px;" href="<?= $list_permalink ?>">Liste des véhicules</a>
		<?php

		/* Affichage du véhicule 
	   ----------------------- */

		$vehicule_id = isset($_GET['id']) ? $_GET['id'] : null;
		$sql = "SELECT * FROM $wpdb->prefix" . "vehicules WHERE vehicule_id =%d";

		$vehicule = $wpdb->get_row($wpdb->prepare($sql, $vehicule_id));
		if ($vehicule !== null) :
			$current_user = wp_get_current_user();
			if (empty($current_user->roles)) $current_user->roles = ["annonyme"];

			$settings = get_option('csw2_vehicules_settings');

			$propietaire = get_user_by("id", $vehicule->vehicule_proprietaire_id);

			if ($propietaire === false) { // Si le propriétaire de l'annonce n'est pas un utilisateur enregistré,
				$propietaire = (object) $propietaire;
				$propietaire->user_login = "annonyme"; // lui donner l'identifiant "annonyme"
			}
		?>
			<div style="display: flex">
				<p style="width:270px; padding: 5px; color: #777">Propriétaire :</p>
				<p style="padding: 5px"><?= $propietaire->user_login ?></p>
			</div>

			<div style="display: flex">
				<p style="width:270px; padding: 5px; color: #777">Marque :</p>
				<p style="padding: 5px"><?= stripslashes(nl2br($vehicule->vehicule_marque)) ?></p>
			</div>

			<div style="display: flex">
				<p style="width:270px; padding: 5px; color: #777">Modèle :</p>
				<p style="padding: 5px"><?= stripslashes(nl2br($vehicule->vehicule_modele)) ?></p>
			</div>

			<div style="display: flex">
				<p style="width:270px; padding: 5px; color: #777">Couleur :</p>
				<p style="padding: 5px"><?= stripslashes(nl2br($vehicule->vehicule_couleur)) ?></p>
			</div>

			<div style="display: flex">
				<p style="width:270px; padding: 5px; color: #777">Année de mise en circulation : </p>
				<p style="padding: 5px"><?= $vehicule->vehicule_annee_circulation ?></p>
			</div>

			<div style="display: flex">
				<p style="width:270px; padding: 5px; color: #777">Kilométrage :</p>
				<p style="padding: 5px"><?= $vehicule->vehicule_kilometrage ?>km</p>
			</div>

			<div style="display: flex">
				<p style="width:270px; padding: 5px; color: #777">Prix :</p>
				<p style="padding: 5px"><?= $vehicule->vehicule_prix ?> $</p>
			</div>

			<div style="display: flex">
				<p style="width:270px; padding: 5px; color: #777">Date d'enregistrement :</p>
				<p style="padding: 5px"><?= $vehicule->vehicule_date_enregistrement ?></p>
			</div>

			<?php // Si l'utilisateur conecté est un administrateur où si il est celui qui a publié l'annonce et qu'il a un rôle autorisé
			if ((current_user_can('administrator') || (get_current_user_id() == $vehicule->vehicule_proprietaire_id)) && (in_array($current_user->roles[0], $settings["roles_permis"]))) : ?>
				<!-- Il peut Supprimmer ou modifer son annonce (ou toutes si il est administrateur) -->
				<div>
					<button><a style="color: #fff; text-decoration: none;" href="<?= $delete_permalink . "?id=" . $vehicule->vehicule_id ?>">Supprimmer</a></button>
					<button><a style="color: #fff; text-decoration: none;" href="<?= $update_permalink . "?id=" . $vehicule->vehicule_id ?>">Modifier</a></button>
				</div>
			<?php endif; ?>
		<?php
		else :
		?>
			<p>Ce véhicule n'est pas enregistré.</p>
		<?php
		endif;
		?>
	</section>
<?php
}

/**
 * Exécution du code court (shortcode) d'affichage d'une véhicule
 *
 * @param none
 * @return the content of the output buffer (end output buffering)
 */
function csw2_vehicules_shortcode_single()
{
	ob_start(); // temporisation de sortie
	csw2_vehicules_html_single_code();
	return ob_get_clean(); // fin de la temporisation de sortie pour l'envoi au navigateur
}

// créer un shortcode pour afficher une véhicule
add_shortcode('csw2_vehicules_single', 'csw2_vehicules_shortcode_single');

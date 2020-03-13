<?php

// l'exécution du hook 'admin_menu' sert à compléter le panneau d'administration,
// pour les extensions et les thèmes
add_action('admin_menu', 'csw2_vehicules_add_menu_page');

/**
 * Ajout de la page formulaire des réglages dans le panneau d'administration,
 * et ajout d'une action d'initialisation du traitement de cette page au crochet 'admin_init' 
 *
 * @param none
 * @return none
 */
function csw2_vehicules_add_menu_page()
{
	add_menu_page(
		'Réglages de l\'extension csw2 vehicules',	// balise title de la page des réglages 
		'CSW2_vehicules', // texte de menu de la page des réglages dans le menu latéral gauche
		'administrator', // capacité pour afficher cette page
		'csw2-vehicules-settings-page', // slug dans l'url de la page
		'csw2_vehicules_settings_page' // fonction d'affichage de la page
	);

	// l'exécution du hook 'admin_init' sert à initialiser le traitement de la page des réglages,
	// avant l'affichage du panneau d'administration
	add_action('admin_init', 'csw2_vehicules_register_setting');
}

/**
 * Initialisation du traitement de la page formulaire des réglages 
 *
 * @param none
 * @return none
 */
function csw2_vehicules_register_setting()
{
	register_setting(
		'csw2_vehicules_option_group', // nom de la zone des réglages, associée à la saisie des valeurs de l'option
		'csw2_vehicules_settings', // nom de l'option des réglages
		'csw2_vehicules_sanitize_option' // fonction pour assainir les valeurs de l'option des réglages	
	);
}

/**
 * Assainissement des valeurs de l'option renvoyées par le formulaire des réglages
 *
 * @param none
 * @return none
 */
function csw2_vehicules_sanitize_option($input)
{
	$input['view_ingredients']  = sanitize_text_field($input['view_ingredients']);
	$input['view_instructions'] = sanitize_text_field($input['view_instructions']);
	$input['view_prep_time']    = sanitize_text_field($input['view_prep_time']);
	$input['view_cook_time']    = sanitize_text_field($input['view_cook_time']);
	return $input;
}

/**
 * Affichage de la page du formulaire des réglages
 *
 * @param none
 * @return none
 */
function csw2_vehicules_settings_page()
{
?>
	<div class="wrap">
		<h2>Réglages de CSW2_vehicules</h2>
		<form method="post" action="options.php">
			<?php settings_fields('csw2_vehicules_option_group'); // génération de balises input cachés pour faire le lien avec la fonction register_setting par le paramètre option_group 
			$csw2_vehicules_settings = get_option('csw2_vehicules_settings'); ?>
			<table class="form-table">
				<tr>
					<th scope="row">Durée de l'affichage des annonces</th>
					<td>
						<p>
							<input type="number" name="csw2_vehicules_settings['duree_affichage']" value="<?= $csw2_vehicules_settings["duree_affichage"] ?>"> jours
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Rôles permis</th>
					<td>
						<p>
							<input type="checkbox" name="csw2_vehicules_settings['roles_permis']" value="administrator" <?php checked(in_array("administrator", $csw2_vehicules_settings["roles_permis"])) ?>>
							Administrateurs
							<br>
							<input type="checkbox" name="csw2_vehicules_settings['roles_permis']" value="editor" <?php checked(in_array("editor", $csw2_vehicules_settings["roles_permis"])) ?>>
							Éditeurs
							<br>
							<input type="checkbox" name="csw2_vehicules_settings['roles_permis']" value="author" <?php checked(in_array("author", $csw2_vehicules_settings["roles_permis"])) ?>>
							Auteurs
							<br>
							<input type="checkbox" name="csw2_vehicules_settings['roles_permis']" value="contributor" <?php checked(in_array("contributor", $csw2_vehicules_settings["roles_permis"])) ?>>
							Contributeurs
							<br>
							<input type="checkbox" name="csw2_vehicules_settings['roles_permis']" value="subscriber" <?php checked(in_array("subscriber", $csw2_vehicules_settings["roles_permis"])) ?>>
							Abonnés

						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Visibilité par défaut des annonces</th>
					<td>
						<p>
							<input type="radio" name="csw2_vehicules_settings[visibilite_annonces]" value="yes" <?php checked(!isset($csw2_vehicules_settings['visibilite_annonces']) || $csw2_vehicules_settings['visibilite_annonces'] === 'yes') ?>>
							oui
							<br>
							<input type="radio" name="csw2_vehicules_settings[visibilite_annonces]" value="no" <?php checked(isset($csw2_vehicules_settings['visibilite_annonces']) && $csw2_vehicules_settings['visibilite_annonces'] === 'no') ?>>
							non
						</p>
					</td>
				</tr>
			</table>
			<pre><?php print_r($csw2_vehicules_settings) ?></pre>
			<p class="submit">
				<input type="submit" class="button-primary" value="Enregistrer les modifications">
			</p>
		</form>
	</div>
<?php
}

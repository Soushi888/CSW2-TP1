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
		'csw2 vehicules',							// texte de menu de la page des réglages
		// dans le menu latéral gauche
		'administrator',						// capacité pour afficher cette page
		'csw2-vehicules-settings-page',			// slug dans l'url de la page
		'csw2_vehicules _settings_page'
	);			// fonction d'affichage de la page

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
		'csw2_vehicules_option_group',		// nom de la zone des réglages, associée
		// à la saisie des valeurs de l'option
		'csw2_vehicules_settings',			// nom de l'option des réglages
		'csw2_vehicules_sanitize_option'
	);	// fonction pour assainir les valeurs de l'option des réglages
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
		<h2>Réglages de csw2 vehicules</h2>
		<form method="post" action="options.php">
			<?php settings_fields('csw2_vehicules_option_group'); // génération de balises input cachés pour faire le lien
			// avec la fonction register_setting par le paramètre option_group 
			?>
			<?php $csw2_vehicules_settings = get_option('csw2_vehicules_settings'); ?>
			<h3>Visibilité des rubriques sur la page de liste</h3>
			<table class="form-table">
				<tr>
					<th scope="row">Ingrédients</th>
					<td>
						<p>
							<input type="radio" name="csw2_vehicules_settings[view_ingredients]" value="yes" <?php checked(!isset($csw2_vehicules_settings['view_ingredients']) || $csw2_vehicules_settings['view_ingredients'] === 'yes') ?>>
							oui
							<br>
							<input type="radio" name="csw2_vehicules_settings[view_ingredients]" value="no" <?php checked(isset($csw2_vehicules_settings['view_ingredients']) && $csw2_vehicules_settings['view_ingredients'] === 'no') ?>>
							non
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Instructions</th>
					<td>
						<p>
							<input type="radio" name="csw2_vehicules_settings[view_instructions]" value="yes" <?php checked(!isset($csw2_vehicules_settings['view_instructions']) || $csw2_vehicules_settings['view_instructions'] === 'yes') ?>>
							oui
							<br>
							<input type="radio" name="csw2_vehicules_settings[view_instructions]" value="no" <?php checked(isset($csw2_vehicules_settings['view_instructions']) && $csw2_vehicules_settings['view_instructions'] === 'no') ?>>
							non
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Temps de préparation</th>
					<td>
						<p>
							<input type="radio" name="csw2_vehicules_settings[view_prep_time]" value="yes" <?php checked(!isset($csw2_vehicules_settings['view_prep_time']) || $csw2_vehicules_settings['view_prep_time'] === 'yes') ?>>
							oui
							<br>
							<input type="radio" name="csw2_vehicules_settings[view_prep_time]" value="no" <?php checked(isset($csw2_vehicules_settings['view_prep_time']) && $csw2_vehicules_settings['view_prep_time'] === 'no') ?>>
							non
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Temps de cuisson</th>
					<td>
						<p>
							<input type="radio" name="csw2_vehicules_settings[view_cook_time]" value="yes" <?php checked(!isset($csw2_vehicules_settings['view_cook_time']) || $csw2_vehicules_settings['view_cook_time'] === 'yes') ?>>
							oui
							<br>
							<input type="radio" name="csw2_vehicules_settings[view_cook_time]" value="no" <?php checked(isset($csw2_vehicules_settings['view_cook_time']) && $csw2_vehicules_settings['view_cook_time'] === 'no') ?>>
							non
						</p>
					</td>
				</tr>
			</table>
			<pre><?php // print_r($csw2_vehicules_settings); 
					?></pre>
			<p class="submit">
				<input type="submit" class="button-primary" value="Enregistrer les modifications">
			</p>
		</form>
	</div>
<?php
}

<?php

/**
 * Création du formulaire de modification d'un véhicule
 *
 * @param none
 * @return echo html update vehicule code
 */
function html_update_vehicule_code()
{
    global $wpdb;

    $current_user = wp_get_current_user();
    if (empty($current_user->roles)) $current_user->roles = ["annonyme"];

    $settings = get_option('csw2_vehicules_settings');

    $postmeta = $wpdb->get_row(
        "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules' AND meta_value = 'single'"
    );
    $single_permalink = get_permalink($postmeta->post_id);

    $vehicule_id = isset($_GET['id']) ? $_GET['id'] : null;
    $sql = "SELECT * FROM $wpdb->prefix" . "vehicules
    WHERE vehicule_id = %d";

    $vehicule = $wpdb->get_row($wpdb->prepare($sql, $vehicule_id));
    if ($vehicule === null) : ?>
        <p>Ce véhicule n'existe pas.</p>
    <?php elseif (get_current_user_id() == $vehicule->vehicule_proprietaire_id && in_array($current_user->roles[0], $settings["roles_permis"])) : ?>
        <h4>véhicule no.<?= stripslashes($vehicule->vehicule_id) . " : " . stripslashes($vehicule->vehicule_marque) . " " . stripslashes($vehicule->vehicule_modele) . " " . stripslashes($vehicule->vehicule_couleur) ?></h4>
        <p><a href="<?= $single_permalink . "?id=" . $vehicule_id ?>">Retour à la page du véhicule.</a></p>
        <form action="<?php echo esc_url($_SERVER['REQUEST_URI']) ?>" method="post" enctype="multipart/form-data">
            <label for="marque">Marque du véhicule
                <input type="text" name="marque" id="marque" value="<?= $vehicule->vehicule_marque ?>" required></label><br>
            <label for="modele">Modèle du véhicule
                <input type="text" name="modele" id="modele" value="<?= $vehicule->vehicule_modele ?>" required></label><br>
            <label for="couleur">Couleur du véhicule
                <input type="text" name="couleur" id="couleur" value="<?= $vehicule->vehicule_couleur ?>" required></label><br>
            <!-- <label>Photo du véhicule
            <input type="file" name="photo" required></label><br> -->
            <label for="annee-circulation">Année de mise en circulation du véhicule
                <input type="number" min="1900" max="<?= date("Y") + 1 ?>" step="1" value="<?= date("Y") ?>" name="annee_circulation" id="annee_circulation" required></label><br>
            <label for="kilometrage">Kilométrage du véhicule
                <input type="text" name="kilometrage" id="kilometrage" value="<?= $vehicule->vehicule_kilometrage ?>" required></label><br>
            <label for="prix">Prix du véhicule
                <input type="text" name="prix" id="prix" value="<?= $vehicule->vehicule_prix ?>" required></label><br>
            <fieldset style="display: flex; justify-content: center;">
                <legend>Visibilite du véhicule</legend>
                <label for="oui">Oui
                    <input type="radio" name="visibilite" id="oui" value="oui" <?php checked($vehicule->vehicule_visibilite == "oui") ?> required>
                </label>
                <label for="non">Non
                    <input type="radio" name="visibilite" id="non" value="non" <?php checked($vehicule->vehicule_visibilite == "non") ?> required>
                </label>
            </fieldset>

            <input type="hidden" name="proprietaire_id" id="proprietaire_id" value="<?= get_current_user_id() ?>" required>

            <input type="submit" style="margin-top: 30px;" name="submitted" value="Envoyez">
        </form>
    <?php else : ?>
        <p>Vous n'avez pas l'autorisation de supprimer ce véhicule.</p>
        <?php endif;
}

/**
 * Modification d'un véhicule dans la table vehicules
 *
 * @param none
 * @return none
 */
function update_vehicule()
{
    global $wpdb;

    $vehicule_id = isset($_GET['id']) ? $_GET['id'] : null;
    // si le bouton submit est cliqué
    if (isset($_POST['submitted'])) {
        // assainir les valeurs du formulaire
        $marque = sanitize_text_field($_POST["marque"]);
        $modele = sanitize_text_field($_POST["modele"]);
        $couleur = sanitize_text_field($_POST["couleur"]);
        $annee_circulation = sanitize_text_field($_POST["annee_circulation"]);
        $kilometrage = sanitize_text_field($_POST["kilometrage"]);
        $visibilite = sanitize_text_field($_POST["visibilite"]);
        $prix = sanitize_text_field($_POST["prix"]);

        // Modification dans la table
        global $wpdb;
        try {
            $wpdb->update(
                $wpdb->prefix . 'vehicules',
                array(
                    'vehicule_marque' => $marque,
                    'vehicule_modele' => $modele,
                    'vehicule_couleur' => $couleur,
                    'vehicule_annee_circulation' => $annee_circulation,
                    'vehicule_kilometrage' => $kilometrage,
                    'vehicule_prix' => $prix,
                    'vehicule_visibilite' => $visibilite
                ),
                array(
                    'vehicule_id' => $vehicule_id,
                )
            );
        ?>
            <p>Les modifications ont été enregistrées.</p>
        <?php
        } catch (Exception $e) { ?>
            <p>Les modifications n'ont pas été enregistrées.</p>
<?php
            echo "Erreur : " . $e->getMessage();
        }
    }
    // génèrer le titre de l'image avec l'id de le véhicule insérée dans la table vehicules
    // $vehicule_image_title = "vehicule-" . $wpdb->insert_id;

    // chargement des fichiers nécessaires à l'exécution de la fonction media_handle_upload
    // require_once(ABSPATH . 'wp-admin/includes/image.php');
    // require_once(ABSPATH . 'wp-admin/includes/file.php');
    // require_once(ABSPATH . 'wp-admin/includes/media.php');

    // déplacement du fichier image dans le dossier wp-content/uploads et création d'un post de type attachment dans la table posts le premier paramètre 'image' est le nom du champ input qui suit dans $_FILES['image']
    // $vehicule_image_post_id = media_handle_upload('image', 0, array('post_title' => $vehicule_image_title));
    // echo "<pre>".print_r($vehicule_image_post_id, true)."</pre>"; exit;

    // ajouter une métadonnée csw2_vehicules dans la table postmeta, associée au post précédent, pour rattacher ce post à l'extension   
    // $unique = true;
    // // add_post_meta($vehicule_image_post_id, 'csw2_vehicules', 'img', $unique);

}

/**
 * Exécution du code court (shortcode) de saisie d'une véhicule 
 *
 * @param none
 * @return the content of the output buffer (end output buffering)
 */
function shortcode_input_update_vehicule()
{
    ob_start(); // temporisation de sortie
    update_vehicule();
    html_update_vehicule_code();
    return ob_get_clean(); // fin de la temporisation de sortie pour l'envoi au navigateur
}

// créer un shortcode pour afficher et traiter le formulaire
add_shortcode('modifier_vehicule', 'shortcode_input_update_vehicule');

<?php

/**
 * Création du formulaire de supression d'un véhicule
 *
 * @param none
 * @return echo html delete vehicule code
 */
function html_delete_vehicule_code()
{
    global $wpdb;

    $current_user = wp_get_current_user();
    if (empty($current_user->roles)) $current_user->roles = ["annonyme"];

    $settings = get_option('csw2_vehicules_settings');

    $vehicule_id = isset($_GET['id']) ? $_GET['id'] : null;
    $sql = "SELECT * FROM $wpdb->prefix" . "vehicules
    WHERE vehicule_id = %d";

    $vehicule = $wpdb->get_row($wpdb->prepare($sql, $vehicule_id));

    // if ($vehicule === null) $vehicule = (object)$vehicule;

    // var_dump($vehicule);

    if ($vehicule === null) : ?>
        <p>Ce véhicule n'existe pas.</p>
    <?php elseif (get_current_user_id() == $vehicule->vehicule_proprietaire_id && in_array($current_user->roles[0], $settings["roles_permis"])) : ?>
        <form action="<?php echo esc_url($_SERVER['REQUEST_URI']) ?>" method="post" enctype="multipart/form-data">
            <p>Voulez-vous vraiment supprimer le véhicule no.<?= stripslashes($vehicule->vehicule_id) . " : " . stripslashes($vehicule->vehicule_marque) . " " . stripslashes($vehicule->vehicule_modele) . " " . stripslashes($vehicule->vehicule_couleur) ?></p>

            <div style="display: flex; justify-content: center;">
                <label for="oui">Oui<input type="radio" name="confirmation" id="oui" value="oui" required></label>
                <label for="non">Non<input type="radio" name="confirmation" id="non" value="non" required></label>
            </div>

            <input type="submit" style="margin-top: 30px;" name="submitted" value="Envoyez">
        </form>
    <?php else : ?>
        <p>Vous n'avez pas l'autorisation de supprimer ce véhicule.</p>
        <?php endif;
}

/**
 * Suppression d'une véhicule dans la table vehicules
 *
 * @param none
 * @return none
 */
function delete_vehicule()
{
    global $wpdb;
    $postmeta = $wpdb->get_row(
        "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules' AND meta_value = 'single'"
    );
    $single_permalink = get_permalink($postmeta->post_id);
    // si le bouton submit est cliqué
    if (isset($_POST['submitted'])) {

        global $wpdb;
        $vehicule_id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($_POST["confirmation"] == "oui") :
            try {
                $wpdb->delete(
                    $wpdb->prefix . 'vehicules',
                    array(
                        "vehicule_id" => $vehicule_id
                    )
                );
        ?>
                <p>Le véhicule a été supprimé.</p>
            <?php
                exit;
            } catch (Exception $e) { ?>
                <p>Le véhicule n'a pas été supprimé.</p>
            <?php
                echo "Erreur : " . $e->getMessage();
            } elseif ($_POST["confirmation"] == "non") : ?>
            <p>Le véhicule n'a pas été supprimé.</p>
            <p><a href="<?= $single_permalink . "?id=" . $vehicule_id ?>">Retour à la page du véhicule.</a></p>
<?php
            exit;
        endif;
    }
    // supression dans la table

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
function shortcode_input_delete_vehicule()
{
    ob_start(); // temporisation de sortie
    delete_vehicule();
    html_delete_vehicule_code();
    return ob_get_clean(); // fin de la temporisation de sortie pour l'envoi au navigateur
}

// créer un shortcode pour afficher et traiter le formulaire
add_shortcode('supprimer_vehicule', 'shortcode_input_delete_vehicule');

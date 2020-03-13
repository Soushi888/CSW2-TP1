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
    $postmeta = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules' AND meta_value = 'list'");
    
    $vehicule_id = isset($_GET['id']) ? $_GET['id'] : null;
    $sql = "SELECT * FROM $wpdb->prefix" . "vehicules
    WHERE vehicule_id = %d";
    $vehicule = $wpdb->get_results($wpdb->prepare($sql, $vehicule_id));

?>
    <form action="<?php echo esc_url($_SERVER['REQUEST_URI']) ?>" method="post" enctype="multipart/form-data">
        <label for="marque">Voulez-vous vraiment supprmier le véhicule <?= stripslashes($vehicule->vehicule_marque) . " " . stripslashes($vehicule->vehicule_modele) . " " . stripslashes($vehicule->vehicule_couleur) ?>
            <input type="text" name="marque" id="marque" required></label><br>
        

        <input type="hidden" name="proprietaire_id" id="proprietaire_id" value="<?= get_current_user_id() ?>" required>


        <input type="submit" style="margin-top: 30px;" name="submitted" value="Envoyez">
    </form>
    <?php
}

/**
 * Insertion d'une véhicule dans la table vehicules
 *
 * @param none
 * @return none
 */
function delete_vehicule()
{
    // si le bouton submit est cliqué
    if (isset($_POST['submitted'])) {
        // assainir les valeurs du formulaire
        $marque = sanitize_text_field($_POST["marque"]);
        $modele = sanitize_text_field($_POST["modele"]);
        $couleur = sanitize_text_field($_POST["couleur"]);
        $annee_circulation = sanitize_text_field($_POST["annee_circulation"]);
        $kilometrage = sanitize_text_field($_POST["kilometrage"]);
        $prix = sanitize_text_field($_POST["prix"]);
        $proprietaire_id = sanitize_text_field($_POST["proprietaire_id"]);

        // insertion dans la table
        global $wpdb;
        try {
            $wpdb->insert(
                $wpdb->prefix . 'vehicules',
                array(
                    'vehicule_marque' => $marque,
                    'vehicule_modele' => $modele,
                    'vehicule_couleur' => $couleur,
                    'vehicule_annee_circulation' => $annee_circulation,
                    'vehicule_kilometrage' => $kilometrage,
                    'vehicule_prix' => $prix,
                    'vehicule_proprietaire_id' => $proprietaire_id
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%d'
                )
            );
    ?>
            <p>Le véhicule a été enregistré.</p>
        <?php
        } catch (Exception $e) { ?>
            <p>Le véhicule n'a pas été enregistré.</p>
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
function shortcode_input_delete_vehicule()
{
    ob_start(); // temporisation de sortie
    insert_vehicule();
    html_delete_vehicule_code();
    return ob_get_clean(); // fin de la temporisation de sortie pour l'envoi au navigateur
}

// créer un shortcode pour afficher et traiter le formulaire
add_shortcode('supprimer_vehicule', 'shortcode_input_delete_vehicule');

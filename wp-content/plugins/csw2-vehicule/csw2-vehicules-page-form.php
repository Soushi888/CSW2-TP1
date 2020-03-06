<?php

/**
 * Création du formulaire de saisie d'une véhicule
 *
 * @param none
 * @return echo html form vehicule code
 */
function html_form_vehicule_code()
{
?>
    <form action="<?php echo esc_url($_SERVER['REQUEST_URI']) ?>" method="post" enctype="multipart/form-data">
        <label for="marque">Marque du véhicule
            <input type="text" name="marque" id="marque" required></label>
        <label for="modele">Modèle du véhicule
            <input type="text" name="modele" id="modele" required></label>
        <label for="couleur">Couleur du véhicule
            <input type="text" name="couleur" id="couleur" required></label>
        <label>Photo du véhicule
            <input type="file" name="photo" required></label>
        <label for="annee-circulation">Année de mise en circulation du véhicule
            <input type="number" min="1900" max="2099" step="1" value="2020" name="annee-circulation" id="annee-circulation" required></label>
        <label for="kilometrage">Kilométrage du véhicule
            <input type="text" name="kilometrage" id="kilometrage" required></label>
        <label for="prix">Prix du véhicule
            <input type="text" name="prix" id="prix" required></label>

        <input type="hidden" name="proprietaire-id" id="proprietaire-id" value="<?= get_current_user_id() ?>" required>


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
function insert_vehicule()
{
    global $post;
    // si le bouton submit est cliqué
    if (isset($_POST['submitted'])) {
        // assainir les valeurs du formulaire
        $title        = sanitize_text_field($_POST["title"]);
        $ingredients  = sanitize_textarea_field($_POST["ingredients"]);
        $instructions = sanitize_textarea_field($_POST["instructions"]);
        $prep_time    = sanitize_text_field($_POST["prep_time"]);
        $cook_time    = sanitize_text_field($_POST["cook_time"]);

        // insertion dans la table
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'vehicules',
            array(
                'title' => $title,
                'ingredients' => $ingredients,
                'instructions' => $instructions,
                'prep_time' => $prep_time,
                'cook_time' => $cook_time
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%d',
                '%d'
            )
        );
        // génèrer le titre de l'image avec l'id de le véhicule insérée dans la table vehicules
        $vehicule_image_title = "vehicule-" . $wpdb->insert_id;
        // echo "<pre>".print_r($_FILES, true)."</pre>"; exit;
        // chargement des fichiers nécessaires à l'exécution de la fonction media_handle_upload
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        // déplacement du fichier image dans le dossier wp-content/uploads
        // et création d'un post de type attachment dans la table posts
        // le premier paramètre 'image' est le nom du champ input qui suit dans $_FILES['image']
        $vehicule_image_post_id = media_handle_upload('image', 0, array('post_title' => $vehicule_image_title));
        // ajouter une métadonnée n41_vehicules dans la table postmeta, associée au post précédent,
        // pour rattacher ce post à l'extension   
        $unique = true;
        add_post_meta($vehicule_image_post_id, 'n41_vehicules', 'img', $unique);
    ?>
        <p>Le véhicule a été enregistré.</p>
<?php
    }
}

/**
 * Exécution du code court (shortcode) de saisie d'une véhicule 
 *
 * @param none
 * @return the content of the output buffer (end output buffering)
 */
function shortcode_input_form_vehicule()
{
    ob_start(); // temporisation de sortie
    insert_vehicule();
    html_form_vehicule_code();
    return ob_get_clean(); // fin de la temporisation de sortie pour l'envoi au navigateur
}

// créer un shortcode pour afficher et traiter le formulaire
add_shortcode('annoncer_vehicule', 'shortcode_input_form_vehicule');

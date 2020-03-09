<?php 
// Section pour le traitement de la page de liste des véhicules


/**
 * Création de la page de liste des véhicules
 *
 * @param none
 * @return echo html list vehicules code
 */
function csw2_vehicules_html_list_code()
{

    /* Affichage d'un lien vers le formulaire de saisie d'une véhicule pour l'administrateur du site */
?>
    <section style="margin: 0 auto; width: 80%; max-width: 100%; padding: 0">
        <?php
        global $wpdb;
        if (current_user_can('administrator')) :
            $postmeta = $wpdb->get_row(
                "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules' AND meta_value = 'form'"
            );
        ?>
            <a href="<?php echo get_permalink($postmeta->post_id) ?>">Annoncer un véhicule</a>
        <?php
        endif;

        $vehicule_search = '';
        if (isset($_POST['vehicule-search'])) :
            $vehicule_search = trim($_POST['vehicule-search']);
        endif;

        /* Affichage du formulaire de filtrage de véhicules 
	   ----------------------------------------------- */
        ?>
        <form style="margin-top: 30px" action="<?= esc_url($_SERVER['REQUEST_URI']) ?>" method="post">
            <input type="text" style="display: inline-block; width: 500px; padding: 0 10px; line-height: 50px" name="vehicule-search" placeholder="Filtrer les véhicules contenant cette chaîne de caractères" value="<?= $vehicule_search ?>">
            <input type="submit" style="display: inline-block; margin-left: 20px; padding: 0 24px; line-height: 50px;" name="submitted" value="Envoyez">
        </form>
        <?php

        /* Affichage de la liste des véhicules 
	   ---------------------------------- */

        $sql  = "SELECT * FROM $wpdb->prefix" . "vehicules
			 WHERE title LIKE '%s'
	   		 ORDER BY title ASC";

        $vehicules = $wpdb->get_results($wpdb->prepare($sql, '%' . $vehicule_search . '%'));

        if (count($vehicules) > 0) :
            $postmeta = $wpdb->get_row(
                "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules' AND meta_value = 'single'"
            );
            $single_permalink = get_permalink($postmeta->post_id);

            $settings = get_option('csw2_vehicules_settings');

            foreach ($vehicules as $vehicule) :
        ?>
                <hr>
                <article style="display: flex">
                    <h4 style="margin: 0; width: 300px;">
                        <a href="<?php echo $single_permalink . '?page=' . stripslashes($vehicule->title) . '&id=' . $vehicule->id ?>"><?= stripslashes($vehicule->title) ?></a>
                    </h4>
                    <div>
                        <?php
                        if (isset($settings['view_ingredients']) && $settings['view_ingredients'] === 'yes') :
                        ?>
                            <div style="display: flex">
                                <p style="width:250px; padding: 5px; color: #777">Ingrédients:</p>
                                <p style="padding: 5px"><?= stripslashes(nl2br($vehicule->ingredients)) ?></p>
                            </div>
                        <?php
                        endif;
                        if (isset($settings['view_instructions']) && $settings['view_instructions'] === 'yes') :
                        ?>
                            <div style="display: flex">
                                <p style="width:250px; padding: 5px; color: #777">Instructions:</p>
                                <p style="padding: 5px"><?= stripslashes(nl2br($vehicule->instructions)) ?></p>
                            </div>
                        <?php
                        endif;
                        if (isset($settings['view_prep_time']) && $settings['view_prep_time'] === 'yes') :
                        ?>
                            <div style="display: flex">
                                <p style="width:250px; padding: 5px; color: #777">Temps de préparation:</p>
                                <p style="padding: 5px"><?= $vehicule->prep_time ?> minutes</p>
                            </div>
                        <?php
                        endif;
                        if (isset($settings['view_cook_time']) && $settings['view_cook_time'] === 'yes') :
                        ?>
                            <div style="display: flex">
                                <p style="width:250px; padding: 5px; color: #777">Temps de cuisson:</p>
                                <p style="padding: 5px"><?= $vehicule->cook_time ?> minutes</p>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                </article>
            <?php
            endforeach;
            ?>
            </table>
        <?php
        else :
        ?>
            <p>Aucun véhicule n'est enregistré.</p>
        <?php
        endif;
        ?>
    </section>
<?php
}

/**
 * Exécution du code court (shortcode) d'affichage de la liste des véhicules
 *
 * @param none
 * @return the content of the output buffer (end output buffering)
 */
function csw2_vehicules_shortcode_list()
{
    ob_start(); // temporisation de sortie
    csw2_vehicules_html_list_code();
    return ob_get_clean(); // fin de la temporisation de sortie pour l'envoi au navigateur
}

// créer un shortcode pour afficher la liste des véhicules
add_shortcode('csw2_vehicules_list', 'csw2_vehicules_shortcode_list');

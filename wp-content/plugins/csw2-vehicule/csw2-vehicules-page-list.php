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
        $vehicule_prop = 'vehicule_marque';
        $vehicule_sens = "ASC";
        if (isset($_POST['vehicule-search'])) {
            $vehicule_search = trim($_POST['vehicule-search']);
        }

        if (isset($_POST['vehicule-prop'])) {
            $vehicule_prop = trim($_POST['vehicule-prop']);
        }

        if (isset($_POST['vehicule-sens'])) {
            $vehicule_sens = trim($_POST['vehicule-sens']);
        }



        /* Affichage du formulaire de filtrage de véhicules 
	   ----------------------------------------------- */
        ?>
        <style>
            .form_vehicules_search fieldset {
                display: inline;
            }
        </style>

        <form class="form_vehicules_search" style="margin-top: 30px" action="<?= esc_url($_SERVER['REQUEST_URI']) ?>" method="post">
            <input type="text" style="display: inline-block; width: 500px; padding: 0 10px; line-height: 50px" name="vehicule-search" placeholder="Filtrer les véhicules contenant cette chaîne de caractères" value="<?= $vehicule_search ?>">
            <fieldset>
                <legend>Propriété</legend>
                <select name="vehicule-prop" id="vehicule-prop">
                    <option value="marque">Marque</option>
                    <option value="modele">Modèle</option>
                    <option value="couleur">Couleur</option>
                    <option value="annee_circulation">Année mise en circulation</option>
                </select>
            </fieldset>
            <fieldset>
                <legend>Sens</legend>
                <label for="ASC">ASC<input type="radio" name="vehicule-sens" id="ASC" value="ASC"></label>
                <label for="DESC">DESC<input type="radio" name="vehicule-sens" id="DESC" value="DESC"></label>
            </fieldset>

            <input type="submit" style="display: inline-block; margin-left: 20px; padding: 0 24px; line-height: 50px;" name="submitted" value="Envoyez">
        </form>
        <?php

        /* Affichage de la liste des véhicules 
	   ---------------------------------- */

        $sql  = "SELECT * FROM $wpdb->prefix" . "vehicules
			 WHERE (vehicule_marque LIKE '%s') OR (vehicule_modele LIKE '%s') OR (vehicule_couleur LIKE '%s') OR (vehicule_prix < '%d')
	   		 ORDER BY '%s' $vehicule_sens;";

        $vehicules = $wpdb->get_results($wpdb->prepare($sql, '%' . $vehicule_search . '%', '%' . $vehicule_search . '%', '%' . $vehicule_search . '%', '%' . $vehicule_search . '%', $vehicule_prop));

        if (count($vehicules) > 0) :
            $postmeta = $wpdb->get_row(
                "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules' AND meta_value = 'single'"
            );
            $single_permalink = get_permalink($postmeta->post_id);

            // $settings = get_option('csw2_vehicules_settings');

            foreach ($vehicules as $vehicule) :
        ?>
                <hr>
                <article style="display: flex">
                    <h4 style="margin: 0; width: 300px;">
                        <a href="<?php echo $single_permalink . '?id=' . $vehicule->vehicule_id ?>"><?= stripslashes($vehicule->vehicule_marque) . " " . stripslashes($vehicule->vehicule_modele) . " " . stripslashes($vehicule->vehicule_couleur) ?></a>
                    </h4>
                    <div>
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

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
        $vehicule_prop = 'vehicule_id';
        $vehicule_sens = "DESC";

        if (isset($_POST['vehicule-search'])) {
            $vehicule_search = trim($_POST['vehicule-search']);
        }

        if (isset($_POST['vehicule-prop'])) {
            $vehicule_prop = "vehicule_" . trim($_POST['vehicule-prop']);
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
            <fieldset>
                <legend>Recherche</legend>
                <fieldset>
                    <legend>Mots-clés</legend>
                    <input type="text" style="display: inline-block; width: 500px; padding: 0 10px; line-height: 50px" name="vehicule-search" placeholder="Filtrer les véhicules par marque, modèle, couleur ou prix max" value="<?= $vehicule_search ?>">
                </fieldset>
                <fieldset>
                    <legend>Trie</legend>
                    <fieldset>
                        <legend>Propriété</legend>
                        <select name="vehicule-prop" id="vehicule-prop">
                            <option value="id">Id</option>
                            <option value="marque">Marque</option>
                            <option value="modele">Modèle</option>
                            <option value="couleur">Couleur</option>
                            <option value="annee_circulation">Année mise en circulation</option>
                            <option value="prix">Prix</option>
                            <option value="date_enregistrement">Date d'enregistrement</option>
                        </select>
                    </fieldset>
                    <fieldset>
                        <legend>Sens</legend>
                        <label for="ASC">ASC<input type="radio" name="vehicule-sens" id="ASC" value="ASC"></label>
                        <label for="DESC">DESC<input type="radio" name="vehicule-sens" id="DESC" value="DESC" checked></label>
                    </fieldset>
                </fieldset>
            </fieldset>

            <input type="submit" style="display: inline-block; margin-left: 20px; padding: 0 24px; line-height: 50px;" name="submitted" value="Recherchez">
        </form>
        <?php

        /* Affichage de la liste des véhicules 
	   ---------------------------------- */

        $sql  = "SELECT * FROM $wpdb->prefix" . "vehicules
			 WHERE (vehicule_marque LIKE '%s') OR (vehicule_modele LIKE '%s') OR (vehicule_couleur LIKE '%s') OR (vehicule_prix <= '%d')
                ORDER BY $vehicule_prop $vehicule_sens;";


        $vehicules = $wpdb->get_results($wpdb->prepare($sql, '%' . $vehicule_search . '%', '%' . $vehicule_search . '%', '%' . $vehicule_search . '%',  $vehicule_search));

        // die($wpdb->prepare($sql, '%' . $vehicule_search . '%', '%' . $vehicule_search . '%', '%' . $vehicule_search . '%', '%' . $vehicule_search . '%'));

        if (count($vehicules) > 0) :
            $postmeta = $wpdb->get_row(
                "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules' AND meta_value = 'single'"
            );
            $single_permalink = get_permalink($postmeta->post_id);

            $current_user = wp_get_current_user();
            if (empty($current_user->roles)) $current_user->roles = ["annonyme"];

            global $csw2_vehicules_settings; ?>

            <!-- <pre><?= var_dump($current_user->roles); ?></pre>
            <pre><?= var_dump(in_array($current_user->roles[0], $csw2_vehicules_settings["roles_permis"])); ?></pre> -->

            <?php
            foreach ($vehicules as $vehicule) :
                $propietaire = get_user_by("id", $vehicule->vehicule_proprietaire_id);

                if ($propietaire === false) { // Si le propriétaire de l'annonce n'est pas un utilisateur enregistré,
                    $propietaire = (object) $propietaire;
                    $propietaire->user_login = "annonyme"; // lui donner l'identifiant "annonyme"
                }
                // Si l'utilisateur n'est pas connecté ou si il simple abonné ou administrateur : afficher toutes les annonces ou alors seulement celles de l'utilisateur autorisé
                if (((current_user_can('administrator') || (in_array("annonyme", $current_user->roles)  && $vehicule->vehicule_visibilite == "oui)" || (in_array("subscriber", $current_user->roles)  && $vehicule->vehicule_visibilite == "oui")) || (get_current_user_id() == $vehicule->vehicule_proprietaire_id)))) :  ?>
                    <hr>
                    <article style="display: flex">

                        <h4 style="margin: 0; width: 400px;">
                            <a href="<?php echo $single_permalink . '?id=' . $vehicule->vehicule_id ?>">#<?= stripslashes($vehicule->vehicule_id) . " " . stripslashes($vehicule->vehicule_marque) . " " . stripslashes($vehicule->vehicule_modele) . " " . stripslashes($vehicule->vehicule_couleur) ?></a>
                        </h4>
                        <div>
                            <div style="display: flex">
                                <p style="width:270px; padding: 5px; color: #777">Propriétaire :</p>
                                <p style="padding: 5px"><?= $propietaire->user_login ?></p>
                            </div>

                            <?php if ((current_user_can('administrator')) || get_current_user_id() == $vehicule->vehicule_proprietaire_id) : ?>
                                <div style="display: flex">
                                    <p style="width:270px; padding: 5px; color: #777">Visibilité :</p>
                                    <p style="padding: 5px"><?= $vehicule->vehicule_visibilite ?></p>
                                </div>
                            <?php endif; ?>

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

                            <?php // Si l'utilisateur conecté est un administrateur où si il est celui qui a publié l'annonce
                            if ((current_user_can('administrator') || (get_current_user_id() == $vehicule->vehicule_proprietaire_id)) && (in_array($current_user->roles[0], $csw2_vehicules_settings["roles_permis"]))) :
                                $postmeta = $wpdb->get_row(
                                    "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules' AND meta_value = 'delete'"
                                );
                                $delete_permalink = get_permalink($postmeta->post_id);

                                $postmeta = $wpdb->get_row(
                                    "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'csw2_vehicules' AND meta_value = 'update'"
                                );
                                $update_permalink = get_permalink($postmeta->post_id); ?>

                                <!-- Il peut Supprimmer ou modifer son annonce (ou toutes si il est administrateur) -->
                                <div>
                                    <button><a style="color: #fff; text-decoration: none;" href="<?= $delete_permalink . "?id=" . $vehicule->vehicule_id ?>">Supprimmer</a></button>
                                    <button><a style="color: #fff; text-decoration: none;" href="<?= $update_permalink . "?id=" . $vehicule->vehicule_id ?>">Modifier</a></button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
            <?php
                endif;
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

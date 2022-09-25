<?php

/**
 * Plugin Name: Fermeture infos
 * Description: Plugin indiquant les dates de fermeture de l'établissement "La fourchette gourmande" sur la page home du site.
 * Version: 1.0
 * Author: Laurent Cantos
 * Author URI: http://www.laurentcantos.fr
 */

// SECURITY - Bloquer l'accès direct au fichier
defined('ABSPATH') or die('Access denied');

// Variables globales
define('PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PLUGIN_URL', plugin_dir_url(__FILE__));


/*
 * Ajoute le bouton du plugin dans le back-admin
 */
function holidays_admin_menu() : void {
    add_menu_page(
        'Fermeture infos',
        'Fermeture infos',
        'manage_options',
        'Fermeture_infos',
        'fermeture_admin_page_render', // Callback de la fonction qui affiche le contenu de la page
        'dashicons-palmtree',
        20
    );
}
add_action('admin_menu', 'holidays_admin_menu');


function holidays_register_settings() : void {
    // Enregistre un champ dans la table wp_options pour stocker la première date de fermeture
    register_setting('holidays_settings', 'holidays_date_content_first_day');

    // Enregistre un champ pour stocker la seconde date de fermeture
    register_setting('holidays_settings', 'holidays_date_content_last_day');

    // Enregistre un champ pour activer ou non la fermeture
    register_setting('holidays_settings', 'holidays_radio_content');

    // Include du fichier déporté
    include_once PLUGIN_DIR . 'inc/holidays_settings_sections.php';
    // Lance la fonction déportée qui enregistre les sections et les inputs
    holidays_sections_settings();
}
// Lancer la fonction dans le hook admin_init
add_action('admin_init', 'holidays_register_settings');


/*
 * Affiche le rendu de la page d'administration
 */
function fermeture_admin_page_render() : void {
    ?>
    <div class="wrap">
        <h1>Paramétrage de "Fermeture infos" :</h1>
        <blockquote style="color: #2062d8; margin-bottom: 50px; margin-top: 0">😎 Plugin développé par <a href="https://www.laurentcantos.fr" target="_blank">Laurent Cantos</a> pour "La fourchette gourmande".</blockquote>
        <p style="margin-bottom: 50px">Suivez les indications de cette page pour indiquer sur la page d'accueil du site la fermeture du restaurant à vos clients.</p>
        <h2>Informations d'utilisation:</h2>
        <ul style="list-style-type:square; margin-left: 40px">
        <li style="color: red; margin-bottom: 20px">La bannière d'information se désactivera automatiquement après le dernier jour de fermeture.</li>
        <li style="color: red; margin-bottom: 20px">Les dates renseignées doivent être supérieures à la date actuelle.</li>
        <li style="color: red; margin-bottom: 50px">Astuce: 💡 Pour indiquer une fermeture exceptionnelle, renseigner les deux dates d'une valeur identique.</li>
        </ul>
        <form method="post" action="options.php">
            <?php
            settings_fields('holidays_settings'); // Correspond à option_group du register_setting
            settings_fields('holidays_settings');
            settings_fields('holidays_settings');
            do_settings_sections('Fermeture_infos'); // Correspond à page du add_settings_section
            submit_button('Sauvegarder'); // Créer le bouton de sauvegarde
            ?>
        </form>
    </div>
    <?php
}


// Charger le fichier déporté des fonctions des rendus des input
include_once PLUGIN_DIR . 'inc/holidays_render_sections.php';


/*
 * Ajout du script et du style seulement sur la page d'administration du plugin
 */
function holidays_enqueue_admin_style_script($page_id): void
{
    // var_dump($page_id);  // Pour connaitre l'id de la page
    if ($page_id === 'toplevel_page_Fermeture_infos') {
        wp_register_style('holidays-css', PLUGIN_URL . 'assets/css/admin.css', array());
        wp_enqueue_style('holidays-css');
        wp_register_script('holidays-js', PLUGIN_URL . 'assets/js/admin.js', array("jquery"), '', true);
        wp_enqueue_script('holidays-js');
    }
}
add_action('admin_enqueue_scripts', 'holidays_enqueue_admin_style_script');


/*
 * Charge les scripts et styles sur le front
 */
function holidays_enqueue_front_styles_scripts(): void
{
    wp_register_style('holidays-front-css', PLUGIN_URL . 'assets/css/front.css', array());
    wp_enqueue_style('holidays-front-css');
    wp_register_script('holidays-front-js', PLUGIN_URL . 'assets/js/front.js', array("jquery"), '', true);
    wp_enqueue_script('holidays-front-js');

    // Envoie les réglages enrégistrés dans la bdd au fichier js
    wp_localize_script('holidays-front-js', 'settings', array(
        'first_day' => get_option('holidays_date_content_first_day'),
        'last_day' => get_option('holidays_date_content_last_day'),
        'is_active' => get_option('holidays_radio_content'),
    ));
}
add_action('wp_enqueue_scripts', 'holidays_enqueue_front_styles_scripts');


// Si la date actuelle est supérieur au dernier jour de fermeture (value à 0, reset du champ radio)
function holidays_check_date(){
    if ((date('Y-m-d') > get_option('holidays_date_content_last_day'))) {
        update_option('holidays_radio_content', '0');
    };
};
add_action('init', 'holidays_check_date');


/*
 * Conditions d'affichage du message de fermeture
 */
function holidays_insert_snippet_in_front()
{
    // Si la page est la page d'accueil et que la bannière est activée
    if (get_option('holidays_radio_content') == '1' and is_front_page()) {
        // Si la date actuelle est inférieur ou égale au dernier jour de fermeture et que les deux dates sont différentes
        if (date('Y-m-d') <= get_option('holidays_date_content_last_day') and get_option('holidays_date_content_first_day') != get_option('holidays_date_content_last_day') ) {
            // Affiche le snippet
            ?>
            <div id="ep-banner">
                <p>Fermé du <b><?php echo date_i18n('j F Y', strtotime(get_option('holidays_date_content_first_day')));?></b> au <b><?php echo date_i18n('j F Y', strtotime(get_option('holidays_date_content_last_day')));?></b> inclus</p>
            </div>
            <?php
        }
        // Si la date actuelle est inférieur ou égale au dernier jour de fermeture et que les deux dates sont identiques
        if (date('Y-m-d') <= get_option('holidays_date_content_last_day') and get_option('holidays_date_content_first_day') === get_option('holidays_date_content_last_day') ) {
            // Affiche le snippet
            ?>
            <div id="ep-banner">
                <p>Fermeture exceptionnelle le <b><?php echo date_i18n('l j F Y', strtotime(get_option('holidays_date_content_last_day')));?></b></p>
            </div>
            <?php
        }
    }
}
add_action('wp_footer', 'holidays_insert_snippet_in_front');
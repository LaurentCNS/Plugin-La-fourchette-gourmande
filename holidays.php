<?php

/**
 * Plugin Name: Fermeture infos
 * Description: Plugin indiquant les dates de fermeture de l'établissement "La fourchette gourmande" sur lea page home du theme.
 * Version: 1.0
 * Author: Laurent Cantos
 * Author URI: http://www.laurentcantos.fr
 */

// SECURITY - Bloquer l'accès direct au fichier
defined('ABSPATH') or die('Access denied');


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


/*
 * Enregistre les champs de la bdd, les sections et les inputs
 */
function holidays_register_settings() : void {
    // Enregistre un champ dans la table wp_options pour stocker la première date de fermeture
    register_setting('holidays_settings_group_first', 'holidays_date_content_first_day');

    // Enregistre un champ dans la table wp_options pour stocker la seconde date de fermeture
    register_setting('holidays_settings_group_last', 'holidays_date_content_last_day');

    // Enregistre une checkbox pour activer ou non la fermeture avec une option de type booléen
    register_setting('holidays_settings_group_checkbox', 'holidays_checkbox_content');

    // Ajouter une section de reglage
    add_settings_section(
        'holidays_section_text',
        'Réglages des dates de fermeture du restaurant "La fourchette gourmande"',
        '',
        'Fermeture_infos'
    );

    // Enregistrer la date picker pour le premier jour de fermeture
    add_settings_field(
        'holidays_date_content_first_day',  // Correspond à option_name du register_setting
        'Premier jour de fermeture:',
        'holidays_date_field_first', // Pour créer le champ
        'Fermeture_infos',
        'holidays_section_text'
    );
    // Enregistrer la date picker pour le second jour de fermeture
    add_settings_field(
        'holidays_date_content_last_day',  // Correspond à option_name du register_setting
        'Dernier jour de fermeture:',
        'holidays_date_field_last', // Pour créer le champ
        'Fermeture_infos',
        'holidays_section_text'
    );
    // Enregistrer la checkbox pour activer ou non la fermeture
    add_settings_field(
        'holidays_checkbox_content',  // Correspond à option_name du register_setting
        'Activer la fermeture (si coché):',
        'holidays_checkbox_field', // Pour créer le champ
        'Fermeture_infos',
        'holidays_section_text'
    );
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
        <p>Suivez les indications de cette page pour indiquer la fermeture du restaurant à vos clients sur la page d'accueil.</p>
        <p>Cocher la case pour activer la fermeture (décocher pour la désactiver).</p>
        <p style="color: red; margin-bottom: 50px">Attention, sauvegarder avant de quitter pour mettre à jour !</p>
        <form method="post" action="options.php">
            <?php
            settings_fields('holidays_settings_group_first'); // Correspond à option_group du register_setting
            settings_fields('holidays_settings_group_last'); // Correspond à option_group du register_setting
            settings_fields('holidays_settings_group_checkbox'); // Correspond à option_group du register_setting
            do_settings_sections('Fermeture_infos'); // Correspond à page du add_settings_section
            submit_button('sauvegarder'); // Créer le bouton de sauvegarde
            ?>
        </form>
    </div>
    <?php
}


/*
 * Sauvegarde la date de fermeture du premier jour
 */
function holidays_date_field_first() : void { // Callback de add_settings_field
    $holidays_date_content_first = get_option('holidays_date_content_first_day');  // récupère les infos de la bdd - option_name du register_setting
    ?>
    <input type="date" name="holidays_date_content_first_day" value="<?php echo $holidays_date_content_first; ?>" />
    <?php
}

/*
 * Sauvegarde la date de fermeture du dernier jour
 */
function holidays_date_field_last() : void { // Callback de add_settings_field
    $holidays_date_content_last = get_option('holidays_date_content_last_day');  // récupère les infos de la bdd - option_name du register_setting
    ?>
    <input type="date" name="holidays_date_content_last_day" value="<?php echo $holidays_date_content_last; ?>" />
    <?php
}

/*
 * Sauvegarde la checkbox pour activer ou non la fermeture
 */
function holidays_checkbox_field() : void { // Callback de add_settings_field
    $holidays_checkbox_content = get_option('holidays_checkbox_content');  // récupère les infos de la bdd - option_name du register_setting
    ?>
    <input type="checkbox" name="holidays_checkbox_content" value="1" <?php checked(1, $holidays_checkbox_content, true); ?> />
    <?php
}
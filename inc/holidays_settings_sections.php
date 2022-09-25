<?php
/*
 * Enregistre les champs de la bdd, les sections et les inputs
 */
function holidays_sections_settings() : void {

    // Ajouter une section de reglage
    add_settings_section(
        'holidays_section_text',
        'Réglages des dates de fermeture du restaurant:',
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
        'holidays_date_content_last_day',
        'Dernier jour de fermeture:',
        'holidays_date_field_last',
        'Fermeture_infos',
        'holidays_section_text'
    );
    // Enregistrer les inputs radios pour activer ou non la fermeture
    add_settings_field(
        'holidays_checkbox_content',
        'Activer la fermeture:',
        'holidays_radio_field',
        'Fermeture_infos',
        'holidays_section_text'
    );
}
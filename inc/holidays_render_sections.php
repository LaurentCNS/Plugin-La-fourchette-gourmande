<?php

/*
 * Sauvegarde la date de fermeture du premier jour
 */
function holidays_date_field_first() : void { // Callback de add_settings_field
    $holidays_date_content_first = get_option('holidays_date_content_first_day');  // récupère les infos de la bdd - option_name du register_setting
    ?>
    <input type="date" name="holidays_date_content_first_day" value="<?php echo $holidays_date_content_first; ?>" /> <!-- Name de l'input identitique -->
    <?php
}

/*
 * Sauvegarde la date de fermeture du dernier jour
 */
function holidays_date_field_last() : void {
    $holidays_date_content_last = get_option('holidays_date_content_last_day');
    ?>
    <input type="date" name="holidays_date_content_last_day" value="<?php echo $holidays_date_content_last; ?>" />
    <?php
}

/*
 * Sauvegarde les inputs radio pour activer ou non la fermeture
 */
function holidays_radio_field() : void {
    $holidays_radio_content = get_option('holidays_radio_content');
    ?>
    <input  type="radio" name="holidays_radio_content" value="1" <?php checked(1, $holidays_radio_content, true); ?> /> Oui
    <input style="margin-left: 30px" type="radio" name="holidays_radio_content" value="0" <?php checked(0, $holidays_radio_content, true); ?> /> Non
    <?php
}
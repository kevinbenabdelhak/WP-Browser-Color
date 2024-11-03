<?php

/*
Plugin Name: WP Browser Color
Plugin URI: https://kevin-benabdelhak.fr/plugins/wp-browser-color/
Description: WP Browser Color permet de sélectionner une couleur pour la balise <meta name="theme-color">, offrant une personnalisation facile du thème pour les navigateurs.
Version: 1.0
Author: Kevin BENABDELHAK
Author URI: https://kevin-benabdelhak.fr/
Contributors: kevinbenabdelhak
*/

if (!defined('ABSPATH')) {
    exit; 
}

require_once plugin_dir_path(__FILE__) . 'options/form.php';
require_once plugin_dir_path(__FILE__) . 'meta/color.php';
require_once plugin_dir_path(__FILE__) . 'options/posts.php';





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





if ( !class_exists( 'YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory' ) ) {
    require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';
}
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$monUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/kevinbenabdelhak/WP-Browser-Color/', 
    __FILE__,
    'wp-browser-color' 
);

// Optionnel : préciser la branche stable si ce n'est pas "master" ou "main"
$monUpdateChecker->setBranch('main');



require_once plugin_dir_path(__FILE__) . 'options/form.php';
require_once plugin_dir_path(__FILE__) . 'meta/color.php';
require_once plugin_dir_path(__FILE__) . 'options/posts.php';





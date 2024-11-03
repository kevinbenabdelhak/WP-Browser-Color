<?php 

if (!defined('ABSPATH')) {
    exit; 
}


function wbc_add_meta_theme_color() {

    $colors = get_option('wbc_theme_colors', array());

    $current_url = home_url(add_query_arg(array(), $_SERVER['REQUEST_URI']));
    $color = '#ffffff';

    foreach ($colors as $color_setting) {
        if ($color_setting['type'] === 'all') {
            $color = $color_setting['value'];
        } elseif ($color_setting['type'] === get_post_type()) {
            $color = $color_setting['value'];
        } elseif ($color_setting['type'] === 'url') {
            $url = isset($color_setting['url']) ? trim($color_setting['url']) : '';
            if ($url && (stripos($current_url, $url) !== false)) {
              
                $color = $color_setting['value'];
            }
        }
    }

    echo '<meta name="theme-color" content="' . esc_attr($color) . '">' . "\n";
}
add_action('wp_head', 'wbc_add_meta_theme_color');
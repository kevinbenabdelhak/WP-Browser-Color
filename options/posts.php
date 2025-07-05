<?php 


if (!defined('ABSPATH')) {
    exit; 
}

// récupérer toutes les publications en fonction du post sélectionné (en ajax)
function wbc_get_posts() {
    $type = $_GET['type'] ?? 'post';
    $args = array(
        'post_type' => $type,
        'posts_per_page' => -1
    );
    $posts = get_posts($args);
    $response = array();

    foreach ($posts as $post) {
        $response[] = array(
            'ID' => $post->ID,
            'post_title' => $post->post_title,
        );
    }

    wp_send_json($response);
}
add_action('wp_ajax_wbc_get_posts', 'wbc_get_posts');
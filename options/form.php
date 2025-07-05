<?php 


if (!defined('ABSPATH')) {
    exit; 
}


function wbc_add_admin_menu() {
    add_submenu_page('themes.php', 'WP Browser Color', 'WP Browser Color', 'manage_options', 'wp_browser_color', 'wbc_options_page');
}
add_action('admin_menu', 'wbc_add_admin_menu');


function wbc_options_page() {
    ?>
    <div>
        <h1>WP Browser Color</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('wbc_options_group');
            do_settings_sections('wbc_options_group');       
            $colors = get_option('wbc_theme_colors', array());
            ?>

            <h2>Couleurs par type de contenu/publication</h2>
            <div id="color-repeater">
                <style>
                    .color-row {
                        display: flex;
                        flex-direction: row;
                        flex-wrap: nowrap;
                        align-items: center;
                        gap: 8px;
                        margin-top: 5px;
                    }

                    .url-input {
                        display: none;
                    }

                    .post-id-select {
                        display: none;
                    }
                </style>
                <?php foreach ($colors as $key => $color): ?>
                    <div class="color-row">
                        <label>Type :</label>
                        <select name="wbc_theme_colors[<?php echo esc_attr($key); ?>][type]" class="post-type-select">
                            <option value="all" <?php selected($color['type'], 'all'); ?>>Tous les types de contenu</option>
                            <option value="post" <?php selected($color['type'], 'post'); ?>>Tous les articles</option>
                            <option value="page" <?php selected($color['type'], 'page'); ?>>Toutes les pages</option>
                            <option value="product" <?php selected($color['type'], 'product'); ?>>Tous les produits</option>
                            <option value="url" <?php selected($color['type'], 'url'); ?>>URL</option>
                        </select>

                        <input type="color" name="wbc_theme_colors[<?php echo esc_attr($key); ?>][value]" value="<?php echo esc_attr($color['value']); ?>" />

                        <input type="text" name="wbc_theme_colors[<?php echo esc_attr($key); ?>][url]" class="url-input" value="<?php echo isset($color['url']) ? esc_attr($color['url']) : ''; ?>" placeholder="Entrez une URL" />

                        <select name="wbc_theme_colors[<?php echo esc_attr($key); ?>][post_id]" class="post-id-select">
                            <option value="">Sélectionner une publication</option>
                            <?php
                            if (in_array($color['type'], ['post', 'page', 'product'])) {
                                $args = array(
                                    'post_type' => $color['type'] === 'product' ? 'product' : 'post',
                                    'posts_per_page' => -1,
                                );
                                $posts = get_posts($args);
                                foreach ($posts as $post) {
                                    echo '<option value="' . esc_attr($post->ID) . '" ' . selected($color['post_id'], $post->ID, false) . '>' . esc_html($post->post_title) . '</option>';
                                }
                            }
                            ?>
                        </select>

                        <button type="button" class="remove-color button">Supprimer</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-color" class="button" style="margin-top: 10px;">Ajouter une nouvelle couleur</button>

            <?php submit_button(); ?>
        </form>

        <script type="text/javascript">
            document.getElementById('add-color').addEventListener('click', function () {
                const container = document.getElementById('color-repeater');
                const newIndex = container.children.length;
 
    const newKey = Date.now();
    const newColor = document.createElement('div');
    newColor.classList.add('color-row');


                
                const label = document.createElement('label');
                label.textContent = 'Type :';

                    const typeSelect = document.createElement('select');
    typeSelect.name = `wbc_theme_colors[${newKey}][type]`;
    typeSelect.classList.add('post-type-select');

                const options = ['all', 'post', 'page', 'product', 'url'];
                const optionLabels = ['Tous les types de contenu', 'Tous les articles', 'Toutes les pages', 'Tous les produits', 'URL'];

                options.forEach((option, index) => {
                    const opt = document.createElement('option');
                    opt.value = option;
                    opt.textContent = optionLabels[index];
                    typeSelect.appendChild(opt);
                });

                 const colorInput = document.createElement('input');
    colorInput.type = 'color';
    colorInput.name = `wbc_theme_colors[${newKey}][value]`;

                 const urlInput = document.createElement('input');
    urlInput.type = 'text';
    urlInput.name = `wbc_theme_colors[${newKey}][url]`;
    urlInput.classList.add('url-input');
    urlInput.placeholder = 'Entrez une URL';
                
    const postIdSelect = document.createElement('select');
    postIdSelect.name = `wbc_theme_colors[${newKey}][post_id]`;
    postIdSelect.classList.add('post-id-select');

                postIdSelect.innerHTML = '<option value="">Sélectionner une publication</option>';

                
                options.forEach((option) => {
                    if (option !== 'url') {
                        const opt = document.createElement('option');
                        opt.value = option;
                        opt.textContent = optionLabels[options.indexOf(option)];
                        postIdSelect.appendChild(opt);
                    }
                });

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.classList.add('remove-color', 'button');
                removeButton.textContent = 'Supprimer';

       
                newColor.append(label, typeSelect, colorInput, urlInput, postIdSelect, removeButton);
                container.appendChild(newColor);

                // Gérer l'affichage dynamique
                togglePostIdVisibility(typeSelect, postIdSelect);
                toggleUrlInputVisibility(typeSelect, urlInput);
                
       
                loadPosts(typeSelect, postIdSelect);
            });

            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-color')) {
                    e.target.parentElement.remove();
                }
            });

            function toggleUrlInputVisibility(select, urlInput) {
                urlInput.style.display = (select.value === 'url') ? 'block' : 'none';
            }

            function togglePostIdVisibility(select, postIdSelect) {
                postIdSelect.style.display = (select.value === 'url') ? 'none' : 'block';
            }

            function loadPosts(select, postIdSelect) {
                const type = select.value;
                postIdSelect.innerHTML = '<option value="">Sélectionner une publication</option>';
                
                if (['post', 'page', 'product'].includes(type)) {
                    fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=wbc_get_posts&type=' + type)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(post => {
                                const option = document.createElement('option');
                                option.value = post.ID;
                                option.textContent = post.post_title;
                                postIdSelect.appendChild(option);
                            });
                        });
                }
            }

            document.getElementById('color-repeater').addEventListener('change', function (e) {
                if (e.target.classList.contains('post-type-select')) {
                    const urlInput = e.target.closest('.color-row').querySelector('.url-input');
                    toggleUrlInputVisibility(e.target, urlInput);
                    
                    const postIdSelect = e.target.closest('.color-row').querySelector('.post-id-select');
                    togglePostIdVisibility(e.target, postIdSelect);
                    
                    loadPosts(e.target, postIdSelect);
                }
            });

            document.querySelectorAll('.post-type-select').forEach(select => {
                const urlInput = select.closest('.color-row').querySelector('.url-input');
                toggleUrlInputVisibility(select, urlInput);
                
                const postIdSelect = select.closest('.color-row').querySelector('.post-id-select');
                togglePostIdVisibility(select, postIdSelect);
                
                loadPosts(select, postIdSelect);
            });
        </script>
    </div>
    <?php
}



function wbc_settings_init() {
    register_setting('wbc_options_group', 'wbc_theme_colors');
}
add_action('admin_init', 'wbc_settings_init');
<?php
// Configuration de base du thème
function nathalie_mota_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    
    register_nav_menus(array(
        'main-menu' => __('Main Menu', 'nathalie-mota'),
        'footer-menu' => __('Footer Menu', 'nathalie-mota')
    ));
}
add_action('after_setup_theme', 'nathalie_mota_setup');

// Enregistrement des scripts et styles
function nathalie_mota_enqueue_scripts() {
    wp_enqueue_style('main-css', get_template_directory_uri() . '/assets/css/styles.css');
    wp_enqueue_style('lightbox-css', get_template_directory_uri() . '/assets/css/lightbox.css');
    wp_enqueue_style('header-css', get_template_directory_uri() . '/assets/css/header.css'); 
    wp_enqueue_style('footer-css', get_template_directory_uri() . '/assets/css/footer.css'); 
    wp_enqueue_style('front-page-css', get_template_directory_uri() . '/assets/css/front-page.css'); 

    wp_enqueue_script('jquery'); 
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/custom.js', array('jquery'), null, true);

    wp_localize_script('custom-js', 'nathalie_mota_ajax', array(
        'url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'nathalie_mota_enqueue_scripts');

// Enregistrement du Custom Post Type pour les photos et les taxonomies personnalisées
function nathalie_mota_custom_post_types() {
    register_post_type('photo', array(
        'label' => __('Photos', 'nathalie-mota'),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
        'taxonomies' => array('category', 'post_tag', 'format'),
        'rewrite' => array('slug' => 'photos'),
        'show_in_rest' => false, // Désactive Gutenberg
        'labels' => array(
            'name' => __('Photos', 'nathalie-mota'),
            'singular_name' => __('Photo', 'nathalie-mota'),
            'add_new' => __('Ajouter Nouvelle', 'nathalie-mota'),
            'add_new_item' => __('Ajouter Nouvelle Photo', 'nathalie-mota'),
            'edit_item' => __('Modifier Photo', 'nathalie-mota'),
            'new_item' => __('Nouvelle Photo', 'nathalie-mota'),
            'view_item' => __('Voir Photo', 'nathalie-mota'),
            'search_items' => __('Rechercher Photos', 'nathalie-mota'),
            'not_found' => __('Pas de Photos trouvées', 'nathalie-mota'),
            'not_found_in_trash' => __('Pas de Photos dans la corbeille', 'nathalie-mota'),
            'all_items' => __('Toutes les Photos', 'nathalie-mota'),
            'archives' => __('Archives des Photos', 'nathalie-mota'),
        ),
    ));

    register_taxonomy('format', 'photo', array(
        'label' => __('Formats', 'nathalie-mota'),
        'rewrite' => array('slug' => 'formats'),
        'hierarchical' => true,
    ));
}
add_action('init', 'nathalie_mota_custom_post_types');

// Désactiver Gutenberg pour le Custom Post Type 'photo'
function nathalie_mota_disable_gutenberg($current_status, $post_type) {
    if ($post_type === 'photo') return false;
    return $current_status;
}
add_filter('use_block_editor_for_post_type', 'nathalie_mota_disable_gutenberg', 10, 2);

// Ajouter une métabox pour les détails de la photo
function add_custom_meta_boxes() {
    add_meta_box(
        'photo_details',
        __('Photo Details', 'nathalie-mota'),
        'render_photo_details_meta_box',
        'photo',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_custom_meta_boxes');

function render_photo_details_meta_box($post) {
    wp_nonce_field('save_photo_details', 'photo_details_nonce');
    $date = get_post_meta($post->ID, '_photo_date', true);
    $reference = get_post_meta($post->ID, '_photo_reference', true);
    ?>
    <p>
        <label for="photo_date"><?php _e('Date de Prise de Vue', 'nathalie-mota'); ?></label>
        <input type="date" id="photo_date" name="photo_date" value="<?php echo esc_attr($date); ?>" />
    </p>
    <p>
        <label for="photo_reference"><?php _e('Référence Photo', 'nathalie-mota'); ?></label>
        <input type="text" id="photo_reference" name="photo_reference" value="<?php echo esc_attr($reference); ?>" />
    </p>
    <?php
}

function save_photo_details($post_id) {
    if (!isset($_POST['photo_details_nonce']) || !wp_verify_nonce($_POST['photo_details_nonce'], 'save_photo_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['photo_date'])) {
        update_post_meta($post_id, '_photo_date', sanitize_text_field($_POST['photo_date']));
    }
    if (isset($_POST['photo_reference'])) {
        update_post_meta($post_id, '_photo_reference', sanitize_text_field($_POST['photo_reference']));
    }
}
add_action('save_post', 'save_photo_details');

// Enregistrement des scripts AJAX pour les filtres et la pagination
function nathalie_mota_ajax_scripts() {
    wp_localize_script('custom-js', 'nathalie_mota_ajax', array(
        'url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'nathalie_mota_ajax_scripts');

function filter_photos() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';

    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => $order,
        'tax_query' => array(
            'relation' => 'AND',
        ),
    );

    if ($category && $category != 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => $category,
        );
    }

    if ($format && $format != 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'format',
            'field' => 'slug',
            'terms' => $format,
        );
    }

    $photos = new WP_QUERY($args);

    if ($photos->have_posts()) :
        while ($photos->have_posts()) : $photos->the_post();
            ?>
            <div class="photo-item">
                <a href="<?php the_permalink(); ?>" class="photo-link">
                    <?php
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('thumbnail');
                    } else {
                        echo __('No image', 'nathalie-mota');
                    }
                    ?>
                </a>
                <a href="<?php echo wp_get_attachment_url(get_post_thumbnail_id()); ?>" data-lightbox="image">
                    <i class="fa fa-expand"></i>
                </a>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
    else:
        echo __('No photos found', 'nathalie-mota');
    endif;
    wp_die();
}
add_action('wp_ajax_filter_photos', 'filter_photos');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos');

function load_more_photos() {
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;

    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'offset' => $offset,
    );

    $photos = new WP_Query($args);

    if ($photos->have_posts()) :
        while ($photos->have_posts()) : $photos->the_post();
            ?>
            <div class="photo-item">
                <a href="<?php the_permalink(); ?>" class="photo-link">
                    <?php
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('thumbnail');
                    } else {
                        echo __('No image', 'nathalie-mota');
                    }
                    ?>
                </a>
                <a href="<?php echo wp_get_attachment_url(get_post_thumbnail_id()); ?>" data-lightbox="image">
                    <i class="fa fa-expand"></i>
                </a>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
    else:
        echo __('No more photos found', 'nathalie-mota');
    endif;
    wp_die();
}
add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');

// Supprimer la catégorie "Uncategorized" et exclure la catégorie "General" des sélecteurs personnalisés
function remove_uncategorized_category() {
    $uncategorized_id = get_cat_ID('Uncategorized');
    if ($uncategorized_id) {
        wp_delete_term($uncategorized_id, 'category');
    }
}
add_action('init', 'remove_uncategorized_category');

// Exclure "Uncategorized" et "General" des sélecteurs personnalisés
function exclude_uncategorized_and_general_term($terms, $taxonomies, $args) {
    if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
        foreach ($terms as $key => $term) {
            if (is_object($term) && ($term->slug == 'uncategorized' || $term->slug == 'general')) {
                unset($terms[$key]);
            }
        }
    }
    return $terms;
}
add_filter('get_terms', 'exclude_uncategorized_and_general_term', 10, 3);

// Permettre la suppression des termes de taxonomie dans les Custom Post Types
function allow_term_deletion() {
    global $wp_taxonomies;
    foreach ($wp_taxonomies as $taxonomy => $object) {
        if (in_array('photo', $object->object_type)) {
            $wp_taxonomies[$taxonomy]->public = true;
        }
    }
}
add_action('init', 'allow_term_deletion');

// Page d'options pour gérer les termes personnalisés
function add_taxonomy_management_page() {
    add_menu_page(
        __('Gestion des Taxonomies', 'nathalie-mota'),
        __('Gestion des Taxonomies', 'nathalie-mota'),
        'manage_options',
        'taxonomy-management',
        'render_taxonomy_management_page'
    );
}
add_action('admin_menu', 'add_taxonomy_management_page');

function render_taxonomy_management_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Gestion des Taxonomies', 'nathalie-mota'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('delete_terms_nonce', 'delete_terms_nonce_field'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Supprimer un terme de catégorie', 'nathalie-mota'); ?></th>
                    <td>
                        <select name="term_id">
                            <?php
                            $terms = get_terms(array('taxonomy' => 'category', 'hide_empty' => false));
                            foreach ($terms as $term) {
                                if ($term->slug != 'uncategorized' && $term->slug != 'general') {
                                    echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Supprimer', 'nathalie-mota')); ?>
        </form>
        <?php
        if (isset($_POST['delete_terms_nonce_field']) && wp_verify_nonce($_POST['delete_terms_nonce_field'], 'delete_terms_nonce')) {
            $term_id = intval($_POST['term_id']);
            wp_delete_term($term_id, 'category');
            echo '<div class="updated"><p>' . __('Terme supprimé.', 'nathalie-mota') . '</p></div>';
        }
        ?>
    </div>
    <?php
}

// Customizer pour ajouter une image dans la section hero
function nathalie_mota_customizer_register($wp_customize) {
    // Ajouter une section pour la photo du hero
    $wp_customize->add_section('hero_section', array(
        'title'    => __('Hero Image', 'nathalie-mota'),
        'priority' => 30,
    ));

    // Ajouter un paramètre pour l'image
    $wp_customize->add_setting('hero_image', array(
        'default'   => '',
        'transport' => 'refresh',
    ));

    // Ajouter un contrôle pour l'image
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_image', array(
        'label'    => __('Upload Hero Image', 'nathalie-mota'),
        'section'  => 'hero_section',
        'settings' => 'hero_image',
    )));
}
add_action('customize_register', 'nathalie_mota_customizer_register');

?>

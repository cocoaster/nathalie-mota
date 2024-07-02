<?php
/*
Template Name: Front Page
*/

get_header(); ?>

<div id="main-content">
    <div class="hero">
        <?php if (get_theme_mod('hero_image')) : ?>
            <img src="<?php echo esc_url(get_theme_mod('hero_image')); ?>" alt="Hero Image">
        <?php endif; ?>
    </div>
    <div id="filters">
        <div class="filter-group">
            <label for="category-filter"><?php _e('CATÉGORIES', 'nathalie-mota'); ?></label>
            <select id="category-filter">
                <option value=""><?php _e('', 'nathalie-mota'); ?></option>
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'category',
                    'hide_empty' => false,
                ));
                foreach ($categories as $category) {
                    if ($category->slug != 'general') {
                        echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="filter-group">
            <label for="format-filter"><?php _e('FORMATS', 'nathalie-mota'); ?></label>
            <select id="format-filter">
                <option value=""><?php _e('', 'nathalie-mota'); ?></option>
                <?php
                $formats = get_terms(array('taxonomy' => 'format', 'hide_empty' => false));
                foreach ($formats as $format) {
                    echo '<option value="' . esc_attr($format->slug) . '">' . esc_html($format->name) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="filter-group">
            <label for="order-filter"><?php _e('TRIER PAR', 'nathalie-mota'); ?></label>
            <select id="order-filter">
                <option value=""><?php _e('', 'nathalie-mota'); ?></option>
                <option value="DESC"><?php _e('Les plus récentes', 'nathalie-mota'); ?></option>
                <option value="ASC"><?php _e('Les plus anciennes', 'nathalie-mota'); ?></option>
            </select>
        </div>
    </div>
    <div id="photo-list"></div>
    <button id="load-more"><?php _e('Voir plus de photos', 'nathalie-mota'); ?></button>
</div>

<?php get_footer(); ?>

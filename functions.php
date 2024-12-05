<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {
	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );
}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );
// Do not remove the code above!!!


// Write your code below

// CUSTOM FOOTER BLOCK
function kpi_custom_footer() {
    ?>
    <footer class="custom-site-footer">
        <div class="custom-footer-container">
            <!-- Left Section -->
            <div class="custom-footer-left">
                <?php
                echo get_menu_from_shared_folder();
                ?>
            </div>

            <!-- Middle Section -->
            <div class="custom-footer-middle">
                <!-- Logo KPI -->
                <a href="https://kpi.ua" target="_blank">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/brandbook-symbols/main-building/kpi_main_building_white.png" alt="KPI Main Building">
                </a>
                <p>
                    <!-- Copyright with dynamic name -->
                    <a href="<?php echo home_url(); ?>" class="footer-link">
                        Copyright <?php echo date('Y'); ?> © КПІ ім. І. Сікорського, <?php echo get_bloginfo('name'); ?>
                    </a>
                </p>
                <p>
                    <!-- KBIS link -->
                    <a href="https://kbis.kpi.ua/" class="footer-link">Розроблено КБІС</a>
                </p>
            </div>

            <!-- Right Section -->
            <div class="custom-footer-right">
                <p>
                    <strong>Адреса:</strong>
                    <a
                        href="https://www.google.com/maps/search/<?php echo urlencode(get_theme_mod('fixed_field_address', 'Address not available')); ?>"
                        target="_blank" 
                        class="footer-link"
                    >
                    <?php echo esc_html(get_theme_mod('fixed_field_address', 'Address not available')); ?>
                </p>
                <p><strong>Тел.:</strong> <a href="tel:<?php echo esc_attr(get_theme_mod('fixed_field_phone', 'Phone not available')); ?>" class="footer-link"><?php echo esc_html(get_theme_mod('fixed_field_phone', 'Phone not set')); ?></a></p>
                <p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr(get_theme_mod('fixed_field_email', 'Email not available')); ?>" class="footer-link"><?php echo esc_html(get_theme_mod('fixed_field_email', 'Email not set')); ?></a></p>
            </div>
        </div>
    </footer>
    <?php
}



// ASTRA THEME settings BEGIN ==================================================

// Hides the "Customise CSS" option in theme settings
function remove_custom_css_section( $wp_customize ) {
    $wp_customize->remove_section( 'custom_css' );
}
add_action( 'customize_register', 'remove_custom_css_section', 15 );


// Sets a single color palette according to the brand book (db table "astra-color-palettes")
function update_astra_color_palettes() {
    $new_palette = array(
        'currentPalette' => 'palette_1',
        'palettes' => array(
            'palette_1' => array(
                '#1c396e',
                '#008acf',
                '#1062a3',
                '#0d5690',
                '#004f7f',
                '#f07d00',
                '#ec6605',
                '#7f0d38',
                '#620c33',
                '#ffffff',
                '#bbbbbb',
                '#958f93',
                '#000000',
            ),
        ),
        'presets' => array(),
        'flag' => true,
    );
    update_option('astra-color-palettes', $new_palette);
}
add_action('init', 'update_astra_color_palettes');


// Sets Exo 2 as the main font family
function set_custom_astra_typography_defaults() {
    // get current settings from db
    $astra_settings = get_option('astra-settings', array());

    // define needed values for fonts
    $custom_typography_settings = array(
        'body-font-family'       => "'Exo 2', sans-serif",
        'headings-font-family'   => "'Exo 2', sans-serif",
        'font-family-h1'         => "'Exo 2', sans-serif",
        'font-family-h2'         => "'Exo 2', sans-serif",
        'font-family-h4'         => "'Exo 2', sans-serif",
        'font-family-h5'         => "'Exo 2', sans-serif",
        'font-family-h6'         => "'Exo 2', sans-serif",
    );

    // merge current settings with customized
    $astra_settings = array_merge($astra_settings, $custom_typography_settings);

    // save the updates to db
    update_option('astra-settings', $astra_settings);
}
add_action('init', 'set_custom_astra_typography_defaults');

// ASTRA THEME settings END ==================================================

// custom rest api for getting menus by id
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/menu/(?P<id>\d+)', [
        'methods'  => 'GET',
        'callback' => 'get_menu_by_id',
    ]);
});

function get_menu_by_id($data) {
    $menu = wp_get_nav_menu_items($data['id']);
    if (empty($menu)) {
        return new WP_Error('no_menu', 'Menu not found', ['status' => 404]);
    }
    return $menu;
}

// fetch custom menu
function fetch_global_menu_items() {
    $response = wp_remote_get('https://localhost:8080/wp-json/custom/v1/menu/2');
    if (is_wp_error($response)) {
        return '<li>Error menu loading</li>';
    }

    $menu_data = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($menu_data)) {
        return '<li>Menu unavailable</li>';
    }

    $menu_html = '';
    foreach ($menu_data as $item) {
        $menu_html .= '<li class="menu-item"><a href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a></li>';
    }

    return $menu_html;
}

// shared menu shortcode
function display_global_menu() {
    return '<ul class="main-navigation">' . fetch_global_menu_items() . '</ul>';
}
add_shortcode('global_menu', 'display_global_menu');




// custom text fields
function add_custom_fixed_fields_to_customizer($wp_customize) {
    // Section for new fields
    $wp_customize->add_section('fixed_fields_section', array(
        'title'    => __('Fixed Text Fields', 'astra-child'),
        'priority' => 30,
    ));

    // Address field
    $wp_customize->add_setting('fixed_field_address', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('fixed_field_address', array(
        'label'   => __('Address', 'astra-child'),
        'section' => 'fixed_fields_section',
        'type'    => 'text',
    ));

    // Phone field
    $wp_customize->add_setting('fixed_field_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('fixed_field_phone', array(
        'label'   => __('Phone', 'astra-child'),
        'section' => 'fixed_fields_section',
        'type'    => 'text',
    ));

    // Email field
    $wp_customize->add_setting('fixed_field_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('fixed_field_email', array(
        'label'   => __('Email', 'astra-child'),
        'section' => 'fixed_fields_section',
        'type'    => 'email',
    ));
}
add_action('customize_register', 'add_custom_fixed_fields_to_customizer');




// custom menu
function get_menu_from_shared_folder() {
    // Get the current theme directory path
    $theme_dir = get_template_directory();

    // Path to the menu.html file
    $menu_file = $theme_dir . '/menu.html';

    // Check if the file exists
    if (file_exists($menu_file)) {
        // Read the content of the file
        $menu_html = file_get_contents($menu_file);
        return $menu_html;
    } else {
        return 'Menu not found';
    }
}
?>

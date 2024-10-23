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
    <footer class="site-footer">
         <div class="custom-footer-text">
             <!-- Logo KPI -->
             <a href="https://kpi.ua" target="_blank">
                 <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/brandbook-symbols/main-building/kpi_main_building_white.png" alt="Головний корпус КПІ" style="width: 40%;">
             </a>
             <br><br>
             <!-- Copyright with dynamic name -->
             <p>
                 <a href="<?php echo home_url(); ?>" class="footer-link">
                     Copyright <?php echo date('Y'); ?> © КПІ ім. І. Сікорського, <?php echo get_bloginfo('name'); ?>. Всі Права Захищено
                 </a>
             </p>
             <!-- KBIS link -->
             <p>
                 <a href="https://kbis.kpi.ua/" class="footer-link">Розроблено КБІС</a>
             </p>
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


// Sets a single color palette according to the brand book
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
                '#ffffff',
                '#f07d00',
                '#ec6605',
                '#7f0d38',
            ),
        ),
        'presets' => array(),
        'flag' => true,
    );
    update_option('astra-color-palettes', $new_palette);
}
add_action('init', 'update_astra_color_palettes');



// ASTRA THEME settings END ==================================================
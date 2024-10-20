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


// THEME SETTINGS
// Hides the "Customise CSS" option in theme settings
function remove_custom_css_section( $wp_customize ) {
    $wp_customize->remove_section( 'custom_css' );
}
add_action( 'customize_register', 'remove_custom_css_section', 15 );


// CUSTOM BLOCKS
function kpi_custom_footer() {
    ?>
    <footer class="site-footer">
        <div class="custom-footer-text">
            <p>© КПІ ім. І. Сікорського, <?php echo get_bloginfo('name'); ?> | Всі права захищено</p>
        </div>
    </footer>
    <?php
}

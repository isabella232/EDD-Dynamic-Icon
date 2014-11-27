<?php
/**
 * Scripts
 *
 * @package     EDD\DynamicIcon
 * @since       1.0.0
 * @author      Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright   Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Load scripts
 *
 * @since       1.0.0
 * @return      void
 */
function edd_dynamic_icon_scripts() {
    wp_enqueue_script( 'favico', EDD_DYNAMIC_ICON_URL . 'assets/js/favico-0.3.5.min.js' );
    wp_enqueue_script( 'edd-dynamic-icon', EDD_DYNAMIC_ICON_URL . 'assets/js/edd-dynamic-icon.js', array( 'jquery' ), EDD_DYNAMIC_ICON_VER );
    wp_localize_script( 'edd-dynamic-icon', 'edd_dynamic_icon_vars', array(
        'color'     => edd_get_option( 'edd_dynamic_icon_color', '#ffffff' ),
        'background'=> edd_get_option( 'edd_dynamic_icon_background', '#ff0000' ),
        'style'     => edd_get_option( 'edd_dynamic_icon_style', 'bold' ),
        'shape'     => edd_get_option( 'edd_dynamic_icon_shape', 'circle' ),
        'animation' => edd_get_option( 'edd_dynamic_icon_animation', 'none' ),
        'count'     => edd_get_cart_quantity()
    ) );
}
add_action( 'wp_enqueue_scripts', 'edd_dynamic_icon_scripts' );

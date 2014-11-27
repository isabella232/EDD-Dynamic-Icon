<?php
/**
 * Plugin Name:     Easy Digital Downloads - Dynamic Icon
 * Description:     Adds a dynamic favicon to the EDD shopping cart
 * Version:         1.0.0
 * Author:          Daniel J Griffiths
 * Author URI:      http://section214.com
 * Text Domain:     edd-dynamic-icon
 *
 * @package         EDD\DynamicIcon
 * @author          Daniel J Griffiths <dgriffiths@section214.com>
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'EDD_Dynamic_Icon' ) ) {


    /**
     * Main EDD_Dynamic_Icon class
     *
     * @since       1.0.0
     */
    class EDD_Dynamic_Icon {

        /**
         * @var         EDD_Dynamic_Icon $instance The one true EDD_Dynamic_Icon
         * @since       1.0.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      self::$instance The one true EDD_Dynamic_Icon
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new EDD_Dynamic_Icon();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'EDD_DYNAMIC_ICON_VER', '1.0.0' );

            // Plugin path
            define( 'EDD_DYNAMIC_ICON_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'EDD_DYNAMIC_ICON_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            // Load core files
            require_once EDD_DYNAMIC_ICON_DIR . 'includes/scripts.php';
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function hooks() {
            // Register settings
            add_filter( 'edd_settings_extensions', array( $this, 'settings' ), 1 );

            // Add favicon
            add_action( 'wp_head', array( $this, 'add_favicon' ) );
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
            $lang_dir = apply_filters( 'EDD_Dynamic_Icon_language_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale     = apply_filters( 'plugin_locale', get_locale(), '' );
            $mofile     = sprintf( '%1$s-%2$s.mo', 'edd-dynamic-icon', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/edd-dynamic-icon/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/edd-dynamic-icon/ folder
                load_textdomain( 'edd-dynamic-icon', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/edd-dynamic-icon/languages/ folder
                load_textdomain( 'edd-dynamic-icon', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'edd-dynamic-icon', false, $lang_dir );
            }
        }


        /**
         * Add settings
         *
         * @access      public
         * @since       1.0.0
         * @param       array $settings the existing EDD settings array
         * @return      array $settings the filtered EDD settings array
         */
        public function settings( $settings ) {
            $new_settings = array(
                array(
                    'id'    => 'edd_dynamic_icon',
                    'name'  => '<strong>' . __( 'Dynamic Icon', 'edd-dynamic-icon' ) . '</strong>',
                    'desc'  => '',
                    'type'  => 'header'
                ),
                array(
                    'id'    => 'edd_dynamic_icon_favicon',
                    'name'  => __( 'Favicon', 'edd-dynamic-icon' ),
                    'desc'  => __( 'If your theme doesn\'t natively support favicons, upload one here!', 'edd-dynamic-icon' ),
                    'type'  => 'upload'
                ),
                array(
                    'id'    => 'edd_dynamic_icon_color',
                    'name'  => __( 'Text Color', 'edd-dynamic-icon' ),
                    'desc'  => __( 'Specify the color for the icon text', 'edd-dynamic-icon' ),
                    'type'  => 'color',
                    'std'   => '#ffffff'
                ),
                array(
                    'id'    => 'edd_dynamic_icon_background',
                    'name'  => __( 'Background Color', 'edd-dynamic-icon' ),
                    'desc'  => __( 'Specify the background color for the icon', 'edd-dynamic-icon' ),
                    'type'  => 'color',
                    'std'   => '#ff0000'
                ),
                array(
                    'id'    => 'edd_dynamic_icon_style',
                    'name'  => __( 'Font Style', 'edd-dynamic-icon' ),
                    'desc'  => __( 'Set the style for the badge text', 'edd-dynamic-icon' ),
                    'type'  => 'select',
                    'options'   => array(
                        'normal'    => __( 'Normal', 'edd-dynamic-icon' ),
                        'italic'    => __( 'Italic', 'edd-dynamic-icon' ),
                        'bold'      => __( 'Bold', 'edd-dynamic-icon' )
                    ),
                    'std'   => 'bold'
                ),
                array(
                    'id'    => 'edd_dynamic_icon_shape',
                    'name'  => __( 'Badge Shape', 'edd-dynamic-icon' ),
                    'desc'  => __( 'Specify the shape of the badge', 'edd-dynamic-icon' ),
                    'type'  => 'select',
                    'options'   => array(
                        'circle'    => __( 'Circle', 'edd-dynamic-icon' ),
                        'rectangle' => __( 'Rectangle', 'edd-dynamic-icon' ),
                    ),
                    'std'   => 'circle'
                ),
                array(
                    'id'    => 'edd_dynamic_icon_animation',
                    'name'  => __( 'Badge Animation', 'edd-dynamic-icon' ),
                    'desc'  => __( 'Specify the animation for badge updates', 'edd-dynamic-icon' ),
                    'type'  => 'select',
                    'options'    => array(
                        'none'      => __( 'None', 'edd-dynamic-icon' ),
                        'slide'     => __( 'Slide', 'edd-dynamic-icon' ),
                        'fade'      => __( 'Fade', 'edd-dynamic-icon' ),
                        'pop'       => __( 'Pop', 'edd-dynamic-icon' ),
                        'popFade'   => __( 'Pop/Fade', 'edd-dynamic-icon' )
                    ),
                    'std'   => 'none'
                )
            );

            return array_merge( $settings, $new_settings );
        }


        /**
         * Conditionally add favicon
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function add_favicon() {
            $favicon = edd_get_option( 'edd_dynamic_icon_favicon', false );

            if( $favicon ) {
                $type = edd_get_file_ctype( $favicon );
                echo '<link rel="icon" href="' . esc_url( $favicon ) . '" type="' . $type . '" />';
            }
        }
    }
}


/**
 * The main function responsible for returning the one true EDD_Dynamic_Icon
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      EDD_Dynamic_Icon The one true EDD_Dynamic_Icon
 */
function EDD_Dynamic_Icon_load() {
    if( !class_exists( 'Easy_Digital_Downloads' ) ) {
        if( !class_exists( 'S213_EDD_Activation' ) ) {
            require_once( 'includes/class.s214-edd-activation.php' );
        }

        $activation = new S214_EDD_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
        $activation = $activation->run();
    } else {
        return EDD_Dynamic_Icon::instance();
    }
}
add_action( 'plugins_loaded', 'EDD_Dynamic_Icon_load' );

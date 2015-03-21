<?php
/**
 * Activation handler
 *
 * @package     EDD\ActivationHandler
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * Section214 EDD Activation Handler Class
 *
 * @since       1.0.0
 */
class S214_EDD_Activation {

    public $plugin_name, $plugin_path, $plugin_file, $has_edd;

    /**
     * Setup the activation class
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function __construct( $plugin_path, $plugin_file ) {
        // We need plugin.php!
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $plugins = get_plugins();

        // Set plugin directory
        $plugin_path = array_filter( explode( '/', $plugin_path ) );
        $this->plugin_path = end( $plugin_path );

        // Set plugin file
        $this->plugin_file = $plugin_file;

        // Set plugin name
        $this->plugin_name = str_replace( 'Easy Digital Downloads - ', '', $plugins[$this->plugin_path . '/' . $this->plugin_file]['Name'] );

        // Is EDD installed?
        foreach( $plugins as $plugin_path => $plugin ) {
            if( $plugin['Name'] == 'Easy Digital Downloads' ) {
                $this->has_edd = true;
            }
        }
    }


    /**
     * Process plugin deactivation
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function run() {
        // Display notice
        add_action( 'admin_notices', array( $this, 'missing_edd_notice' ) );
    }


    /**
     * Display notice if EDD isn't installed
     *
     * @access      public
     * @since       1.0.0
     * @return      string The notice to display
     */
    public function missing_edd_notice() {
        if( $this->has_edd ) {
            echo '<div class="error"><p>' . $this->plugin_name . __( ' requires Easy Digital Downloads! Please activate it to continue!', 's214-edd-activation' ) . '</p></div>';
        } else {
            echo '<div class="error"><p>' . $this->plugin_name . __( ' requires Easy Digital Downloads! Please install it to continue!', 's214-edd-activation' ) . '</p></div>';
        }
    }
}

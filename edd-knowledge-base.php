<?php
/**
 * Plugin Name:     Easy Digital Downloads - Knowledge Base
 * Plugin URI:      https://easydigitaldownloads.com/extensions/knowledge-base/
 * Description:     Allows site owners to add KB items to their downloads
 * Version:         1.0.0
 * Author:          Daniel J Griffiths
 * Author URI:      http://section214.com
 * Text Domain:     edd-knowledge-base
 *
 * @package         EDD\KnowledgeBase
 * @author          Daniel J Griffiths <dgriffiths@section214.com>
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


if( ! class_exists( 'EDD_Knowledge_Base' ) ) {


    /**
     * Main EDD_Knowledge_Base class
     *
     * @since       1.0.0
     */
    class EDD_Knowledge_Base {


        /**
         * @var         EDD_Knowledge_Base $instance The one true EDD_Knowledge_Base
         * @since       1.0.0
         */
        private static $instance;


        public $maybe_exit = false;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      self::$instance The one true EDD_Knowledge_Base
         */
        public static function instance() {
            if( ! self::$instance ) {
                self::$instance = new EDD_Knowledge_Base();
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
            define( 'EDD_KNOWLEDGE_BASE_VER', '1.0.0' );
            
            // Plugin path
            define( 'EDD_KNOWLEDGE_BASE_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'EDD_KNOWLEDGE_BASE_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            require_once EDD_KNOWLEDGE_BASE_DIR . 'includes/scripts.php';
            require_once EDD_KNOWLEDGE_BASE_DIR . 'includes/functions.php';
            require_once EDD_KNOWLEDGE_BASE_DIR . 'includes/post-types.php';
            require_once EDD_KNOWLEDGE_BASE_DIR . 'includes/meta-boxes.php';
            require_once EDD_KNOWLEDGE_BASE_DIR . 'includes/template-functions.php';
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        PRIVATe function hooks() {
            // Add our extension settings
            add_filter( 'edd_settings_extensions', array( $this, 'add_settings' ) );

            // Add a link to the extension settings
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );

            // Handle licensing
            if( class_exists( 'EDD_License' ) ) {
                $license = new EDD_License( __FILE__, 'Knowledge Base', EDD_KNOWLEDGE_BASE_VER, 'Daniel J Griffiths' );
            }
        }


        /**
         * Add settings
         *
         * @access      public
         * @since       1.0.0
         * @param       array $settings The existing plugin settings
         * @return      array The modified plugin settings
         */
        public function add_settings( $settings ) {
            $new_settings = array(
                array(
                    'id'    => 'edd_knowledge_base_settings',
                    'name'  => '<strong>' . __( 'Knowledge Base Settings', 'edd-knowledge-base' ) . '</strong>',
                    'desc'  => '',
                    'type'  => 'header'
                ),
                array(
                    'id'    => 'edd_knowledge_base_title',
                    'name'  => __( 'Title', 'edd-knowledge-base' ),
                    'desc'  => __( 'Enter the title to display above the knowledge base.', 'edd-knowledge-base' ),
                    'type'  => 'text',
                    'std'   => __( 'Knowledge Base', 'edd-knowledge-base' )
                ),
                array(
                    'id'    => 'edd_knowledge_base_category_icon',
                    'name'  => __( 'Category Icon', 'edd-knowledge-base' ),
                    'desc'  => sprintf( __( 'Enter the <a href="%s" target="_blank">icon</a> you want to use for categories, or enter \'none\' for none.', 'edd-knowledge-base' ), 'http://fontawesome.io/icons/' ),
                    'type'  => 'text',
                    'std'   => 'folder-o'
                ),
                array(
                    'id'    => 'edd_knowledge_base_category_icon_color',
                    'name'  => __( 'Category Icon Color', 'edd-knowledge-base' ),
                    'desc'  => '<div class="edd-knowledge-base-color-picker-label">' . __( 'Select the color for the category icons.', 'edd-knowledge-base' ) . '</div>',
                    'type'  => 'color',
                    'std'   => '#333333'
                ),
                array(
                    'id'    => 'edd_knowledge_base_article_icon',
                    'name'  => __( 'Article Icon', 'edd-knowledge-base' ),
                    'desc'  => sprintf( __( 'Enter the <a href="%s" target="_blank">icon</a> you want to use for articles, or enter \'none\' for none.', 'edd-knowledge-base' ), 'http://fontawesome.io/icons/' ),
                    'type'  => 'text',
                    'std'   => 'file-text-o'
                ),
                array(
                    'id'    => 'edd_knowledge_base_category_article_color',
                    'name'  => __( 'Article Icon Color', 'edd-knowledge-base' ),
                    'desc'  => '<div class="edd-knowledge-base-color-picker-label">' . __( 'Select the color for the article icons.', 'edd-knowledge-base' ) . '</div>',
                    'type'  => 'color',
                    'std'   => '#333333'
                ),
            );

            return array_merge( $settings, $new_settings );
        }


        /**
         * Add a link to the KB settings
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function admin_menu() {
            add_submenu_page( 'edit.php?post_type=edd_kb_article', __( 'Settings', 'edd-knowledge-base' ), __( 'Settings', 'edd-knowledge-base' ), 'manage_shop_settings', 'edit.php?post_type=download&page=edd-settings&tab=extensions#edd_settings[edd_knowledge_base_title]' );
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
            $lang_dir = apply_filters( 'edd_knowledge_base_language_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), '' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'edd-knowledge-base', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/edd-knowledge-base/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/edd-knowledge-base/ folder
                load_textdomain( 'edd-knowledge-base', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/edd-knowledge-base/ folder
                load_textdomain( 'edd-knowledge-base', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'edd-knowledge-base', false, $lang_dir );
            }
        }
    }
}


/**
 * The main function responsible for returning the one true EDD_Knowledge_Base
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      EDD_Knowledge_Base The one true EDD_Knowledge_Base
 */
function edd_knowledge_base() {
    if( ! class_exists( 'Easy_Digital_Downloads' ) ) {
        if( ! class_exists( 'S214_EDD_Activation' ) ) {
            require_once 'includes/class.s214-edd-activation.php';
        }

        $activation = new S214_EDD_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
        $activation = $activation->run();
        
        return EDD_Knowledge_Base::instance();
    } else {
        return EDD_Knowledge_Base::instance();
    }
}
add_action( 'plugins_loaded', 'edd_knowledge_base' );

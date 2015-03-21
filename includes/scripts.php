<?php
/**
 * Scripts
 *
 * @package     EDD\KnowledgeBase\Scripts
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Load scripts
 *
 * @since       1.0.0
 * @return      void
 */
function edd_knowledge_base_scripts() {
    wp_enqueue_style( 'edd-knowledge-base-fa', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );
    wp_enqueue_style( 'edd-knowledge-base', EDD_KNOWLEDGE_BASE_URL . 'assets/css/style.css', array(), EDD_KNOWLEDGE_BASE_VER );
}
add_action( 'wp_enqueue_scripts', 'edd_knowledge_base_scripts' );


/**
 * Load admin scripts
 *
 * @since       1.0.0
 * @return      void
 */
function edd_knowledge_base_admin_scripts() {
    wp_enqueue_style( 'edd-knowledge-base', EDD_KNOWLEDGE_BASE_URL . 'assets/css/admin.css', array(), EDD_KNOWLEDGE_BASE_VER );
}
add_action( 'admin_enqueue_scripts', 'edd_knowledge_base_admin_scripts' );

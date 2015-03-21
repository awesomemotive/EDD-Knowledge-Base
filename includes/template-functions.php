<?php
/**
 * Template functions
 *
 * @package     EDD\KnowledgeBase\Template
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Add the KB to a given download
 *
 * @since       1.0.0
 * @return      void
 */
function edd_knowledge_base_display( $post_id ) {
    $articles = edd_knowledge_base_get_articles( $post_id );

    echo $articles;
}
add_action( 'edd_after_download_content', 'edd_knowledge_base_display' );

<?php
/**
 * Helper functions
 *
 * @package     EDD\KnowledgeBase\Functions
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Get knowledge base articles
 *
 * @since       1.0.0
 * @param       int $download_id The ID of a download to retrieve articles for
 * @return      string $content The formatted content
 */
function edd_knowledge_base_get_articles( $download_id = false ) {
    $post_args = array(
        'posts_per_page'    => 99999,
        'category'          => '',
        'post_type'         => 'edd_kb_article',
        'post_status'       => 'publish'
    );

    // Maybe filter by ID
    if( $download_id ) {
        $post_args['meta_key']   = '_edd_knowledge_base_download';
        $post_args['meta_value'] = $download_id;
    }

    $category_args = array(
        'type'      => 'post',
        'orderby'   => 'name',
        'order'     => 'ASC',
        'hide_empty'=> 1,
        'taxonomy'  => 'edd_kb_category'
    );

    $categories = get_categories( $category_args );

    $header  = '<div class="edd-knowledge-base-wrapper">';
    $header .= '<h3 class="edd-knowledge-base-title">' . edd_get_option( 'edd_knowledge_base_title', __( 'Knowledge Base', 'edd-knowledge-base' ) ) . '</h3>';

    $category_icon_color= edd_get_option( 'edd_knowledge_base_category_icon_color', '#333333' );
    $category_icon      = edd_get_option( 'edd_knowledge_base_category_icon', 'folder-o' );
    $category_icon      = ( $category_icon != 'none' ? '<i class="fa fa-' . $category_icon . '" style="color: ' . $category_icon_color . ';"></i>' : false );
    $article_icon_color = edd_get_option( 'edd_knowledge_base_article_icon_color', '#333333' );
    $article_icon       = edd_get_option( 'edd_knowledge_base_article_icon', 'file-text-o' );
    $article_icon       = ( $article_icon != 'none' ? '<i class="fa fa-' . $article_icon . '" style="color: ' . $article_icon_color . ';"></i>' : false );

    $content = '';

    foreach( $categories as $category_id => $category_data ) {
        // Set the category to check
        $post_args['edd_kb_category'] = $category_data->name;

        // Get the articles
        $articles = get_posts( $post_args );

        if( $count = count( $articles ) > 0 ) {
            $content .= '<div class="edd-knowledge-base-category-wrapper">';
            $content .= '<h4 class="edd-knowledge-base-category-title">' . ( $category_icon ? $category_icon : '' ) . $category_data->name . ' (' . $count . ')' . '</h4>';
            $content .= '<ul>';

            foreach( $articles as $article_id => $article_data ) {
                $content .= '<li>' . ( $article_icon ? $article_icon : '' ) . '<a href="' . get_permalink( $article_data->ID ) . '">' . $article_data->post_title . '</a></li>';
            }

            $content .= '</ul>';
            $content .= '</div>';
        }
    }

    // Add the header
    if( $content != '' ) {
        $content  = $header . $content;
        $content .= '</div>';
    }

    return $content;
}

<?php
/**
 * Post types
 *
 * @package     EDD\KnowledgeBase\PostTypes
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Register a new post type for Articles
 *
 * @since       1.0.0
 * @return      void
 */
function edd_knowledge_base_post_types() {
    $labels = apply_filters( 'edd_knowledge_base_labels', array(
        'name'              => _x( 'Article', 'knowledge base post type name', 'edd-knowledge-base' ),
        'singular_name'     => _x( 'Article', 'singular knowledge base post type name', 'edd-knowledge-base' ),
        'add_new'           => __( 'Add New', 'edd-knowledge-base' ),
        'add_new_item'      => __( 'Add New Article', 'edd-knowledge-base' ),
        'edit_item'         => __( 'Edit Article', 'edd-knowledge-base' ),
        'new_item'          => __( 'New Article', 'edd-knowledge-base' ),
        'all_items'         => __( 'All Articles', 'edd-knowledge-base' ),
        'view_item'         => __( 'View Article', 'edd-knowledge-base' ),
        'search_items'      => __( 'Search Articles', 'edd-knowledge-base' ),
        'not_found'         => __( 'No Articles found', 'edd-knowledge-base' ),
        'not_found_in_trash'=> __( 'No Articles found in Trash', 'edd-knowledge-base' ),
        'parent_item_colon' => '',
        'menu_name'         => __( 'Knowledge Base', 'edd-knowledge-base' )
    ) );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'publicly_queryable'=> true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'kb-article', 'with_front' => false ),
        'has_archive'       => false,
        'hierarchical'      => false,
        'supports'          => apply_filters( 'edd_knowledge_base_supports', array( 'title', 'editor', 'revisions', 'author', 'category' ) ),
        'menu_icon'         => 'dashicons-feedback'
    );

    register_post_type( 'edd_kb_article', apply_filters( 'edd_knowledge_base_post_type_args', $args ) );
}
add_action( 'init', 'edd_knowledge_base_post_types', 1 );


/**
 * Register taxonomies for the Article post type
 *
 * @since       1.0.0
 * @return      void
 */
function edd_knowledge_base_taxonomies() {
    $labels = array(
        'name'                  => _x( 'Categories', 'taxonomy general name', 'edd-knowledge-base' ),
        'singular_name'         => _x( 'Category', 'taxonomy singular name', 'edd-knowledge-base' ),
        'search_items'          => __( 'Search Categories', 'edd-knowledge-base' ),
        'all_items'             => __( 'All Categories', 'edd-knowledge-base' ),
        'edit_item'             => __( 'Edit Category', 'edd-knowledge-base' ),
        'update_item'           => __( 'Update Category', 'edd-knowledge-base' ),
        'add_new_item'          => __( 'Add New Knowledge Base Category', 'edd-knowledge-base' ),
        'new_item_name'         => __( 'New Category Name', 'edd-knowledge-base' ),
        'choose_from_most_used' => __( 'Choose from the most used categories', 'edd-knowledge-base' ),
        'not_found'             => __( 'No categories found.', 'edd-knowledge-base' )
    );

    $args = apply_filters( 'edd_knowledge_base_category_args', array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'show_ui'           => true,
        'query_var'         => 'edd_kb_category'
    ) );

    register_taxonomy( 'edd_kb_category', array( 'edd_kb_article' ), $args );
    register_taxonomy_for_object_type( 'edd_kb_category', 'edd_kb_article' );
}
add_action( 'init', 'edd_knowledge_base_taxonomies', 0 );


/**
 * Change default "Enter title here" text
 *
 * @since       1.0.0
 * @param       string $title Default placeholder
 * @return      string $title New placeholder
 */
function edd_knowledge_base_change_default_title( $title ) {
    $screen = get_current_screen();

    if( $screen->post_type ) {
        $title = __( 'Enter Article name here', 'edd-knowledge-base' );
    }

    return $title;
}
add_filter( 'enter_title_here', 'edd_knowledge_base_change_default_title' );


/**
 * Updated messages
 *
 * @since       1.0.0
 * @param       array $messages Post updated messages
 * @return      array $messages New post updated messages
 */
function edd_knowledge_base_updated_messages( $messages ) {
    $messages['edd_kb_article'] = array(
        1 => __( 'Article updated.', 'edd-knowledge-base' ),
        4 => __( 'Article updated.', 'edd-knowledge-base' ),
        6 => __( 'Article published.', 'edd-knowledge-base' ),
        7 => __( 'Article saved.', 'edd-knowledge-base' ),
        8 => __( 'Article submitted.', 'edd-knowledge-base' )
    );

    return $messages;
}
add_filter( 'post_updated_messages', 'edd_knowledge_base_updated_messages' );


/**
 * Updated bulk messages
 *
 * @since       1.0.0
 * @param       array $bulk_messages Post updated messages
 * @param       array $bulk_counts Post counts
 * @return      array $bulk_messages New post updated messages
 */
function edd_knowledge_base_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
    $bulk_messages['edd_kb_article'] = array(
        'updated'   => sprintf( _n( '%1$s Article updated.', '%1$s Articles updated.', $bulk_counts['updated'], 'edd-knowledge-base' ), $bulk_counts['updated'] ),
        'locked'    => sprintf( _n( '%1$s Article not updated, somebody is editing it.', '%1$s Articles not updated, somebody is editing them.', $bulk_counts['locked'], 'edd-knowledge-base' ), $bulk_counts['locked'] ),
        'deleted'   => sprintf( _n( '%1$s Article permanently deleted.', '%1$s Articles permanently deleted.', $bulk_counts['deleted'], 'edd-knowledge-base' ), $bulk_counts['deleted'] ),
        'trashed'   => sprintf( _n( '%1$s Article moved to the Trash.', '%1$s Articles moved to the Trash.', $bulk_counts['trashed'], 'edd-knowledge-base' ), $bulk_counts['trashed'] ),
        'untrashed' => sprintf( _n( '%1$s Article restored from the Trash.', '%1$s Articles restored from the Trash.', $bulk_counts['untrashed'], 'edd-knowledge-base' ), $bulk_counts['untrashed'] )
    );

    return $bulk_messages;
}
add_filter( 'bulk_post_updated_messages', 'edd_knowledge_base_bulk_updated_messages', 10, 2 );

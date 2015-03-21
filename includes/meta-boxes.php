<?php
/**
 * Meta boxes
 *
 * @package     EDD\KnowledgeBase\MetaBoxes
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Register meta box
 *
 * @since       1.0.0
 * @return      void
 */
function edd_knowledge_base_add_meta_box() {
    add_meta_box(
        'edd-download',
        __( 'Link to Download', 'edd-knowledge-base' ),
        'edd_knowledge_base_render_meta_box',
        'edd_kb_article',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'edd_knowledge_base_add_meta_box' );


/**
 * Render meta box
 *
 * @since       1.0.0
 * @global      object $post The post we are editing
 * @return      void
 */
function edd_knowledge_base_render_meta_box() {
    global $post;

    $download = get_post_meta( $post->ID, '_edd_knowledge_base_download', true );
    $download = ( $download ? $download : 0 );

    echo EDD()->html->product_dropdown( array(
        'chosen'    => true,
        'id'        => '_edd_knowledge_base_download',
        'name'      => '_edd_knowledge_base_download',
        'selected'  => $download
    ) );

    wp_nonce_field( basename( __FILE__ ), 'edd_knowledge_base_meta_box_nonce' );
}


/**
 * Save post meta when the save_post action is called
 *
 * @since       1.0.0
 * @param       int $post_id The ID of the post we are saving
 * @global      object $post The post we are saving
 * @return      void
 */
function edd_knowledge_base_meta_box_save( $post_id ) {
    global $post;

    // Don't process if nonce can't be validated
    if( ! isset( $_POST['edd_knowledge_base_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['edd_knowledge_base_meta_box_nonce'], basename( __FILE__ ) ) ) return $post_id;
    
    // Don't process if this is an autosave
    if( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) return $post_id;

    // Don't process if this is a revision
    if( isset( $post->post_type ) && $post->post_type == 'revision' ) return $post_id;

    // The default fields that get saved
    $fields = apply_filters( 'edd_knowledge_base_meta_box_fields_save', array(
        '_edd_knowledge_base_download'
    ) );
    
    foreach( $fields as $field ) {
        if( isset( $_POST[ $field ] ) ) {
            if( is_string( $_POST[ $field ] ) ) {
                $new = esc_attr( $_POST[ $field ] );
            } else {
                $new = $_POST[ $field ];
            }

            $new = apply_filters( 'edd_knowledge_base_meta_box_save_' . $field, $new );

            update_post_meta( $post_id, $field, $new );
        } else {
            delete_post_meta( $post_id, $field );
        }
    }
}
add_action( 'save_post', 'edd_knowledge_base_meta_box_save' );

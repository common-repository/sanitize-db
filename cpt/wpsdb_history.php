<?php
if ( ! function_exists('wpsdb_history_cpt') ) {
	function wpsdb_history_cpt() {
    $form_caps = array();
    register_post_type( 'wpsdb_history',
       // CPT Options
        array(
            'labels' => array(
                'name' => __( 'History' ),
                'singular_name' => __( 'History' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'wpsdb_history'),
            'show_in_rest' => true,
 
        )
    );
	}
	add_action( 'init', 'wpsdb_history_cpt', 18 );
}